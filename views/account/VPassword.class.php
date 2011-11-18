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
	
	public function index()
	{
		//$this->redirect(_URL_HOME);
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
			
			// If the user has not been found 
			if ( !$user ){ $this->data['errors'][10205] = null; $this->render(); }
			
			// Send the reset password link to the user
			$CUsers->sendResetPasswordMail($user['id']);
				
			$this->data['success'] = true;
		}

		return $this->render();
	}
	
	
	public function reset()
	{
		// TODO:
		// make password reset key valid only for some time (48 hours?)
		
		if ( !_APP_ALLOW_LOST_PASSWORD_RESET ){ return $this->redirect(_URL_HOME); }
		
		$args 		= func_get_args();
		
		$this->data['view'] = array_merge((array) @$this->data['view'], array(
			'name'           => 'accountPassword' . ucfirst(__FUNCTION__),
			'method' 		=> __FUNCTION__,
			'template'		=> 'specific/pages/account/password/' . __FUNCTION__ . '.tpl',
		));
		
		// Get the passed security key
		$key 				= !empty($_GET['key']) ? filter_var($_GET['key'], FILTER_SANITIZE_STRING) : null;
		$uId 				= !empty($args[0]) ? intVal($args[0]) : null;
		
		// Check that the user id has been passed
		if ( !$uId ){ $this->data['errors'][1001] = 'id'; $this->render(); }

		// Check that the user id has been passed
		if ( !$key ){ $this->data['errors'][1001] = 'key'; $this->render(); }
		
		// Instanciate proper controller
		$CUsers 			= new CUsers();
		
		// We have to make the 'password_expiration' fields temporarily editable
		$uDM 				= &$CUsers->application->dataModel['users'];
		if ( isset($uDM['password_expiration']) )
		{
			$curPassExpEditable =  isset($uDM['password_expiration']['editable']) 
									? $uDM['password_expiration']['editable'] 
									: null ;
			$uDM['password_expiration']['editable'] = true;	
		}
		
		// Get user
		$user 				= !empty($args[0]) ? $CUsers->retrieve(array('by' => 'id', 'values' => $uId)) : null;
		
		// Get request or current time
		$curTime 			= !empty($_SERVER['REQUEST_TIME']) ? $_SERVER['REQUEST_TIME'] : time();
		
		// If the feature is activated, check that the minimun required time between 2 password change has passed
		if ( defined('_APP_PASS_MIN_TIME_BETWEEN_CHANGES') && _APP_PASS_MIN_TIME_BETWEEN_CHANGES 
				&& !empty($user['password_lastedit_date'])
				&& ($curTime - $user['password_lastedit_date']) < _APP_PASS_MIN_TIME_BETWEEN_CHANGES  
		){ $this->data['errors'][] = 10035; $this->render(); }
		
		// Check that the user has really been sent a reset key
		if ( empty($user['password_reset_key']) ){ $this->data['errors'][10017] = null; $this->render(); }
		
		// Check that the passed key match the stored one 
		if ( $key !== $user['password_reset_key'] ){ $this->data['errors'][10018] = null; $this->render(); }
		
		if ( !empty($_POST) )
		{			
			// Check for required fields
			$req 		= array('userNewPassword','userNewPasswordConfirm');
			$present 	= array_intersect(array_keys($_POST),$req);
			
			// Compare required fields list with passed one
			if ( $present !== $req ) { $this->data['errors'][1001] = join(', ', $present); $this->render(); }
			
			// Check that the new password is not empty
			if ( empty($_POST['userNewPassword']) ){ $this->data['errors'][1001] = 'new password'; $this->render(); }

			// Check if passed passwords are identical
			if ( $_POST['userNewPassword'] !== $_POST['userNewPasswordConfirm'] ){ $this->data['errors'][] = 10004; $this->render(); }
			
			// If the feature is activated, check that the new password is neither one of the last 2 used passwords nor the current one
			if ( defined('_APP_PASSWORD_FORBID_LAST_TWO') && _APP_PASSWORD_FORBID_LAST_TWO 
				&& !empty($user['password_old_1']) && !empty($user['password_old_2'])
				&& in_array(sha1($_POST['userNewPassword']), array($user['password'], $user['password_old_1'], $user['password_old_2']))
			)
			{ $this->data['errors'][] = 10019; $this->render(); }
		}

		// If there's no error
		if ( !empty($_POST) && !$this->data['errors'] )
		{
			// Since user passwords are protected against modification (only editable by logged owner)
			// We have to temporarily log the user, creating a session
			$curPOST 	= $_POST;
			$_POST 		= array(
				'name' 				=> session_id(), 
				'user_id' 			=> $user['id'], 
				'expiration_time' 	=> ( $curTime ) + (int) _APP_SESSION_DURATION,
				'ip' 				=> $_SERVER['REMOTE_ADDR'],
			);
			$sid 		= CSessions::getInstance()->create(array('isApi' => 1, 'returning' => 'id'));
			
			// Store the current session data
			$curSESSION = $_SESSION;
			
			// Insert logged user data into session
			$_SESSION 	= array_merge((array) $_SESSION, array('id' => session_id(), 'user_id' => $user['id']));
			
			// If everything is ok, reset the user password
			$_POST 		= $curPOST;
			$_POST 		= array(
				'password' 				=> $_POST['userNewPassword'], 
				'password_reset_key' 	=> '',
				'password_expiration' 	=> _APP_PASSWORDS_EXPIRATION_TIME > 0 ? $curTime + _APP_PASSWORDS_EXPIRATION_TIME : null,
			);
			
			// Only then can the password be changed			
			$CUsers->update(array('isApi' => 1, 'conditions' => array('id' => $user['id'])));
			
			// We can now log the user out & restore session to it's previous state
			$this->application->logged = false;
			unset($_SESSION);
			session_destroy();
			$_SESSION = $curSESSION;
			
			$this->data['success'] 	= $CUsers->success;
			$this->data['errors'] 	= $CUsers->errors;
			$this->data['warnings'] = $CUsers->warnings;
		}

		// We can now return 'password_expiration' editable property to its original value
		if ( isset($uDM['password_expiration']) ){ $uDM['password_expiration']['editable'] 	= $curPassExpEditable; }
		
		// Send the dataModel to the html templates to be able to add pattern & hints to form elements
		if ( in_array($this->options['output'], array('html','xhtml')) )
		{
			isset($dataModel) || include(_PATH_CONFIG . 'dataModel.php');
			$this->data['dataModel']['users'] = $dataModel['users'];
		}

		$this->render();
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

		$this->_handlePasswordChange();

		$this->render();
	}

	public function expired()
	{
		// If password expiration feature is activated
		if ( !defined('_APP_PASSWORDS_EXPIRATION_TIME') || _APP_PASSWORDS_EXPIRATION_TIME <= 0 ){ $this->redirect(_URL_HOME); }
		
		$this->data['view'] = array_merge((array) @$this->data['view'], array(
			'name'          => 'accountPassword' . ucfirst(__FUNCTION__),
			'method' 		=> __FUNCTION__,
			'template'		=> 'specific/pages/account/password/' . __FUNCTION__ . '.tpl',
		));
		 
		$this->_handlePasswordchange(array('caseExpired' => true));
		
		if ( $this->data['success'] ) { $this->redirect(_URL_ACCOUNT_PASSWORD_EXPIRED . '?success=1'); }
		
		$this->render();	
	}
	
	
	public function _handlePasswordChange($params = array())
	{
		$p = array_merge(array('caseExpired' => false), $params);
		
		// Send the dataModel to the html templates to be able to add pattern & hints to form elements
		if ( in_array($this->options['output'], array('html','xhtml')) )
		{
			isset($dataModel) || include(_PATH_CONFIG . 'dataModel.php');
			$this->data['dataModel']['users'] = $dataModel['users'];
		}
		
		if ( !empty($_POST) )
		{
			// Required params (param => error code if missing)
			$req = array('userOldPassword' => 20012, 'userNewPassword' => 20013, 'userNewPasswordConfirm' => 20014);
			
			// If the user is not logged, add the email to the required fields array
			if ( !$this->isLogged() ){ $req = array('userEmail' => 20010) + $req; }

			// Foreach of the required fields
			foreach ($req as $key => $val)
			{
				// If it has not been passed
				if ( !isset($_POST[$key]) )	{ $this->data['errors'][] = $val; continue; }
				
				// Otherwise, clean it
				$type 			= $key === 'userEmail' ? 'email' : 'string';
				$_POST[$key] 	= Tools::sanitize($_POST[$key], array('type' => $type));
				
				// If the field is invalid or empty after sanitization
				if ( ($key === 'userEmail' && !Tools::validateEmail($_POST[$key]))
					|| empty($_POST[$key]) ){ $this->data['errors'][] = $val; continue; }
			}
			
			// If there's errors at this point, do not continue and just render
			if ( !empty($this->data['errors']) ){ $this->render(); }
			
			// Check if password and password confirmation are the same
			if ( $_POST['userNewPassword'] !== $_POST['userNewPasswordConfirm'] ) { $this->data['errors'][] = '10004'; $this->render(); }
			
			// Get user data
			$CUsers 	= new CUsers();
			
			// We have to make the 'password_expiration' fields temporarily editable
			$uDM 		= &$CUsers->application->dataModel['users'];
			$curPassExpEditable = isset($uDM['password_expiration']['editable']) ? $uDM['password_expiration']['editable'] : null ;
			$uDM['password_expiration']['editable'] = true;
			
			// We have to prevent old passwords (that are already encrypted to be encrypted again)
			unset($uDM['password_old_1']['subtype']);
			unset($uDM['password_old_2']['subtype']);
			
			$opts 		= !$this->isLogged() 
							? array('by' => 'email', 	'values' => $_POST['userEmail']) 
							: array('by' => 'id', 		'values' => $_SESSION['user_id']);
			$user 		= $CUsers->retrieve($opts);
			
			// Get request or current time
			$curTime 	= !empty($_SERVER['REQUEST_TIME']) ? $_SERVER['REQUEST_TIME'] : time();
			
			// User not found
			if ( !$this->isLogged() && !$user ){ $this->data['errors'][] = 10002; $this->render(); }
			
			// If the feature is activated, check that the minimun required time between 2 password change has passed
			if ( defined('_APP_PASS_MIN_TIME_BETWEEN_CHANGES') && _APP_PASS_MIN_TIME_BETWEEN_CHANGES
					&& empty($p['caseExpired']) 
					&& !empty($user['password_lastedit_date'])
					&& ($curTime - $user['password_lastedit_date']) < _APP_PASS_MIN_TIME_BETWEEN_CHANGES  
			){ $this->data['errors'][] = 10035; $this->render(); }
			
			// If pass does not match the stored one
			//if ( sha1($_POST['userOldPassword']) !== $user['password'] ){ $this->data['errors'][] = 10016; $this->render(); }
			if ( sha1($_POST['userOldPassword']) !== $user['password'] ){ $this->data['errors'][] = 10003; $this->render(); }

			// If the feature is activated, check that the new password is neither one of the last 2 used passwords nor the current one
			if ( defined('_APP_PASSWORD_FORBID_LAST_TWO') && _APP_PASSWORD_FORBID_LAST_TWO 
				&& !empty($user['password_old_1']) && !empty($user['password_old_2'])
				&& in_array(sha1($_POST['userNewPassword']), array($user['password'], $user['password_old_1'], $user['password_old_2']))
			){ $this->data['errors'][] = 10019; $this->render(); }
			
			// If everything is ok, update the user password
			if ( empty($this->data['errors']) )
			{				
				if ( !$this->isLogged() )
				{
					// Since user passwords are protected against modification (only editable by logged owner)
					// We have to temporarily log the user, creating a session
					$curPOST 	= $_POST; 
					$_POST 		= array(
						'name' 				=> session_id(), 
						'user_id' 			=> $user['id'], 
						'expiration_time' 	=> ( $curTime ) + (int) _APP_SESSION_DURATION,
						'ip' 				=> $_SERVER['REMOTE_ADDR'],
					);
					$sid 		= CSessions::getInstance()->create(array('isApi' => 1, 'returning' => 'id'));
					$_POST 		= $curPOST;
					
					// Store the current session data
					$curSESSION = $_SESSION;
					
					// Insert logged user data into session
					$_SESSION 	= array_merge((array) $_SESSION, array('id' => session_id(), 'user_id' => $user['id']));
				}
				
				// Only then can the password be changed 
				$_POST 		= array(
					'password' 				=> $_POST['userNewPassword'], 
					'password_old_2' 		=> !empty($user['password_old_1']) ? $user['password_old_1'] : null,
					'password_old_1' 		=> !empty($user['password']) ? $user['password'] : null,
					'password_reset_key' 	=> '',
					'password_expiration' 	=> _APP_PASSWORDS_EXPIRATION_TIME > 0 ? $curTime + _APP_PASSWORDS_EXPIRATION_TIME : null,
					'password_lastedit_date'=> $curTime,
				);
				$CUsers->update(array('isApi' => 1, 'conditions' => array('id' => $user['id'])));
				
				if ( !$this->isLogged() )
				{
					// We can now log the user out & restore session to it's previous state
					$this->application->logged = false;
					unset($_SESSION);
					session_destroy();
					$_SESSION = $curSESSION;
				}
				
				// Clean $_POST
				$_POST 		= array();
				unset($_POST);
				
				$this->data['success'] 	= $CUsers->success;
				$this->data['errors'] 	= $CUsers->errors;
				$this->data['warnings'] = $CUsers->warnings;
			}

			// We can now return 'password_expiration' editable property to its original value
			$uDM['password_expiration']['editable'] 	= $curPassExpEditable;
			
			// We can now return old passwords subtype property to their original value
			$uDM['password_old_1']['subtype'] = 'password';
			$uDM['password_old_2']['subtype'] = 'password';
		}
	}
	
};

?>