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
			//'resourceName'   => $this->resourceName,
			'title'          => _APP_TITLE . ' - ' . ucfirst(_('login')),
			'js'             => 'accountLogin'
		));
		
		// If the user is already logged, do not continue & redirect him to the hub
		if ( $this->isLogged() )
		{
			$this->data['success'] = true;
			
			$url = !empty($redir) ? _URL . $redir : _URL_HOME;
			$this->redirect($url);
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
		
		// Instanciate proper controller
		$CSessions = new CSessions();
		
		// If data have been posted
		if ( !empty($_POST) )
		{
			// If the user login attemps reached the max allowed one
			// and if its IP it not in the whitelist
			if ( defined('_APP_MAX_LOGIN_ATTEMPTS') 
				&& _APP_MAX_LOGIN_ATTEMPTS >= 1 
				&& isset($_SESSION['login_attemps']) && $_SESSION['login_attemps'] >= _APP_MAX_LOGIN_ATTEMPTS
				&& ( !defined('_APP_IP_WHITELIST') || !in_array($_SERVER['REMOTE_ADDR'], explode(',',_APP_IP_WHITELIST)) ) )
			{
				// Ban it's ip for some time
				$_POST = array(
					'ip' 		=> $_SERVER['REMOTE_ADDR'],
					'reason' 	=> 'max login allowed attemps',
					'end_date' 	=> $_SERVER['REQUEST_TIME'] + _APP_MAX_LOGIN_ATTEMPTS_BAN_TIME, 
				);
				CBans::getInstance()->create(array('isApi' => 1));
				
				// Add the proper error
				$this->data['errors'][] = 10030;
				
				// Reset login attemps count (to avoid conflicts when it will be unbanned)
				unset($_SESSION['login_attemps']);
				
				// And finaly return with the proper status code
				return $this->statusCode(401);
			}
			
			// Increase login attemps count
			$_SESSION['login_attemps'] = isset($_SESSION['login_attemps']) ? $_SESSION['login_attemps']+1 : 1;
			
			// Add a warning with remaining login attempts
			if ( $_SESSION['login_attemps'] >= 1 ){ $this->data['warnings'][10031] = _APP_MAX_LOGIN_ATTEMPTS - $_SESSION['login_attemps']; }
			
			// Check for the required params
			$req = array('userEmail' => 20010, 'userPassword' => 20012);
			foreach ($req as $key => $val){ if ( empty($_POST[$key]) ) { $this->data['errors'][] = $val; $this->statusCode(400); return $this->render(); } }
			
			// Filter POST data
			$email 			= !empty($_POST['userEmail']) 			? filter_var($_POST['userEmail'], FILTER_VALIDATE_EMAIL) : null;
			$pass 			= !empty($_POST['userPassword']) 		? filter_var($_POST['userPassword'], FILTER_SANITIZE_STRING) : null; 
			$resolution 	= !empty($_POST['deviceResolution']) 	? filter_var($_POST['deviceResolution'], FILTER_SANITIZE_STRING) : null;
			$orientation 	= !empty($_POST['deviceOrientation']) 	? filter_var($_POST['deviceOrientation'], FILTER_SANITIZE_STRING) : null;
			
			// Get the user data
			$CUsers 		= new CUsers();
			$user 			= $CUsers->retrieve(array('by' => 'email', 'values' => $email));
			
			// If user mail is not found
			if ( !$user ){ $this->data['errors'][] = 10002; $this->statusCode(401); return $this->render(); }
			
			// If pass does not match the stored one
			if ( empty($pass) || sha1($pass) !== $user['password'] ){ $this->data['errors'][] = 10003; $this->statusCode(401); return $this->render(); }

			// If user is not confirmed
			if ( defined('_APP_USE_ACCOUNTS_CONFIRMATION') 
				&& _APP_USE_ACCOUNTS_CONFIRMATION && !$user['activated'] )
			{ $this->data['errors'][] = 10005; $this->statusCode(401); return $this->render(); }

			// If password is expired 
			// and user does not belong to a groups who is exempted of password expiration
			if ( defined('_APP_PASSWORDS_EXPIRATION_TIME') 
				&& _APP_PASSWORDS_EXPIRATION_TIME > 0 
				&& ( !$user['password_expiration'] || $user['password_expiration'] < (!empty($_SERVER['REQUEST_TIME']) ? $_SERVER['REQUEST_TIME'] : time()) )
				&& ( !($uGps = array_intersect((array) Tools::toArray(_APP_PASSWORDS_EXPIRATION_EXEMPTED_GROUPS), (array) Tools::toArray($user['group_admin_titles']))) && empty($uGps) )
			)
			{ $this->data['errors'][] = 10033; $this->redirect(_URL_ACCOUNT_PASSWORD_EXPIRED . '?errors=10033'); }
			
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
			// Clean the POST
			unset($_POST);
			
			// Remove remaning attempts warning by deleting all warnings
			unset($this->data['warnings']);
			
			// Store the session data
			$_SESSION = array_merge((array) $_SESSION, array(
			     'id'            => $newPOST['name'], 
			     'user_id'       => $newPOST['user_id'],
			     'resolution'    => $resolution,
			     'orientation'   => $orientation,
			     'login_attemps' => 0, // reset login attemps
            ));
			$this->logged 				= true;
			$this->application->logged 	= true;
			
			// Clean old expired session for this user id
			!_APP_KEEP_OLD_SESSIONS && $CSessions->delete(array(
				'conditions' 	=> array(
					'user_id' => $user['id'],
					array('expiration_time', '<', ("FROM_UNIXTIME('" . strtotime('-1 day') . "')")),
				),
			));
			
			// Return them as proper data session object
			$this->data[$this->resourceSingular] = array('id' => $newPOST['name'], 'user_id' => $newPOST['user_id']);
			
			//if ( !empty($redir) ) { $this->redirect($redir); }
			if ( !empty($redir) ) { $this->redirect(_URL . $redir); }
			
			$this->respondError(201);
		}
		
		return $this->render();
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
			//'resourceName' 	=> $this->resourceName,
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
			$req 	= array('email','password','password_confirmation');
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
			unset($_POST);
			
			$this->respondError(201);
		}
				
		return $this->render();
	}

	public function confirmation()
	{
		// Set template data
		$this->data['view'] = array_merge((array) @$this->data['view'], array(
			'name' 			=> 'account' . ucfirst(__FUNCTION__),
			'method' 		=> __FUNCTION__,
			'template' 		=> 'specific/pages/account/' . __FUNCTION__ . '.tpl',
			//'resourceName' 	=> $this->resourceName,
			'title' 			=> _APP_TITLE . ' - ' . ucfirst(_('Account Confirmation')),
		));
		
		// Send the dataModel to the html templates to be able to add pattern & hints to form elements
		if ( in_array($this->options['output'], array('html','xhtml')) )
		{
			isset($dataModel) || include(_PATH_CONFIG . 'dataModel.php');
			$this->data['dataModel']['users'] = $dataModel['users'];
		}
		
		// Redirect to home page if the activation_key is not found
		if ( empty($_GET['key']) ){ return $this->redirect(_URL_HOME); }
		
		// Filter passed key
		$key 	= filter_var($_GET['key'], FILTER_SANITIZE_STRING);
		
		// Get user data
		$CUsers = new CUsers();
		$user 	= $CUsers->retrieve(array('conditions' => array('activation_key' => $key)));
		
		// Redirect to home page if the user has not been found (wrong key or already activated account)
//if ( !$user ){ return $this->redirect(_URL_HOME); }
		
		// If the 'define pass on 1st login' feature is activated
		if ( defined('_APP_PASS_FORCE_DEFINE_ON_1ST_LOGIN') && _APP_PASS_FORCE_DEFINE_ON_1ST_LOGIN )
		{
			// If ther's no POST data, just render directly
			if ( empty($_POST) ){ $this->render(); }
			
			// Otherwise
			
			// Required fields
			$req = array('userNewPassword' => 'password', 'userNewPasswordConfirm' => 'password confirmation');
			
			// Loop over the required fields
			foreach ($req as $k => $v)
			{
				// filter it
				$_POST[$k] = Tools::sanitize($_POST[$k], array('type' => 'password'));
				
				// Check if not empty after sanitization
				if ( empty($_POST[$k]) ){ $this->data['errors'][1003] = $v; $this->render(); }
			}
			
			// Check if password and password confirmation are the same
			if ( $_POST['userNewPassword'] !== $_POST['userNewPasswordConfirm'] ) { $this->data['errors'][] = '10004'; $this->render(); }
			
			// We have to make the 'password_expiration' fields temporarily editable
			$uDM 		= &$CUsers->application->dataModel['users'];
			$curPassExpEditable = isset($uDM['password_expiration']['editable']) ? $uDM['password_expiration']['editable'] : null ;
			$uDM['password_expiration']['editable'] = true;
			
			// Get request or current time
			$curTime 	= !empty($_SERVER['REQUEST_TIME']) ? $_SERVER['REQUEST_TIME'] : time();
			
			// User not found
			if ( !$this->isLogged() && !$user ){ $this->data['errors'][] = 10002; $this->render(); }
			
			// If the feature is activated, check that the minimun required time between 2 password change has passed
			//if ( defined('_APP_PASS_MIN_TIME_BETWEEN_CHANGES') && _APP_PASS_MIN_TIME_BETWEEN_CHANGES 
				//	&& !empty($user['password_lastedit_date'])
				//	&& ($curTime - $user['password_lastedit_date']) < _APP_PASS_MIN_TIME_BETWEEN_CHANGES  
			//){ $this->data['errors'][] = 10035; $this->render(); }

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
					'password_reset_key' 	=> '',
					'password_expiration' 	=> _APP_PASSWORDS_EXPIRATION_TIME > 0 ? $curTime + _APP_PASSWORDS_EXPIRATION_TIME : null,
					'password_lastedit_date'=> $curTime,
					
					'activated' 			=> true,
					'activation_key' 		=> '',
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
		}
		// Otherwise, directly activate the user account
		else
		{
			// Set account as 'activated' and remove activation key
			$_POST 	= array('activated' => true, 'activation_key' => '');
			$CUsers->update(array('isApi' => 1, 'conditions' => array('id' => $user['id'])));			
		}
		
		$this->render();
	}
	
};

?>