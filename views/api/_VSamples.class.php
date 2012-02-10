<?php

class VSamples extends ApiView
{
    public function __construct(&$application)
    {
        $this->setResource(array('class' => __CLASS__));
		$this->filePath 		= dirname(__FILE__);
		
		parent::__construct($application);
		
		return $this;
	}
	
	public function index()
	{
		$args 		= func_get_args(); 															// Get passed arguments
		$o 			= &$this->options;															// Shortcurt/alias for options
		$rName 		= $this->resourceName; 														// Shortcut for resource name
		
		$this->dispatchMethods($args, array('allowed' => 'index'));
		//$this->dispatchMethods($args, array('allowed' => 'index,create,retrieve,update,delete'));
		
		# Comment/remove the following block if you want to allow listing resources
		//$opts 		= array('by' => ( !empty($o['by']) ? $o['by'] : 'id' ) ); 
		//$opts 		= array_merge($o, $opts);
		//$resources 	= $this->C->index(array_merge($o, $opts)); 								// Try to get the resources
		
		$resources 	= $this->C->index($o); 														// Try to get the resources

		// Set output data		
		$this->data = array_merge($this->data, array(
			$rName 			=> $resources,
			'success' 		=> $this->C->success, 
			'errors'		=> $this->C->errors,
			'warnings' 		=> $this->C->warnings,
		));
		
		// If no resource has been found
		if ( empty($resources) ) { return $this->statusCode(204); }
		# listing block end
			
		return $this->render();
	}

	public function create()
	{
		$args 		= func_get_args(); 															// Get passed arguments
		$o 			= &$this->options;															// Shortcurt/alias for options
		$rName 		= $this->resourceName; 														// Shortcut for resource name
		
		// If no resource identifier has been found
		//if ( empty($_POST['device_id']) && empty($_POST['user_firstname']) ){ $this->data['errors'][1001] = 'device_id or user_firstname'; return $this->statusCode(417); } 
		
		// If POST data have been passed
		if ( !empty($_POST) )
		{
			// Try to update the resource & get the create resource
			$rid 					= $this->C->create(array('returning' => 'id'));
			
			// Get the output data
			$this->data				 = array_merge($this->data, array(
				'success' 	=> $this->C->success, 
				'errors'	=> $this->C->errors,
				'warnings' 	=> $this->C->warnings,
			));
		}
				
		// If the operation succeed, reset the $_POST
		if ( $this->data['success'] )
		{
			unset($_POST);
			
			// Get the created resource
			$this->data[$rName] = $this->C->retrieve(array('by' => 'id', 'values' => $rid));
			
			return $this->statusCode(201);
		}
		
		return $this->render();
	}
	
	
	public function retrieve()
	{
		$args 		= func_get_args(); 															// Get passed arguments
		$o 			= &$this->options;															// Shortcurt/alias for options
		$rName 		= $this->resourceName; 														// Shortcut for resource name
		$rid 		= !empty($args[0]) ? $args[0] : null; 										// Shortcut for resource identifier
		$filter 	= 'FILTER_SANITIZE_' . (is_numeric($rid) ? 'NUMBER_INT' : 'STRING'); 		// Set the filter to use
		$rid 		= filter_var($rid, constant($filter));										// Filter the value
		$opts 		= array('by' => ( !empty($o['by']) ? $o['by'] : ( is_numeric($rid) ? 'id' : 'slug' ) ) ); 
		$opts 		= array_merge($o, $opts, array('values' => $rid));
		
		// If no resource identifier has been found
		if ( empty($rid) ) { $this->data['errors'][1001] = 'id or slug'; return $this->statusCode(400); }
		
		// Try to get the resource
		$resource 	= $this->C->retrieve($opts);
		
		// Set output data		
		$this->data = array_merge($this->data, array(
			$rName 		=> $resource,
			'success' 	=> $this->C->success, 
			'errors'	=> $this->C->errors,
			'warnings' 	=> $this->C->warnings,
		));
		
		// If no resource has been found
		if ( empty($resource) ) { return $this->statusCode(204); }
		
		return $this->render();
	}	
	
	
	public function update()
	{
		$args 		= func_get_args(); 															// Get passed arguments
		$o 			= &$this->options;															// Shortcurt/alias for options
		$rName 		= $this->resourceName; 														// Shortcut for resource name
		$rid 		= !empty($args[0]) ? $args[0] : null; 										// Shortcut for resource identifier
		$filter 	= 'FILTER_SANITIZE_' . (is_numeric($rid) ? 'NUMBER_INT' : 'STRING'); 		// Set the filter to use
		$rid 		= filter_var($rid, constant($filter));										// Filter the value
		$opts 		= array('by' => ( !empty($o['by']) ? $o['by'] : ( is_numeric($rid) ? 'id' : 'slug' ) ) ); 
		$opts 		= array_merge($o, $opts, array('values' => $rid));
		
		// If no resource identifier has been found
		if ( empty($rid) ) { $this->data['errors'][1001] = 'id or slug'; return $this->statusCode(400); }
		
		// If POST data have been passed
		if ( !empty($_POST) )
		{
			// Try to update the resource
			$this->C->update($opts);
			
			// Get the output data
			$this->data = array_merge($this->data, array(
				'success' 				=> $this->C->success, 
				'errors'				=> $this->C->errors,
				'warnings' 				=> $this->C->warnings,
			));
		}
		
		// Get the output data
		$this->data[$rName] = $this->C->retrieve($opts);
		
			// If no resource has been found
		if ( empty($this->data[$rName]) ) { return $this->statusCode(204); }
		
		// If the operation succeed, reset the $_POST
		if ( $this->data['success'] )
		{			
			unset($_POST);
		}
		
		return $this->render();
	}
	
	
	public function delete()
	{
		$args 		= func_get_args(); 															// Get passed arguments
		$o 			= &$this->options;															// Shortcurt/alias for options
		$rName 		= $this->resourceName; 														// Shortcut for resource name
		$rid 		= !empty($args[0]) ? $args[0] : null; 										// Shortcut for resource identifier
		$filter 	= 'FILTER_SANITIZE_' . (is_numeric($rid) ? 'NUMBER_INT' : 'STRING'); 		// Set the filter to use
		$rid 		= filter_var($rid, constant($filter));										// Filter the value
		$opts 		= array('by' => ( !empty($o['by']) ? $o['by'] : ( is_numeric($rid) ? 'id' : 'slug' ) ) ); 
		$opts 		= array_merge($o, $opts, array('values' => $rid));
		
		// If no resource identifier has been found
		if ( empty($rid) ) { $this->data['errors'][1001] = 'id or slug'; return $this->statusCode(400); }
		
		// Try to get the resource
		$this->C->delete($opts);
		
		// Set output data		
		$this->data = array_merge($this->data, array(
			'success' 				=> $this->C->success, 
			'errors'				=> $this->C->errors,
			'warnings' 				=> $this->C->warnings,
		));
		
		return $this->render();
	}
	
};

?>