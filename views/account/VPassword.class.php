<?php

class VPassword extends View
{
    public function __construct(&$application)
    {
        //$this->setResource(array('class' => __CLASS__, 'singular' => 'sample'));
		$this->filePath 		= dirname(__FILE__);
		
		parent::__construct($application);
		
		return $this;
	}
	
	public function lost()
	{
		if ( !_APP_ALLOW_LOST_PASSWORD_RESET ){ return $this->redirect(_URL_HOME); }
		
		$this->data['view'] = array_merge((array) @$this->data['view'], array(
			'name'           => 'accountPassword' . ucfirst(__FUNCTION__),
			'method' 		=> __FUNCTION__,
			'template'		=> 'specific/pages/account/password/' . __FUNCTION__ . '.tpl',
		));
		
		if ( !empty($_POST['userEmail']) )
		{
			// Get the passed mail, clean it and try to get related user data
			$CUsers 	= new CUsers();
			$email 		= filter_var($_POST['userEmail'], FILTER_VALIDATE_EMAIL);
			$user 		= $CUsers->retrieve(array('by' => 'email', 'values' => $email));
			
			// If the user has been found, send the reset password link to the user
			if ( $user )
			{
				$CUsers->sendResetPasswordMail($user['id']);
				
				$this->data['success'] = true;
			}
		}

		return $this->render();
	}
	
	
	public function reset()
	{
		// TODO: used some goto to prevent doing some tests while others failed
		// !!! goto operator is only available since php 5.3 !!!
		// Removing them will just display all errors to the users (even if the first test failed)
		
		// TODO:
		// make password reset key valid only for some time (48 hours?)
		
		if ( !_APP_ALLOW_LOST_PASSWORD_RESET ){ return $this->redirect(_URL_HOME); }
		
		$args 		= func_get_args();
		
		$this->data['view'] = array_merge((array) @$this->data['view'], array(
			'name'           => 'accountPassword' . ucfirst(__FUNCTION__),
			'method' 		=> __FUNCTION__,
			'template'		=> 'specific/pages/account/password/' . __FUNCTION__ . '.tpl',
		));
		
		// Get passed the security key
		$key 		= !empty($_GET['key']) ? filter_var($_GET['key'], FILTER_SANITIZE_STRING) : null;
		$uId 		= !empty($args[0]) ? intVal($args[0]) : null;
		
		// Check that the user id has been passed
		if ( !$uId ){ $this->data['errors'][1001] = 'id'; goto end; }

		// Check that the user id has been passed
		if ( empty($_GET['key']) ){ $this->data['errors'][1001] = 'key'; goto end; }
		
		// Get user
		$CUsers 	= new CUsers();
		$user 		= !empty($args[0]) ? $CUsers->retrieve(array('by' => 'id', 'values' => $uId)) : null;
		
		// Check that the user has been sent an security key
		if ( empty($user['password_reset_key']) ){ $this->data['errors'][10017] = null; goto end; } // Missing reset password key in database
		
		// Check that the passed key match the store one 
		if ( $key !== $user['password_reset_key'] ){ $this->data['errors'][10018] = null; goto end; } // Wrong security reset password key
		
		if ( !empty($_POST) )
		{			
			// Check for required fields
			$req 		= array('userNewPassword','userNewPasswordConfirm');
			$present 	= array_intersect(array_keys($_POST),$req);
			
			// Compare required fields list with passed one
			if ( $present !== $req ) { $this->data['errors'][1001] = join(', ', $present); goto end; }
			
			// Check that the new password is not empty
			if ( empty($_POST['userNewPassword']) ){ $this->data['errors'][1001] = 'new password'; goto end; }

			// Check if passed password are not empty & are identical
			if ( $_POST['userNewPassword'] !== $_POST['userNewPasswordConfirm'] ){ $this->data['errors'][] = 10004; goto end; }
		}

		// If there's no error
		if ( !empty($_POST) && !$this->data['errors'] )
		{
			// Since user passwords are protected against modification (only editable by logged owner)
			// We have to temporarily log the user session
			$curPOST = $_POST;
			$_POST = array(
				'name' 				=> session_id(), 
				'user_id' 			=> $user['id'], 
				'expiration_time' 	=> ( !empty($_SERVER['REQUEST_TIME']) ? $_SERVER['REQUEST_TIME'] : time() ) + (int) _APP_SESSION_DURATION,
				'ip' 				=> $_SERVER['REMOTE_ADDR'],
			);
			$sid = CSessions::getInstance()->create(array('isApi' => 1, 'returning' => 'id'));
			// Store the session data
			$_SESSION = array_merge((array) $_SESSION, array(
			     'id'            => session_id(), 
			     'user_id'       => $user['id'],
            ));
			
			// If everything is ok, reset the user password
			$_POST = $curPOST;
			$_POST = array('password' => $_POST['userNewPassword'], 'password_reset_key' => '');
			
//$this->dump($_POST);
//$this->dump($_SESSION);
			
			// Only then can the password be changed			
			$CUsers->update(array('isApi' => 1, 'conditions' => array('id' => $user['id'])));
			$this->application->logged = false;
			unset($_SESSION);
			session_destroy();
			
			$this->data['success'] = $CUsers->success;
		}
		
		end:
		return $this->render();
	}
	
	/*
	public function change()
	{
		$this->data['view'] = array_merge((array) @$this->data['view'], array(
			'name'           => 'accountPassword' . ucfirst(__FUNCTION__),
			'method' 		=> __FUNCTION__,
			'template'		=> 'specific/pages/account/password/' . __FUNCTION__ . '.tpl',
		));
		
		if ( !empty($_POST) )
		{
			// Check for required fields
			$req 		= array('userEmail','userOldPassword','userNewPassword','userNewPasswordConfirm');
			$present 	= array_intersect($_POST,$req);
			
			// Compare required fields list with passed one
			if ( $present !== $req ) { $this->data['errors'][1001] = join(', ', $present); }
			
			// If everything is ok, reset the user password
		}

		return $this->render();
	}*/
	
};

?>