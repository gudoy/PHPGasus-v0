<?php

class_exists('Application') || require(_PATH_LIBS . 'Application.class.php');

class Model extends Application
{
	var $debug 			= false;
	var $db 			= null;
	var $success 		= false;
	var $errors 		= null;
	var $warnings 		= null;
	var $affectedRows 	= null;
	var $numRows		= null;
	var $data 			= null;
	var $afterQuery 	= null; 
	
	public function __construct()
	{
		isset($dataModel) || include(_PATH_CONFIG . 'dataModel.php');
		
		$this->dataModel 	= $dataModel;
		$this->resources 	= $resources;
		
		$this->alias 		= !empty($this->resources[$this->resourceName]['alias']) 
								? $this->resources[$this->resourceName]['alias']
								: $this->resourceName;
								
		$this->table 		= !empty($this->resources[$this->resourceName]['table']) 
								? $this->resources[$this->resourceName]['table']
								: $this->resourceName;
		
		return $this->connect();
	}

	public function connect()
	{
		// Open a connection on the db server
		$this->db = pg_connect('host=' . _DB_HOST . ' port=' . _DB_PORT . ' dbname=' . _DB_NAME . ' user=' . _DB_USER . ' password=' . _DB_PASSWORD);
		
		if 		( $this->db === null )	{ $this->errors[] = 4000; } 				// Database connection error
		else if ( !$this->db ) 			{ Controller::redirect(_URL_SITE_DOWN); }
		
		// Tell mysql we are sending already utf8 encoded data
		//mysql_query("SET NAMES 'UTF8'");
		
		return $this;
	}
	
	public function query($query, $options = null)
	{
		$this->data = null;
		
		// Connect to the db
		if ( $this->db === null ) { $this->connect(); }
		
		$this->errors 			= array();
		$o 						= $options;										// Shortcut for options
		$o['type'] 				= !empty($o['type']) ? $o['type'] : 'select'; 	// Set query type to select by default if not already setted
		
		// Do the query
		$queryResult 			= pg_query($this->db, $query);
		
		// 
		$this->success 			= is_bool($queryResult) && $queryResult === false ? false : true;
		
		// If the request succeed
		if ( $this->success )
		{
			// Get number of rows affetected by a insert, update, delete request
			$this->affectedRows = pg_affected_rows($queryResult);
			
			// Get number of selected rows (for select request)
			$this->numRows 		= pg_num_rows($queryResult);
			
			// If the request returns results
			if ( $o['type'] === 'select' || ($o['type'] === 'insert' && !empty($o['returning'])) )
			{
				$this->fetchResults($queryResult, $o);
				
				$this->fixSpecifics($o);
				
				//if ( !empty($o['returning']) ) { $this->data['id'] = $this->insertedId; }
			}
			
			// For insert, we may need to do some process once the request succeed
			if ( $o['type'] === 'insert' && !empty($this->afterQuery) ){ $this->afterQuery(); }
		}
		else
		{
			// Get the last error returned by the db
			$this->errors = pg_last_error($this->db); 
		}	
		
		return $this;
	}
	
	
	private function afterQuery()
	{
		$a = $this->afterQuery;
		
		if ( !empty($a['rename']) )
		{
			$lastId 		= mysql_insert_id();
			$FileManager 	= new FileManager();
			
			foreach ( $a['rename'] as $item )
			{
				$curFolder 		= $item['currentFolder'];
				$curFilepath 	= $curFolder . $item['currentName'];
				$newFolder 		= str_replace($item['tempName'] . '/', $lastId . '/', $curFolder);
				$newFilepath 	= $curFolder . str_replace('_' . $item['tempName'] . '_', '_' . $lastId . '_', $item['currentName']);
				
				if ( $item['renameFile'] ){ $FileManager->connect()->rename($curFilepath, $newFilepath); }
				
				if ( $item['renameFolder'] )
				{
					$finalFilePath = str_replace($item['tempName'] . '/', $lastId . '/', $newFilepath); 
					
					$FileManager
						->mkdir($newFolder)
						->rename($newFilepath, $finalFilePath)
						->rmdir($curFolder)
						->close();
				}
			}
			
			// Now, we have to update the file path in the db
			$this->update(array($item['dbField'] => $finalFilePath), array('values' => $lastId, 'upload' => false));
		}
		
		return $this;
	}
	
	
	private function fixSpecifics($options = null)
	{
		$o = $options;
		
		if ( !empty($o['mode']) && $o['mode'] === 'count' ){ return $this; }
		
		// Handle case where data is just 1 item, where we have to directly loop over the fields
		if 		( $this->numRows == 1 ) { $this->data = $this->fixSpecificsSingle($this->data); }
		
		// Handle case where data is made of serval items, where we have to loop over them all and apply fix to each one of them
		else if ( $this->numRows > 1 )
		{
			foreach($this->data as $index => $itemData) { $this->data[$index] = $this->fixSpecificsSingle($itemData); }
		}
		
		return $this;
	}
	
	private function fixSpecificsSingle($dataRow)
	{
		if ( !is_array($dataRow) && !is_object($dataRow) ){ return $dataRow; }
		
		foreach( $dataRow as $field => $value )
		{
			$rModel = $this->dataModel[$this->resourceName];
			$rField = !empty($rModel[$field]) ? $rModel[$field] : null;
			$type 	= !empty($rField['type']) ? $rField['type'] : null;

			// Fix postgresql 't' === true and 'f' === false
			if ( $type && $type === 'bool' )
			{
				$dataRow[$field] = $dataRow[$field] === 't' ? true : ( $dataRow[$field] === 'f' ? false : $dataRow[$field]);  
			}
		}
		
		return $dataRow;
	}

	private function fetchResults($queryResult, $options = null)
	{
		$o 			= $options;
		$o['mode'] 	= !empty($o['mode']) ? $o['mode'] : '';
		
		// Special cases where we know that we will only get 1 result
		// So, we do not want it to be put into an array but want it instead to be directly returned
		if ( $o['mode'] === 'count' )
		{
			$this->data = pg_fetch_result($queryResult, 0);
		}
		else if ( ($o['mode'] === 'onlyOne' || !empty($o['returning'])) && $this->numRows != 0 )
		{			
			$this->data = pg_fetch_assoc($queryResult, 0);
		}
		// Otherwise, fetch the query results set
		else
		{				
			//$this->data = pg_fetch_all($queryResult);
			$this->data = $this->numRows > 0 ? pg_fetch_all($queryResult) : array();
		}
		
		if ( is_resource($queryResult) ) { pg_free_result($queryResult); }
		
		return $this;
	}

	public function escapeString($string)
	{
		$string = !empty($string) ? (string) $string : '';
		
		return pg_escape_string($string);
	}
	
	
	
	private function magic($tissue)
	{
		return is_array($tissue) ? $tissue : preg_split("/,+\s*/", $tissue);
	}
	
	
	private function magicFields($fieldsStringOrArray = null)
	{
		$fields = $this->magic($fieldsStringOrArray);
		
		foreach ( $fields as $key => $item )
		{
			$k = is_array($item) ? $key : $item;
			
			if ( empty($k) ){ continue; }
			
			$this->queryData['fields'][$k] = array(
				'name' 		=> $k,
				'as' 		=> is_array($item) && !empty($item['as']) ? $item['as'] : null,
				'table' 	=> is_array($item) && !empty($item['table']) ? $item['table'] : null,
				'count' 	=> false,
			);
		}
		
		return $this;
	}
	
	
	public function buildSelect($options = array())
	{
		// Set default params
		$o 				= $options;
		$o['mode'] 		= isset($o['mode']) ? $o['mode'] : null;
		//$o['offset'] 	= isset($o['offset']) ? $o['offset'] : null;
		
		$this->queryData = array(
			'fields' => array(),
			'tables' => array(),
		);
		

		// Get fields we want to request
		//if ( !empty($o['getFields']) ) 	{ $this->magicFields($o['getFields']); }
		if ( !empty($o['getFields']) ) 	{ $o['getFields'] = $this->magic($o['getFields']); $this->magicFields($o['getFields']); }
		else 							{ $this->magicFields($this->dataModel[$this->resourceName]); }
		
		if ( !empty($o['count']) )
		{
			$o['count'] = $this->magic($o['count']);
			
			// Get fields to use in the query
			foreach ($o['count'] as $field)
			{
				if ( isset($this->queryData['fields']) ){ $this->queryData['fields'][$field]['count'] = true; }
			}
		}
		
		$where 		= $this->handleOperations($o);
		$conditions = $this->handleConditions($o);
		
		// Case where we just want to count the number of records in the table
		if ( isset($o['mode']) && $o['mode'] === 'count')
		{			
			$query 		= "SELECT COUNT(id) AS total ";
			//$query 		.= "FROM " . _DB_TABLE_PREFIX . $this->dbTableName . " AS " . $this->dbTableShortName . " ";
			$query 		.= "FROM " . _DB_TABLE_PREFIX . $this->table . " AS " . $this->alias . " ";
			$query 		.= $where;		
		}
		else if ( isset($this->options['mode']) && $this->options['mode'] === 'distinct' && !empty($this->options['field']))
		{
			//$query 		= "SELECT DISTINCT" . $o['field'] . " FROM " . _DB_TABLE_PREFIX . $this->dbTableName;		
			$query 		= "SELECT DISTINCT" . $o['field'] . " FROM " . _DB_TABLE_PREFIX . $this->table;
		}
		// Otherwise, do normal select
		else
		{			
			// Get tables to use in the query
			$queryTables 	= array();
			$leftJoins 		= array();
			$alreadyJoinedTables 	= array();
			$ljcount = 1;
			foreach ($this->dataModel[$this->resourceName] as $fieldName => $field)
			{
				if ( !empty($field['relResource']) 
					&& (empty($o['getFields']) || (!empty($o['getFields']) && in_array($fieldName, $o['getFields'])) ) )
				{
					// Get proper table name
					$field['relResource'] = (!empty($this->resources[$field['relResource']]['tableName']) 
											? $this->resources[$field['relResource']]['tableName']
											: $field['relResource'] );
					
					$queryTables[] = _DB_TABLE_PREFIX . $field['relResource'];
					
					if ( !empty($field['relGetFields']) )
					{						
						$tmpFields = $this->magic($field['relGetFields']);
						
						// 2 possible models for the fields list:
						// case 1: array({$field1} => {$getField1As}, {$field2} => {$getField2As}, ...
						// case 2: or array({$field1}, {$field2}, ...)
						
						foreach ($tmpFields as $key => $val)
						{
							// Check the row index type to know in which case we are
							$which 			=  is_int($key) ? 2 : 1;
							$tmpFieldName 	= $which === 2 ? $val : $key;
							
							// In case where the table has already been joined on, we need to use a new table alias for the current join
							$tmpTableAlias 	= !in_array($field['relResource'], $alreadyJoinedTables)  
												? null
												: $this->resources[$field['relResource']]['tableShortname'] . $ljcount;
							
							//$this->queryData['fields'][($tmpFieldName] = array(
							$storingName = $which === 2 ? ( !empty($field['relGetAs']) ? $field['relGetAs'] : null) : $val;
							$this->queryData['fields'][$storingName] = array(
											'name' 			=> $tmpFieldName,
											//'as' 			=> !empty($field['relGetAs']) ? $field['relGetAs'] . "" : null,
											'as' 			=> $storingName,
											'table' 		=> $field['relResource'],
											'tableAlias' 	=> $tmpTableAlias,
											'count' 		=> isset($this->queryData['fields'][$storingName]['count']) ? $this->queryData['fields'][$storingName]['count'] : false,
							);
						
						}					

						//$joinCondition 			= $this->dbTableShortName . "." . $fieldName . " = " . (!empty($tmpTableAlias) ? $tmpTableAlias : $field['relResource']) . "." . $field['relField'];
						$joinCondition 			= $this->alias . "." . $fieldName . " = " . (!empty($tmpTableAlias) ? $tmpTableAlias : $field['relResource']) . "." . $field['relField'];
						$ljoin 					= "LEFT JOIN " . $field['relResource'];
						$ljoin 					.= (!empty($tmpTableAlias) ? " AS " . $tmpTableAlias : '');
						$ljoin 					.= " ON " . $joinCondition . " ";
						$leftJoins[] 			= $ljoin; 
						$alreadyJoinedTables[] 	= $field['relResource'];
						
						$ljcount++;				
					}		
				}
			}

			// Get fields to use in the query
			$i = 0;
			$finalFields = '';
			foreach ($this->queryData['fields'] as $k => $field)
			{
				$finalFields .= ( $i > 0 ? ", " : '' ) 
								. ( !empty($field['table']) 
									//? $field['table']
									? ( !empty($field['tableAlias']) ? $field['tableAlias'] : $field['table'] ) 
									//: $this->dbTableShortName ) . "."
									: $this->alias ) . "."
								. $field['name'] 
								. ( !empty($field['as']) ? " AS " . $field['as'] : '' )
								;
				
				//if ( !empty($field['table']) && !empty($field['as']) ){ $finalFields .= ( $i > 0 ? ", " : '' ) .  $this->dbTableShortName . "." . $k; }
				//if ( !empty($field['table']) && !empty($field['as']) ){ $finalFields .= ( $i > 0 ? ", " : '' ) .  $this->alias . "." . $k; }
				
				// Add the count if specified to				
				//if ( $field['count'] ){ $finalFields .= ( $i > 0 ? ", " : '' ) . "count(" . $this->dbTableShortName . "." . $k . ") AS " . $k . "_total"; }
				if ( $field['count'] ){ $finalFields .= ( $i > 0 ? ", " : '' ) . "count(" . $this->alias . "." . $k . ") AS " . $k . "_total"; }
				
//$this->log($finalFields);
				
				$i++;
			}
			
//$this->log($finalFields);
//$this->log($this->queryData['fields']);
			
			$queryTables 	= !empty($getFields) ? '' : join(', ', $queryTables);
			$leftJoins 		= !empty($getFields) ? '' : join('', $leftJoins);

			// Build GROUP BY
			$groupBy = '';
			if ( !empty($o['groupBy']) )
			{
				$o['groupBy'] = $this->magic($o['groupBy']);
				
				// We have to append to the GROUP BY all the requested fields			
				// So we get the list of requested fields and store it as a local variable
				$gByFields = $this->queryData['fields'];
				
				// Remove from this array, the fiels really used to the grouping
				foreach ($o['groupBy'] as $field) { unset($gByFields[$field]); }
				
				$i = 0;
				$groupByOthers = '';
				foreach ($gByFields as $k => $f)
				{
					//$groupByOthers .= ($i === 0 ? '' : ", ") . ( !empty($f['table']) ? $f['table'] : $this->dbTableShortName ) . "." . $f['name'];
					$groupByOthers .= ($i === 0 ? '' : ", ") . ( !empty($f['table']) ? $f['table'] : $this->alias ) . "." . $f['name'];
					
					//if ( !empty($f['table']) && !empty($f['as']) ){ $groupByOthers .= ( $i > 0 ? ", " : '' ) .  $this->dbTableShortName . "." . $k; }
					//if ( !empty($f['table']) && !empty($f['as']) ){ $groupByOthers .= ( $i > 0 ? ", " : '' ) .  $this->alias . "." . $k; }
					// Case for joined columns where an alias could be used for the gotten fields
					if ( !empty($f['table']) && !empty($f['as']) ){ $groupByOthers .= ( $i > 0 ? ", " : '' ) . $k; }
					
					$i++;
				}
				
				//$groupByFields 	= is_array($o['groupBy']) ? join(", " . $this->dbTableShortName . ".", $o['groupBy']) : $o['groupBy'];
				$groupByFields 	= is_array($o['groupBy']) ? join(", " . $this->alias . ".", $o['groupBy']) : $o['groupBy'];
				//$groupBy 		= "GROUP BY " . $this->dbTableShortName . "." . $groupByFields . (!empty($groupByOthers) ? ", " . $groupByOthers : '') . " ";
				$groupBy 		= "GROUP BY " . $this->alias . "." . $groupByFields . (!empty($groupByOthers) ? ", " . $groupByOthers : '') . " ";
			}
			
			// Build ORDER BY
			$orderBy = $tmpOrderBy = '';
			if ( !empty($o['sortBy']) )
			{
				$o['sortBy'] = $this->magic($o['sortBy']);
						
				$i = 0;
				foreach ($o['sortBy'] as $f)
				{
					// If the field is not present in the gotten fields, do not use the clause with it
					//if ( !in_array($f, $this->queryData['fields']) ){ continue; }
					if ( !isset($f, $this->queryData['fields'][$f]) ){ continue; }

					$tmpOrderBy .= ($i === 0 ? '' : ", ") 
									//. ( isset($this->queryData['fields'][$f]) ? $this->dbTableShortName . "." : '' ) 
									. ( isset($this->queryData['fields'][$f]) ? $this->alias . "." : '' )
									. $f
									. ( ( strpos($f, ' ASC') > -1 || strpos($f, ' DESC') > -1 ) ? '' : ( !empty($o['orderBy']) ? " " . $o['orderBy'] : '') );
					$i++;
				}
				
				$orderBy .= !empty($tmpOrderBy) ? "ORDER BY " . $tmpOrderBy : '';
			}
			
			// Build final query  
			$query 		= 	"SELECT " . $finalFields . " ";
			//$query 		.= 	"FROM " . _DB_TABLE_PREFIX . $this->dbTableName . " AS " . $this->dbTableShortName . " ";
			$query 		.= 	"FROM " . _DB_TABLE_PREFIX . $this->table . " AS " . $this->alias . " ";
			$query 		.= 	( !empty($leftJoins) ? $leftJoins : " " );
			$query 		.= 	$where . $conditions;
			$query 		.= 	$groupBy;
			$query 		.= 	( !empty($orderBy) ? $orderBy . " " : '' );
			$query 		.= 	( !empty($o['limit']) && $o['limit'] != -1 ? "LIMIT " . $o['limit'] . " " : '' );
			$query 		.= 	( !empty($o['offset']) ? "OFFSET " . $o['offset'] . " " : '' );
		}
		
		$this->launchedQuery = $query;
		
		return $query;
	}
	

	public function buildInsert($resourceData, $options)
	{
		$d 			= $resourceData;										// Shortcut for resource data
		$o 			= $options; 											// Shortcut for options
		$fieldsNb 	= count($this->dataModel[$this->resourceName]);		// Get the number of fields for this resource
		$after 		= array();
		
//var_dump($d);
		
		// Start writing request
		//$query 		= "INSERT INTO " . _DB_TABLE_PREFIX . $this->dbTableName . " (";
		$query 		= "INSERT INTO " . _DB_TABLE_PREFIX . $this->table . " (";
		
		// Loop over the data model of the resource
		$i 			= 0;
		foreach ($this->dataModel[$this->resourceName] as $fieldName => $field)
		{
			$i++;
			if ( $field['type'] === 'int' && isset($field['AI']) && $field['AI'] ){ continue; } // Do not process autoincremented fields
			
			$query .= $fieldName . ($i < $fieldsNb ? ',' : ''); // Add each fields to the request, with coma if not last field
		}
		
		// Now we want to add the values
		$query 		.= ") VALUES (";
		
		// Loop over the passed resource data (filtered and validated POST data)
		$i 			= 0;
		$value		= null;
		foreach ($this->dataModel[$this->resourceName] as $fieldName => $field)
		{
			// Shortcuts
			/*
			$props = array('pk','ai','fk','type','subtype','default','length','relResource','relField','relGetFields','relGetAs',
							'computed','computedValues','eval','storeAs','destFolder','relatedFile');
			foreach ( $props as $prop ){ $$prop = !empty($field[$prop]) ? $field[$prop] : null; }
			*/
			
			$i++;
			
//var_dump($field);
//var_dump($d[$fieldName]);
			
			// Handle value treatments/filters via eval
			if ( !empty($field['eval']) )
			{
				$phpCode 		= str_replace('---self---', '\'' . $d[$fieldName] . '\'', $field['eval']);
				$d[$fieldName] 	= eval('return ' . $phpCode . ';');
				$phpCode 		= null;
			}
			
			// Handle specific cases
			if 		( $field['type'] === 'int' && isset($field['AI']) && $field['AI'] ){ continue; } // Do not process auto-incremented fields
			else if ( !empty($field['subtype']) && $field['subtype'] === 'file' && !empty($d[$fieldName]) )
			{
				class_exists('FileManager') || require(_PATH_LIBS . 'FileManager.class.php');
				
				//$tmpSuffix = time();
				//$destName = str_replace("%resource['id']%", $tmpSuffix, $field['destName']);
				//$destName = str_replace("%file_extension%", $d[$fieldName]['extension'], $destName);
				
				$destRoot 	= !empty($field['destRoot']) ? $field['destRoot'] : ''; 
				$destFolder = $field['destFolder'];
				//$destName 	= $field['destName'];
				$destName 	= str_replace("%file_extension%", $d[$fieldName]['extension'], $field['destName']);
				
				foreach ($this->dataModel[$this->resourceName] as $key => $value)
				{
					// Special case for id where the data to use is not in the resource data but in the options in 'values' var
					$time 			= $key === 'id' ? time() : null;
					$tmpReplaceVal 	= $this->escapeString($time !== null ? $time : ( !empty($d[$key]) ? $d[$key] : ''));

					// If a placeholder for the current field is found in the destination name or the destination folder
					// replace by the proper value
					$nmPlaceholder 	= strpos($destName, '%resource[\'' . $key . '\']%') !== false;
					$fdPlaceholder 	= strpos($destFolder, '%resource[\'' . $key . '\']%') !== false;

					if ( $nmPlaceholder || $fdPlaceholder )
					{						
						$destFolder = str_replace("%resource['" . $key . "']%", $this->escapeString($tmpReplaceVal), $destFolder);	
						$destName 	= str_replace("%resource['" . $key . "']%", $this->escapeString($tmpReplaceVal), $destName);
					}
					
					$renameFolder 	= (isset($renameFolder) && $renameFolder) || $fdPlaceholder;
					$renameFile 	= (isset($renameFile) && $renameFile) || $nmPlaceholder;
					$tempName 		= !empty($tempName) ? $tempName : $time;  
				
				}
				
				// Launch the file upload
				$FileUpload = FileManager::getInstance()->uploadByFtp($d[$fieldName], array(
					'destFolder' 	=> $destRoot . $destFolder,
					'destName' 		=> $destName,
					//'destRoot' 		=> $destRoot,
					'filePath' 		=> $d[$fieldName]['tmp_name'],
					'allowedTypes' 	=> $field['allowedTypes'],
				));
				
				if ( $FileUpload->success )
				{
					// Keep a flag if either the file destination folder or name should contain the resource id 
					// (which is not known as the moment where the file is uploaded)
					$after['rename'][] = array(
						'currentFolder' => $destRoot . $destFolder,
						'currentName' 	=> $destName,
						'renameFolder' 	=> $destRoot . $renameFolder,
						'renameFile' 	=> $renameFile,
						'tempName' 		=> $tempName,
						'dbField' 		=> $fieldName,
					);
					
					$value = "'" . ( !empty($field['storeAs']) && $field['storeAs'] !== 'filename' ?  '' : $destFolder) . $destName . "'";
				}
				else { continue; }
			}
			else if ( !empty($field['subtype']) && $field['subtype'] === 'fileMetaData' )
			{
				$relField 	= $field['relatedFile'];
				$meta 		= $field['meta'];
				$value 		= "'" . ( !empty($d[$relField]) ? $this->escapeString($d[$relField][$meta]) : '') . "'";
			}
			else if ( isset($field['computed']) && $field['computed'] )
			{
				if ( $field['type'] === 'timestamp' ){ $value = $field['computedValue']; }
				// TODO: use proper str_replace ????
				else if ( !empty($field['subtype']) && $field['subtype'] === 'URIname' && !empty($field['useField']) )
				{
				    $charsTable = array(
				        'Š'=>'S', 'š'=>'s', 'Đ'=>'Dj', 'đ'=>'dj', 'Ž'=>'Z', 'ž'=>'z', 'Č'=>'C', 'č'=>'c', 'Ć'=>'C', 'ć'=>'c',
				        'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
				        'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
				        'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss',
				        'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e',
				        'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o',
				        'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b',
				        'ÿ'=>'y', 'Ŕ'=>'R', 'ŕ'=>'r',
				    );
					$tmpVal = $d[$field['useField']];
					$tmpVal = strtr($tmpVal,$charsTable);
					$value 	= "'" . str_replace(' ', '+', $tmpVal) . "'";
				}
				//elseif ( $field['type'] === 'timestamp' ){ $value = 'NOW()'; }
				//else if ( $field['computedValue'] === 'uniqId' ){ $value = uniqid(); }
				//else if ( $field['computedValue'] === 'uniqId' ){ $value = substr(str_rot13(md5(time())), 0, 10); }
				else if ( $field['computedValue'] === 'uniqId' )
				{
					if ( !empty($d[$fieldName]) ) { $value = "'" . $this->escapeString(trim($d[$fieldName])) . "'";  }
					else
					{
						$chars = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
						$value = '';
						$lim = 10;
						for ($j = 0; $j < $lim; $j++) { $value .= $chars[rand(0, strlen($chars) - 1)]; }
						$value = "'" . $value . "'";
					}
				}
				else if ( $field['type'] === 'varchar' ){ $value = "'" . $this->escapeString($field['computedValue']) . "'"; }
				else { $value = !empty($field['computedValue']) ? $field['computedValue'] : "''"; }
			}
			else if ( $field['type'] === 'text' )
			{
				//$value = "'" . $this->escapeString(trim($d[$fieldName])) . "'";
				$value = "'" . $this->escapeString( isset($d[$fieldName]) ? trim($d[$fieldName]) : '') . "'";
			}
			//else if ( $field['type'] === 'varchar' )
			else if ( in_array($field['type'], array('varchar','enum')) )
			{
				// TODO: how to handle not posted fileds
				$d[$fieldName] = isset($d[$fieldName]) ? $d[$fieldName] : '';
				
				$value = "'" . $this->escapeString($d[$fieldName]) . "'";
			}
			else if ( $field['type'] === 'bool' ) { $value = ( !empty($d[$fieldName]) && $d[$fieldName]) ? 'true' : 'false'; }
			// Otherwise, just take the posted data value
			//else { $value = $d[$fieldName]; }
			else if ( $field['type'] === 'float' )
			{
				$value = "'" . $this->escapeString(  str_replace(',','.',(string)($d[$fieldName]))) . "'";
			}
			else if ( $field['type'] === 'timestamp' )
			{
				//if ( empty($d[$fieldName]) ){ continue; }
				//$value = 'to_timestamp(' . $d[$fieldName] . ')';
				//$value = "FROM_UNIXTIME('" . $d[$fieldName] . "')";
//var_dump($d[$fieldName]);
				$value = is_int($d[$fieldName]) && $d[$fieldName] < 0 
							//? "DATE_SUB(FROM_UNIXTIME('". ($d[$fieldName] + 10000000) . "'), INTERVAL 10000000 SECOND)"
							? "DATE_ADD(FROM_UNIXTIME(0), INTERVAL " . $this->escapeString($d[$fieldName]) ." SECOND)"
							: "FROM_UNIXTIME('" . $this->escapeString($d[$fieldName]) . "')";
			}
			else if ( $field['type'] === 'datetime' )
			{
				// TODO: how to handle not posted fileds
				$d[$fieldName] = isset($d[$fieldName]) ? $d[$fieldName] : '';
				
				$value = "'" . $this->escapeString($d[$fieldName]) . "'";
			}
			// Otherwise, just take the posted data value
			//else { $value = $d[$fieldName]; }
			//else { $value = !empty($d[$fieldName]) ? $d[$fieldName] : (isset($field['default']) ? $field['default'] : ''); }
			else { $value = !empty($d[$fieldName]) ? $d[$fieldName] : (isset($field['default']) ? $field['default'] : "''"); }
			
			$query .= $value . ($i < $fieldsNb ? ',' : ''); // Add each fields to the request, with coma if not last field
			//$query .= "'" . $value . ($i < $fieldsNb ? ',' : '') . "'"; // Add each fields to the request, with coma if not last field
		}
		
		// Finish writing the request
		$query 		.= ")";
		
		if ( !empty($o['returning']) ) { $query .= " RETURNING " . $o['returning']; }
		
		$this->launchedQuery 	= $query;
		$this->afterQuery 		= $after;
		
		return $query;
	}
	
	
	public function buildUpdate($resourceData, $options)
	{
		$d 			= $resourceData;										// Shortcut for resource data
		$o 			= $options; 											// Shortcut for options
		$fieldsNb 	= count($d);		// Get the number of fields for this resource
		
		// Start writing request
		$query 		= "UPDATE ";
		//$query 		.=  _DB_TABLE_PREFIX . $this->dbTableName . " AS " . $this->dbTableShortName . " ";
		$query 		.=  _DB_TABLE_PREFIX . $this->table . " AS " . $this->alias . " ";
		$query 		.= "SET ";
		
		// Loop over the passed resource data (filtered and validated POST data)
		$i 			= 0;
		$value		= null;
		foreach ($this->dataModel[$this->resourceName] as $fieldName => $field)
		{	
			// If a field is not passed in the data, do not add it to the request
			$skip = !isset($d[$fieldName]) || $d[$fieldName] === null;
			//$skip = empty($field['computed']) && (!isset($d[$fieldName]) || $d[$fieldName] === null);
			
			// except for fields whose subtype is fileMetaData
			if ( !empty($field['subtype']) && $field['subtype'] === 'fileMetaData' && !empty($d[$field['relatedFile']]) ) { $skip = false; }
			
			// Skip current field process is we have to 
			if ( $skip ) { continue; }
			
			// Shortcuts
			/*
			$props = array('pk','ai','fk','type','subtype','default','length','relResource','relField','relGetFields','relGetAs',
							'computed','computedValues','eval','storeAs','destFolder','relatedFile');
			foreach ( $props as $prop ){ $$prop = !empty($field[$prop]) ? $field[$prop] : null; }
			*/
			
//var_dump($fieldName);
			
			$i++;
			
			// Handle value treatments/filters via eval
			if ( !empty($field['eval']) )
			{
				$phpCode 		= str_replace('---self---', '\'' . $d[$fieldName] . '\'', $field['eval']);
				$d[$fieldName] 	= eval('return ' . $phpCode . ';');
				$phpCode 		= null;
			}
			
			// Handle specific cases
			if 		( $field['type'] === 'int' && isset($field['AI']) && $field['AI'] ){ continue; } // Do not process auto-incremented fields
			else if ( !empty($field['subtype']) && $field['subtype'] === 'file' && ( !isset($o['upload']) || $o['upload'] !== false ) )
			{
				class_exists('FileManager') || require(_PATH_LIBS . 'FileManager.class.php');
				
				// Loop over the resource fields to replace placeholders by proper value
				$destRoot 	= !empty($field['destRoot']) ? $field['destRoot'] : ''; 
				$destFolder = $field['destFolder'];
				//$destName 	= $field['destName'];
				$destName 	= str_replace("%file_extension%", $d[$fieldName]['extension'], $field['destName']);
				
				foreach ($this->dataModel[$this->resourceName] as $key => $value)
				{
					// Special case for id where the data to use is not in the resource data but in the options in 'values' var
					$tmpReplaceVal = $this->escapeString($key === 'id' ? $o['values'] : ( !empty($d[$key]) ? $d[$key] : ''));

					// If a placeholder for the current field is found in the destination name or the destination folder
					// replace by the proper value
					if ( strpos($destName, '%resource[\'' . $key . '\']%') !== false || strpos($destFolder, '%resource[\'' . $key . '\']%') !== false )
					{						
						$destFolder = str_replace("%resource['" . $key . "']%", $this->escapeString($tmpReplaceVal), $destFolder);	
						$destName 	= str_replace("%resource['" . $key . "']%", $this->escapeString($tmpReplaceVal), $destName);	
					}
				}
				
				$FileUpload = FileManager::getInstance()->uploadByFtp($d[$fieldName], array(
					'destFolder' 	=> $destRoot . $destFolder,
					'destName' 		=> $destName,
					//'destRoot' 		=> $destRoot,
					'filePath' 		=> $d[$fieldName]['tmp_name'],
					'allowedTypes' 	=> $field['allowedTypes'],
				));
				
				if ( $FileUpload->success )
				{
					$value = "'" . ( !empty($field['storeAs']) && $field['storeAs'] !== 'filename' ?  '' : $destFolder) . $destName . "'";
				}
				else { continue; }
			}
			else if ( !empty($field['subtype']) && $field['subtype'] === 'fileMetaData' )
			{
				// If the upload options has been set to false, do not process the fied
				if ( !empty($o['upload']) && !$o['upload'] ) { continue; }
				
				$relField 	= $field['relatedFile'];
				$meta 		= $field['meta'];
				$value 		= "'" . ( !empty($d[$relField]) ? $this->escapeString($d[$relField][$meta]) : '') . "'";
			}
			else if ( !empty($field['computed']) && $field['type'] === 'timestamp' ){ $value = $field['computedValue']; }
			else if ( !empty($field['computed']) && !empty($field['subtype'])  )
			{
				if ( $field['subtype'] === 'URIname' && !empty($field['useField']) )
				{
				    $charsTable = array(
				        'Š'=>'S', 'š'=>'s', 'Đ'=>'Dj', 'đ'=>'dj', 'Ž'=>'Z', 'ž'=>'z', 'Č'=>'C', 'č'=>'c', 'Ć'=>'C', 'ć'=>'c',
				        'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
				        'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
				        'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss',
				        'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e',
				        'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o',
				        'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b',
				        'ÿ'=>'y', 'Ŕ'=>'R', 'ŕ'=>'r',
				    );
					$tmpVal = $d[$field['useField']];
					$tmpVal = strtr($tmpVal,$charsTable);
					$value 	= "'" . str_replace(' ', '+', $tmpVal) . "'";
				}
				else { $value = $d[$fieldName]; }
			}
			else if ( $field['type'] === 'text' )
			{
				//$value = "'" . $this->escapeString(trim($d[$fieldName])) . "'";
				$value = "'" . $this->escapeString(trim(stripslashes($d[$fieldName]))) . "'";
				//$value = "'" . trim($d[$fieldName])) . "'";
			}
			else if ( $field['type'] === 'bool' ) { $value = ( !empty($d[$fieldName]) && $d[$fieldName]) ? 'true' : 'false'; }
			else if ( $field['type'] === 'float' )
			{
				$value = "'" . $this->escapeString(  str_replace(',','.',(string)($d[$fieldName]))) . "'";
			}
			else if ( $field['type'] === 'timestamp' )
			{
				//if ( empty($d[$fieldName]) ){ continue; }
				$value = 'to_timestamp(' . $d[$fieldName] . ')';
			}
			else if ( $field['type'] === 'datetime' )
			{
				// TODO: how to handle not posted fileds
				$d[$fieldName] = isset($d[$fieldName]) ? $d[$fieldName] : '';
				
				$value = "'" . $this->escapeString($d[$fieldName]) . "'";
			}
			//else if ( $field['type'] === 'varchar' )
			else if ( in_array($field['type'], array('varchar','enum')) )
			{
				$value = "'" . $this->escapeString($d[$fieldName]) . "'";
			}
			// Otherwise, just take the posted data value
			else
			{
				$value = $d[$fieldName];
			}
			
			$query .= ($i == 1 ? '' : ',') . $fieldName . " = " . $value; // Add each fields to the request, with coma if not last field
		}
		
		// Finish writing the request
		//$query 		.= " WHERE " . $o['by'] . " = '" . $this->escapeString($o['values']) . "'";
		$query 		.= !empty($o['conditions'])
						? " " . $this->handleConditions($o)
						: " WHERE " . $o['by'] . " = '" . $this->escapeString($o['values']) . "'";
		
		$this->launchedQuery = $query;
		
		return $query;
	}


	public function buildDelete($options)
	{
		$o 			= $options; 											// Shortcut for options
	
		// Build WHERE (concatenating values)
		$where 		= $this->handleOperations($o);
	
		// Start writing request
		// When using "AS", mysql seems want to have the defined as just before the FROM
		//$query 		= "DELETE " . $this->dbTableShortName . " ";
		//$query 		= "DELETE " . $this->alias . " ";
		$query 		= "DELETE ";
		//$query 		.= "FROM " . _DB_TABLE_PREFIX . $this->dbTableName . " AS " . $this->alias . " ";
		$query 		.= "FROM " . _DB_TABLE_PREFIX . $this->dbTableName . " AS " . $this->alias . " ";
		$query 		.= $where;
		
		$this->launchedQuery = $query;
		
		return $query;
	}


	public function handleConditions($options)
	{
		$o 			= $options; 		// Shortcut for options
		
		// TODO: clean using magic
		$conditions = '';
		if ( !empty($o['conditions']) )
		{
			$i = 0;
			foreach ($o['conditions'] as $condFieldName => $condFieldValues)
			{
				if ( !isset($condFieldValues) ) { break; }
				
				$conditions .= empty($o['values']) && $i == 0 ? "WHERE " : "AND ";
				//$conditions .= ( !empty($this->queryData[$condFieldName]) ? $this->queryData[$condFieldName] : $this->dbTableShortName ) . ".";
				$conditions .= ( !empty($this->queryData[$condFieldName]) ? $this->queryData[$condFieldName] : $this->alias ) . ".";
				$conditions .= $condFieldName . " IN ('";
				$conditions .= is_array($condFieldValues) 
								? join("', '", is_bool($condFieldValues) ? (int) $condFieldValues : $condFieldValues) 
								: $condFieldValues;
				$conditions .= "') ";
				$i++;
			}
		}
		
		return $conditions;
	}
	
	
	public function handleOperations($options)
	{
		$o 			= $options; 		// Shortcut for options
		
		$where 		= '';
		if ( isset($o['values']) && !empty($o['by']) )
		{
			$whereValues 	= $this->magic($o['values']);
			$op 			= !empty($o['operation']) ? $o['operation'] : '';
			
			switch($op)
			{
				case 'valueContains': 
					//foreach ($whereValues as $item) { $tmpWhere[] = $this->dbTableShortName . "." . $o['by'] . " ILIKE '%" . $this->escapeString($item) . "%'"; }
					foreach ($whereValues as $item) { $tmpWhere[] = $this->alias . "." . $o['by'] . " ILIKE '%" . $this->escapeString($item) . "%'"; }
					$where = "WHERE " . join(" OR ", $tmpWhere) . " ";
					break;
				case 'valueNotContains': 
					//foreach ($whereValues as $item) { $tmpWhere[] = $this->dbTableShortName . "." . $o['by'] . " NOT ILIKE '%" . $this->escapeString($item) . "%'"; }
					foreach ($whereValues as $item) { $tmpWhere[] = $this->alias . "." . $o['by'] . " NOT ILIKE '%" . $this->escapeString($item) . "%'"; }
					$where = "WHERE " . join(" AND ", $tmpWhere) . " ";
					break;
				case 'valueStartsBy': 
					//foreach ($whereValues as $item) { $tmpWhere[] = $this->dbTableShortName . "." . $o['by'] . " LIKE '" . $this->escapeString($item) . "%'"; }
					foreach ($whereValues as $item) { $tmpWhere[] = $this->alias . "." . $o['by'] . " LIKE '" . $this->escapeString($item) . "%'"; }
					$where = "WHERE " . join(" OR ", $tmpWhere) . " ";
					break;
				case 'valueEndsBy': 
					//foreach ($whereValues as $item) { $tmpWhere[] = $this->dbTableShortName . "." . $o['by'] . " LIKE '%" . $this->escapeString($item) . "'"; }
					foreach ($whereValues as $item) { $tmpWhere[] = $this->alias . "." . $o['by'] . " LIKE '%" . $this->escapeString($item) . "'"; }
					$where = "WHERE " . join(" OR ", $tmpWhere) . " ";
					break;
				case 'valueIsNot': 
					//$where 	= "WHERE " . $this->dbTableShortName . "." . $o['by'] . " NOT IN ('" . join("', '", $whereValues) . "') ";
					$where 	= "WHERE " . $this->alias . "." . $o['by'] . " NOT IN ('" . join("', '", $whereValues) . "') ";
					break;
				case 'valueIsGreater': 
					//$where 	= "WHERE " . $this->dbTableShortName . "." . $o['by'] . " > '" . $this->escapeString($whereValues[0]) . "' ";
					$where 	= "WHERE " . $this->alias . "." . $o['by'] . " > '" . $this->escapeString($whereValues[0]) . "' ";
					break;
				case 'valueIsGreaterOrEqual': 
					//$where 	= "WHERE " . $this->dbTableShortName . "." . $o['by'] . " >= '" . $this->escapeString($whereValues[0]) . "' ";
					$where 	= "WHERE " . $this->alias . "." . $o['by'] . " >= '" . $this->escapeString($whereValues[0]) . "' ";
					break;
				case 'valueIsLower': 
					//$where 	= "WHERE " . $this->dbTableShortName . "." . $o['by'] . " < '" . $this->escapeString($whereValues[0]) . "' ";
					$where 	= "WHERE " . $this->alias . "." . $o['by'] . " < '" . $this->escapeString($whereValues[0]) . "' ";
					break;
				case 'valueIsLowerOrEqual': 
					//$where 	= "WHERE " . $this->dbTableShortName . "." . $o['by'] . " <= '" . $this->escapeString($whereValues[0]) . "' ";
					$where 	= "WHERE " . $this->alias . "." . $o['by'] . " <= '" . $this->escapeString($whereValues[0]) . "' ";
					break;
				default:
					//$where 	= "WHERE " . $this->dbTableShortName . "." . $o['by'] . " IN ('" . join("', '", $whereValues) . "') ";
					$where 	= "WHERE " . $this->alias . "." . $o['by'] . " IN ('" . join("', '", $whereValues) . "') ";
					break;
			}	
		}
		
		return $where;
	}
	
	
	public function index($options = array())
	{
		// Set default params
		$o 				= $options;
		$o['by'] 		= !empty($o['by']) ? $o['by'] : 'id';
		$o['sortBy'] 	= !empty($o['sortBy']) ? $o['sortBy'] : 'id';
		$o['orderBy'] 	= !empty($o['orderBy']) ? $o['orderBy'] : 'ASC';
		
		// Build the proper query
		$query 	= $this->buildSelect($o);
		
		$this->log($query);

		// Execute the query and store the returned data
		$this->query($query, $o);
		
		
		return $this->data;
	}
	
	
	public function create($resourceData = null, $options = array())
	{
		// Do not continue if no data has been passed 
		if ( empty($resourceData) ) { return; }
		
		$o 			= $options;
		$o['type'] 	= 'insert';
		
		// Build the proper query
		$query 	= $this->buildInsert($resourceData, $o);
		
		$this->log($query);

		// Execute the query and store the returned data
		$this->query($query, $o);
		
		//return ( !empty($o['returning']) && count((array) $o['returning']) === 1 ) ? $this->data[$o['returning']] : $this;
		return !empty($o['returning']) ? ( isset($this->data[$o['returning']]) ? $this->data[$o['returning']] : null) : $this;
	}
	
	
	public function retrieve($options = array())
	{
		$o 				= $options;
		$o['by'] 		= !empty($o['by']) ? $o['by'] : 'id';
		$o['values'] 	= !empty($o['values']) ? $o['values'] : null;
		$o['mode']		= !empty($o['mode']) ? $o['mode'] : 'onlyOne';
		$o['limit'] 	= $o['mode'] !== 'onlyOne' && !empty($o['limit']) ? $o['limit'] : null;
		
		// Do not continue if no value has been passed
		if ($o['values'] === null) { return false; }
		
		$query 	= $this->buildSelect($o);
		
		$this->log($query);
		
		$this->data = $this->query($query, $o)->data;
		
		return $this->data;
	}
	
	
	public function update($resourceData = null, $options = array())
	{
		$o 				= $options;
		$o['by'] 		= !empty($o['by']) ? $o['by'] : 'id';
		$o['values'] 	= !empty($o['values']) ? $o['values'] : null;
		$o['type'] 		= 'update';
		
		// Do not continue if no data or no item value has been passed 
		//if ( empty($resourceData) || empty($o['values']) ) { return; }
		if ( empty($resourceData) ) { return; }
		
		// Build the proper query
		$query = $this->buildUpdate($resourceData, $o);
		
		$this->log($query);

		// Execute the query and store the returned data
		$this->query($query, $o);
		
		return $this;
	}
	
	
	public function delete($options = array())
	{		
		$o 				= $options;
		$o['by'] 		= !empty($o['by']) ? $o['by'] : 'id';
		$o['values'] 	= !empty($o['values']) ? $o['values'] : null;
		$o['type'] 		= 'delete';
		
		// Do not continue if no value has been passed
		if ($o['values'] === null) { return false; }
		
		// Build the proper query
		$query = $this->buildDelete($o);
		
		$this->log($query);

		// Execute the query and store the returned data
		$this->query($query, $o);
		
		return $this;
	}

}
?>