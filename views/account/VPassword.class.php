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
				
				/*
				// If password expiration feature is activated
				if ( defined('_APP_PASSWORDS_EXPIRATION') && _APP_PASSWORDS_EXPIRATION > 0 )
				{
					$curTime = !empty($_SERVER['REQUEST_TIME']) ? $_SERVER['REQUEST_TIME'] : time();
					$_POST = array(
						'password_expiration' => $curTime + _APP_PASSWORDS_EXPIRATION
					);
					$CUsers->update(array('isApi' => 1, 'conditions' => array('id' => $user['id'])));	
				}*/
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
			$curTime 	= !empty($_SERVER['REQUEST_TIME']) ? $_SERVER['REQUEST_TIME'] : time();
			$_POST 		= $curPOST;
			$_POST 		= array(
				'password' 				=> $_POST['userNewPassword'], 
				'password_reset_key' 	=> '',
				'password_expiration' 	=> _APP_PASSWORDS_EXPIRATION_TIME > 0 ? $curTime + _APP_PASSWORDS_EXPIRATION_TIME : null,
			);
			
			// Only then can the password be changed			
			$CUsers->update(array('isApi' => 1, 'conditions' => array('id' => $user['id'])));
			$this->application->logged = false;
			unset($_SESSION);
			session_destroy();
			
			$this->data['success'] = $CUsers->success;
		}
		
		end:
		return $this->render();
		;
	}
	
	public function change()
	{
		// Require the user to be logged
		$this->requireLogin();
		
		$this->data['view'] = array_merge((array) @$this->data['view'], array(
			'name'          => 'accountPassword' . ucfirst(__FUNCTION__),
			'method' 		=> __FUNCTION__,
			'template'		=> 'specific/pages/account/password/' . __FUNCTION__ . '.tpl',
		));

		if ( !empty($_POST) )
		{			
			// Required params (param => error code if missing)
			$req = array('userOldPassword' => 20012, 'userNewPassword' => 20013, 'userNewPasswordConfirm' => 20014);

			// Foreach of the required params
			foreach ($req as $key => $val)
			{
				// If it has been passed, and is not empty after beeing sanitized, just continue
				if ( isset($_POST[$key])
					&& ($_POST[$key] = Tools::sanitizeString($_POST[$key])) 
					&& !empty($_POST[$key]) ) { continue; }
				
				// Otherwise, return the proper error 
				$this->data['errors'][] = $val;
			}
			if ( !empty($this->data['errors']) ){ $this->render(); }
			
			// Check if password and password confirmation are the same
			if ( $_POST['userNewPassword'] !== $_POST['userNewPasswordConfirm'] ) { $this->data['errors'][] = '10004'; $this->render(); }
			
			// Get user data
			$CUsers = new CUsers();
			$user 	= $CUsers->retrieve(array('by' => 'id', 'values' => $_SESSION['user_id']));
			
			// If pass does not match the stored one
			if ( sha1($_POST['userOldPassword']) !== $user['password'] ){ $this->data['errors'][] = 10016; $this->render(); }
			
			// If everything is ok, update the user password
			if ( empty($this->data['errors']) )
			{
				$curTime 	= !empty($_SERVER['REQUEST_TIME']) ? $_SERVER['REQUEST_TIME'] : time();
				$_POST 		= array(
					'password' 				=> $_POST['userNewPassword'], 
					'password_reset_key' 	=> '',
					'password_expiration' 	=> _APP_PASSWORDS_EXPIRATION_TIME > 0 ? $curTime + _APP_PASSWORDS_EXPIRATION_TIME : null,
				);
				$CUsers->update(array('isApi' => 1, 'conditions' => array('id' => $user['id'])));
				$_POST 		= array();
				
				$this->data['success'] = $CUsers->success;
			}
		}

		$this->render();
	}
	
};

?>