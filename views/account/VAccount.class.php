<?php

class VAccount extends View
{
    public function __construct(&$application)
    {
		$this->resourceName 	= 'users';
		$this->filePath 		= dirname(__FILE__);
		
		parent::__construct($application);
		
		return $this;
	}
	
	public function index($options = null)
	{
		
		$this->data['view'] = array_merge((array) @$this->data['view'], array(
			'name' 				=> 'account',
			'method' 			=> __FUNCTION__,
			'template'			=> 'specific/pages/account/' . __FUNCTION__ . '.tpl',
			'current' 			=> array('menu' => 'account'),
		));
		
		$this->render();
	}
	
	public function login($options = null)
	{		
		// Shortcut for options
		$o 		= $options;
		
		// Try to get success redirect URL
		$sr 	= 'successRedirect';
		$redir 	= !empty($_POST[$sr]) ? $_POST[$sr] : ( !empty($_GET[$sr]) ? $_GET[$sr] : null );
		
		// Set template data
		$this->data['view'] = array_merge((array) @$this->data['view'], array(
			'name'           => 'account' . ucfirst(__FUNCTION__),
			'method'         => __FUNCTION__,
			'template'       => 'specific/pages/account/' . __FUNCTION__ . '.tpl',
			'resourceName'   => $this->resourceName,
			'title'          => _APP_TITLE . ' - ' . ucfirst(_('login')),
			'js'             => 'accountLogin'
		));
		
		// If the user is already logged, do not continue & redirect him to the hub
		if ( $this->isLogged() )
		{
			$this->data['success'] = true;
			
			$url = !empty($redir) ? $redir : _URL_HOME;
			$this->redirect($url);
			//if ( empty($redir) ){ $this->redirect(_URL_HOME); }
		}
		
		// If max login attemps feature has beend activated
		if ( defined('_APP_MAX_LOGIN_ATTEMPTS') && _APP_MAX_LOGIN_ATTEMPTS >= 1 )
		{
			$ban = CBans::getInstance()->retrieve(array('by' => 'ip', 'values' => $_SERVER['REMOTE_ADDR']));
			
			//date_default_timezone_set('UTC');
			
			// If the ban is found and if the ban time is not passed or if ban if forever
			// Do not continue
			if ( $ban && ( empty($ban['end_date']) || $ban['end_date'] < $_SERVER['REQUEST_TIME'] ) )
			{
				$this->data['errors'][] = 10030;
				return $this->statusCode(401);
			}
		}
		
		// Load proper controller and instanciate it
		//$this->requireControllers('CSessions');
		$CSessions = new CSessions();
		
		// If data have been posted
		if ( !empty($_POST) )
		{
			// Increase login attemps count
			$_SESSION['login_attemps'] = isset($_SESSION['login_attemps']) ? $_SESSION['login_attemps']+1 : 1;
			
			// If the user login attemps reached the max allowed one, ban it's ip for some time
			if ( defined('_APP_MAX_LOGIN_ATTEMPTS') && _APP_MAX_LOGIN_ATTEMPTS >= 1 && $_SESSION['login_attemps'] > _APP_MAX_LOGIN_ATTEMPTS )
			{
				$_POST = array(
					'ip' 		=> $_SERVER['REMOTE_ADDR'],
					'reason' 	=> 'max login allowed attemps',
					'end_date' 	=> $_SERVER['REQUEST_TIME'] + _APP_MAX_LOGIN_ATTEMPTS_BAN_TIME, 
				);
				CBans::getInstance()->create(array('isApi' => 1));
				
				$this->data['errors'][] = 10030;
				
				return $this->statusCode(401);
			}
			
			// Check for the required params
			$reqParams = array('userEmail' => 20010, 'userPassword' => 20012);
			foreach ($reqParams as $key => $val){ if ( empty($_POST[$key]) ) { $this->data['errors'][] = $val; $this->statusCode(400); goto render; } }
			
			// Get the user data
			//$this->requireControllers('CUsers');
			$email           = !empty($_POST['userEmail']) ? filter_var($_POST['userEmail'], FILTER_VALIDATE_EMAIL) : null;
			$pass            = !empty($_POST['userPassword']) ? filter_var($_POST['userPassword'], FILTER_SANITIZE_STRING) : null; 
			$user            = CUsers::getInstance()->retrieve(array('by' => 'email', 'values' => $email));
			$resolution      = $_POST['deviceResolution'] ? filter_var($_POST['deviceResolution'], FILTER_SANITIZE_STRING) : null;
			$orientation     = $_POST['deviceOrientation'] ? filter_var($_POST['deviceOrientation'], FILTER_SANITIZE_STRING) : null;
			
			// If user mail is not found
			if ( empty($user) ){ $this->data['errors'][] = 10002; $this->statusCode(401); goto render; }
			
			// If pass does not match the stored one
			if ( empty($pass) || sha1($pass) !== $user['password'] ){ $this->data['errors'][] = 10003; $this->statusCode(401); goto render; }

			// If user is not confirmed
			if ( defined('_APP_USE_ACCOUNTS_CONFIRMATION') && _APP_USE_ACCOUNTS_CONFIRMATION && !$user['activated'] ){ $this->data['error'][] = 10005; $this->statusCode(401); goto render; }
			
			// Build session data (after saving current post data)
			$savePOST = $_POST;
			$newPOST = array(
				'name' 				=> session_id(), 
				'user_id' 			=> $user['id'], 
				'expiration_time' 	=> ( !empty($_SERVER['REQUEST_TIME']) ? $_SERVER['REQUEST_TIME'] : time() ) + (int) _APP_SESSION_DURATION,
				'ip' 				=> $_SERVER['REMOTE_ADDR'],
				//TODO, add this to session datamodel & to sessions db table
				'resolution'        => $resolution,
			);
			foreach ($newPOST as $key => $val) { $_POST['session' . ucfirst($key)] = $val; }
						
			// Launch the creation
			$CSessions->create();
		}
		
		// Set output data		
		$this->data = array_merge($this->data, array(
			'success' 		=> $CSessions->success, 
			'errors'		=> $this->data['errors'] + (array) $CSessions->errors,
			'warnings' 		=> $this->data['warnings'] + (array) $CSessions->warnings,
		));
		
		// If the operation succeed, reset the $_POST
		if ( $this->data['success'] )
		{
			unset($_POST);
			
			// Store the session data
			$_SESSION = array_merge((array) $_SESSION, array(
			     'id'            => $newPOST['name'], 
			     'user_id'       => $newPOST['user_id'],
			     'resolution'    => $resolution,
			     'orientation'   => $orientation,
			     'login_attemps' => 0, // reset login attemps
            ));
			$this->logged = true;
			
			// Clean old expired session for this user id
			!_APP_KEEP_OLD_SESSIONS && $CSessions->delete(array(
				'conditions' 	=> array(
					'user_id' => $user['id'],
					array('expiration_time', '<', ("FROM_UNIXTIME('" . strtotime('-1 day') . "')")),
				),
			));
			
			// Return them as proper data session object
			$this->data[$this->resourceSingular] = array('id' => $newPOST['name'], 'user_id' => $newPOST['user_id']);
			
			if ( !empty($redir) ) { $this->redirect($redir); }
			
			$this->respondError(201);
		}
		
		render:
		return $this->render();
		;
	}
	
	
	public function logout()
	{
		if ( !empty($_SESSION['id']) ) { CSessions::getInstance()->delete(array('values' => $_SESSION['id'])); }
		
		// Destroy cookie session if used (default)
		if ( ini_get('session.use_cookies') )
		{
	    	$p 		= session_get_cookie_params();
			$time 	= !empty($_SERVER['REQUEST_TIME']) ? $_SERVER['REQUEST_TIME'] : time();
	    	//setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $params['httponly']);
			setcookie(session_name(), '', $time - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
		}
		
		// Delete session var
		$_SESSION = array();
		unset($_SESSION);
		
		// Finally, destroy properly the session
		session_destroy();
		
		return $this->redirect(_URL_LOGIN);
	}
	
	
	public function signup($options = null)
	{
		// Shortcut for options
		$o 		= $options;
		
		// Try to get success redirect URL
		$sr 	= 'successRedirect';
		$redir 	= !empty($_POST[$sr]) ? $_POST[$sr] : ( !empty($_GET[$sr]) ? $_GET[$sr] : null );
		
		// Set template data
		$this->data['view'] = array_merge((array) @$this->data['view'], array(
			'name' 			=> 'account' . ucfirst(__FUNCTION__),
			'method' 		=> __FUNCTION__,
			'template' 		=> 'specific/pages/account/' . __FUNCTION__ . '.tpl',
			'resourceName' 	=> $this->resourceName,
			'title' 			=> _APP_TITLE . ' - ' . ucfirst(_('sign up')),
		));
		
		// If the user is already logged, do not continue & redirect him to the hub
		if ( $this->isLogged() )
		{
			$this->data['success'] = true;
			
			if ( empty($redir) ){ $this->redirect(_URL_HOME); } 
		}
		
		// If data have been posted
		if ( !empty($_POST) )
		{
			// Check for the required params
			$req 	= array('email','password','password_confirmation','first_name','last_name','address','country','city','zipcode','TCS_accepted','TU_accepted');
			$miss 	= '';
			foreach ($req as $name) { if ( empty($_POST['user' . ucfirst($name)]) ) { $miss .= ( empty($miss) ? '' : ', ') . $name; } }
			if  ( !empty($miss) ) { $this->data['errors'][1003] = $miss; $this->statusCode(400); } 
			
			// Check if the user does not already exists
			$email 		= !empty($_POST['userEmail']) ? filter_var($_POST['userEmail'], FILTER_VALIDATE_EMAIL) : '';
			$userExists = CUsers::getInstance()->retrieve(array('by' => 'email', 'values' => $email, 'mode' => 'count'));
			if ( $userExists >= 1 && strpos($email, '@clicmobile.com') === false ) { $this->data['errors'][] = 10021; $this->statusCode(409); }
			
			// Check if pass and its confirmation are identical
			$pass 		= !empty($_POST['userPassword']) ? filter_var($_POST['userPassword'], FILTER_SANITIZE_STRING) : '';
			$confirm 	= !empty($_POST['userPassword_confirmation']) ? filter_var($_POST['userPassword_confirmation'], FILTER_SANITIZE_STRING) : '';
			if ( $pass !== $confirm ) { $this->data['errors'][] = 10004; $this->statusCode(400); }
						
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
			// Send the confirmation mail
			$this->requireLibs('Mailer');
			
			$this->Mailer = new Mailer();
			
			$user 		= CUsers::getInstance()->retrieve(array('by' => 'email', 'values' => $email, 'limit' => 1));	
			$from 		= 'Collectorserie <info@collectorserie.com>';
			$to			= $email;
			$subject 	= _('Your Account');
			$content 	= $this->Mailer->fetch(array(
				'template' 	=> 'common/mails/account/signup/success.tpl',
				'data' 		=> array('user' => $user)
			));			

			// Send the mail				
			$this->Mailer->send(array('from' => $from, 'to' => $to, 'subject' => $subject, 'content' => $content));
			
			if ( !$this->Mailer->success ) 	{ $this->errors = array_merge($this->data['errors'], $this->Mailer->errors); }
			
			unset($_POST);
			
			$this->respondError(201);
		}
				
		return $this->render();
	}

	public function confirmation()
	{
		// Redirect to home page if the activation_key is not found
		if ( empty($_GET['key']) ){ return $this->redirect(_URL_HOME); }
		
		// Filter passed key
		$key 	= filter_var($_GET['key'], FILTER_SANITIZE_STRING);
		
		// Get user data
		$uId 	= CUsers::getInstance()->retrieve(array('conditions' => array('activation_key' => $key)));
		
		// Redirect to home page if the user has not been found (wrong key or already activated account)
		if ( !$uId ){ return $this->redirect(_URL_HOME); }
		
		// Set account as 'activated' and remove activation key
		$_POST 	= array('activated' => true, 'activation_key' => '');
		CUsers::getInstance()->update(array('isApi' => 1, 'conditions' => array('id' => $uId)));
		
		// Set template data
		$this->data['view'] = array_merge((array) @$this->data['view'], array(
			'name' 			=> 'account' . ucfirst(__FUNCTION__),
			'method' 		=> __FUNCTION__,
			'template' 		=> 'specific/pages/account/' . __FUNCTION__ . '.tpl',
			'resourceName' 	=> $this->resourceName,
			'title' 			=> _APP_TITLE . ' - ' . ucfirst(_('Account Confirmation')),
		));
		
		$this->render();
	}
	
};

?>