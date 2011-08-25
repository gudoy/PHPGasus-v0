<?php

class VAccount extends ApiView
{
	public function __construct(&$application)
	{
		//$this->resourceName 	= strtolower(preg_replace('/^V(.*)/','$1', __CLASS__));
		$this->resourceName 	= 'sessions';
		$this->filePath 		= dirname(__FILE__);
		
		parent::__construct($application);
		
		return $this;
	}
	
	public function index()
	{
		$args = func_get_args();
		
		$this->dispatchMethods($args, array('allowed' => 'login'));
	}
	
	
	public function login()
	{
		$args = func_get_args();
		
		// Set template data
		$this->data['view'] = array_merge((array) @$this->data['view'], array(
			'method' 		=> __FUNCTION__,
			'template' 		=> 'specific/pages/api/account/' . __FUNCTION__ . '.tpl',
		));
		
		if ( !empty($_POST) )
		{
			// Check for the required params
			$reqParams = array('email', 'password','device_id');
			//$reqParams = array('email', 'password');
			foreach ($reqParams as $param)
			{
				// If the field is found
				if ( !empty($_POST[$param]) ){ continue; }
				
				// Otherwise, throw an 417 Expectation Failed with missing field detail
				$this->data['errors'][1003] = $param;
				
				return $this->statusCode(417);
			}
			
			// Get the user data
			$email 		= !empty($_POST['email']) ? filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) : null;
			$pass 		= !empty($_POST['password']) ? filter_var($_POST['password'], FILTER_SANITIZE_STRING) : null; 
			$user 		= CUsers::getInstance()->retrieve(array('by' => 'email', 'values' => $email));
			
			// If user mail is not found
			//if ( empty($user) ){ $this->data['errors'][] = 10002; $this->statusCode(401); }
			if ( empty($user) ){ return $this->statusCode(401); }
			
			// If pass does not match the stored one
			//if ( empty($pass) || sha1($pass) !== $user['password'] ){ $this->data['errors'][] = 10003; $this->statusCode(401); }
			if ( empty($pass) || sha1($pass) !== $user['password'] ){ return $this->statusCode(401); }
			
			// Check that the passed device id is the same than the user one
			$dvcId = filter_var($_POST['device_id'], FILTER_SANITIZE_STRING);
			if ( $dvcId !== $user['device_id'] )
			{
				$this->data['users'] = $user;
				return $this->statusCode(409);
			}
			
			// Build session data (after saving current post data)
			//$savePOST 	= $_POST;
			$_POST 		= array(
				'name' 				=> session_id(), 
				'user_id' 			=> $user['id'], 
				'expiration_time' 	=> time() + (int) _APP_SESSION_DURATION, 
				'ip' 				=> $_SERVER['REMOTE_ADDR']
			);
						
			// Launch the creation
			$this->C->create();
		}
		
		// Set output data		
		$this->data = array_merge($this->data, array(
			'success' 		=> $this->C->success, 
			'errors'		=> $this->data['errors'] + (array) $this->C->errors,
			'warnings' 		=> $this->data['warnings'] + (array) $this->C->warnings,
		));
		
		// If the operation succeed, reset the $_POST
		if ( $this->data['success'] )
		{
			unset($_POST);
			
			// Store the session data
			$_SESSION['id'] 		= session_id();
			$_SESSION['user_id'] 	= $user['id'];
			
			// Clean old expired session for this user id
			!_APP_KEEP_OLD_SESSIONS && $this->C->delete(array(
				'conditions' 	=> array(
					'users_id' => $user['id'],
					//array('expiration_time', '<', ("FROM_UNIXTIME('" . strtotime('-1 day') . "')")),
					array('expiration_time', '<', strtotime('-1 day')),
				),
			));
			
			// Return session data (filtered)
			$this->data[$this->resourceSingular] = array('id' => session_id(), 'user_id' => $user['id']);
			
			$this->statusCode(201);
		}
				
		return $this->render();
	}
}

?>