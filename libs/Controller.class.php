<?php

class Controller extends Application
{
	public $application;
	public $model					= null;
	public $errors					= null;
	public $success					= null;
	public $warnings				= null;
	public $data 					= array();
	
	public function __construct()
	{
		isset($dataModel) || include(_PATH_CONFIG . 'dataModel.php');
		
		$this->application->dataModel = &$dataModel;
		
		if ( isset($this->resourceName) )
		{
			class_exists('Model') || require(_PATH_LIBS . 'databases/Model_' . _DB_SYSTEM . '.class.php');
            
			// Instanciate the resource model
			$mName  	= 'M' . ucfirst($this->resourceName);
			$this->model 		= new $mName($this->application);
			//$this->m 			= &$this->model; 						// Shortcut for model 
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
			'whose', + 
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
		
		//$parts = preg_split('/(?<!^)(?=[A-Z])/', $foo, -1, PREG_SPLIT_OFFSET_CAPTURE);
		$parts = preg_split('/(?=[A-Z])/', $method, -1, PREG_SPLIT_NO_EMPTY);
		
var_dump($parts);
		
		// Loop over the parts
		$i = 0;
		foreach($parts as $part)
		{
			$lower = strtolower($part);
			
			# Verb
			// Check that the verb is a known/allowed one
			if ( $i === 0 && !isset($verbs[$lower]) ){ return; } // TODO: how to handle errors????
			$method = $verbs[$lower];
			
			# Limiters
			// If the part is a number, assume use it as a limit
			if 		( is_numeric($part) ){ $opts['limit'] = (int) $part; }
			// Otherwise, check if it's a known limiter
			else if ( in_array($lower, $limiters) )
			{
				$opts = array_merge($opts + $limiters[$lower]);
			}
			
			# Offseters
			
			# Restricters
			
			# Conditioners
			
			# Sorters
			 
			$i++;
		}

		return $this->$method($opts);
    }

	
	public function index($options = array())
	{
		$o                = &$options;
		$this->success    = false;
		$this->errors     = array();
		$this->warnings   = array();
		
		$this->data       = $this->model->index($o);
		$this->success    = $this->model->success;
		$this->warnings   = array_merge($this->warnings, (array) $this->model->warnings);
		
		//if ( $this->success ) { $this->extendsData($o); }
		//if ( $this->success ) { $this->extendsData($o + array('method' => __FUNCTION__)); }
		
		// If the request failed, get the errors
		if ( !$this->success )
		{
			//$this->model->errors;
			
			$this->handleModelErrors();
		}
		else
		{
			$this->extendsData($options);
		}
		
		if ( !empty($o['reindexby']) ){ self::reindex($o); }
		
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
		
		//if ( $this->success ) { $this->extendsData($o); }
		//if ( $this->success ) { $this->extendsData($o + array('method' => __FUNCTION__)); }
		
		// If the request failed, get the errors
		if ( !$this->success )
		{
			//$this->model->errors;
			
			$this->handleModelErrors();
		}
		else
		{
			$this->extendsData($o);
		}
		
		if ( !empty($o['reindexby']) ){ self::reindex($o); }
		
		return $this->data;
	}
	

	public function create($options = array())
	{	
        $o                = &$options;
        $this->success    = false;
        $this->errors     = array();
        $this->warnings   = array();
		
		$resourceData     = $this->filterPostData(array_merge($o,array('method' => 'create')));

		if ( !empty($resourceData) )
		{
			// Launch the creation
			$this->data = $this->model->create($resourceData, $o);
			
			// Get the success of the request
			$this->success 	= $this->model->success;
			
			$this->warnings = array_merge($this->warnings, (array) $this->model->warnings);
			
			// If the request failed, get the errors
			if ( !$this->success )
			{
				$this->extendsData();
				$this->handleModelErrors();
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

		if ( !empty($resourceData) )
		{
			// Launch the creation
			$this->data = $this->model->upsert($resourceData, $o);
			
			// Get the success of the request
			$this->success 	= $this->model->success;
			
			$this->warnings = array_merge($this->warnings, (array) $this->model->warnings);
			
			// If the request failed, get the errors
			if ( !$this->success )
			{
				$this->extendsData();
				$this->handleModelErrors();
			}
		}
		
		return !empty($o['returning']) && isset($this->data) ? $this->data : null;
	}
	
	
	public function retrieve($options = array())
	{
        $o                = &$options;
        $this->success    = false;
        $this->errors     = array();
        $this->warnings   = array();
		
		$this->data       = $this->model->retrieve($o);

		$this->success    = $this->model->success;		
		$this->warnings   = array_merge($this->warnings, (array) $this->model->warnings);

		// If the request failed, get the errors
		if ( !$this->success )
		{
			//$this->model->errors;
			
			$this->handleModelErrors();
		}
		else
		{
			$this->extendsData(array('isCollection' => false));
		}
		
		return $this->data;
	}
	
	
	public function update($options = null)
	{
        $o                = &$options;
        $this->success    = false;
        $this->errors     = array();
        $this->warnings   = array();
    
		$resourceData     = $this->filterPostData(array_merge($o, array('method' => 'update')));
        
		if ( !empty($resourceData) )
		{			
			// Launch the creation
			$this->data = $this->model->update($resourceData, $o);
			
			// Get the success of the request
			$this->success 	= $this->model->success;
			
			$this->warnings = array_merge($this->warnings, (array) $this->model->warnings);
			
			// If the request failed, get the errors
			if ( !$this->success )
			{
				//$this->model->errors;
				
				$this->handleModelErrors();
			}
		}
		
		return $this;
	}
	
	
	public function delete($options = null)
	{
        $o                = &$options;
        $this->success    = false;
        $this->errors     = array();
        $this->warnings   = array();

		// Launch the creation
		$this->model->delete($o);
		
		// Get the success of the request
		$this->success    = $this->model->success;
		
		$this->warnings   = array_merge($this->warnings, (array) $this->model->warnings);
		
		// If the request failed, get the errors
		if ( !$this->success )
		{
			//$this->model->errors;
			
			$this->handleModelErrors();
		}
		
		//if ( !$this->data['success'] ) { $this->data['errors'] = '11100'; } // Common object deletion error
		
		return $this;
	}
	
	
	public function handleModelErrors()
	{
		foreach ((array) $this->model->errors as $errNb => $err )
		{
			//TODO: always display sql error message 4100 => $err??? 
			if 		( $errNb === 1048 ){ $this->errors[4100] = $err; continue; } 	// Column(s) cannot be null
			else if ( $errNb === 1054 ){ $this->errors[4100] = $err; continue; } 	// Unknown column
			else if ( $errNb === 1062 ){ $this->errors[] = 4030; continue; } 		// Duplicate entry error (unique key constraint)
			else if ( $errNb === 1066 ){ $this->errors[] = 4150; continue; } 		// Tables alias conflict
			elseif 	( $errNb === 1064 ){ $this->errors[] = 4020; continue; } 		// Request syntax error
			elseif 	( $errNb === 1451 ){ $this->errors[] = 4110; continue; } 		// Creation/update error due to fk constraint(s)
			elseif 	( $errNb === 1452 ){ $this->errors[] = 4050; continue; } 		// Deletion error due to fk constraint(s)
			
			$this->errors[] = 4010; 
		}
		
		return $this;
	}
	
    // TODO: index on database fetch	
	public function reindex($options = array())
	{		
		// Shortcut for options and default options
		$o 					= &$options;
		$rModel 			= &$this->application->dataModel[$this->resourceName];
		$o['indexModifier'] = !empty($o['indexModifier']) ? $o['indexModifier'] : null;
		
		// Handle deprecate param name (incorrect camelCase)
		$o['reindexBy'] 	= isset($o['reindexby']) ? $o['reindexby'] : null;

		// Do not continue if there's no data to process of if data is not an array( ie: for count operations) 
		//if ( empty($this->data) || empty($o['reindexby']) || !is_array($this->data) || !isset($this->data[0][$o['reindexby']])) { return false; }
		if ( empty($this->data) || empty($o['reindexBy']) || !is_array($this->data) || !isset($this->data[0][$o['reindexBy']])) { return false; }
		
		$tmpData 	= array();
		$isIndex 	= $o['reindexBy'] === 'id' || ( !empty($rModel[$o['reindexBy']]['fk']) && $rModel[$o['reindexBy']]['fk']);
		$isUnique 	= isset($o['isUnique']) ? $o['isUnique'] : ( $isIndex ? true : false );	// Will the new indexes containes unique values or arrays?
		
		foreach ($this->data as $item)
		{
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
		
		$resourceData     = array();
		$rName            = &$this->application->dataModel[$this->resourceName];
		
		$isApi            = ( !empty($_SERVER['PATH_INFO']) && strpos($_SERVER['PATH_INFO'], '/api/') !== false ) || ( isset($o['isApi']) && $o['isApi'] );
		
		// Loop over the data model of the resource
		foreach ($rName as $fieldName => $field)
		{
			// Shortcut for the field name
			// For the api, we use 'normal' forms fieldnames/$resource fields whitout prefix
			// But for everywhere else, they are prefixed by the name of the resource (to avoid conflicts) [ex: userLogin, productSummary, entryExpirationtime]
			$f = $isApi ? $fieldName : $this->resourceSingular . ucFirst($fieldName);
            
			// If the field is required
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
				if ( !isset($f) || (isset($f['error']) && $f['error'] === 4) ) { return; }
						
				class_exists('FileManager') || require(_PATH_LIBS . 'storage/FileManager.class.php');
				
				$fileExtension = FileManager::getInstance()->checkFileType($f, array('allowedTypes' => $field['allowedTypes']));
				
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
		else if ( $field['type'] === 'varchar' )
		{
			$filteredData = filter_var($f, FILTER_SANITIZE_STRING);
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
		/*
		else if ( $field['type'] === 'text' && !empty($field['subtype']) && $field['subtype'] === 'html' )
		{
			$filteredData  = $f;
		}*/
		else
		{
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

}

?>