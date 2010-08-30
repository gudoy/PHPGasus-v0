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
	var $safeWrapper 	= '`'; 
	
	public function __construct($application)
	{		
		isset($dataModel) || include(_PATH_CONFIG . 'dataModel.php');
		
		//$this->dataModel 	= $dataModel;
		$this->application 	= $application;
		$this->resources 	= $resources;
		
		$this->alias 		= !empty($this->resources[$this->resourceName]['alias']) 
								? $this->resources[$this->resourceName]['alias']
								: $this->resourceName;
								
		$this->table 		= !empty($this->resources[$this->resourceName]['table']) 
								? $this->resources[$this->resourceName]['table']
								: $this->resourceName;
								
		//  
		if ( !empty($this->resourceName) )
		{
			$this->resourceSingular = !empty($this->resourceSingular) ? $this->resourceSingular : $this->singularize((string) $this->resourceName);
		}
		
		return $this->connect();
	}

	public function connect()
	{
		// Open a connection on the db server
		$this->db 			= @mysql_connect(_DB_HOST, _DB_USER, _DB_PASSWORD);
		
		// TODO: use error codes
		//if ( !is_resource($this->db) ){ die('Database connection error ' . mysql_error()); }
		if ( !is_resource($this->db) ){ $this->errors[] = 4000; die('Database connection error'); }
		
		$this->selectedDb 	= @mysql_select_db(_DB_NAME, $this->db);
		
		// TODO: use error codes
		if ( !$this->selectedDb ) { $this->errors[] = 4001; die('Database selection error'); }
		
		// Tell mysql we are sending already utf8 encoded data
		mysql_query("SET NAMES 'UTF8'");
		
		return $this;
	}
	
	public function query($query, $options = null)
	{
		$this->data = null;
		
		// Connect to the db
		if ( !$this->db ) { $this->connect(); }
		
		$this->errors 			= array();
		$o 						= $options;										// Shortcut for options
		$o['type'] 				= !empty($o['type']) ? $o['type'] : 'select'; 	// Set query type to select by default if not already setted
		
		// Do the query
		$queryResult 			= mysql_query($query, $this->db);
		
		// 
		//$this->success 			= is_bool($queryResult) && $queryResult === false ? false : true;
		$this->success 			= is_bool($queryResult) && !$queryResult ? false : true;
		//$this->success 			= !(is_bool($queryResult) && !$queryResult);
		
		// If the request succeed
		if ( $this->success )
		{
			// Get number of rows affetected by a insert, update, delete request
			$this->affectedRows = mysql_affected_rows($this->db);
			
			// Get number of selected rows (for select request)
			$this->numRows 		= is_resource($queryResult) ? mysql_num_rows($queryResult) : 0;
			
			if ( $o['type'] === 'insert' ){ $this->insertedId = mysql_insert_id($this->db); }
			
			// If the request returns results
			// HOW TO handle RETURNING clause for mysql ??? 
			if ( $o['type'] === 'select' || ($o['type'] === 'insert' && !empty($o['returning'])) )
			{

				$this->fetchResults($queryResult, $o);
				
				$this->fixSpecifics($o);
				
				// TODO: if returning !== 'id', make a select using id value, then return column value
				if ( !empty($o['returning']) ) { $this->data['id'] = $this->insertedId; }
			}
			
			// For insert, we may need to do some process once the request succeed
			if ( $o['type'] === 'insert' && !empty($this->afterQuery) ){ $this->afterQuery(); }
		}
		else
		{			
			// Get the last error returned by the db
			//$this->errors = mysql_error($this->db);
			$errId = mysql_errno($this->db);
			$this->errors[$errId] = mysql_error($this->db);
		}	
		
		return $this;
	}	
	
	private function fixSpecifics($options = null)
	{
		$o = $options;
		
		if ( !empty($o['mode']) && $o['mode'] === 'count' ){ return $this; }
		
//$this->dump('count:' . $this->numRows);
//$this->dump($this->data);
		
		// Handle case where data is just 1 item, where we have to directly loop over the fields
		if ( $this->numRows == 1 && !empty($o['mode']) && $o['mode'] === 'onlyOne' ) { $this->data = $this->fixSpecificsSingle($this->data); }
		//if 		( $this->numRows == 1 ) { $this->data = $this->fixSpecificsSingle($this->data); }
		
		// Handle case where data is made of serval items, where we have to loop over them all and apply fix to each one of them
		//else if ( $this->numRows > 1 )
		else
		{
			foreach($this->data as $index => $itemData) { $this->data[$index] = $this->fixSpecificsSingle($itemData); }
		}
		
		return $this;
	}
	
	private function fixSpecificsSingle($dataRow)
	{
//var_dump(__METHOD__);
		
		if ( !is_array($dataRow) && !is_object($dataRow) ){ return $dataRow; }
		
//var_dump($this->resourceName);
//var_dump($dataRow);

		//$rModel 	= $this->dataModel[$this->resourceName];
		$rModel 	= $this->application->dataModel[$this->resourceName];
		
		foreach( $dataRow as $field => $value )
		{
			$rField 	= !empty($rModel[$field]) ? $rModel[$field] : null;
			$type 		= !empty($rField['type']) ? $rField['type'] : null;
			$subtype 	= !empty($rField['subtype']) ? $rField['subtype'] : null;

			$curVal = $dataRow[$field];

			// Fix postgresql 't' === true and 'f' === false
			if ( $type === 'bool' )
			{
				//$dataRow[$field] = $curVal === 't' ? true : ( $curVal === 'f' ? false : $curVal);  
				//$dataRow[$field] = $curVal === 't' || $curVal == true  ? true : ( $curVal === 'f' || $curVal == false ? false : $curVal);
				$dataRow[$field] = $curVal === 't' || $curVal == true  ? true : false;
			}
			else if ( $type === 'int' )
			{
				$dataRow[$field] = (int) $curVal;
			}
			else if ( $type === 'float' )
			{
				$dataRow[$field] = (float) $curVal;
			}
			else if ( $type === 'timestamp' )
			{
				//$dataRow[$field] = is_numeric($curVal) ? $curVal : strtotime($curVal);  
				$dataRow[$field] = is_numeric($curVal) ? (int) $curVal : strtotime($curVal);
			}
			//else if ( $type === 'varchar' && $subtype === 'file' )
			else if ( $type === 'varchar' && in_array($subtype, array('file', 'fileDuplicate')) )
			{
				if ( !empty($curVal) && !empty($rField['destBaseURL']) )
				{
					$dataRow[$field] = $rField['destBaseURL'] . $curVal;
				}
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
			$this->data = mysql_result($queryResult, 0,0);
		}
		else if ( ($o['mode'] === 'onlyOne' || !empty($o['returning'])) && $this->numRows != 0 )
		{			
			$this->data = mysql_fetch_array($queryResult, MYSQL_ASSOC);
		}
		// Otherwise, fetch the query results set
		else
		{				
			if ( $this->numRows > 0 ) 	{ while ($row = mysql_fetch_array($queryResult, MYSQL_ASSOC)) { $this->data[] = $row; } }
			else 						{ $this->data = array(); }
		}
		
		if ( is_resource($queryResult) ) { mysql_free_result($queryResult); }
		
		return $this;
	}

	public function escapeString($string)
	{
		$string = !empty($string) ? (string) $string : '';
		
		return mysql_real_escape_string($string);
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
	
	// TODO: refactor
	private function afterQuery()
	{
		$a = $this->afterQuery;
		
		if ( !empty($a['rename']) )
		{
			// Get the sql id of the resource
			$lastId 		= mysql_insert_id();
			
			// Array tha will contains key/value couples to update
			$updateKeyVals 	= array();
			
			foreach ( $a['rename'] as $item )
			{
				$curFolder 		= $item['currentFolder'];
				$curFilepath 	= $item['destRoot'] . $curFolder . $item['currentName'];
				$newFolder 		= str_replace($item['tempName'] . '/', $lastId . '/', $curFolder);
				//$newFilename	= str_replace('_' . $item['tempName'] . '_', '_' . $lastId . '_', $item['currentName']);
				//$newFilename	= str_replace('_' . $item['tempName'], '_' . $lastId, $item['currentName']);
				$newFilename	= str_replace($item['tempName'], $lastId, $item['currentName']);
				$tmpNewFilepath = $newFolder . $newFilename;
				$newFilepath 	= $item['destRoot'] . $tmpNewFilepath;
				$storedFilePath = $tmpNewFilepath;
				
				$updateKeyVals[$item['dbField']] = $storedFilePath;
				
//$this->dump($curFilepath);
//$this->dump($newFilepath);
				
				// Case:  Amazon S3
				if ( !empty($item['storeOn']) && $item['storeOn'] === 'amazon_S3' )
				{
					class_exists('AmazonS3') || require(_PATH_LIBS . 'storage/CloudFusion/cloudfusion.class.php');
					
					$s3 = new AmazonS3(_AWS_ACCESSKEY, _AWS_SECRET_KEY);
					
					$s3->rename_object(_AWS_BASE_BUCKET, $curFilepath, $newFilepath, $item['acl']);
				}
				// Default case: ftp
				else
				{
					class_exists('FileManager') || require(_PATH_LIBS . 'storage/FileManager.class.php');	
					$FileManager 	= new FileManager();
					$FileManager 	= $FileManager->connect();
					
					/*
					if 		( $item['renameFile'] )		{ $FileManager->connect()->rename($curFilepath, $item['destRoot'] . $curFolder . $newFilename); }
					else if ( $item['renameFolder'] )
					{
						$FileManager
							//->mkdir($item['destRoot'] . $newFolder)
							->rename($item['destRoot'] . $curFolder, $item['destRoot'] . $newFolder)
							//->rmdir($item['destRoot'] . $curFolder)
							->close();
					}
					*/
					
					/*
					if ( $item['renameFile'] ){ $FileManager->rename($curFilepath, $item['destRoot'] . $curFolder . $newFilename); }
					
					if ( $item['renameFolder'] )
					{
						$FileManager
							->mkdir($item['destRoot'] . $newFolder)
							->rename($item['destRoot'] . $curFolder, $item['destRoot'] . $newFolder);
							//->rmdir($item['destRoot'] . $curFolder)
					}
					*/
					
					$FileManager
						->mkdir($item['destRoot'] . $newFolder)
						->rename($curFilepath, $item['destRoot'] . $newFolder . $newFilename)
						->rmdir($item['destRoot'] . $curFolder);
					
					$FileManager->close();
				}
/*
$this->dump('---AFTER---');
$this->dump($item);					
$this->dump($lastId);
$this->dump($curFolder);
$this->dump($curFilepath);
$this->dump($newFolder);
$this->dump($newFilepath);
$this->dump($storedFilePath);
$this->dump('renamed file:' . $curFilepath . ' IN ' . $item['destRoot'] . $curFolder . $newFilename);
$this->dump('renamed folder:' . $item['destRoot'] . $curFolder . ' IN ' . $item['destRoot'] . $newFolder);
*/
				
				// Now, we have to update the file path in the db
				//$this->update(array($item['dbField'] => $storedFilePath), array('values' => $lastId, 'upload' => false));	
			}
			
			// Now, we have to update the file path in the db
			$this->update($updateKeyVals, array('values' => $lastId, 'upload' => false));
		}
		
		return $this;
	}
	
	
	public function buildSelect($options = array())
	{
		// Set default params
		$o 				= $options;
		$o['mode'] 		= isset($o['mode']) ? $o['mode'] : null;
		
		// Should we force mysql to return real unix_timestamps for timestamp fields instead of mysql formatted dates/
		// Setting to true prevents costly calls to strtotime() to do the conversion
		$o['force_unix_timestamps'] = true;
		
		//$rModel 	= $this->dataModel[$this->resourceName];
		$rModel 	= $this->application->dataModel[$this->resourceName];
		
		$this->queryData = array(
			'fields' => array(),
			'tables' => array(),
		);

		// Get fields we want to request
		//if ( !empty($o['getFields']) ) 	{ $this->magicFields($o['getFields']); }
		if ( !empty($o['getFields']) ) 	{ $o['getFields'] = $this->magic($o['getFields']); $this->magicFields($o['getFields']); }
		else 							{ $this->magicFields($rModel); }
		
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
			$queryTables 			= array();
			$leftJoins 				= array();
			$alreadyJoinedTables 	= array();
			$ljcount = 1;
			foreach ($rModel as $fieldName => $field)
			{
				if ( !empty($field['relResource']) 
					&& ( empty($o['getFields']) || (!empty($o['getFields']) && in_array($fieldName, $o['getFields'])) ) )
				{
					// Get proper table name
					$field['relResource'] = //(!empty($this->resources[$field['relResource']]['tableName']) 
											(!empty($this->resources[$field['relResource']]['table'])
												//? $this->resources[$field['relResource']]['tableName']
												? $this->resources[$field['relResource']]['table']
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
												: $this->resources[$field['relResource']]['alias'] . $ljcount;
							
							$storingName = $which === 2 ? ( !empty($field['relGetAs']) ? $field['relGetAs'] : null) : $val;
							$this->queryData['fields'][$storingName] = array(
											'name' 			=> $tmpFieldName,
											'as' 			=> $storingName,
											'table' 		=> $field['relResource'],
											'tableAlias' 	=> $tmpTableAlias,
											'count' 		=> isset($this->queryData['fields'][$storingName]['count']) ? $this->queryData['fields'][$storingName]['count'] : false,
							);	
						}

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
			
//var_dump($this->queryData['fields']);
			
			// Get fields to use in the query
			$i = 0;
			$finalFields = '';
			foreach ($this->queryData['fields'] as $k => $field)
			{
				// Get the field type
				$type = !empty($rModel[$field['name']]['type']) ? $rModel[$field['name']]['type'] : '';
				
				$finalFields .= ( $i > 0 ? ", " : '' ) 
								. ( $type === 'timestamp' && $o['force_unix_timestamps']  ? "UNIX_TIMESTAMP(" : '' )
								. ( !empty($field['table']) 
									//? $field['table']
									? ( !empty($field['tableAlias']) ? $field['tableAlias'] : $field['table'] ) 
									//: $this->dbTableShortName ) . "."
									: $this->alias ) . "."
								. $field['name'] 
								. ( !empty($field['as']) ? " AS " . $field['as'] : '' )
								. ( $type === 'timestamp' && $o['force_unix_timestamps'] ? ") as " . $field['name'] : '' )
								;
				
				//if ( !empty($field['table']) && !empty($field['as']) ){ $finalFields .= ( $i > 0 ? ", " : '' ) .  $this->dbTableShortName . "." . $k; }
				//if ( !empty($field['table']) && !empty($field['as']) ){ $finalFields .= ( $i > 0 ? ", " : '' ) .  $this->alias . "." . $k; }
				
				// Add the count if specified to				
				//if ( $field['count'] ){ $finalFields .= ( $i > 0 ? ", " : '' ) . "count(" . $this->dbTableShortName . "." . $k . ") AS " . $k . "_total"; }
				if ( $field['count'] )
				{
					//$finalFields .= (( $i > 0 ) ? ", " : '' ) . "COUNT(" . $this->alias . "." . $k . ") AS " . $k . "_total";
					$finalFields .= ( !empty($finalFields) ? ", " : '' ) . "COUNT(" . $this->alias . "." . $k . ") AS " . $k . "_total";
				}
				
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
					if ( !empty($f['table']) && !empty($f['as']) )
					{
						//$groupByOthers .= ( $i > 0 ? ", " : '' ) . $k;
						$groupByOthers .= ( !empty($groupByOthers) ? ", " : '' ) . $k;
					}
					
					$i++;
				}

				//$groupByFields 	= is_array($o['groupBy']) ? join(", " . $this->dbTableShortName . ".", $o['groupBy']) : $o['groupBy'];
				$groupByFields 	= is_array($o['groupBy']) ? join(", " . $this->alias . ".", $o['groupBy']) : $o['groupBy'];
				//$groupBy 		= "GROUP BY " . $this->dbTableShortName . "." . $groupByFields . (!empty($groupByOthers) ? ", " . $groupByOthers : '') . " ";
				$groupBy 		= "GROUP BY " . $this->alias . "." . $groupByFields . (!empty($groupByOthers) ? ", " . $groupByOthers : '') . " ";
			}
			
			$orderBy = $this->handleOrder($o);
			
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
		
		//$rModel 	= $this->dataModel[$this->resourceName];
		$rModel 	= $this->application->dataModel[$this->resourceName];
		
		$fieldsNb 	= count($rModel);		// Get the number of fields for this resource
		$after 		= array();
		
//var_dump($d);
		
		// Start writing request
		//$query 		= "INSERT INTO " . _DB_TABLE_PREFIX . $this->dbTableName . " (";
		$query 		= "INSERT INTO " . _DB_TABLE_PREFIX . $this->table . " (";
		
		// Loop over the data model of the resource
		$i 			= 0;
		foreach ($rModel as $fieldName => $field)
		{
			$i++;
			if ( $field['type'] === 'int' && isset($field['AI']) && $field['AI'] ){ continue; } // Do not process autoincremented fields
			
			$query .= $this->safeWrapper . $fieldName . $this->safeWrapper . ($i < $fieldsNb ? ',' : ''); // Add each fields to the request, with coma if not last field
		}
		
		// Now we want to add the values
		$query 		.= ") VALUES (";
		
		// Loop over the passed resource data (filtered and validated POST data)
		$i 				= 0;
		$value			= null;
		$storedValues 	= array(); 
		foreach ($rModel as $fieldName => $field)
		{
			$skip = false;
				
			// Shortcuts
			/*
			$props = array('pk','ai','fk','type','subtype','default','length','relResource','relField','relGetFields','relGetAs',
							'computed','computedValues','eval','storeAs','destFolder','relatedFile');
			foreach ( $props as $prop ){ $$prop = !empty($field[$prop]) ? $field[$prop] : null; }
			*/
			
			$i++;
			
			// Do not process some fields
			if ( ( $field['type'] === 'int' && isset($field['AI']) && $field['AI'] ) ){ $skip = true; }
			
			// Skip current field process is we have to 
			if ( $skip ) { continue; }
			
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
			//if 		( $field['type'] === 'int' && isset($field['AI']) && $field['AI'] ){ continue; } // Do not process auto-incremented fields
			//else if ( !empty($field['subtype']) && $field['subtype'] === 'file' && !empty($d[$fieldName]) )
			if ( !empty($field['subtype']) && $field['subtype'] === 'file' && !empty($d[$fieldName]) )
			{
				// If the passed value is not an array, we do not handle file upload and just update db value 
				$uploadFile 	= is_array($d[$fieldName]);
				
				$destRoot 		= !empty($field['destRoot']) ? $field['destRoot'] : ''; 
				$destFolder 	= $field['destFolder'];
				
				// Get the setted destination name or use the uploaded file name 
				$destName 		= !empty($field['destName'])
									? str_replace("%file_extension%", $d[$fieldName]['extension'], $field['destName'])
									//: $this->resourceSingular . '_' . time() . '.' . $d[$fieldName]['extension'];
									: $d[$fieldName]['name'];
									
				if ( !$uploadFile )
				{
					//$value = "'" . $this->escapeString(trim(stripslashes($d[$fieldName]))) . "'";
					$value = "'" . $this->escapeString(trim(stripslashes($d[$fieldName]))) . "'";
				}
				// Otherwise (common case)
				else
				{
					// Handle duplicates/thumbnails generation if necessary
					if ( !empty($field['duplicates']) )
					{
						$this->requireLibs(array('Image' => 'tools/'));
						$params = array_merge($field['duplicates'], array('src' => $d[$fieldName]['tmp_name']));
						
						$duplicate = Image::getInstance()->duplicate($params);
						$d[$fieldName]['duplicates'] = $duplicate;
					}
					
					// Reset some values
					$renameFolder 	= $renameFile = $tempName = null; 
					
					// Loop over the resource fields to replace placeholders by proper value
					foreach ($rModel as $key => $value)
					{
						// Special case for id where the data to use is not in the resource data but in the options in 'values' var
						$time 			= $key === 'id' ? time() : null;
						$tmpReplaceVal 	= $this->escapeString($time !== null ? $time : ( !empty($d[$key]) ? $d[$key] : ''));
										
						// If a placeholder for the current column is found in the destination name or the destination folder
						// replace by the proper value
						$nmPlaceholder 	= strpos($destName, '%resource[\'' . $key . '\']%') !== false;
						$fdPlaceholder 	= strpos($destFolder, '%resource[\'' . $key . '\']%') !== false;
	
						if ( $nmPlaceholder || $fdPlaceholder )
						{						
							$destFolder = str_replace("%resource['" . $key . "']%", $this->escapeString($tmpReplaceVal), $destFolder);	
							$destName 	= str_replace("%resource['" . $key . "']%", $this->escapeString($tmpReplaceVal), $destName);
						}
						
						// Have the folder and file to be renamed after upload (only if placeholders used in their names)
						$renameFolder 	= (isset($renameFolder) && $renameFolder) || $fdPlaceholder;
						$renameFile 	= (isset($renameFile) && $renameFile) || $nmPlaceholder; 
						$tempName 		= !empty($tempName) ? $tempName : $time;  		
					}
					
					if ( !empty($field['storeOn']) && $field['storeOn'] === 'amazon_S3' )
					{
						class_exists('AmazonS3') || require(_PATH_LIBS . 'storage/CloudFusion/cloudfusion.class.php');
						
						$s3 	= new AmazonS3(_AWS_ACCESSKEY, _AWS_SECRET_KEY);
						$acl 	= !empty($field['acl']) && constant($field['acl']) ? constant($field['acl']) : S3_ACL_PRIVATE;
						$dest 	= $destRoot . $destFolder . $destName;
						$body 	= file_get_contents($d[$fieldName]['tmp_name']);
						$bucket = !empty($field['bucket']) ? $field['bucket'] : _AWS_BASE_BUCKET;
						
						// If file exists, rename it with a time_suffix
						if ( $s3->if_object_exists($bucket, $dest) ) { $s3->rename_object($bucket, $dest, $dest . '_old_' . time(), $acl); }
						
						// Then, create/replace the file/object
						$FileUpload 			= $s3->create_object($bucket, array('filename' => $dest, 'body' => $body, 'contentType' => $d[$fieldName]['type'], 'acl' => $acl )); 
						$FileUpload->success 	= !empty($FileUpload->status) && $FileUpload->status === 200;
						
						// Handle duplicates/thumbnails generation if necessary
						if ( !empty($field['duplicates']) && is_array($field['duplicates']) )
						{
							$tmpTime = time();
							
							foreach ( $d[$fieldName]['duplicates'] as $dup )
							{
								$storedFilePath = $destFolder . $dup['outputFilename'] . '_' . $tmpTime . '.' . $dup['outputFormat'];
								$destDup 	= $destRoot . $storedFilePath;
								$bodyDup 	= file_get_contents( $dup['outputDirname'] . $dup['outputBasename']);
								$dupUpload 	= $s3->create_object($bucket, array('filename' => $destDup, 'body' => $bodyDup, 'contentType' => $d[$fieldName]['type'], 'acl' => $acl ));
	
								$d[$fieldName][$dup['prefix']] = $storedFilePath;
							}
						}
					}
					// Default upload by ftp
					else
					{
						// Launch the file upload
						class_exists('FileManager') || require(_PATH_LIBS . 'storage/FileManager.class.php');
						$FileUpload = FileManager::getInstance()->uploadByFtp($d[$fieldName], array(
							'destFolder' 	=> $destRoot . $destFolder,
							'destName' 		=> $destName,
							//'destRoot' 		=> $destRoot,
							'filePath' 		=> $d[$fieldName]['tmp_name'],
							'allowedTypes' 	=> $field['allowedTypes'],
						));
					}
					
					if ( $FileUpload->success )
					{					
						// Keep a flag if either the file destination folder or name should contain the resource id 
						// (which is not known as the moment where the file is uploaded)
						$after['rename'][] = array(
							'storeOn' 		=> !empty($field['storeOn']) ? $field['storeOn'] : 'ftp',
							'currentFolder' => $destFolder,
							'currentName' 	=> $destName,
							'renameFolder' 	=> $renameFolder,	// has the folder to be renamed
							'renameFile' 	=> $renameFile, 	// has the file to be renamed
							'tempName' 		=> $tempName,
							'dbField' 		=> $fieldName,		// database colum to update on rename success
							'destRoot' 		=> $destRoot,		// Root folder (may be empty), should not be stored in the db value
							'acl' 			=> !empty($acl) ? $acl : null,
						);
						
						//$value = "'" . ( !empty($field['storeAs']) && $field['storeAs'] !== 'filename' ?  '' : $destFolder) . $destName . "'";
					}
					//else { continue; }
					else { $this->warnings[] = 6110; } // error on file upload
					
					// Even if the upload did not success, we need to add the value into db
					$value = "'" . ( !empty($field['storeAs']) && $field['storeAs'] !== 'filename' ?  '' : $destFolder) . $destName . "'";
				}
												

			}
			else if ( !empty($field['subtype']) && $field['subtype'] === 'fileMetaData' )
			{
				$relField 	= $field['relatedFile'];
				$meta 		= $field['meta'];
				$value 		= "'" . ( !empty($d[$relField]) ? $this->escapeString($d[$relField][$meta]) : '') . "'";
			}
			else if ( !empty($field['subtype']) && $field['subtype'] === 'fileDuplicate' )
			{
				$original 	= $field['original'];
				$prop 		= !empty($field['propertyName']) ? $field['propertyName'] : null;
				$tmpVal 	= $prop && !empty($d[$original][$prop]) ? $d[$original][$prop] : $d[$original];
				$value 		= "'" . ( !empty($d[$original]) ? $this->escapeString($tmpVal) : '') . "'";
			}
			else if ( !empty($field['subtype']) && $field['subtype'] === 'slug' && !empty($field['from']) )
			{
				$tmpVal = !empty($d[$fieldName]) 
							? $this->slugify($d[$fieldName])
							: ( !empty($d[$field['from']]) ? $this->slugify($d[$field['from']]) : '');
				$value 	= "'" . $this->escapeString($tmpVal) . "'";
			}
			else if ( isset($field['computed']) && $field['computed'] )
			{
				if ( $field['type'] === 'timestamp' ){ $value = $field['computedValue']; }
				// TODO: use proper str_replace ????
				else if ( !empty($field['subtype']) && $field['subtype'] === 'URIname' && !empty($field['useField']) )
				{
					$tmpVal = $this->deaccentize($d[$field['useField']]);
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
				//$value = "'" . $this->escapeString( isset($d[$fieldName]) ? trim($d[$fieldName]) : '') . "'";
				$value = "'" . $this->escapeString( isset($d[$fieldName]) ? trim(stripslashes($d[$fieldName])) : '') . "'";
			}
			//else if ( $field['type'] === 'varchar' )
			else if ( $field['type'] === 'varchar' && !empty($field['subtype']) && $field['subtype'] === 'password' )
			{
				$value = "'" . sha1($this->escapeString($d[$fieldName])) . "'";
			}
			else if ( $field['type'] === 'enum' )
			{
				$tmpVal = !empty($d[$fieldName]) ? $d[$fieldName] : ( !empty($field['default']) ? $field['default'] : '' );
				$value = "'" . $this->escapeString(trim(stripslashes($tmpVal))) . "'";  
			}
			else if ( $field['type'] === 'point' )
			{
				$tmpVal = !empty($d[$fieldName]) ? $d[$fieldName] : ( !empty($field['default']) ? $field['default'] : '' );
				if ($tmpVal !== '') $value = $this->escapeString(trim(stripslashes('POINTFROMTEXT(\''.$tmpVal.'\')')));
				else $value = "''";
			}
			else if ( $field['type'] === 'varchar' )
			{
				$tmpVal = !empty($d[$fieldName]) ? $d[$fieldName] : ( !empty($field['default']) ? $field['default'] : '' );
				$value = "'" . $this->escapeString(trim(stripslashes($tmpVal))) . "'";   
				//$value = "'" . $this->escapeString(trim($tmpVal)) . "'";
			}
			//else if ( $field['type'] === 'bool' ) { $value = ( !empty($d[$fieldName]) && $d[$fieldName]) ? 'true' : 'false'; }
			else if ( $field['type'] === 'bool' ) { $value = ( !empty($d[$fieldName]) && $d[$fieldName]) ? 1 : 0; }
			// Otherwise, just take the posted data value
			//else { $value = $d[$fieldName]; }
			else if ( $field['type'] === 'float' )
			{
				$tmpVal = !empty($d[$fieldName]) ? $d[$fieldName] : ( !empty($field['default']) ? $field['default'] : 0 );
				$value = "'" . $this->escapeString(  str_replace(',','.',(string)($tmpVal))) . "'";
			}
			/*
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
			*/
			else if ( $field['type'] === 'timestamp' )
			{
				// Get the passed value if present, otherwise, try to use default value
				$tmpVal = !empty($d[$fieldName]) ? $d[$fieldName] : (isset($field['default']) ? ( strpos($field['default'], 'now') !== false ? time() : '0' ) : 0);
				$value 	= is_int($tmpVal) && $tmpVal < 0 
							? "DATE_ADD(FROM_UNIXTIME(0), INTERVAL " . $this->escapeString($tmpVal) ." SECOND)"
							: "FROM_UNIXTIME('" . $this->escapeString($tmpVal) . "')";
			}
			else if ( $field['type'] === 'datetime' )
			{
				// TODO: how to handle not posted fileds
				$d[$fieldName] = isset($d[$fieldName]) ? $d[$fieldName] : '';
				
				$value = "'" . $this->escapeString($d[$fieldName]) . "'";
			}
			// Otherwise, just take the posted data value
			else
			{
				// TODO: isset(null) => false how to test if default prop setted, event if set to null?
				$value = !empty($d[$fieldName]) 
							? $d[$fieldName] 
							: (isset($field['default']) ? ( is_null($field['default']) ? "NULL" : $field['default']) : "''");
			}
			//else { $value = !empty($d[$fieldName]) ? $d[$fieldName] : (isset($field['default']) ? $field['default'] : (string) null); }
			
			// Finally, add the value to the request
			$query .= $value . ($i < $fieldsNb ? ',' : ''); // Add each fields to the request, with coma if not last field
			//$query .= "'" . $value . ($i < $fieldsNb ? ',' : '') . "'"; // Add each fields to the request, with coma if not last field
			
			// And store it in an array, for possible later use
			$storeValues[$fieldName] = $value; 
		}
		
		// Finish writing the request
		$query 		.= ")";
		
		//if ( !empty($o['returning']) ) { $query .= " RETURNING " . $o['returning']; }
		
		$this->launchedQuery 	= $query;
		$this->afterQuery 		= $after;
		
		return $query;
	}
	
	
	public function buildUpdate($resourceData, $options)
	{
		$d 			= $resourceData;										// Shortcut for resource data
		$o 			= $options; 											// Shortcut for options
		$fieldsNb 	= count($d);											// Get the number of fields for this resource
		
		//$rModel 	= $this->dataModel[$this->resourceName];
		$rModel 	= $this->application->dataModel[$this->resourceName];
		
		// Start writing request
		$query 		= "UPDATE ";
		$query 		.=  _DB_TABLE_PREFIX . $this->table . " AS " . $this->alias . " ";
		$query 		.= "SET ";
		
//$this->dump($d);
		
		// Loop over the passed resource data (filtered and validated POST data)
		$i 			= 0;
		$value		= null;
		foreach ($rModel as $fieldName => $field)
		{
			// Shortcuts, maybe usefull to prevent !empty() checks and use props directly
			/*
			$props = array('pk','ai','fk','type','subtype','default','length','relResource','relField','relGetFields','relGetAs',
							'computed','computedValues','eval','storeAs','destFolder','relatedFile');
			foreach ( $props as $prop ){ $$prop = !empty($field[$prop]) ? $field[$prop] : null; }
			*/
			
			// If a field is not passed in the data, do not add it to the request
			$skip = !isset($d[$fieldName]) || $d[$fieldName] === null;
			
			// Do not process not editable, autoincrement fields fields
			if ( ( isset($field['editable']) && !$field['editable'] ) || ( $field['type'] === 'int' && isset($field['AI']) && $field['AI'] ) )
				{ $skip = true; }
				
			if ( isset($field['forceUpdate']) && $field['forceUpdate'] ){ $skip = false; }
			
			// except for fields whose subtype is fileMetaData
			if ( !empty($field['subtype']) && $field['subtype'] === 'fileMetaData' && !empty($d[$field['relatedFile']]) ) { $skip = false; }
			
			// except for fields whose subtype is fileDuplicate
			if ( !empty($field['subtype']) && $field['subtype'] === 'fileDuplicate' && !empty($field['original']) && !empty($d[$field['original']]) ) { $skip = false; }
			
			// For password fields, only users to modifie oneself password 
			//if ( isset($field['subtype']) && $field['subtype'] === 'password' && $this->resourceName === 'users' )
			if ( isset($field['subtype']) && $field['subtype'] === 'password' && $this->resourceName === 'users' )
			{
				// Get the user whose data are being updated and get the current user
				$updatedUser 	= CUsers::getInstance()->retrieve(array_merge($o, array('limit' => 1)));
				//$currentUser 	= !empty($this->data['current']['user']) ? $this->data['current']['user'] : null; 
				$currentUser 	= CUsers::getInstance()->retrieve(array_merge($o, array('limit' => 1, 'by' => 'id', 'values' => $_SESSION['users_id'])));

				// Has the current user higher authorization than the updated one
				$foundUsersData = !empty($updatedUser) && !empty($currentUser);
				$hasHigherAuth 	= $foundUsersData && $currentUser['auth_level_nb'] > $updatedUser['auth_level_nb'];
				$allowEdit	 	= $foundUsersData && ( $updatedUser['id'] === $currentUser['id'] || ($currentUser['auth_level_nb'] >= 500 && $hasHigherAuth));
				$skip 			= $allowEdit ? false : true;
				
				// If the users data have been found but the current user is not allowed to edit password for this user 
				if ( $foundUsersData && $skip ){ $this->warnings[] = 6050; }
			}
			
			// Skip current field process is we have to 
			if ( $skip ) { continue; }
			
			$i++;
			
			// Handle value treatments/filters via eval
			if ( !empty($field['eval']) )
			{
				$phpCode 		= str_replace('---self---', '\'' . $d[$fieldName] . '\'', $field['eval']);
				$d[$fieldName] 	= eval('return ' . $phpCode . ';');
				$phpCode 		= null;
			}
			
			// Handle specific cases
			//if 		( $field['type'] === 'int' && isset($field['AI']) && $field['AI'] ){ continue; } // Do not process auto-incremented fields
			//else if ( !empty($field['subtype']) && $field['subtype'] === 'file' && ( !isset($o['upload']) || $o['upload'] !== false ) )
			if ( !empty($field['subtype']) && $field['subtype'] === 'file' && ( !isset($o['upload']) || $o['upload'] !== false ) )
			{
				// If the passed value is not an array, we do not handle file upload and just update db value 
				$uploadFile 	= is_array($d[$fieldName]);
				
				$destRoot 		= !empty($field['destRoot']) ? $field['destRoot'] : ''; 
				$destFolder 	= $field['destFolder'];
				
				if ( isset($_GET['forceFileDeletion']) && $_GET['forceFileDeletion'] )
				{
					//class_exists('FileManager') || require(_PATH_LIBS . 'storage/FileManager.class.php');
					$this->requireLibs(array('FileManager' => 'storage/'));
					
					
					// Get the current value
					$curData 	= $this->retrieve(array_merge($options), array('getFields' => $fieldName));
					$curVal 	= !empty($curData) && isset($curData[$fieldName]) ? $curData[$fieldName] : null;
					$filePath 	= $destRoot . $curVal;
					
					// Remove the file from the ftp
					if ( !empty($curVal) ){ FileManager::getInstance()->delete($filePath); }

					// Finally, set the new value as an empty string
					$value = "''";
				}
				else if ( !$uploadFile )
				{
					//$value = "'" . $this->escapeString(trim(stripslashes($d[$fieldName]))) . "'";
					$value = "'" . $this->escapeString(trim(stripslashes($d[$fieldName]))) . "'";
				}
				// Otherwise (common case)
				else
				{					
					// Get the setted destination name or use the uploaded file name 
					$destName 		= !empty($field['destName'])
										? str_replace("%file_extension%", $d[$fieldName]['extension'], $field['destName'])
										//: $this->resourceSingular . '_' . time() . '.' . $d[$fieldName]['extension'];
										: $d[$fieldName]['name'];
					
					if ( !empty($field['duplicates']) )
					{
						$this->requireLibs(array('Image' => 'tools/'));
						$params = array_merge($field['duplicates'], array('src' => $d[$fieldName]['tmp_name']));
						
						$duplicate = Image::getInstance()->duplicate($params);
						$d[$fieldName]['duplicates'] = $duplicate;
					}
					
					// Loop over the resource fields to replace placeholders by proper value
					foreach ($rModel as $key => $value)
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
					
					if ( !empty($field['storeOn']) && $field['storeOn'] === 'amazon_S3' )
					{
						class_exists('AmazonS3') || require(_PATH_LIBS . 'storage/CloudFusion/cloudfusion.class.php');
						
						$s3 	= new AmazonS3(_AWS_ACCESSKEY, _AWS_SECRET_KEY);
						$acl 	= !empty($field['acl']) && constant($field['acl']) ? constant($field['acl']) : S3_ACL_PRIVATE;
						$dest 	= $destRoot . $destFolder . $destName;
						$body 	= file_get_contents($d[$fieldName]['tmp_name']);
						$bucket = !empty($field['bucket']) ? $field['bucket'] : _AWS_BASE_BUCKET;
						
						// If file exists, rename it with a time_suffix
						if ( $s3->if_object_exists($bucket, $dest) ) { $s3->rename_object($bucket, $dest, $dest . '_old_' . time(), $acl); }
						
						// Then, create/replace the file/object
						$FileUpload 			= $s3->create_object($bucket, array('filename' => $dest, 'body' => $body, 'contentType' => $d[$fieldName]['type'], 'acl' => $acl )); 
						$FileUpload->success 	= !empty($FileUpload->status) && $FileUpload->status === 200;
						
						// Handle duplicates if necessary
						if ( !empty($field['duplicates']) && is_array($field['duplicates']) )
						{
							$tmpTime = time();
							
							foreach ( $d[$fieldName]['duplicates'] as $dup )
							{
								$storedFilePath = $destFolder . $dup['outputFilename'] . '_' . $tmpTime . '.' . $dup['outputFormat'];
								$destDup 		= $destRoot . $storedFilePath;
								$bodyDup 		= file_get_contents( $dup['outputDirname'] . $dup['outputBasename']);
								
								// If file exists, rename it with a time_suffix
								if ( $s3->if_object_exists($bucket, $destDup) ) { $s3->rename_object($bucket, $destDup, $destDup . '_old_' . $tmpTime, $acl); }
								
								$dupUpload 	= $s3->create_object($bucket, array('filename' => $destDup, 'body' => $bodyDup, 'contentType' => $d[$fieldName]['type'], 'acl' => $acl ));
								
								$d[$fieldName][$dup['prefix']] = $storedFilePath;
							}
						}
					}
					// Default upload by ftp
					else
					{
						class_exists('FileManager') || require(_PATH_LIBS . 'storage/FileManager.class.php');
						
						$FileUpload = FileManager::getInstance()->uploadByFtp($d[$fieldName], array(
							'destFolder' 	=> $destRoot . $destFolder,
							'destName' 		=> $destName,
							//'destRoot' 		=> $destRoot,
							'filePath' 		=> $d[$fieldName]['tmp_name'],
							'allowedTypes' 	=> $field['allowedTypes'],
						));
					}
					
					if ( $FileUpload->success )
					{
						//$value = "'" . ( !empty($field['storeAs']) && $field['storeAs'] !== 'filename' ?  '' : $destFolder) . $destName . "'";
					}
					//else { continue; }
					else { $this->warnings[] = 6110; } // error on file upload
					
					$value = "'" . ( !empty($field['storeAs']) && $field['storeAs'] !== 'filename' ?  '' : $destFolder) . $destName . "'";	
				}
			}
			else if ( !empty($field['subtype']) && $field['subtype'] === 'fileMetaData' )
			{
				// If the upload options has been set to false, do not process the fied
				if ( !empty($o['upload']) && !$o['upload'] ) { continue; }
				
				$relField 	= $field['relatedFile'];
				$meta 		= $field['meta'];
				$value 		= "'" . ( !empty($d[$relField]) ? $this->escapeString($d[$relField][$meta]) : '') . "'";
			}
			else if ( !empty($field['subtype']) && $field['subtype'] === 'fileDuplicate' )
			{
				// If the upload options has been set to false, do not process the field
				if ( isset($o['upload']) && !$o['upload'] ) { continue; }
				
				$original 	= $field['original'];
				$prop 		= !empty($field['propertyName']) ? $field['propertyName'] : null;
				$tmpVal 	= $prop && !empty($d[$original][$prop]) ? $d[$original][$prop] : ( is_string($d[$original]) ? $d[$original] : '' );
				$value 		= "'" . ( !empty($d[$original]) ? $this->escapeString($tmpVal) : '') . "'";
			}
			else if ( !empty($field['subtype']) && $field['subtype'] === 'slug' && !empty($field['from']) )
			{
				$tmpVal = !empty($d[$fieldName]) 
							? $this->slugify($d[$fieldName])
							: ( !empty($d[$field['from']]) ? $this->slugify($d[$field['from']]) : '');
				$value 	= "'" . $this->escapeString($tmpVal) . "'";
			}
			//else if ( !empty($field['computed']) && $field['type'] === 'timestamp' ){ $value = $field['computedValue']; }
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
			//else if ( $field['type'] === 'bool' ) { $value = ( !empty($d[$fieldName]) && $d[$fieldName]) ? 'true' : 'false'; }
			else if ( $field['type'] === 'bool' ) { $value = ( !empty($d[$fieldName]) && $d[$fieldName]) ? 1 : 0; }
			else if ( $field['type'] === 'float' )
			{
				$value = "'" . $this->escapeString(  str_replace(',','.',(string)($d[$fieldName]))) . "'";
			}
			/*
			else if ( $field['type'] === 'timestamp' )
			{
				$value = is_int($d[$fieldName]) && $d[$fieldName] < 0 
							? "DATE_ADD(FROM_UNIXTIME(0), INTERVAL " . $d[$fieldName] ." SECOND)"
							: "FROM_UNIXTIME('" . $d[$fieldName] . "')";
			}
			*/
			else if ( $field['type'] === 'timestamp' )
			{
				// Get the passed value if present, otherwise, try to use default value
				//$tmpVal = !empty($d[$fieldName]) ? $d[$fieldName] : (isset($field['default']) ? ( strpos($field['default'], 'now') !== false ? time() : '0' ) : 0);
				$tmpVal = !empty($d[$fieldName]) ? $d[$fieldName] : (isset($field['default']) ? ( strpos($field['default'], 'now') !== false ? time() : '0' ) : time());
//$this->dump($tmpVal);
//$this->dump(strftime('%Y-%m-%d %H:%M:%S',$tmpVal));
				$value 	= is_int($tmpVal) && $tmpVal < 0 
							? "DATE_ADD(FROM_UNIXTIME(0), INTERVAL " . $this->escapeString($tmpVal) ." SECOND)"
							: "FROM_UNIXTIME('" . $this->escapeString($tmpVal) . "')";
			}
			else if ( $field['type'] === 'datetime' )
			{
				// TODO: how to handle not posted fileds
				$d[$fieldName] = isset($d[$fieldName]) ? $d[$fieldName] : '';
				
				$value = "'" . $this->escapeString($d[$fieldName]) . "'";
			}
			//else if ( $field['type'] === 'varchar' )
			else if ( $field['type'] === 'varchar' && !empty($field['subtype']) && $field['subtype'] === 'password' )
			{
				$tmpVal = !empty($d[$fieldName]) ? sha1($d[$fieldName]) : '';
				$value 	= "'" . $tmpVal . "'";
			}
			else if ( $field['type'] === 'enum' )
			{
				$tmpVal = !empty($d[$fieldName]) ? $d[$fieldName] : ( !empty($field['default']) ? $field['default'] : '' );
				$value = "'" . $this->escapeString(trim(stripslashes($tmpVal))) . "'";  
				//$value = "'" . $this->escapeString(trim($tmpVal)) . "'";
			}
			else if ( $field['type'] === 'point' )
			{
				$tmpVal = !empty($d[$fieldName]) ? $d[$fieldName] : ( !empty($field['default']) ? $field['default'] : '' );
				if ($tmpVal !== '') $value = $this->escapeString(trim(stripslashes('POINTFROMTEXT('.$tmpVal.')')));
				else $value = "''";
			}
			else if ( $field['type'] === 'varchar' )
			{
				//$value = "'" . $this->escapeString($d[$fieldName]) . "'";
				$value = "'" . $this->escapeString(trim(stripslashes($d[$fieldName]))) . "'";  
				//$value = "'" . $this->escapeString(trim($d[$fieldName])) . "'";
			}
			// Otherwise, just take the posted data value
			else
			{
				$value = "'" . $d[$fieldName] . "'";
			}
			
			$query .= ($i == 1 ? '' : ', ') . $this->escapeColumn($fieldName) . " = " . $value; // Add each fields to the request, with coma if not last field
		}
		
		$orderBy = $this->handleOrder($o);
		
		// Finish writing the request
		//$query 		.= " WHERE " . $o['by'] . " = '" . $this->escapeString($o['values']) . "'";
		$query 		.= !empty($o['conditions'])
						? " " . $this->handleConditions($o)
						: " WHERE " . $o['by'] . " = '" . $this->escapeString($o['values']) . "'";
		$query 		.= 	( !empty($orderBy) ? $orderBy . " " : '' );
		$query 		.= 	( !empty($o['limit']) && $o['limit'] != -1 ? " LIMIT " . $o['limit'] . " " : '' );
		$query 		.= 	( !empty($o['offset']) ? " OFFSET " . $o['offset'] . " " : '' );
		
		$this->launchedQuery = $query;
		
		return $query;
	}


	public function buildDelete($options)
	{
		$o 			= $options; 											// Shortcut for options
	
		// Build WHERE (concatenating values)
		$where 		= $this->handleOperations($o);
		$conditions = $this->handleConditions($o);
	
		// Start writing request
		// When using "AS", mysql seems want to have the defined as just before the FROM
		//$query 		= "DELETE " . $this->dbTableShortName . " ";
		$query 		= "DELETE " . $this->alias . " ";
		//$query 		= "DELETE ";
		//$query 		.= "FROM " . _DB_TABLE_PREFIX . $this->dbTableName . " AS " . $this->dbTableShortName . " ";
		$query 		.= "FROM " . _DB_TABLE_PREFIX . $this->table . " AS " . $this->alias . " ";
		//$query 		.= $where;
		$query 		.= 	$where . $conditions;
		
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
				# 2 possible patterns
				# - simple: conditions => array('col1' => 'val1', 'col2' => 'val2', ...)
				# - advanced: conditions => array(array('col1','operationType','val1'), array('col2','operationType','val2')) 
				$pattern 	= is_numeric($condFieldName) && is_array($condFieldValues) ? 'advanced' : 'simple';
				
				if ( !isset($condFieldValues) ) { break; }
				
				if ( $pattern === 'advanced' )
				{					
					$argsNb 	= count($condFieldValues);
					$colName 	= $condFieldValues[0];
					//$opType 	= $condFieldValues[1];
					$opType 	= !empty($condFieldValues[1]) ? $condFieldValues[1] : '='; // Default operator to '='
					//$condValue 	= $condFieldValues[2];
					$condValue 	= $argsNb === 3 ? $condFieldValues[2] : $condFieldValues[1];
					
					$conditions .= empty($o['values']) && $i == 0 ? "WHERE " : " AND ";
					//$conditions .= $this->alias . ".";
					$conditions .= ( !empty($this->queryData[$condFieldName]) ? $this->queryData[$condFieldName]['tableAlias'] : $this->alias ) . ".";
					//$conditions .= $colName . " " . $opType . " " . $condValue;
					$conditions .= $colName . " " . $opType . " ";
					$conditions .= $opType  === '=' && is_array($condValue) 
										? ' IN (' . (join("', '", is_bool($condValue) ? (int) $condValue : $condValue)) . ' ) ' 
										: ( is_bool($condValue) ? (int) $condValue : $condValue );
										//: ( is_bool($condValue) ? (int) $condValue : "'" . $condValue . "'" );
										
				}
				else
				{
					$conditions .= empty($o['values']) && $i == 0 ? "WHERE " : " AND ";
					//$conditions .= ( !empty($this->queryData[$condFieldName]) ? $this->queryData[$condFieldName] : $this->dbTableShortName ) . ".";
					//$conditions .= ( !empty($this->queryData[$condFieldName]) ? $this->queryData[$condFieldName] : $this->alias ) . ".";
					$conditions .= ( !empty($this->queryData[$condFieldName]) ? $this->queryData[$condFieldName]['tableAlias'] : $this->alias ) . ".";
					$conditions .= $condFieldName . " IN ('";
					$conditions .= is_array($condFieldValues) 
									? join("', '", is_bool($condFieldValues) ? (int) $condFieldValues : $condFieldValues) 
									//: $condFieldValues;
									: ( is_bool($condFieldValues) ? (int) $condFieldValues : $condFieldValues );
									//: ( is_bool($condFieldValues) ? (int) $condFieldValues : "'" . $condFieldValues . "'" );
					$conditions .= "') ";
				}

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
	
	
	public function handleOrder($options = array())
	{
		$o 			= $options; 		// Shortcut for options
		
		// Build ORDER BY
		$orderBy = $tmpOrderBy = '';
		if ( !empty($o['sortBy']) )
		{
			$o['sortBy'] = $this->magic($o['sortBy']);
					
			$i = 0;
			foreach ($o['sortBy'] as $f)
			{
				// Shortcut for fields query props 
				$qf = !empty($this->queryData['fields'][$f]) ? $this->queryData['fields'][$f] : null;
//$this->dump($f);
//var_dump($o['type']);
//$this->dump($this->queryData['fields']);

				// If the field is not present in the gotten fields (case for select request)
				// do NOT use the ORDER clause with it
				//if ( !isset($f, $this->queryData['fields'][$f]) && ( !empty($o['type']) && $o['type'] !== 'update' )  ){ continue; }
				//if ( !empty($qf) && ( !empty($o['type']) && $o['type'] !== 'update' )  ){ continue; }
				if ( empty($qf) && ( empty($o['type']) || ( !empty($o['type']) && $o['type'] !== 'update' ) )  ){ continue; }
									
				$tmpOrderBy .= ($i === 0 ? '' : ", ")  
								//. ( isset($this->queryData['fields'][$f]) ? $this->alias . "." : '' )
								. ( !empty($qf['table']) ? ( !empty($qf['tableAlias']) ? $qf['tableAlias'] : $qf['table'] ) . "." : '' )
								//. $f
								. ( !empty($qf['table']) ? $qf['name'] : $f)
								. ( ( strpos($f, ' ASC') > -1 || strpos($f, ' DESC') > -1 ) ? '' : ( !empty($o['orderBy']) ? " " . $o['orderBy'] : '') );
				$i++;
			}
			
			$orderBy .= !empty($tmpOrderBy) ? " ORDER BY " . $tmpOrderBy : '';
		}
		
//var_dump($orderBy);
		
		return $orderBy;
	}
	
	
	public function escapeColumn($name = '')
	{
		return $this->safeWrapper . $name . $this->safeWrapper;
	}
	
	
	public function index($options = array())
	{
		// Set default params
		// TODO: use $this->options instead, and use array_merge
		$o 				= $options;
		$o['by'] 		= !empty($o['by']) ? $o['by'] : 'id';
		$o['sortBy'] 	= !empty($o['sortBy']) ? $o['sortBy'] : 'id';
		$o['orderBy'] 	= !empty($o['orderBy']) ? $o['orderBy'] : 'ASC';
		$o['type'] 		= 'select';
		
		// If a manual query has not been passed, build the proper one
		//$query 	= $this->buildSelect($o);
		$query 	= !empty($o['manualQuery']) ? $o['manualQuery'] : $this->buildSelect($o);
		
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
		
		// If a manual query has not been passed, build the proper one
		//$query 	= $this->buildInsert($resourceData, $o);
		$query 	= !empty($o['manualQuery']) ? $o['manualQuery'] : $this->buildInsert($resourceData, $o);
		
		$this->log($query);

		// Execute the query and store the returned data
		$this->query($query, $o);
		
		//return ( !empty($o['returning']) && count((array) $o['returning']) === 1 ) ? $this->data[$o['returning']] : $this;
		return !empty($o['returning']) ? ( isset($this->data[$o['returning']]) ? $this->data[$o['returning']] : null) : $this;
	}
	
	
	public function retrieve($options = array())
	{
		// TODO: use $this->options instead, and use array_merge
		$o 				= $options;
		$o['by'] 		= !empty($o['by']) ? $o['by'] : 'id';
		$o['values'] 	= !empty($o['values']) ? $this->magic($o['values']) : null;
		$o['mode']		= !empty($o['mode']) ? $o['mode'] : ( count($o['values']) <= 1 ? 'onlyOne' : null );
		$o['limit'] 	= $o['mode'] !== 'onlyOne' && !empty($o['limit']) ? $o['limit'] : 1;
		$o['type'] 		= 'select';
		
		// Do not continue if no value has been passed
		//if ($o['values'] === null) { return false; }
		
		// If a manual query has not been passed, build the proper one
		//$query 	= $this->buildSelect($o);
		$query 	= !empty($o['manualQuery']) ? $o['manualQuery'] : $this->buildSelect($o);
		
		$this->log($query);
		
		$this->data = $this->query($query, $o)->data;
		
		return $this->data;
	}
	
	
	public function update($resourceData = null, $options = array())
	{
		// TODO: use $this->options instead, and use array_merge
		$o 				= $options;
		$o['by'] 		= !empty($o['by']) ? $o['by'] : 'id';
		$o['values'] 	= !empty($o['values']) ? $o['values'] : null;
		$o['limit'] 	= !empty($o['limit']) ? $o['limit'] : null;
		$o['type'] 		= 'update';
		
		// Do not continue if no data or no item value has been passed 
		//if ( empty($resourceData) || empty($o['values']) ) { return; }
		if ( empty($resourceData) ) { return; }
		
		// If a manual query has not been passed, build the proper one
		//$query = $this->buildUpdate($resourceData, $o);
		$query 	= !empty($o['manualQuery']) ? $o['manualQuery'] : $this->buildUpdate($resourceData, $o);
		
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
		if ( empty($o['values']) && empty($o['conditions']) ) { return false; }
		
		// Build the proper query
		$query = $this->buildDelete($o);
		
		$this->log($query);

		// Execute the query and store the returned data
		$this->query($query, $o);
		
		return $this;
	}

}
?>