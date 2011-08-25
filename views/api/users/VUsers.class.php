<?php

class VUsers extends ApiView
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
		
		$this->dispatchMethods($args, array('allowed' => 'create,update'));
		//$this->dispatchMethods($args, array('allowed' => 'index,create,retrieve,update,delete'));
		
		# Comment/remove the following block if you want to allow listing resources
		/*
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
		*/
			
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
			// Check for required fields
			$req 		= array('email', 'password', 'device_id');
			
			// Check for valid mail, not empty pass & not empty device_id
			foreach ( $req as $field )
			{
				// Set filter to use
				$filter = $field === 'email' ? FILTER_VALIDATE_EMAIL : FILTER_SANITIZE_STRING;
				
				// If invalid value, return 'missing valid $field' error 
				if ( !isset($_POST[$field]) || !filter_var($_POST[$field], $filter) )
				{
					$this->data['errors'][1001] = $field; 
					return $this->statusCode(417);
				}
			}
			
			// Try to update the resource & get the create resource
			$rid 					= $this->C->create(array('returning' => 'id'));
			
			if ( in_array(4030, $this->C->errors) ){ return $this->statusCode(409); }
			
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


	public function update()
	{
		$args 		= func_get_args(); 															// Get passed arguments
		$rName 		= $this->resourceName; 														// Shortcut for resource name
		$rid 		= !empty($args[0]) ? intVal($args[0]) : null; 	// Shortcut for resource identifier
		
		// If no resource identifier has been found
		if ( empty($rid) ) { $this->data['errors'][1001] = 'id'; return $this->statusCode(400); }
		
		// If POST data have been passed
		if ( !empty($_POST) )
		{
			// Get user data
			$user = $this->C->retrieve(array('by' => 'id', 'values' => $rid));
			
			// Require the user to be logged or to have passed the password of the user it tries to update
			if ( ( empty($_POST['password']) || sha1($_POST['password']) !== $user['password'] ) 
				&& !$this->isLogged() ){ $this->statusCode(401); }
		
			// If the user is logged, only allow him to update himself
			if ( $this->logged && $rid !== $_SESSION['user_id'] ){ $this->statusCode(401); }
			
			// If a new device_id is passed, we have to delete all the current user's medias
			if ( !empty($_POST['device_id']) && $_POST['device_id'] !== $user['device_id'] )
			{
				$userMedias = CMedias::getInstance()->index(array('conditions' => array('user_id' => $user['id']), 'limit' => -1));
				
				$encFilePath	= _PATH . '../coffretDigitialMedias/';
				
				foreach ( (array) $userMedias as $media )
				{
					$deleted 	= unlink($encFilePath . $media['url']);
					$deletedTn 	= unlink($encFilePath . $media['cover_small_url']);
				} 
				
				CMedias::getInstance()->delete(array('conditions' => array('user_id' => $user['id'])));
			}
			
			// Prevents conflics with the database model which,
			// if the password is passed, 
			// currently requires the user to be logged to edit its password  
			unset($_POST['password']);
			
			// Prevents user from self activating
			// TODO: handle this in controller validation
			unset($_POST['activated']);
			unset($_POST['password_reset_key']);
			unset($_POST['activation_key']);
			
			$opts = array('conditions' => array('id' => $rid));
			
			// Try to update the resource
			$this->C->update($opts);
			
			// Get the output data
			$this->data = array_merge($this->data, array(
				'success' 				=> $this->C->success, 
				'errors'				=> $this->C->errors,
				'warnings' 				=> $this->C->warnings,
			));
			
			// Get the output data
			$this->data[$rName] = $this->C->retrieve($opts);
		}
		
		// If no resource has been found
		if ( empty($this->data[$rName]) ) { return $this->statusCode(204); }
		
		// If the operation succeed, reset the $_POST
		if ( $this->data['success'] )
		{			
			unset($_POST);
		}
		
		return $this->render();
	}
	
};

?>