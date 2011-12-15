<?php

class VCategories extends ApiView
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
		
		$this->dispatchMethods($args, array('allowed' => 'index,retrieve'));
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
		
		// If no wishlist has been found
		if ( empty($resources) ) { return $this->statusCode(204); }
		# listing block end
			
		return $this->render();
	}

	public function create()
	{
		$this->requireLogin();
		
		$args 		= func_get_args(); 															// Get passed arguments
		$o 			= &$this->options;															// Shortcurt/alias for options
		$rName 		= $this->resourceName; 														// Shortcut for resource name 
		
		
		// If POST data have been passed
		if ( !empty($_POST) )
		{
			if ( empty($_POST['name']) ){ $this->data['errors'][1001] = 'name'; return $this->statusCode(417); }
			
			// Check that the category does not already exists
			$category = $this->C->retrieve(array('by' => 'name', 'values' => filter_var($_POST['name'], FILTER_SANITIZE_STRING)));
			if ( !empty($category) )
			{ 
				$this->data['categories'] = $category; 
				
				return $this->statusCode(409);
			}
			
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
		$opts 		= array('by' => ( !empty($o['by']) ? $o['by'] : ( is_numeric($rid) ? 'id' : 'admin_title' ) ) ); 
		$opts 		= array_merge($o, $opts, array('values' => $rid));
		
		// If no resource identifier has been found
		if ( empty($rid) ) { $this->data['errors'][1001] = 'id or admin_title'; return $this->statusCode(400); }
		
		// Try to get the wishlist
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
	
};

?>