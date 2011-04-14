<?php

class VResources extends AdminView
{
    public function __construct(&$application)
	{
        $this->setResource(array('class' => __CLASS__));
		$this->filePath 		= dirname(__FILE__);
		
        parent::__construct($application);
		
		$this->events->register('onAfterIndex', array('class' => &$this, 'method' => 'handleDatamodelCode'));
		$this->events->register('onCreateSuccess', array('class' => &$this, 'method' => 'createResourceExtra'));
		//$this->events->register('onAfterCreate', array('class' => &$this, 'method' => 'createResourceFiles'));
		
		return $this;
	}
	
	public function handleDatamodelCode()
	{
		// If the resources are not defined, try to get them
		if ( !isset($this->data['resources']) ){ $this->data['resources'] = $this->C->index(); }
		
		// If even after this, no resource where found, do not continue
		if ( empty($this->data['resources']) ){ return $this; }
		
		$lb 		= "\n";
		$code 		= '$resources = array(' . $lb;
		$resources 	= &$this->data['resources'];
		
		// Loop over the resources
		foreach ( $resources as $props )
		{
			// Do not continue if the name is not found
			if ( empty($props['name']) ) { continue; }
			
			$name 				= $props['name'];
			$type 				= isset($props['type']) ? $props['type'] : 'native';
			$singular 			= !empty($props['singular']) ? $props['singular'] : '';
			$plural 			= !empty($props['plural']) ? $props['plural'] : $name;
			// TODO
			$database 			= !empty($props['database']) ? $props['database'] : 'default';
			// TODO: smart table name for resource name
			$table 				= !empty($props['table']) ? $props['table'] : $name;
			$alias 				= !empty($props['alias']) ? $props['alias'] : $table;
			$displayName 		= !empty($props['displayName']) ? $props['displayName'] : $name;
			$defaultNameField 	= !empty($props['defaultNameField']) ? $props['defaultNameField'] : null;
			$extends 			= !empty($props['extends']) ? $props['extends'] : null;
			$searchable 		= !empty($props['searchable']) ? $props['searchable'] : 0;
			$exposed 			= !empty($props['exposed']) ? $props['exposed'] : 0;
			$crudability 		= !empty($props['crudability']) ? $props['crudability'] : 'CRUD';
			
			$code .= "'" . $name . "' => array(";
			$code .= "'type' => '" . $type . "'";
			$code .= ", 'singular' => '" . $singular . "'";
			$code .= ", 'plural' => '" . $plural . "'";
			$code .= ", 'displayName' => '" . $displayName . "'";
			$code .= ", 'defaultNameField' => '" . (string) $defaultNameField . "'";
			$code .= ", 'extends' => '" . (string) $extends . "'";
			$code .= ", 'database' => '" . $database . "'";
			$code .= ", 'table' => '" . $table . "'";
			$code .= ", 'alias' => '" . $alias . "'";
			$code .= ", 'searchable' => " . (bool) $searchable . "";
			$code .= ", 'exposed' => '" . (bool) $exposed . "'";
			$code .= ", 'crudability' => '" . (string) $crudability . "'";
			$code .= ")," . $lb;
		}
		
		$code .= ');';

		$this->data['_extras']['dataModel']['resources']['code'] = $code;
		
		return $this;
	}
	
	
	public function createResourceExtra()
	{
    	// Do not continue if the resource name is not found
    	if ( empty($_POST['resourceName']) ){ return $this; }
		
		$this->tmp['resource'] = array(
			'name' 		=> filter_var($_POST['resourceName'], FILTER_SANITIZE_STRING),
			'singular' 	=> !empty($_POST['resourceName']) ? filter_var($_POST['resourceSingular'], FILTER_SANITIZE_STRING) : Tools::singular($r['name']),
			'table' 	=> !empty($_POST['resourceTable']) ? filter_var($_POST['resourceTable'], FILTER_SANITIZE_STRING) : null,
		);
		
		return $this
			->createResourceTable()
			->createResourceFiles()
			;
	}
	
	public function createResourceTable()
	{
		$r = &$this->tmp['resource'];
		
		$name = !empty($r['table']) ? $r['table'] : $r['name'];
		
		$this->controller->model->createTable(array('name' => $name));
		
		return $this;
	}
	
    public function createResourceFiles()
    {		
		$r = &$this->tmp['resource'];
        
		// Create a zip archive and open it
		$zipFile 	= tempnam('tmp', 'zip');
		$zip 		= new ZipArchive();
		$zip->open($zipFile, ZipArchive::OVERWRITE);
		
		$filesNb = 0;
		foreach ( array('controller','model','view') as $item )
		{
			$firstChar 		= ucfirst($item[0]);
			$fName 			= $firstChar . ucfirst($r['name']); 													// file name
			$fExt 			= '.class.php'; 																		// file extension
			$fFullname 		= $fName . $fExt; 																		// file full name
			$fFolderPath 	= constant('_PATH_' . strtoupper($item . 's')); 										// file folder path
			$fPath 			= $fFolderPath . $fFullname; 															// file final path
			
			// For view, create the admin view too 
			if ( $item === 'view' && !empty($_POST['createAdminView']) )
			{
				$adminViewCtnt = file_get_contents(_PATH_VIEWS . 'admin/_VSamples' . $fExt);
				$adminViewCtnt = preg_replace(
								array('/VSamples/', '/singular/'),
								array($fName, $r['singular']), $adminViewCtnt);
								
				$zip->addEmptyDir('views/admin/');
				$zip->addEmptyDir('views/admin/' . $r['name']);
				$zip->addFromString('views/admin/' . $r['name'] . '/' . $fFullname, $adminViewCtnt);
				
				$filesNb++;
			}
			
			if ( empty($_POST['create' . ucfirst($item)]) ){ continue; }
			
			$fCtnt      	= file_get_contents($fFolderPath . '_' . $firstChar . 'Samples' . $fExt); 				// 
			$fCtnt      	= preg_replace(
								array('/' . $firstChar . 'Samples/', '/singular/'),
								array($fName, $r['singular']), $fCtnt); 												// file content
			
			// If the current file folder is writable, create the file 
			if ( is_writable($fFolderPath) )
			{
		        $created    = file_put_contents($fPath, $fCtnt);	
			}
			// Otherwise throw a warning
			else
			{
				$this->data['warnings'][15010] = $cFilePath;
			}
			
			// Create the proper folder into the archive
			$zip->addEmptyDir($item . 's/');
			
			// Create the proper file into the archive
			$zip->addFromString($item . 's/' . $fFullname, $fCtnt);
			
			$filesNb++;
		}
		
		$zip->close();
		
		// If no file have been create, we do not need to continue
		if ( !$filesNb ){ return $this; }
		
		// Stream the file to the client
		header('Content-Type: application/zip');
		header('Content-Length: ' . filesize($zipFile));
		header('Content-Disposition: attachment; filename="[' . _APP_NAME . ']_' . $r['name'] . '.zip"');
		readfile($zipFile);
		unlink($zipFile);
		
		return this;
    }

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
				'string', 'varchar', 'slug', 'email', 'password', 'url', 'color', 'meta', 
				'text', 'html', 'code',
				
				# Numbers
				'int', 'integer', 'numeric',
				'float', 'real', 'double',
				
				# Booleans
				'bool','boolean',
				
				# Dates & times
				'timestamp', 'datetime', 'date', 'time', 'year', 'month', 'week', 'day', 'hour', 'minutes', 'seconds', 
				
				# Relations
				'onetoone', 'onetomany', 'manytoone', 'manytomany',
				
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
					'email' 		=> 'string', // + validator pattern
					'password'		=> 'string', // + modifiers = sha1
					'url' 			=> 'string', // + validator pattern 
					'color'			=> 'string', // + length = 32, + validator pattern (#hex, rgb(), rgba(), hsl(), ... ?)
					'meta' 			=> 'string',
					// texts (length=null)				
					'html' 			=> 'text',
					'code' 			=> 'text',
					'text' 			=> 'text',

				# Numbers
					// ints
					'int' 			=> 'integer',
					'integer'		=> 'integer',
					'num'			=> 'integer',
					'number'		=> 'integer',
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
					
				# Relations
					'onetoone' 		=> 'onetone',
					'onetomany' 	=> 'onetomany',
					'manytoone' 	=> 'manytoone',
					'manytomany' 	=> 'manytomany',
				
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

	static function magicType($colName)
	{
		$parts = explode('_', $colName);
		
		$resources = &$this->dataModel['resources'];
		
		foreach ( (array) $parts as $part )
		{
			$sing = Tools::singular($part);
			$plur = Tools::plural($part);
			
			// Check if is an existing resource
			$isResource = isset($resources[$sing]) || isset($resources[$plur]);
			
			// If resource && resource not current one, assume it's a relation
			
			
			// 'name$' 				=> 'string'
			// 'title$' 			=> 'title'
			// 'color' 				=> 'color'
			
			// '_url' 				=> 'url'
			// 'url_' 				=> 'url'
			
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
			// 'creation_date' 		=> 'timestamp'
			// 'created_' 			=> 'timestamp'
			// 'update_date' 		=> 'timestamp'
			// 'updated_at' 		=> 'timestamp'
			
			// 'text' 				=> 'text'
			// 'summary' 			=> 'text',
			// 'description' 		=> 'text',
			// 'desc' 				=> 'text',
			
			// TODO
			// length, _len,
		}
	}
	
};

?>