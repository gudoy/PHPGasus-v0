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
		
		//$this->dataModel = $dataModel;
		$this->application->dataModel = &$dataModel;
		
		if ( isset($this->resourceName) )
		{
			class_exists('Model') || require(_PATH_LIBS . 'databases/Model_' . _DB_SYSTEM . '.class.php');
			
//$this->dump($this->resourceName);
            
			// Instanciate the resource model
			$mName  	= 'M' . ucfirst($this->resourceName);
			$this->model 		= new $mName(&$this->application);
			//$this->m 			= &$this->model; 						// Shortcut for model 
		}
		
		return $this;
	}

	
	public function index($options = array())
	{
		$o                = &$options;
		$this->success    = false;
		$this->errors     = array();
		$this->warnings   = array();
		
		$this->data       = $this->model->index($options);
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
		
		if ( !empty($o['reindexby']) ){ self::reindex($options); }
		
		return $this->data;
	}
	

	public function search($options = array())
	{
        $o                = &$options;
        $this->success    = false;
        $this->errors     = array();
        $this->warnings   = array();
		
		$this->data       = $this->model->search($options);
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
		
		if ( !empty($o['reindexby']) ){ self::reindex($options); }
		
		return $this->data;
	}
	

	public function create($options = null)
	{	
        $o                = &$options;
        $this->success    = false;
        $this->errors     = array();
        $this->warnings   = array();
		
		$resourceData     = $this->filterPostData((array) $options + array('method' => 'create'));

		if ( !empty($resourceData) )
		{
			// Launch the creation
			$this->data = $this->model->create($resourceData, $options);
			
			// Get the success of the request
			$this->success 	= $this->model->success;
			
			$this->warnings = array_merge($this->warnings, (array) $this->model->warnings);
			
			// If the request failed, get the errors
			if ( !$this->success )
			{
				//$this->model->errors;
				
				$this->extendsData();
				$this->handleModelErrors();
			}
		}
		
//var_dump($this->data);
		
		//return $this;
		//return !empty($o['returning']) && isset($this->data) ? $this->data : $this;
		return !empty($o['returning']) && isset($this->data) ? $this->data : null;
	}
	
	
	public function retrieve($options = array())
	{
        $o                = &$options;
        $this->success    = false;
        $this->errors     = array();
        $this->warnings   = array();
		
		$this->data       = $this->model->retrieve($options);
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
    
		$resourceData     = $this->filterPostData((array) $options + array('method' => 'update'));
        
		if ( !empty($resourceData) )
		{			
			// Launch the creation
			$this->data = $this->model->update($resourceData, $options);
			
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
		$this->model->delete($options);
		
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
//var_dump($this->model->errors);
		
		foreach ((array) $this->model->errors as $errNb => $err )
		{
//var_dump($errNb);
			
			if 		( $errNb === 1054 ){ $this->errors[4100] = $err; continue; } 	// Unknown column
			else if ( $errNb === 1062 ){ $this->errors[] = 4030; continue; } 		// Duplicate entry error (unique key constraint)
			elseif 	( $errNb === 1064 ){ $this->errors[] = 4020; continue; } 		// Request syntax error
			elseif 	( $errNb === 1451 ){ $this->errors[] = 4110; continue; } 		// Creation/update error due to fk constraint(s)
			elseif 	( $errNb === 1452 ){ $this->errors[] = 4050; continue; } 		// Deletion error due to fk constraint(s)
			
			$this->errors[] = 4010; 
		}
		
//var_dump($this->errors);
		
		return $this;
	}
	
    // TODO: index on database fetch	
	public function reindex($options = array())
	{
		// Shortcut for options and default options
		$o 					= &$options;
		$rModel 			= $this->application->dataModel[$this->resourceName];
		$o['indexModifier'] = !empty($o['indexModifier']) ? $o['indexModifier'] : null;

		// Do not continue if there's no data to process of if data is not an array( ie: for count operations) 
		if ( empty($this->data) || empty($o['reindexby']) || !is_array($this->data) || !isset($this->data[0][$o['reindexby']])) { return false; }
		
		$tmpData 	= array();
		$isIndex 	= $o['reindexby'] === 'id' || ( !empty($rModel[$o['reindexby']]['fk']) && $rModel[$o['reindexby']]['fk']);
		$isUnique 	= isset($o['isUnique']) ? $o['isUnique'] : ( $isIndex ? true : false );	// Will the new indexes containes unique values or arrays?
		
		foreach ($this->data as $item)
		{
			// Set index key/name
			if 			( $o['indexModifier'] === 'lower' )	{ $k = strtolower($item[$o['reindexby']]); }
			else if 	( $o['indexModifier'] === 'upper' )	{ $k = strtoupper($item[$o['reindexby']]); }
			else 											{ $k = $item[$o['reindexby']]; }  
			
			// Do not assign data whose index is empty
			//if ( $k == '' ){ continue; }
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
	
	/*
	 * @Deprecated???
     * Safe to be remove
	 */
	/*
	public function checkRequiredFields($options = array())
	{
		$o 		= &$options; 		// Shortcut for options
		$return = true; 			// Default return value to true
		
		// Do not continue if no fields list has been passed 
		if ( empty($o['fields']) ){ return false; }
		
		// Loop over the fields
		foreach ($o['fields'] as $fieldName)
		{
			// If one of them is missing, break returning false
			if ( empty($_POST[$fieldName]) ) { $return = false; break; }
		}
		
		return $return;
	} 
    */
	
	public function filterPostData($options = null)
	{
		// Shortcut for options
		$o = &$options;
		
		// Do not continue if the resource has not been defined
		if ( empty($this->resourceName) ) { return; }
		
		$resourceData     = array();
		$rName            = &$this->application->dataModel[$this->resourceName];
		
		//$isApi = strpos($_SERVER['PATH_INFO'], '/api/') !== false;
		$isApi            = !empty($_SERVER['PATH_INFO']) && strpos($_SERVER['PATH_INFO'], '/api/') !== false;
		
		// Loop over the data model of the resource
		//foreach ($this->dataModel[$this->resourceName] as $fieldName => $field)
		//foreach ($this->application->dataModel[$this->resourceName] as $fieldName => $field)
		foreach ($rName as $fieldName => $field)
		{
			// Shortcut for the field name
			// For the api, we use 'normal' forms fieldnames/$resource fields whitout prefix
			// But for everywhere else, they are prefixed by the name of the resource (to avoid conflicts) [ex: userLogin, productSummary, entryExpirationtime]
			$f = $isApi ? $fieldName : $this->resourceSingular . ucFirst($fieldName);
            
			// If the field is required but not present, throw an error
			// TODO: continue looping over the fields to list all missing ones
			//if ( isset($field['required']) && $field['required'] && (empty($_FILES[$f]) && empty($_POST[$f])) ){ $this->errors[] = 1002; return; }
			//if ( isset($field['required']) && $field['required'] && (empty($_FILES[$f]) && empty($_POST[$f])) )
			if ( isset($field['required']) && $field['required'] && empty($_FILES[$f]) && empty($_POST[$f])
			         && ( empty($o['method']) || $o['method'] !== 'update' )
               )
			{
			    $this->errors[1003] = $f;
			    return;
            }
			
			// if the POST data for each field exists
			if ( isset($_FILES[$f]) || isset($_POST[$f]) )
			{
				// Set the proper super global to use: $_FILES for posted files, otherwise $_POST
				$usedSpGlobale = isset($_FILES[$f]) ? 'files' : 'post';
				$spGlobaleItems = $usedSpGlobale === 'files' ? $_FILES[$f] : $_POST[$f];
				
				if ( is_array($spGlobaleItems) && count($spGlobaleItems) > 0 && ($usedSpGlobale !== 'files' || (isset($o['multipleItems']) && $o['multipleItems'] === true)) )
				{
					// Loop over superglobal field indexes
					$i = 0;
					foreach ($spGlobaleItems as $index => $val)
					{
						$resourceData[$i][$fieldName] = $this->filterSingle($field, $val, $o);
						$i++;
					}
				}
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
			$filteredData = filter_var($f, FILTER_SANITIZE_NUMBER_INT);
		}
		else if ( $field['type'] === 'float' )
		{
			$filteredData = filter_var($f, FILTER_VALIDATE_FLOAT);
		}
		else if ( $field['type'] === 'varchar' && !empty($field['subtype']) && $field['subtype'] === 'email' )
		{
			$filteredData = filter_var($f, FILTER_VALIDATE_EMAIL);
		}
		else if ( $field['type'] === 'varchar' && !empty($field['subtype']) && $field['subtype'] === 'password' )
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
			$filteredData = $f == '1' || $f == 'true' || $f == 't' ? 1 : 0;
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
	
	
    /*
    // Safe for remove???
	public function redirect($url)
	{
		header("Location:" . $url);
		die();
	}
    */

}

?>
