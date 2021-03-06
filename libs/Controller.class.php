<?php

class Controller extends Application
{
	public $model		= null;
	public $errors		= null;
	public $success		= null;
	public $warnings	= null;
	public $events 		= null;
	public $data 		= array();
	
	public function __construct()
	{		
		isset($dataModel) || include(_PATH_CONFIG . 'dataModel.php');
		
		$this->application->_columns 	= &$_columns;
		$this->application->_resources 	= &$_resources;
		
		if ( isset($this->resourceName) )
		{
			class_exists('Model') || require(_PATH_LIBS . 'databases/Model_' . _DB_SYSTEM . '.class.php');
            
			// Instanciate the resource model
			$mName  		= 'M' . ucfirst($this->resourceName);
			$this->model 	= new $mName($this->application);
		}		
		
		// If events are enabled
		if ( _APP_USE_EVENTS )
		{			
			$this->requireLibs('Events');
			$this->events = new Events();
			
			// Triggered events:
			// onBeforeUpdate 		(controller)
			// onUpdateSuccess 		(controller)
			// onUpdateError 		(controller)
			// onAfterUpdate 		(controller)
			// onBeforeDelete 		(controller)
			// onAfterDelete 		(controller)
			// onDeleteSuccess 		(controller)
			// onDeleteError 		(controller)
			// onBeforeIndex 		(controller)
			// onAfterIndex 		(controller)
			// onBeforeRetrieve 	(controller)
			// onBeforeCreate 		(controller)
			// onCreateSuccess 		(controller)
			// onCreateError 		(controller)
			// onAfterCreate 		(controller)
			
			// onBeforeRender 		(view)
			// onBeforeDisplay 		(view)
		} 
		
		return $this;
	}
    
	public function __call($method, $args)
    {
//var_dump(__METHOD__);
//var_dump($method);
//var_dump($args);

		// PATTERN: verb[Limiters][offseters][restricters][conditioners][condition operator][sorters]
		
		$verbs = array(
			'find' 				=> 'select',
			'get'				=> 'select',
			'select' 			=> 'select',
								// 'retrieve' will be used if limiter = one|first|last 
			
			'update' 			=> 'update',
			
			//'createOrUpdate' 	=> 'upsert',
			//'updateOrCreate' 	=> 'upsert',
			//'upsert' 			=> 'upsert',
			
			'remove' 			=> 'delete',
			'delete' 			=> 'delete',
		);
		
		$limiters 		= array(
			// {$nb}
			'one' 			=> array('action' => 'retrieve', 'limit' => 1),
			'all'			=> array('limit' => -1),
			'first' 		=> array('limit' => 1, 'sortBy' => 'ASC'), // + use default oderBy column (default to id)
			'last' 			=> array('limit' => 1, 'sortBy' => 'DESC'), // + use default oderBy column (default to id)
			// + 'firstX'
			// + 'lastX' 
		);
		
		$offseters 		= array(
			'before' 		=> array(), // TODO
			'after' 		=> array(), // TODO
		);
		
		$restricters 	= array(
			//{$colName}
			'distinct' 		=> array(), // TODO
		);
		
		$conditioners 	= array(
			'by',
			'where',
			'with',
			'whose', 
			'which',
			'whom',
			'and',
			'or',
		);
		
		$conditionOperators = array(
			// use model condition operators
			'having',
			'maching',
			'verifying',
		);
			
		// Default request options	
		$opts = array(
			'getFields' 	=> '', 		// TODO: deprecate. rename into 'columns'
			'limit' 		=> null,
			'conditions' 	=> array(), // reset or extends
		);
		
		// Split on UpperCase
		//$parts = preg_split('/(?<!^)(?=[A-Z])/', $foo, -1, PREG_SPLIT_OFFSET_CAPTURE);
		$parts = preg_split('/(?=[A-Z])/', $method, -1, PREG_SPLIT_NO_EMPTY);
		
//var_dump($parts);

		$next 		= null;
		$current 	= null;
		$prev 		= null;
		
		// Loop over the parts
		$i = 0;
		foreach($parts as $part)
		{
			$i++;
			$lower = strtolower($part);
			$next = next($parts);
			
//var_dump($lower);
			
			# Verb
			// Check that the verb is a known/allowed one
			if ( $i === 1 )
			{
//var_dump('handle verb');
				if ( !isset($verbs[$lower]) ) { return; } // TODO: how to handle errors????
				
				$method = $verbs[$lower];
				$prev 	= 'verb';
				continue;
			}
			
			# Limiters
			// If the part is a number, assume use it as a limit
			// Otherwise, check if it's a known limiter
			if ( $prev === 'verb' )
			{
//var_dump('handle limiter');
				
				if ( in_array($lower, $limiters) )
				{
					$opts 			= array_merge($opts + $limiters[$lower]);	
				}
				elseif 		( is_numeric($part) )
				{
					$opts['limit'] 	= (int) $part;
				}
				else
				{
					$prev = 'limiter';
					continue;	
				}
			}
			
			# Offseters
			
			# Restricters
			
			# Conditioners
			
			# Sorters
		}
		
//var_dump($opts);
//die();

		return $this->$method($opts);
    }

	public function triggerEvent($event, $data)
	{
		if ( _APP_USE_EVENTS ){ return $this->events->trigger($event, $data); }
	}

	
	public function index($options = array())
	{
		$o                = &$options;
		$this->success    = false;
		$this->errors     = array();
		$this->warnings   = array();
		
		if ( empty($o['mode']) || $o['mode'] !== 'count' )
		{
			$this->triggerEvent('onBeforeIndex', array('source' => array('class' => __CLASS__, 'method' => __FUNCTION__)));	
		}
		
		$this->data       = $this->model->index($o);

		if ( empty($o['mode']) || $o['mode'] !== 'count' )
		{
			$this->triggerEvent('onAfterIndex', array('source' => array('class' => __CLASS__, 'method' => __FUNCTION__)));
		}		
		
		$this->success    = $this->model->success;
		$this->warnings   = array_merge($this->warnings, (array) $this->model->warnings);
		
		// If the request failed, get the errors
		if ( !$this->success )
		{
			$this->handleModelErrors();
		}
		else
		{
			//$this->extendsData($o);
			if ( !isset($o['extendsData']) || $o['extendsData'] ) { $this->extendsData($o); }	
		}
		
		// Deprecated: use 'indexBy' or 'indexByUnique' options instead
		//if ( !empty($o['reindexby']) ){ self::reindex($o); }
		
		return $this->data;
	}
	

	public function search($options = array())
	{
        $o                = &$options;
        $this->success    = false;
        $this->errors     = array();
        $this->warnings   = array();
        
		$this->data       = $this->model->search($o);
		$this->success    = $this->model->success;
		$this->warnings   = array_merge($this->warnings, (array) $this->model->warnings);
		
		// If the request failed, get the errors
		if ( !$this->success )
		{
			$this->handleModelErrors();
		}
		else
		{
			$this->extendsData($o);
		}
		
		// Deprecated: use 'indexBy' or 'indexByUnique' options instead
		//if ( !empty($o['reindexby']) ){ self::reindex($o); }
		
		return $this->data;
	}
	

	public function create($options = array())
	{	
        $o                = &$options;
        $this->success    = false;
        $this->errors     = array();
        $this->warnings   = array();
		
		$resourceData     = $this->filterPostData(array_merge($o,array('method' => 'create')));

//var_dump($resourceData);

		//if ( !empty($resourceData) )
		if ( !empty($resourceData) && empty($this->errors) )
		{
			$this->triggerEvent('onBeforeCreate', array('source' => array('class' => __CLASS__, 'method' => __FUNCTION__)));			
			
			// Launch the creation
			$this->data = $this->model->create($resourceData, $o);
			
			$this->triggerEvent('onAfterCreate', array('source' => array('class' => __CLASS__, 'method' => __FUNCTION__)));
			
			// Get the success of the request
			$this->success 	= $this->model->success;
			
			$this->warnings = array_merge($this->warnings, (array) $this->model->warnings);
			
			// If the request failed, get the errors
			if ( !$this->success )
			{
				$this->extendsData();
				$this->handleModelErrors();
				$this->triggerEvent('onCreateError', array('source' => array('class' => __CLASS__, 'method' => __FUNCTION__)));
			}
			else
			{
				$this->triggerEvent('onCreateSuccess', array('source' => array('class' => __CLASS__, 'method' => __FUNCTION__)));
			}
		}
		
		return !empty($o['returning']) && isset($this->data) ? $this->data : null;
	}
	
	
	public function upsert($options = array())
	{
        $o                = &$options;
        $this->success    = false;
        $this->errors     = array();
        $this->warnings   = array();
		
		$resourceData     = $this->filterPostData(array_merge($o,array('method' => 'create')));

		//if ( !empty($resourceData) )
		if ( !empty($resourceData) && empty($this->errors) )
		{
			// Launch the creation
			$this->data = $this->model->upsert($resourceData, $o);
			
			// Get the success of the request
			$this->success 	= $this->model->success;
			
			$this->warnings = array_merge($this->warnings, (array) $this->model->warnings);
			
			// If the request failed, get the errors
			if ( !$this->success )
			{
				$this->handleModelErrors();
			}
		}
		
		return !empty($o['returning']) && isset($this->data) ? $this->data : null;
	}
	
	public function findOrCreate()
	{
		// TODO
	}
	
	public function retrieve($options = array())
	{
        $o                = &$options;
        $this->success    = false;
        $this->errors     = array();
        $this->warnings   = array();
		
		$this->triggerEvent('onBeforeRetrieve', array('source' => array('class' => __CLASS__, 'method' => __FUNCTION__)));
		
		$this->data       = $this->model->retrieve($o);
		
		$this->triggerEvent('onAfterRetrieve', array('source' => array('class' => __CLASS__, 'method' => __FUNCTION__)));

		$this->success    = $this->model->success;		
		$this->warnings   = array_merge($this->warnings, (array) $this->model->warnings);

		// If the request failed, get the errors
		if ( !$this->success )
		{
			$this->handleModelErrors();
		}
		else
		{
			if ( !isset($o['extendsData']) || $o['extendsData'] ) { $this->extendsData(array_merge($o, array('isCollection' => false))); }
		}
		
		return $this->data;
	}
	
	
	public function update($options = array())
	{
        $o                = &$options;
        $this->success    = false;
        $this->errors     = array();
        $this->warnings   = array();
	
		$resourceData     = $this->filterPostData(array_merge($o, array('method' => 'update')));
        
		//if ( !empty($resourceData) )
		if ( !empty($resourceData) && empty($this->errors) )
		{
			$this->triggerEvent('onBeforeUpdate', array('source' => array('class' => __CLASS__, 'method' => __FUNCTION__)));
			
			// Launch the creation
			$this->data = $this->model->update($resourceData, $o);
			
			$this->triggerEvent('onAfterUpdate', array('source' => array('class' => __CLASS__, 'method' => __FUNCTION__)));
			
			// Get the success of the request
			$this->success 	= $this->model->success;
			
			$this->warnings = array_merge($this->warnings, (array) $this->model->warnings);
			
			// If the request failed, get the errors
			if ( !$this->success )
			{
				//$this->model->errors;
				$this->handleModelErrors();
				$this->triggerEvent('onUpdateError', array('source' => array('class' => __CLASS__, 'method' => __FUNCTION__)));
			}
			else
			{
				$this->triggerEvent('onUpdateSuccess', array('source' => array('class' => __CLASS__, 'method' => __FUNCTION__)));
			}
		}
		
		return $this;
	}
	
	
	public function delete($options = array())
	{
        $o                = &$options;
        $this->success    = false;
        $this->errors     = array();
        $this->warnings   = array();

		$this->triggerEvent('onBeforeDelete', array('source' => array('class' => __CLASS__, 'method' => __FUNCTION__)));

		// Launch the creation
		$this->model->delete($o);
		
		$this->triggerEvent('onAfterDelete', array('source' => array('class' => __CLASS__, 'method' => __FUNCTION__)));
		
		// Get the success of the request
		$this->success    = $this->model->success;
		
		$this->warnings   = array_merge($this->warnings, (array) $this->model->warnings);
		
		// If the request failed, get the errors
		if ( !$this->success )
		{
			//$this->model->errors;
			$this->handleModelErrors();
			$this->triggerEvent('onDeleteError', array('source' => array('class' => __CLASS__, 'method' => __FUNCTION__)));
		}
		else
		{
			$this->triggerEvent('onDeleteSuccess', array('source' => array('class' => __CLASS__, 'method' => __FUNCTION__)));
		}
		
		//if ( !$this->data['success'] ) { $this->data['errors'] = '11100'; } // Common object deletion error
		
		return $this;
	}
	
	
	public function handleModelErrors()
	{
//var_dump($this->model->errors);
		
		foreach ((array) $this->model->errors as $errNb => $err )
		{
			// TODO: if error codes are known (not db error codes), just forward them
			if 		( $errNb === 21 ){ $this->errors[] = 21; continue; } 			// Resource columns not defined
			
			//TODO: always display sql error message 4100 => $err??? 
			elseif 	( $errNb === 1048 ){ $this->errors[4100] = $err; continue; } 	// Column(s) cannot be null
			elseif 	( $errNb === 1054 ){ $this->errors[4100] = $err; continue; } 	// Unknown column
			elseif 	( $errNb === 1062 ){ $this->errors[] = 4030; continue; } 		// Duplicate entry error (unique key constraint)
			elseif 	( $errNb === 1066 ){ $this->errors[] = 4150; continue; } 		// Tables alias conflict
			elseif 	( $errNb === 1064 ){ $this->errors[] = 4020; continue; } 		// Request syntax error
			elseif 	( $errNb === 1451 ){ $this->errors[] = 4110; continue; } 		// Creation/update error due to fk constraint(s)
			elseif 	( $errNb === 1452 ){ $this->errors[] = 4050; continue; } 		// Deletion error due to fk constraint(s)
			
			$this->errors[] = 4010; 
		}

//var_dump($this->errors);
		
		return $this;
	}
	
	// TODO: refactor & split in 2 methods: reIndexBy([$column(s)]) & reIndexByUnique([$column(s)], $options array()) 
	public function reindex($options = array())
	{
		// Shortcut for options and default options
		$o 					= &$options;
		$rModel 			= &$this->application->_columns[$this->resourceName];
		$o['indexModifier'] = !empty($o['indexModifier']) ? $o['indexModifier'] : null;
		
		// Handle deprecate param name (incorrect camelCase)
		$o['reindexBy'] 	= isset($o['reindexby']) ? $o['reindexby'] : null;

		// Do not continue if there's no data to process of if data is not an array( ie: for count operations) 
		//if ( empty($this->data) || empty($o['reindexby']) || !is_array($this->data) || !isset($this->data[0][$o['reindexby']])) { return false; }
		if ( empty($this->data) || empty($o['reindexBy']) || !is_array($this->data) || !isset($this->data[0][$o['reindexBy']])) { return false; }
		
		$tmpData 	= array();
		$isIndex 	= $o['reindexBy'] === 'id' || ( !empty($rModel[$o['reindexBy']]['fk']) && $rModel[$o['reindexBy']]['fk']);
		$isUnique 	= isset($o['isUnique']) ? $o['isUnique'] : ( $isIndex ? true : false );	// Will the new indexes containes unique values or arrays?
		
		//foreach ($this->data as $item)
		foreach ( array_keys($this->data) as $key)
		{
			$item = $this->data[$key];
			
			// Set index key/name
			if 			( $o['indexModifier'] === 'lower' )	{ $k = strtolower($item[$o['reindexBy']]); }
			else if 	( $o['indexModifier'] === 'upper' )	{ $k = strtoupper($item[$o['reindexBy']]); }
			else 											{ $k = $item[$o['reindexBy']]; }  
			
			// Do not assign data whose index is empty
			if ( $k === '' || $k === null ){ continue; }
			
			$k 				= (string) $k; 	// Cast key into a string to prevent index conflicts
			
			if 	( $isUnique )	{ $tmpData[$k] 		= $item; }
			else 				{ $tmpData[$k][] 	= $item; }
		}

		// Reassign data reindexed
		$this->data = $tmpData;
		
		return $this->data;
	}

	
	
	
	public function extendsData($options = array())
	{
		$o = array_merge(array(
			'extendsData' => true,
		), $options);
		
		// Filter returned data to remove not exposed columns
		$this->filterOutputData($options);
		
		// Do not continue if there's no data to process or if data is not an array( ie: for count operations)
		if ( empty($this->data) || !is_array($this->data) || empty($o['extendsData']) ) { return $this; }
		
		// Nothing here for the moment
		// Overload this method specificaly in you controllers when you need to extends your data 
		// (ie: append subresource to your resources)
		
		return $this;
	}
	
	// Deprecated
	public function filterPostData($options = null)
	{
		return $this->filterInputData($options);
	}
	
	public function filterInputData($options = null)
	{
		// Shortcut for options
		$o = &$options;
		
		// Do not continue if the resource has not been defined
		if ( empty($this->resourceName) ) { return; }
		
		$resourceData 	= array();
		$_dm 			= &$this->application->_columns[$this->resourceName];
		
		// TODO: rename isApi to something like 'fieldnamesPattern' ('column' or 'resourceColumn')
		//$isApi            = ( !empty($_SERVER['PATH_INFO']) && strpos($_SERVER['PATH_INFO'], '/api/') !== false ) || ( isset($o['isApi']) && $o['isApi'] );
		$fromApi = isset($_SERVER['PATH_INFO']) && strpos($_SERVER['PATH_INFO'], '/api/') !== false;
		$isApi = (isset($o['isApi']) && $o['isApi']) || $fromApi;
		
		// Loop over the data model of the resource
		//foreach ((array) $rName as $fieldName => $field)
		foreach ((array) $_dm as $fieldName => $field)
		{
			
			// Shortcut for the field name
			// For the api, we use 'normal' forms fieldnames/$resource fields whitout prefix
			// But for everywhere else, they are prefixed by the name of the resource (to avoid conflicts) [ex: userLogin, productSummary, entryExpirationtime]
			$f = $isApi ? $fieldName : $this->resourceSingular . ucFirst($fieldName);
			
			// TODO: if column is not exposed and request is from API, remove passed column value
			$isExposed = !isset($field['exposed']) || $field['exposed'];
			//if ( $fromApi && !$isExposed && isset($_POST[$fieldName]) ){ unset($_POST[$fieldName]); }
			if ( (isset($o['filterNotExposed']) && $o['filterNotExposed']) && !$isExposed && isset($_POST[$fieldName]) ){ unset($_POST[$fieldName]); }
			
			// If the column is required
			// TODO: continue looping over the fields to list all missing ones
			if ( isset($field['required']) && $field['required'] 
				&& empty($_FILES[$f]) && empty($_POST[$f])
				&& ( empty($o['method']) || $o['method'] !== 'update' ) )
			{				
				// If a default value is defined, set it
				if ( array_key_exists('default', (array) $field) )
				{
					$_POST[$f] = $field['default'];
				}
				else
				{
				    $this->errors[1003] = $f;
				    return;	
				}
            }
			
			// if the POST data for each field exists
			// TODO: also test with array_key_exists for values that whould have been setted to null?????
			if ( isset($_FILES[$f]) || isset($_POST[$f]) )
			{
				// Set the proper super global to use: $_FILES for posted files, otherwise $_POST
				$usedSpGlobale = isset($_FILES[$f]) ? 'files' : 'post';
				$spGlobaleItems = $usedSpGlobale === 'files' ? $_FILES[$f] : $_POST[$f];
				
				// Case multiple items
				//if ( is_array($spGlobaleItems) && count($spGlobaleItems) > 0 && ($usedSpGlobale !== 'files' || (isset($o['multipleItems']) && $o['multipleItems'] === true)) )
				//if ( is_array($spGlobaleItems) && count($spGlobaleItems) > 0 && ( $usedSpGlobale !== 'files' || !empty($o['multipleItems']) ) )
				//if ( is_array($spGlobaleItems) && count($spGlobaleItems) > 0  && ($usedSpGlobale === 'files' || !empty($o['multipleItems'])) )
				if ( !empty($o['multipleItems']) && is_array($spGlobaleItems) && count($spGlobaleItems) > 0 )
				{
					// Loop over superglobal field indexes
					$i = 0;
					foreach ($spGlobaleItems as $index => $val)
					{
						$resourceData[$i][$fieldName] = $this->filterSingle($field, $val, $o);
						$i++;
					}
				}
				// Case single item
				else
				{				
					// If a validation pattern has been defined for this field
					// Check the passed value against
					$checkPattern 	= defined('_APP_USE_PATTERN_VALIDATION') && _APP_USE_PATTERN_VALIDATION;
					$hasPattern 	= !empty($field['pattern']);
					$reg 			= $hasPattern ? ( $field['pattern'][0] === '/' ? $field['pattern'] : '/' . $field['pattern'] . '/' ) : '';
					if ( $checkPattern
						//&& !empty($field['pattern']) && !preg_match('/' . $field['pattern'] . '/', $spGlobaleItems) ) 
						&& $hasPattern && !preg_match($reg, $spGlobaleItems) )
					{
						$this->errors[1007] = $fieldName;
					}
					
					// Assign it to the field in the $resourceData array
					$resourceData[0][$fieldName] = $this->filterSingle($field, $spGlobaleItems, $o);
				}
			} 
		}
		
		// If multiple items have been posted, just return the resourceData value as is it
		// Otherwise, we have to get them in the first (and normally only) row of the resourceData array
		//$return = isset($o['multipleItems']) && $o['multipleItems'] ? $resourceData : $resourceData[0];
		$return = isset($o['multipleItems']) && $o['multipleItems'] ? $resourceData : ( isset($resourceData[0]) ? $resourceData[0] : null);
		
		return $return;
	}

	
	// TODO filter on request build for perf issues (merge 2 loop cycles on the same data)???
	public function filterSingle($fieldModel, $superGlobaleField, $options = array())
	{
		$field 			= &$fieldModel;
		$f 				= &$superGlobaleField;
		
		// Reset temp filtered data
		$filteredData 	= null;
	
		// Filter data
		if ( $field['type'] === 'int' )
		{
			//$filteredData = filter_var($f, FILTER_SANITIZE_NUMBER_INT);
			$filteredData = Tools::sanitize($f, array('type' => 'int'));
		}
		else if ( $field['type'] === 'float' )
		{
			//$filteredData = filter_var($f, FILTER_VALIDATE_FLOAT);
			$filteredData = Tools::sanitize($f, array('type' => 'float'));
		}
		else if ( $field['type'] === 'tel')
		{
			$filteredData = Tools::sanitize($f, array('type' => 'tel'));
		}
		else if ( $field['type'] === 'email' || (!empty($field['subtype']) && $field['subtype'] === 'email') )
		{
			$filteredData = filter_var($f, FILTER_VALIDATE_EMAIL);
		}
		/*
		else if ( $field['type'] === 'url' || (!empty($field['subtype']) && $field['subtype'] === 'url') )
		{
var_dump($f);
			$filteredData = $f;
			//$filteredData = filter_var($f, FILTER_VALIDATE_URL);
		}*/
		else if ( $field['type'] === 'password' || (!empty($field['subtype']) && $field['subtype'] === 'password') )
		{
			$filteredData = $f;
		}
		else if ( $field['type'] === 'varchar' && !empty($field['subtype']) && $field['subtype'] === 'file' )
		{
			// If the passed value is not an array, we do not handle file upload and just update db value 
			$uploadFile 	= is_array($f);
			
			if ( isset($_GET['forceFileDeletion']) && $_GET['forceFileDeletion'] )
			{
				$filteredData = '';
			}
			else if ( !$uploadFile )
			{
				$filteredData = filter_var($f, FILTER_SANITIZE_STRING);
			}
			else
			{
				//if ( !isset($f) || (isset($f['error']) && $f['error'] === 4) ) { return; }
				if ( !isset($f) ) { return; }
				
				switch($f['error'])
				{
					case 0: break; // ok 
					case 1: return; // file exceed upoad_max_filesize
					case 3: break; // file partially uploaded
					case 4: return; // no file uploaded
					case 6: return; // Missing temp folder
					case 7: return; // failed to write file
					case 8: return; // a php extension stopped file upload
				}
						
				class_exists('FileManager') || require(_PATH_LIBS . 'storage/FileManager.class.php');
				
				$fileExtension = FileManager::getInstance()->checkFileType($f, array(
					'allowedTypes' => !empty($field['allowedTypes']) ? $field['allowedTypes'] : array(),
				));
				
				//if ( !isset($_FILES[$f]) || $_FILES[$f]['error'] === 4 )
				if ( $fileExtension === false )
				{
					// TODO: how to handle errors???
					$this->warnings[] = 6100;
				}
				else
				{
					$filteredData = array_merge($f, array('extension' => $fileExtension, 'md5_hash' => md5_file($f['tmp_name'])));
				}	
			}
		}
/*
		else if ( $field['type'] === 'enum' )
		{
var_dump('validating enum '. $f);
			//$filteredData = in_array($f, (array) $field['possibleValues']) ? $f : null;
			$filteredData = in_array($f, (array) Tools::toArray($field['possibleValues'])) ? $f : null;
var_dump('val after:' . $filteredData);
		}*/
		else if ( $field['type'] === 'varchar' )
		{
			//$filteredData = filter_var($f, FILTER_SANITIZE_STRING);
			$filteredData = addslashes(filter_var($f, FILTER_SANITIZE_STRING));
		}
		else if ( $field['type'] === 'datetime' )
		{
			// TODO: DateTime::createFromFormat is only available since php 5.3. Handle fallback???
			$filteredData = is_numeric($f) ? DateTime::createFromFormat('U', (int) $f)->format(DateTime::W3C) : $f;
		}
		else if ( $field['type'] === 'date' )
		{
			// TODO: DateTime::createFromFormat is only available since php 5.3. Handle fallback???
			$filteredData = is_numeric($f) ? DateTime::createFromFormat('U', (int) $f)->format('Y-m-d') : $f;
		}
		else if ( $field['type'] === 'timestamp' )
		{
			// Do not return a filtered data if the
			/*
			if ( !empty($_POST[$f]) ) { $filteredData = strtotime($_POST[$f]); }
			else { continue; }
			*/

			/*
			$filteredData = is_int($_POST[$f]) 
				? $_POST[$f]
				: ( !empty($_POST[$f]) && !is_int($_POST[$f]) ? strtotime($_POST[$f]) : null );
			*/
			
			$filteredData = is_int($f) ? $f : ( !empty($f) && !is_int($f) ? strtotime($f) : null );
		}
		else if ( $field['type'] === 'bool' )
		{
			//$filteredData = $f == '1' ? 1 : 0;
			//$filteredData = $f == '1' || $f == 'true' || $f == 't' ? 1 : 0;
			$filteredData = in_array($f, array(1,true,'1','true','t'), true) ? 1 : 0;
		}
		else if ( $field['type'] === 'set' )
		{
			// Handle case where all values are passed in 1 value (csv string)
			$tmp = is_array($f) ? $f : Tools::toArray(preg_replace('/\s/','',$f)); 	// Remove spaces an makes it an array 

//var_dump('type set');
//var_dump($tmp);
//var_dump($field['possibleValues']);
			
			// Filter to get only values authorized
			$filteredData = array_intersect($tmp, Tools::toArray($field['possibleValues']));
			
//var_dump($filteredData);
		}
		else if ( $field['type'] === 'text' )
		{
			$filteredData  = addslashes($f);
		}
		else if ( $field['type'] === 'json' )
		{
			/*
			// TODO: how to validate json???
			// use Json Schema PHP Validator???
			
			// Try decoding json data and check if it returns an error
			$tmp = json_decode((string) $f);
			unset($tmp);
			
			$filteredData = json_last_error() === JSON_ERROR_NONE ? $f : null;
			*/
			$filteredData = Tools::validate($f, array('type' => 'json')) ? Tools::sanitize($f, array('type' => 'json')) : null;
		}
		/*
		else if ( $field['type'] === 'text' && !empty($field['subtype']) && $field['subtype'] === 'html' )
		{
			$filteredData  = $f;
		}*/
		else
		{
			// addslashes()???
			// FILTER_SANITIZE_STRING???
			$filteredData = $f;
		}
			
//var_dump($filteredData);
				
		return $filteredData;
	}
	
	
	public function values()
	{
		// Do not continue if there's no data to process of if data is not an array( ie: for count operations)
		if ( empty($this->data) || !is_array($this->data) ) { return null; }
		
		$args 		= func_get_args(); 												// Get passed arguments
		$colNames 	= is_array($args[0]) ? $args[0] : explode(',', $args[0]); 		// Shortcut for column names
		$data 		= array();
		
		// Loop over the columns
		foreach ($colNames as $colName)
		{			
			// Then loop over the data rows and for each one
			// get the value associated to the passed column(s) and store them in a temp array
			foreach ($this->data as $dataRow)
			{				
				//if ( !isset($dataRow[$colName]) ){ continue; }
				// Only handle requested columns, and only if the value has not already found for the current column 
				if ( !isset($dataRow[$colName]) 
					|| ( !empty($data[$colName]) && in_array($dataRow[$colName], $data[$colName]) ) ){ continue; }
				
				$data[$colName][] = $dataRow[$colName];
			} 
		}
		
		// If several columns have been requested, we return the whole data array
		// Othewise, just return the part containing the values of the only column requested  
		$returnVal = count($colNames) === 1 && isset($data[$colNames[0]]) ? $data[$colNames[0]] : $data;
		
		return $returnVal;
	}

	// TODO: Should this be done in the model? Either by preventing cols to be selected (in the query) or when fixing output data 
	public function filterOutputData($options = array())
	{		
		// Do not continue if there's no data to filter
		if ( empty($this->data) ){ return; }
		
		// 
		$o = array_merge(array(
		), $options, array(
			'isApi' 		=> ( isset($_SERVER['PATH_INFO']) && strpos($_SERVER['PATH_INFO'], '/api/') !== false ),
			'isCollection' 	=> ( isset($options['isCollection']) && $options['isCollection'] ) || ( is_array($this->data) && isset($this->data[0]) ),
		));
		
		// Only filter data for API outputs
		//if ( !$o['isApi'] ){ return; }
		if ( !isset($o['filterNotExposed']) || !$o['filterNotExposed'] ){ return; }
		
		// Do not continue any longer if the datamodel cannot be found
		if ( !isset($this->application->_columns[$this->resourceName]) ){ return; } 
		
		// Shortcut for resource datamodel
		$_dm = $this->application->_columns[$this->resourceName];

		// TODO: do not continue if the resource is not exposed????
		
		// TODO: Get from resources metadata under '_exposed' prop (when available) 
		// Get columns not exposed
		$notExposed = array();
		foreach ((array) $_dm as $colName => $colProps){ $exposed = !isset($_dm[$colName]['exposed']) || $_dm[$colName]['exposed']; if ( !$exposed ){ $notExposed[] = $colName; } }
		
		// Do not continue any longer if all columns are exposed
		if ( empty($notExposed) ){ return; }
		
		$o['notExposedCols'] = $notExposed;
		
		// For 1 resource/row only, directly handle it
		if 	( !$o['isCollection'] ){ $this->filterOutputOne($this->data, $o); }
		// Otherwise, for a collection, we have to loop over the items
		else
		{
			foreach($this->data as &$item){ $this->filterOutputOne($item, $o); }
		}
	}
	
	public function filterOutputOne(&$row, $options = array())
	{
		$o = $options;
		
		// TODO: Get from resources metadata under '_exposed' prop (when available)
		// Filter returned data to remove not exposed columns
		$notExposed = $o['notExposedCols'];
		
		if ( !is_array($row) ){ return; }
		
		foreach($notExposed as $colName){ unset($row[$colName]); }
	}

}

?>