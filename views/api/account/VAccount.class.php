<?php

class VAccount extends ApiView
{
	public function __construct()
	{
		//$this->resourceName 	= strtolower(preg_replace('/^V(.*)/','$1', __CLASS__));
		$this->resourceName 	= 'sessions';
		$this->filePath 		= dirname(__FILE__);
		
		parent::__construct();
		
		return $this;
	}
	
	public function index()
	{
		return $this;
	}
	
	
	public function login($options = null)
	{		
		// Shortcut for options
		$o = $options;
		
		// Set template data
		$this->data['view'] = array_merge((array) @$this->data['view'], array(
			'method' 		=> __FUNCTION__,
			'template' 		=> 'pages/api/account/' . __FUNCTION__ . '.tpl',
			'resourceName' 	=> $this->resourceName,
			'css' 			=> array('common','api'),
		));
		
		// If the resource creation form has been posted
		if ( !empty($_POST) )
		{
			// Check for the required params
			$reqParams = array('email' => 20010, 'password' => 20012);
			foreach ($reqParams as $key => $val){ if ( empty($_POST[$key]) ) { $this->data['errors'][] = $val; $this->statusCode(400); } }
			
			// Get the user data
			$this->requireControllers('CUsers');
			$email 		= !empty($_POST['email']) ? filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) : null;
			$pass 		= !empty($_POST['password']) ? filter_var($_POST['password'], FILTER_SANITIZE_STRING) : null; 
			$user 		= CUsers::getInstance()->retrieve(array('by' => 'email', 'values' => $email));
			
			// If user mail is not found
			if ( empty($user) ){ $this->data['errors'][] = 10002; $this->statusCode(401); }
			
			// If pass does not match the stored one
			if ( empty($pass) || sha1($pass) !== $user['password'] ){ $this->data['errors'][] = 10003; $this->statusCode(401); }
			
			// Build session data (after saving current post data)
			$savePOST = $_POST;
			$_POST = array();
			$newPOST = array(
				'name' 				=> session_id(), 
				'users_id' 			=> $user['id'], 
				'expiration_time' 	=> time() + (int) _APP_SESSION_DURATION, 
				'ip' 				=> $_SERVER['REMOTE_ADDR']
			);
			foreach ($newPOST as $key => $val) { $_POST[$key] = $val; }
						
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
			$_SESSION = array_merge((array) $_SESSION, array('id' => $newPOST['name'], 'users_id' => $newPOST['users_id']));
			
			// Clean old expired session for this user id
			!_APP_KEEP_OLD_SESSIONS && $this->C->delete(array(
				'conditions' 	=> array(
					'users_id' => $user['id'],
					array('expiration_time', '<', ("FROM_UNIXTIME('" . strtotime('-1 day') . "')")),
				),
			));
			
			// Return them as proper data session object
			$this->data[$this->resourceSingular] = array('id' => $newPOST['name'], 'users_id' => $newPOST['users_id']);
			
			$this->respondError(201);
		}
				
		return $this->render();
	}
	
	public function signup()
	{		
		$this->requireViews(array('VUsers' => 'api/users/'));
		
		$VUsers = new VUsers();
		
		return $VUsers->index();
	}
}

?>