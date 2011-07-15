<?php

class DataModel
{
	static $resources 	= array();
	static $groups 		= array();
	static $columns 	= array();
	
	//static $_r 			= null;
	//static $_c 			= null;
	//static $_gp 		= null;
	
	public function _construct()
	{
		// Define aliases
		//self::$_r 	= &$this->resources;
		//self::$_c 	= &$this->columns;
		//self::$_gp 	= &$this->groups;
	}
	
	// 
	public function build($params = array())
	{
		//$this->buildResources();
		//$this->buildGroups();
		//$this->buildColumns();
		
		$params = array_merge(array(
			'what' => 'resources,colums,groups',
		), $params); 
		
		$dir = 'config/dataModel/';
		
		// Create a zip archive, open it, create the proper folder and create the file
		$zipFile 	= tempnam('tmp', 'zip');
		$zip 		= new ZipArchive();
		$zip->open($zipFile, ZipArchive::OVERWRITE);
		$zip->addEmptyDir($dir);
		
		$files = Tools::toArray($params['what']);
		
		foreach( $files as $name)
		{
			$mthd = 'generate' . ucfirst($name);
			$zip->addFromString($dir . $name . '.php', self::$mthd());
		}
		//$zip->addFromString($dir . 'resources.php', self::generateResources());
		//$zip->addFromString($dir . 'columns.php', self::generateColumns());
		//$zip->addFromString($dir . 'groups.php', self::generateGroups());
		
		$zip->close();
		
		// Stream the file to the client
		header('Content-Type: application/zip');
		header('Content-Length: ' . filesize($zipFile));
		header('Content-Disposition: attachment; filename="[' . _APP_NAME . ']_' . 'dataModel.zip"');
		readfile($zipFile);
		unlink($zipFile);
		
		$this->generate();
	}
	
	public function buildResources()
	{
		return $this->build(array('what' => 'resources'));
	}
	
	public function buildColumns()
	{
		return $this->build(array('what' => 'columns'));
	}
	
	public function buildGroups()
	{
		return $this->build(array('what' => 'groups'));
	}

	
	public function parse()
	{
		$this->parseResources();
		$this->parseGroups();
		$this->parseColumns();
	}
	
	// Merge order: database, dataModel (generated), dataModel (manual)
	public function parseResources()
	{
		// Get resources from dataModel
		require(_PATH_CONF . 'dataModel.php');
		
		// Get unregistered resources from database
		// TODO
		
		// Get registered resources from database
		$dbResources = CResources::getInstance()->index(array('reindexby' => 'name', 'isUnique' => 1));
		
		// Merge both
		self::$resources = array_merge($resources, $dbResources);
		
//var_dump(self::$resources);
//die();
		
		// Loop over the resources
		foreach ( self::$resources as $name => &$res )
		{
			$res = array_merge($res,array(
				'name' 				=> $name,
				'type' 				=> !empty($res['type']) ? $res['type'] : $this->guessResourceType($name),
				'singular' 			=> !empty($res['singular']) ? $res['singular'] : Tools::singular($name),
				'plural' 			=> !empty($res['plural']) ? $res['plural'] : $name,
			));
			$res = array_merge($res,array(
				// TODO
				'database' 			=> 'default',
				'table' 			=> !empty($res['table']) ? $res['table'] : self::getDbTableName($name),
				'alias' 			=> !empty($res['alias']) ? $res['alias'] : self::getDbTableName($name),
				'displayName' 		=> !empty($res['displayName']) ? $res['displayName'] : $name,
				// TODO: deprecate. use nameField instead
				'defaultNameField' 	=> !empty($res['defaultNameField']) ? $res['defaultNameField'] : self::guessNameField($name),
				'nameField' 		=> !empty($res['nameField']) ? $res['nameField'] : self::guessNameField($name),
				'extends' 			=> !empty($res['extends']) ? $res['extends'] : null,
				'searchable' 		=> !empty($res['searchable']) ? $res['searchable'] : 0,
				'exposed' 			=> !empty($res['exposed']) ? $res['exposed'] : 0,
				'crudability' 		=> !empty($res['crudability']) ? $res['crudability'] : 'CRUD',
			));
			/*
			$res['name'] 			= $name;
			$res['type'] 			= !empty($res['type']) ? $res['type'] : $this->guessResourceType($name);
			$res['singular'] 		= !empty($res['singular']) ? $res['singular'] : Tools::singular($name);
			$res['plural'] 			= !empty($res['plural']) ? $res['plural'] : $name;
			// TODO
			$res['database'] 		= 'default';
			$res['table'] 			= !empty($res['table']) ? $res['table'] : self::getDbTableName($name);
			$res['alias'] 			= !empty($res['alias']) ? $res['alias'] : $res['table'];
			 */
			ksort($res);
		}

//var_dump(self::$resources);
//die();

	}
	
	public function parseGroups()
	{
		// TODO
	}
	
	
	// Merge order: database, dataModel (generated), dataModel (manual)
	public function parseColumns()
	{		
		// If the resource are not found, parse them
		if ( !self::$resources ){ self::parseResources(); }

		// Get columns in db, manual dataModel & generated dataModel files
		$dbCols 			= $this->parseDBColumns(array_keys(self::$resources));
		$writtenCols 		= $this->parseManualDataModelColumns();
		//$generatedCols 		= $this->parseGeneratedDataModelColumns();

		// Merge them temporaliry into an unique array
		//$tmpColumns 		= array_merge($dbCols, $writtenCols, $generatedCols);
		$tmpColumns 		= array_merge($dbCols, $writtenCols);
		//self::$columns
		
//var_dump($tmpColumns);
		
		// Loop over this temp array
		foreach ( array_keys((array) $tmpColumns) as $rName )
		{
//var_dump($rName);
			
			// Shortcut for current resource columns
			$rCols = &$tmpColumns[$rName];
			
			// Loop over the columns of the current resource
			$i 		= 0;
			$colsNb = count($rCols); 
			foreach ( array_keys((array) $rCols) as $cName )
			{
//var_dump($cName);
				
				// Shortcut for column properties
				$p = &$rCols[$cName];
				
				// Set default props values is not already defined
				self::$columns[$rName][$cName] = array_merge(array(
					//'name' 				=> $p['Field'],
					'type' 				=> null,
					'realtype' 			=> null,
					'length' 			=> null,
					'null' 				=> false,
					'unsigned' 			=> true, 	// TODO
					'pk' 				=> false,
					'possibleValues' 	=> null, 	// deprecated: use values instead
					'values' 			=> null,
					
					// Relations
					'relResource' 		=> null,
					'relField' 			=> null,
					'relGetFields' 		=> null, 	// 'field1,field2,...' or array('field1','field2',...) or array('field1' => 'my_field_1', 'field2' => 'my_field_2')
					'relGetAS' 			=> null, 	// TODO: deprecate. use associative array in relGetFields
					'pivotResource' 	=> null, 	//
					'pivotLeftField' 	=> null, 	//
					'pivotRightField' 	=> null, 	// 
					
					// Format and/or validation
					'default' 			=> null,
					'placeholder' 		=> null, 
					'computed' 			=> false,	// deprecate. implements custom types instead (possibily with modifiers)
					'unique' 			=> false, 	//
					'index' 			=> false,
					'required' 			=> false,
					'eval' 				=> null, 	// deprecate: implement modifiers instead
					'modifiers' 		=> null, 	// TODO: implement trim|lower|upper|camel|capitalize|now|escape, ....
					'computedValue' 	=> null,	// deprecate. implements custom types instead (possibily with modifiers)
					'pattern' 			=> null,
					'step' 				=> null,
					'min' 				=> null,
					'max' 				=> null,
					
					// Files
					'forceUpload' 		=> false,
					'storeOn' 			=> null, 	// ftp|amazon_s3|amazon_ec2
					'acl' 				=> null, 	// S3_ACL_PRIVATE, S3_ACL_PUBLIC, S3_ACL_OPEN, S3_ACL_AUTH_READ. default = S3_ACL_PRIVATE
					'destRoot' 			=> null,

					'exposed' 			=> null,
					
					// UI or admin purpose
					'displayName' 		=> null,
					'displayedValue' 	=> null,
					'editable' 			=> false,
					'list' 				=> 0,
					'comment' 			=> null, 	
				), $p);
				
				### Now, we can start to do some magic
				
				// Handle Numeric types
				if ( $p['type'] === 'varchar' )
				{
					
				}
				// Handle Numbers types
				elseif ( $p['type'] === 'int' )
				{
					// has AI && is first
					if ( isset($p['AI']) && $i === 0 )
					{
						$p['type'] = 'serial';
					}	
				}
					
				$i++;
			}
		}

//var_dump(self::$columns);

		return self::$columns;
	}
	
	// string: resource name
	// string: csv resource names
	// array: resource names
	// return: array (resource name => columns) is several resource names passed
	// return: array of columns of the passed resource name
	public function parseDBColumns($resources)
	{
		// Force the resource names to be an array
		$rNames = Tools::toArray($resources);
		
		// Init returned data array
		$dbCols = array();

		// Loop over the resource names		
		foreach($rNames as $rName)
		{
			// Set a shortcut to current resource data
			$resource = &self::$resources[$rName];
			
			// Get resource DB columns data using proper query  
			$query 		= "DESCRIBE " . $resource['table'] . ";"; 
			$dbColumns 	= CResourcescolumns::getInstance()->index(array('manualQuery' => $query));
			
			// Do no continue if the table does not exists in DB	
			if ( empty($dbColumns) ){ die($rName); }
			
			// Loop over the found columns
			foreach ( array_keys($dbColumns) as $dbColumn )
			{
				// Shortcut for column properties
				$p = &$dbColumns[$dbColumn];
				
				$realtype 	= strpos($p['Type'], '(') !== false ? substr($p['Type'], 0, strpos($p['Type'], '(')) : $p['Type'];
				$values 	= $realtype === 'enum' 
								? explode(',', str_replace(array('enum(',')',"'"),'', $p['Type']) ) 
								: null;
				
				$dbCols[$rName][$p['Field']] = array(
					'name' 				=> $p['Field'],
					'realtype' 			=> $realtype,
					'length' 			=> strpos($p['Type'], '(') !== false ? (int) substr($p['Type'], strpos($p['Type'], '(') + 1, -1) : null,
					'null' 				=> $p['Null'] === 'YES' ? true : false,
					'pk' 				=> $p['Key'] === 'PRI' ? true : false,
					'values' 			=> $values,
				);
			}
		}
		
		return count($rNames) === 1 ? $dbCols[$rNames[0]] : $dbCols;
	}

	public function parseManualDataModelColumns()
	{
		// Get resources from dataModel
		require(_PATH_CONF . 'dataModel.php');
		
		// Init manual dataModel columns to an empty array
		$manualCols = array();
		
		// If the the dataModel manuel file is defined, and is an array, take it
		if ( is_array($dataModel) ){ $manualCols = &$dataModel; }
		
		return $manualCols;
	}
	
	
	public function parseGeneratedDataModelColumns()
	{
		$generatedCols = array();
		
		return $generatedCols;
	}
	
	
	public function generate()
	{
		$this->generateResources();
		$this->generateGroups();
		$this->generateColumns();
	}
	
	public function generateResources()
	{		
		$lb 			= "\n";
		$tab 			= "\t";
		$code 			= '<?php' . $lb . $lb . '$_resources = array(' . $lb;
		$longer 		= null;
		
		$rPropNames 	= array(
			// Semantic props
			'type', 'singular', 'plural', 'displayName',
			// Database binding props
			'database', 'table', 'alias',
			// Relation props
			'defaultNameField', 'nameField', 'extends',
			// Misc props
			'searchable', 'exposed', 'crudability'
		); 
		
		// Build an array or resource names only (for perf issues)
		$resNames 		= array_keys(self::$resources);
		
		// Get the longest resource name and use it to get position used to verticaly align the resource code (indentation)
		$longRes 		= Tools::longestValue($resNames);
		$resVertPos 	= strlen($longRes) + ( 4 - (strlen($longRes) % 4) );
		
		// Get the longest resource propery name
		$longProp 		= Tools::longestValue($rPropNames);
		$propVertPos	= strlen($longProp) + ( 4 - (strlen($longProp) % 4) );
		
		// Loop over the resources
		foreach ( array_keys((array) self::$resources) as $rName )
		{
			// Shortcut for resource props
			$p 	= &self::$resources[$rName];
			
			// Calculate the number of tabs required for proper vertical alignement of the current resource
			//$tabsCnt = floor(($resVertPos - strlen($rName) / 4));
			$resTabs = ($resVertPos - (strlen($rName)+3))/4;
			//$resTabs = ( $resTabs < 1 ) ? 1 : ( ( $resTabs - ceil($resTabs) < 0.5 ) ? ceil($resTabs) : floor($resTabs) );
			$tabs = '';
			//for($i=0; $i<$resTabs; $i++){ $tabs .= $tab; }
			
			$code .= "'" . $p['name'] . "' " . $tabs . "=> array(" . $lb;
			
			foreach ($rPropNames as $propName)
			{
				// TODO: Calculate the number of tabs required for proper vertical alignement of the current property
				//$tabsCnt = floor(($resVertPos - strlen($rName) / 4));
				$propTabs = ($propVertPos - (strlen($propName)+3))/4;
				//$propTabs = ( $propTabs < 1 ) ? 1 : ( ( $propTabs - ceil($propTabs) < 0.5 ) ? ceil($propTabs) : floor($propTabs) );
				$tabs = '';
				for($i=0; $i<$propTabs; $i++){ $tabs .= $tab; }
				
				$code .=  $tab . "'" . $propName . "' " . $tabs . "=> ";
				
				// Boolean props
				if ( in_array($propName, array('searchable','exposed')) ) 	{ $code .= $p[$propName] ? 'true' : 'false'; }
				// Defined string of default to null props
				elseif ( in_array($propName, array('extends','database')) ) { $code .= !empty($p[$propName]) ? "'" . $p[$propName] . "'" : 'null'; }
				// Default: string props
				else 														{ $code .= "'" . $p[$propName] . "'"; }
				
				$code .=  "," . $lb;
			}
			
			// 

			/*
			$code .= "'type' => '" . $p['type'] . "'";
			$code .= $tab . "'singular' => '" . $p['singular'] . "'," . $lb;
			$code .= $tab . "'plural' => '" . $p['plural'] . "'," . $lb;
			$code .= $tab . "'displayName' => '" . $p['plural'] . "'," . $lb;
			$code .= $tab . "'defaultNameField' => " . (string) $p['defaultNameField'] . "'," . $lb;
			$code .= $tab . "'nameField' => " . (string) $p['nameField'] . "'," . $lb;
			$code .= $tab . "'extends' => " . ( is_string($p['extends']) ? "'" . $p['extends'] .  "'" : 'null' ) . "," . $lb;
			$code .= $tab . "'database' => " . (!empty($p['database']) ? "'" . $p['database'] . "'" : 'null') . "," . $lb;
			$code .= $tab . "'table' => '" . $p['table'] . "'," . $lb;
			$code .= $tab . "'alias' => '" . $p['alias'] . "'," . $lb;
			$code .= $tab . "'searchable' => " . ( $p['searchable'] ? 'true' : 'false' ) . "," . $lb;
			$code .= $tab . "'exposed' => '" . ( $p['exposed'] ? 'true' : 'false' ) . "'," . $lb;
			$code .= $tab . "'crudability' => '" . $p['crudability'] . "'," . $lb;
			*/
			
			$code .= ")," . $lb;
		}
		
		$code .= ');' . $lb . '?>';
		
		return $code;
	}
	
	public function generateGroups()
	{
		$lb 		= "\n";
		$code 		= '<?php' . $lb . $lb . '$_groups = array(' . $lb;

		// TODO

		$code .= ');' . $lb . '?>';
		
		return $code;
	}
	
	public function generateColumns()
	{
		$lb 		= "\n";
		$code 		= '<?php' . $lb . $lb . '$_columns = array(' . $lb;
				
		// Loop over the resources columns
		foreach ( array_keys((array) self::$resources) as $rName )
		{
			// Shortcut for resource col
			$rCols = &self::$columns[$rName];
			
			// Get the longer colum name
			$longest = Tools::longestValue(array_keys($rCols));
			
			//
			foreach ( array_keys((array) $rCols) as $cName )
			{
				// Shortcut for columns properties
				$p = &self::$columns[$rName][$cName];
				
				
			}
		}
		
		$code .= ');' . $lb . '?>';
		
		return $code;		
	}
	
	
	// Checks that a resource exists
	static function isResource($string)
	{
		return !empty(self::$resources[$string]);
	}
	
	
	// Checks that a column existing in a given resource
	static function isColumn($resource, $string)
	{
		return !empty(self::$columns[$resource][$string]);
	}
	
	// Returns the singular of a resource
	static function singular($resource)
	{
		return self::isResource($resource) ? self::$resources[$resource]['singular'] : false;
	}
	
	
	static function guessResourceType($resource)
	{
		// Default type
		$type = 'native';
		
		// Split the name on the '_'
		$parts 		= explode('_', $resource);
		
		// Check if contains name of 2 resources
		// if (  ){ $type = 'relation'; }
		
		return $type;
	}
	
	
	static function getDbTableName($resource)
	{
		$tableName = null;
		
		// If the resource is not found
		if ( !self::isResource($resource) ) { return $tableName; }
			
		// Assume default table name is the resource name
		$tableName = $resource;
		
		// For relation resources, create names like '{$resource1}_{$resource2}' 
		if ( self::$resources[$resource]['type'] === 'relation' )
		{
			// TODO
		}
		
		return $tableName;
	}
	
	
	static function guessNameField($resource)
	{
		$nameField = null;
		
		// TODO
		// Get the first unique index char field
		// Get the first defined char field
		
		return $nameField;
	}
	
	
	static function guessAlias($resource)
	{
		// TODO: use resource resource table
		//$table = self::$resources[$resource]['table'] ? self::$resources[$resource]['table'] : self::getDbTableName($resource); 
		//$table = self::$resources[$resource]['table'] ? self::$resources[$resource]['table'] : self::guessDbTableName($resource);
		$table = $resource;
		
		// Split the resource name on _ chars
		$parts = explode('_', $table);
		
		// Force parts to be an array
		$parts = is_array($parts) ? $parts : $table;
		
		// ex: 			user_medias => um (if not already in use)
		// otherwise: 	user_medias => usr_md
		
		// 1st possibility: get the first char of every part of the name
		$poss1 = '';
		foreach ( $parts as $v ){ $poss1 .= $v{0}; }
		
		// 2nd possibility: get only the vowels of every part of the name
		$poss2 = '';
		foreach ( $parts as $v ){ $poss2 .= Tools::consonants($v); }
		
		// 3rd possibility: use the full resource name
		$poss3 = $resource;
		
		return !empty(self::$resources['_aliases'][$poss1]) 
				? $poss1 
				: ( !empty(self::$resources['_aliases'][$poss2]) ? $poss2 : $poss3 );
	}
	
	
	// Try to gess column type using it's name
	static function guessColumnType($colName)
	{
		// Split the name on the '_'
		$parts 		= explode('_', $colName);
		
		foreach ( (array) $parts as $part )
		{
			$sing = Tools::singular($part);
			$plur = Tools::plural($part);
			
			// Check if is an existing resource
			$isResource = $this->isResource($sing) || $this->isResource($plur);
			
			// If resource && resource not current one, assume it's a relation
			
			// [default] 			=> 'string',
			
			// 'name$' 				=> 'string' + length 64
			// '_name' 				=> 'string' + length 64
			// 'title$' 			=> 'string'
			// '_title' 			=> 'string'
			// 'color' 				=> 'color'
			
			// slug 				=> 'slug'
			
			// '_url' 				=> 'url'
			// 'url_' 				=> 'url'			
			// _uri 				=> 'url'
			
			// 'phone' 				=> 'tel'
			// 'mobile' 			=> 'tel'
			
			// is_ 					=> 'boolean'
			// has_ 				=> 'boolean'
			
			// 'mail_'
			// '_mail' 				=> 'email'
			// '_id' 				=> 'onetoone'
			//
			
			// '_nb' 				=> 'integer'
			// '_number' 			=> 'integer'
			// '_count' 			=> 'integer'
			// '_age' 				=> 'integer' + length = 3
			
			// 'id' 				=> 'pk'
			
			// '_date' 				=> 'date'
			// '_at'
			// 'creation_date' 		=> 'timestamp'
			// 'created_' 			=> 'timestamp'
			// 'update_date' 		=> 'timestamp'
			// 'updated_at' 		=> 'timestamp'
			
			// 'text' 				=> 'text'
			// 'summary' 			=> 'text',
			// 'description' 		=> 'text',
			// 'desc' 				=> 'text',
			
			// ip 					=> 'ip'
			
			// TODO
			// length, _len, 
			// time, _time, year, _year, month, _month, day, _day, hour, _hour, minutes, _minutes, seconds, _seconds
			// amout, price => floats
		}
	}
}


/*
 

	public function dataModelGenerator()
	{
		$args = func_get_args();
		
		$type = !empty($args[0]) ? $args[0] : null;
		
		if ( !in_array((string) $type, array('all','resources','groups','columns')) ){ return $this->index(); }
		
		// Create a zip archive and open it
		$zipFile 	= tempnam('tmp', 'zip');
		$zip 		= new ZipArchive();
		$zip->open($zipFile, ZipArchive::OVERWRITE);
		
		// Create the proper folder into the archive
		$zip->addEmptyDir('config/datamodel');
		
		$types = $type === 'all' ? array('resources','groups','colums') : (array) $type;
		
		$this->handleDatamodelCode();
		
		foreach ($types as $type)
		{
			$ctnt = &$this->data['_extras']['dataModel'][$type]['code'];
			
			if ( !$ctnt ){ continue; }
			
			// Create the proper file into the archive
			$zip->addFromString('config/datamodel/' . $type . '.php', $ctnt);
		}

		$zip->close();
		
		// Stream the file to the client
		header('Content-Type: application/zip');
		header('Content-Length: ' . filesize($zipFile));
		header('Content-Disposition: attachment; filename="[' . _APP_NAME . ']_' . 'dataModel_' . $type . '.zip"');
		readfile($zipFile);
		unlink($zipFile);
	}
 

	public function dataModelColumnsAnalyzer()
	{
		
		
		// If the dataModel is not defined, do not continue
		if ( !isset($this->dataModel['resources']) ){ return; }
		
		$lb 		= "<br/>";
		//$tab 		= "\t";
		$tab 		= "&nbsp;&nbsp;&nbsp;&nbsp;";
		
		// Open resources array
		$code 		= '$_resourcesModel = array(' . $lb;
		
		$known = array(
			'types' 	=> array(
				# Texts
				'string', 'varchar', 'slug', 'email', 'password', 'url', 'tel', 'color', 'meta', 'ip',
				'slug', 'tag', 
				'text', 'html', 'code',
				
				# Numbers
				'int', 'integer', 'numeric',
				'tinyint', 'smallint', 'mediumint', 'bigint',
				'float', 'real', 'double',
				
				# Booleans
				'bool','boolean',
				
				# Dates & times
				'timestamp', 'datetime', 'date', 'time', 'year', 'month', 'week', 'day', 'hour', 'minutes', 'seconds', 
				
				# Relations
				'1-1', 'onetoone', 'one2one', '121', '1to1', '12one',
				'1-n', 'onetomany', 'one2many', '12n', '1ton', '1tomany', '12many',  
				'n-1', 'manytoone', 'many2one', 'n21', 'nto1', 'manyto1', 'many21',
				'n-n', 'manytomany', 'many2many', 'n2n', 'nton',
				
				# Misc
				'pk', 'id', 'serial',
				'enum', 'choice',
				'file', 'image', 'video', 'sound', 'file',
			),
			'realtypes' => array(
				# Texts
				// strings (length=255) 
					'string' 		=> 'string',
					'varchar' 		=> 'string',
					'slug' 			=> 'string', // + length = 64
					'tag' 			=> 'string', // alias of slug
					'email' 		=> 'string', // + validator pattern
					'password'		=> 'string', // + modifiers = sha1
					'url' 			=> 'string', // + FILTER_VALIDATE_URL?
					'tel' 			=> 'string', // + length = 20???, + pattern ? 
					'color'			=> 'string', // + length = 32, + validator pattern (#hex, rgb(), rgba(), hsl(), ... ?)
					'meta' 			=> 'string',
					'ip' 			=> 'string', // + length = 40 + FILTER_VALIDATE_IP, ? 

					
					// texts (length=null)				
					'html' 			=> 'text',
					'code' 			=> 'text',
					'text' 			=> 'text',

				# Numbers
					// ints
					'int' 			=> 'integer', // + min = -2147483648, + max = 2147483648
					'integer'		=> 'integer', // + min = -2147483648, + max = 2147483648
					'num'			=> 'integer', // + min = -2147483648, + max = 2147483648
					'number'		=> 'integer', // + min = -2147483648, + max = 2147483648
					
					'tinyint' 		=> 'tinyint', // + min = -128, + max = 128 
					'smallint' 		=> 'smallint', // + min = -32768, + max = 32768
					'mediumint' 	=> 'mediumint', // + min = -8388608, + max = 8388608
					'bigint' 		=> 'bigint', // + min = -9223372036854775808, + max = 9223372036854775808
					
					// floats
					'float' 		=> 'float',
					'real' 			=> 'float',
					'double'		=> 'float',		
					
				# Booleans
					'bool' 			=> 'boolean',
					'boolean' 		=> 'boolean',
					
				# Dates & times
					// timestamps
					'timestamp' 	=> 'timestamp',
					'date' 			=> 'date',
					'datetime' 		=> 'datetime',
					
				# Relations
					// One to one relations (& aliases)
					'1-1' 			=> 'onetone', 
					'onetoone' 		=> 'onetone', 
					'one2one' 		=> 'onetone', 
					'121' 			=> 'onetone', 
					'1to1' 			=> 'onetone', 
					'12one' 		=> 'onetone',

					// One to many relations (& aliases)
					'1-n' 			=> 'onetomany', 
					'onetomany' 	=> 'onetomany', 
					'one2many' 		=> 'onetomany', 
					'12n' 			=> 'onetomany', 
					'1ton' 			=> 'onetomany', 
					'1tomany' 		=> 'onetomany', 
					'12many' 		=> 'onetomany',  
					
					// Many to one relations (& aliases)
					'n-1' 			=> 'manytoone', 
					'manytoone' 	=> 'manytoone', 
					'many2one' 		=> 'manytoone', 
					'n21' 			=> 'manytoone', 
					'nto1' 			=> 'manytoone', 
					'manyto1' 		=> 'manytoone', 
					'many21' 		=> 'manytoone',
					
					// Many to many relations (& aliases)
					'n-n' 			=> 'manytomany', 
					'manytomany' 	=> 'manytomany', 
					'many2many' 	=> 'manytomany', 
					'n2n' 			=> 'manytomany', 
					'nton' 			=> 'manytomany',
				
				# Misc
					// Enum
					'enum' 			=> 'enum',
					'choice' 		=> 'enum',
					
					// Pk + length = 11, pk = 1, editable = 0
					'pk' 			=> 'integer', 
					'id' 			=> 'integer',
					'id' 			=> 'integer',
			),
		);
		
		// Load the datamodel
		foreach (array_keys($this->dataModel['resources']) as $rName)
		{
			$code .= "'$rName' => array(" . $lb;
			
			// Shortcut for resource columns
			$rCols = &$this->dataModel['resourcesFields'][$rName];
			
			foreach ( array_keys((array) $rCols) as $cName )
			{
				$row = '';
				$row .= $tab . "'$cName' => array(";
				
				$type = $realtype = $ai = $pk = 
						$length = $null = $default = $values = 
						$index = 
						$list = $editable = $searchable =
					null;
				
				// Shortcut for cols properties
				$p = &$rCols[$cName];
				
				# Autoincrement
				$ai 			= isset($p['ai']) ? (int) $p['ai'] : 0;
				
				# Primary key
				$pk 			= isset($p['pk']) ? (int) $p['pk'] : 0;
				
				# Type
				$type 			= isset($p['type']) && in_array(strtolower($p['type']), $known['types']) ? strtolower($p['type']) : 'string';
				if 		( $cName === 'id' ) 	{ $type = 'pk'; $ai = 1; $pk = 1; }
				elseif 	( $cName === 'slug' ) 	{ $type = 'slug'; }
				
				# Realtype
				$realtype  		= $known['realtypes'][$type];
				
				# Length
				$length 		= isset($p['length']) && ( is_numeric($p['length']) || is_null($p['length']) )? $p['length'] : 'null';
				if ( $realtype === 'string' )
				{
					if 		( $type === 'slug' ) 	{ $length = 64; }
					elseif 	( $type === 'color' ) 	{ $length = 32; }
					else 							{ $length = 255; }
				}
				elseif ( $realtype === 'integer' )
				{
					$length = 11;
				}
				if ( $pk ) { $length = 11; }
				
				# Null
				$null 			= isset($p['null']) ? (int) (bool) $p['null'] : 0;
				
				# Default
				$default 		= isset($p['default']) ? $p['default'] : 'null';
				
				# Values
				$values 		= isset($p['values']) ? $p['values'] : 'null';
				
				# Index
				$index 			= isset($p['index']) ? (int) (bool) $p['index'] : 0;
				
				# List
				$list 			= isset($p['list']) ? (int) $p['list'] : 'list';
				
				# Editable
				$editable 		= isset($p['editable']) ? (int) $p['editable'] : 'editable';
				
				
				$row .= "'type' => '" . $type . "', ";
				$row .= "'realtype' => '" . $realtype . "', ";
				$row .= "'length' => '" . (string) $length . "', ";
				$row .= "'null' => " . $null . ", ";
				$row .= "'ai' => " . $ai . ", ";
				$row .= "'pk' => " . $pk . ", ";
				$row .= "'default' => " . $default . ", ";
				$row .= "'values' => " . $values . ", ";
				$row .= "'index' => " . $index . ", ";
				
				# deprecated
				if ( !empty($p['possibleValues']) ){ $values = Tools::toArray($p['possibleValues']); }
				
				// from
				// min
				// max
				
				$row .= "'list' => " . $list . ", ";
				// editable
				// searchable


				$row .= "), ";
				$code .= $row . $lb;
			}
			
			$code .= '),' . $lb;
		}
		
		// Close resources array
		$code .= ');' . $lb;
		
echo $code;
	}
*/

?>