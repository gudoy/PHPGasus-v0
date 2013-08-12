<?php

class CUsers extends Controller
{
	private static $_instance;
	
	public function __construct()
	{
        $this->setResource(array('class' => __CLASS__));
		
		return parent::__construct();
	}
	
	public static function getInstance()
	{
		if ( !(self::$_instance instanceof self) ) { self::$_instance = new self(); } 
		
		return self::$_instance;
	}
	
	public function create($options = array())
	{
		$this->userId = parent::create($options);
		
		// If the user creation succeed
		if ( $this->success && _APP_USE_ACCOUNTS_CONFIRMATION ){ $this->sendConfirmationMail(); }
		
		return $this->userId;
	}
	
	public function sendConfirmationMail()
	{
		// If the user id is not found do not continue;
		if ( !$this->userId ){ return; }
		
		// Get user data
		$user 				= $this->retrieve(array('conditions' => array('id' => $this->userId)));
		
		// Generate an unique key and insert it into the db
		$key 				= Tools::generateUniqueID(array('length' => 32, 'resource' => 'users', 'field' => 'activation_key'));
		$user 				= array_merge($user, array('activation_key' => $key));
		$_POST 				= array('activation_key' => $key);
		$updated 			= $this->update(array('isApi' => 1, 'conditions' => array('id' => $user['id'])));

		// Get user language and set it as the current one (required in the template to get the mail in the proper language)
		$curSessLang 		= $_SESSION['lang'];
		$_SESSION['lang'] 	= !empty($user['prefered_lang']) ? $user['prefered_lang'] : _APP_DEFAULT_LANGUAGE;

		// Send the mail
		$Mailer 			= new Mailer(new Application());
		$Mailer->send(array(
			'from' 		=> _APP_OWNER_MAIL, 
			'to' 		=> $user['email'], 
			'subject' 	=> '[' . _APP_TITLE . '] ' . _('Please activate your account'),
			'content' 	=> $Mailer->fetch(array(
				'data' 		=> array('user' => $user), 
				'template' 	=> _PATH_TEMPLATES . 'specific/mails/account/activate.tpl'
			))
		));
		
		// We can now restore the session lang to its previous value
		$_SESSION['lang'] = $curSessLang;
	}
	
	public function sendResetPasswordMail($userId)
	{
		// Generate an unique key and insert it into the db
		$time 				= !empty($_SERVER['REQUEST_TIME']) ? $_SERVER['REQUEST_TIME'] : time(); 
		$key 				= sha1(md5($time));
		$_POST 				= array('password_reset_key' => $key);
		$updated 			= $this->update(array('isApi' => 1, 'conditions' => array('id' => $userId)));
		
		// Get user data
		$user 				= $this->retrieve(array('conditions' => array('id' => $userId)));
		
		// Get user language and set it as the current one (required in the template to get the mail in the proper language)
		$curSessLang 		= $_SESSION['lang'];
		$_SESSION['lang'] 	= !empty($user['prefered_lang']) ? $user['prefered_lang'] : _APP_DEFAULT_LANGUAGE;

		// Send the mail
		$Mailer 			= new Mailer(new Application());
		
		$Mailer->send(array(
			'from' 		=> _APP_OWNER_MAIL, 
			'to' 		=> $user['email'], 
			'subject' 	=> '[' . _APP_TITLE . '] ' . _('Reset your password'),
			'content' 	=> $Mailer->fetch(array(
				'data' 		=> array('user' => $user), 
				'template' 	=> _PATH_TEMPLATES . 'specific/mails/account/password/lost.tpl'
			))
		));
		
		// We can now restore the session lang to its previous value
		$_SESSION['lang'] = $curSessLang;
	}

	
	public function passwordExpirationsCheck()
	{
        // Create a task an get its id
        $_POST 			= array(
            'slug'           		=> __FUNCTION__ .  strftime('%d-%m-%y', $_SERVER['REQUEST_TIME']), 
            'type'                  => __FUNCTION__, 
            'creation_date'         => $_SERVER['REQUEST_TIME'],
        );
		$tId 			= CTasks::getInstance()->create(array('isApi' => 1, 'returning' => 'id'));
		
		// Count of the sent mails
		$sentMails = 0;
		
		// Get data of users whose password will expire in  
		//$nbOfdays = 5;
		//$query = "SELECT * FROM `users` as u WHERE DATEDIFF(DATE(u.password_expiration), CURDATE()) = " . $nbOfdays;
		
		// Get all users
		$users 		= $this->index(array('limit' => '-1'));
		
		// 
		$today 		= new DateTime('today');
		$curDate 	= $today->format('Y-m-d');
		
		// Groups that are exempted from password expiration
		$exmptGps = Tools::toArray(_APP_PASSWORDS_EXPIRATION_EXEMPTED_GROUPS);
		
		// Loop over them
		foreach ( (array) $users as $user )
		{
			// Do not continue if the current user password has no expiration
			if ( empty($user['password_expiration']) ){ continue; }
		
			// Get intersection between user group and password expiration exempted groups
			$uGps 		= !empty($user['group_slugs']) ? Tools::toArray($user['group_slugs']) : array();
			$intersect 	= array_intersect($uGps, $exmptGps);
			
			// Do not continue if the user belongs to a an exempted group
			if ( !empty($intersect) ) { continue; }
			
			// Get password expiration, create a datetime 
			// and get the number of days between today and this date
			$dt 		= DateTime::createFromFormat('U', $user['password_expiration']);
			$intval 	= is_object($dt) && is_object($today) ? $today->diff($dt, false) : null;
			$days 		= $intval ? ($intval->invert ? -1 : 1) *  $intval->days : null;
						
			if 		( $days === 5 )								{ $case = '5daysBefore'; }	// Password will expire in 5 days 
			elseif 	( $days === -1 )							{ $case = 'after'; } 		// Password is expired since 24 hours
			elseif 	( $days < -1 && (abs($days) % 2 === 0) ) 	{ $case = 'after'; } 		// Password is expired since 24 hours
			else 												{ $case = false; } 			// nothing to do
			
			// Send the mail if we have to and increase counter if it has been sent
			if ( $case && $this->sendPassExpAlertMail($user, array('case' => $case)) ) { $sentMails++; };
		}
		
		// Update the task
        $_POST = array( 'items_count' => $sentMails);
        CTasks::getInstance()->update(array('isApi' => 1, 'by' => 'id', 'values' => $tId)); 
	}

	private function sendPassExpAlertMail($user, $params = array())
	{		
		$p = array_merge(array(
			'case' => null,
		), $params);
		
		if ( $p['case'] === '5daysBefore' )
		{
			$subject 		= sprintf(_("Your password will expire in %d day(s)"), 5);
			$tpl 			= 'expires_in_5days.tpl';
		}
		elseif ( $p['case'] === 'after' )
		{
			$subject 		= _("Your password has expired.");
			$tpl 			= 'expired.tpl';
		}
		
		// Get user language and set it as the current one (required in the template to get the mail in the proper language)
		$curSessLang 		= $_SESSION['lang'];
		$_SESSION['lang'] 	= !empty($user['prefered_lang']) ? $user['prefered_lang'] : _APP_DEFAULT_LANGUAGE;
		$lg 				= substr($_SESSION['lang'], 0, 2); 
		
		// And then send it
		$Mailer 			= new Mailer(new Application());
		$Mailer->send(array(
			'from' 		=> _APP_OWNER_MAIL, 
			'to' 		=> $user['email'], 
			'replyTo' 	=> constant('_MAIL_SUPPORT_' . strtoupper($lg)),
			'subject' 	=> '[' . _APP_TITLE . '] '. $subject, 
			'content' 	=> $Mailer->fetch(array(
				'data' 		=> array('user' => $user), 
				'template' 	=> _PATH_TEMPLATES . 'specific/mails/account/password/' . $tpl,
			)), 
		));
		
		// We can now restore the session lang to its previous value
		$_SESSION['lang'] = $curSessLang;
		
		return $Mailer->success;
	}

	public function handleCreateFromServices()
	{
		$ret = null;
		
		if ( !empty($_POST['google_oauth_token']) )
		{
			$ret = $this->createFromGoogle() ? 'google' : false;
		}

		else if ( !empty($_POST['twitter_oauth_token']) && !empty($_POST['twitter_oauth_token_secret']) )
		{
			$ret = $this->createFromTwitter() ? 'twitter' : false;
		}
		
		// Handle login with facebook
		else if ( !empty($_POST['facebook_oauth_token']) )
		{
			$ret = $this->createFromFacebook() ? 'facebook' : false;
		}
		
		return $ret;
	}
	
	
	private function createFromFacebook()
	{
		// Get token
		$token = Tools::sanitizeString($_POST['facebook_oauth_token']);
		
		// Do not continue any longer if the token is invalid
		if ( !$token ){ $this->errors[5001] = array('_ERR_INVALID_TOKEN'); return false; }
		
		// Request an extended token (valid 2 month instead of just 2 hours)
		$exToken = $this->getFacebookExtendedToken($token);
		
		// Get user profile
		$profile = $this->getFacebookUserProfile(null, $exToken, array('fields' => 'id,name,first_name,last_name,email,birthday,picture.width(300)'));

		// If no profile has been retrieved
		if ( !$profile || !empty($profile['error']) || !isset($profile['id']) )
		{
			if 		( isset($profile['error']['message']) )	{ $this->errors[5002] = array('_ERR_FACEBOOK', $profile['error']['message']); }
			else 											{ $this->errors[5001] = array('_ERR_FACEBOOK'); }
			 
			return false;
		}
	
		// Check if the passed email already exists in the DB
		$emailExists = $this->retrieve(array('getFields' => 'id', 'conditions' => array('email' => $_POST['email'])));
	
		// Do not continue any longer if the email already exists 
		if ( $emailExists ){ $this->errors[] = 4030; return false; }
	
		// Disassociate any existing users already paired with this account  
		$curPOST 	= $_POST;
		$_POST 		= array('facebook_id' => '', 'facebook_oauth_token' => ''); 
		$this->update(array('conditions' => array('facebook_id' => $profile['id'])));
		$_POST 		= $curPOST;
		
		// Complete POST data with retrieved info
		$_POST['facebook_id'] 			= $profile['id'];		
		$_POST['facebook_oauth_token'] 	= $exToken;
		$_POST['birthdate'] 			= DateTime::createFromFormat('m/d/Y', $profile['birthday'])->format('Y-m-d');
		$_POST['firstname'] 			= isset($profile['first_name']) ? $profile['first_name'] : '';
		$_POST['lastname'] 				= isset($profile['last_name']) ? $profile['last_name'] : '';
		$_POST['avatar_url'] 			= isset($profile['picture']['data']['url']) ? $profile['picture']['data']['url'] : '';
		$_POST['password'] 				= sha1(time());
		
		return true;
	}

	private function createFromTwitter()
	{		
		// Get token
		$token 			= Tools::sanitizeString($_POST['twitter_oauth_token']);
		$tokenSecret 	= Tools::sanitizeString($_POST['twitter_oauth_token_secret']);
		
		// Do not continue any longer if the token is invalid
		if ( !$token )		{ $this->errors[5001] = array('_ERR_INVALID_TOKEN'); return false; }
		if ( !$tokenSecret ){ $this->errors[5001] = array('_ERR_INVALID_TOKEN_SECRET'); return false; }
		
		// Get user profile
		$profile = $this->getTwitterUserProfile(null, $token, $tokenSecret);

		// If no profile has been retrieved
		if ( !$profile || !empty($profile['errors']) || !isset($profile['id']) )
		{
			if 		( isset($profile['errors'][0]['message']) )	{ $this->errors[5002] = array('_ERR_TWITTER', $profile['errors'][0]['message']); }
			else 												{ $this->errors[5001] = array('_ERR_TWITTER'); }
			 
			return false;
		}

		// Check if the passed email already exists in the DB
		$emailExists = $this->retrieve(array('getFields' => 'id', 'conditions' => array('email' => $_POST['email'])));
	
		// Do not continue any longer if the email already exists 
		if ( $emailExists ){ $this->errors[] = 4030; return false; }
	
		// Disassociate any existing users already paired with this account
		$curPOST 	= $_POST;
		$_POST 		= array('twitter_id' => '', 'twitter_oauth_token' => '', 'twitter_oauth_token_secret' => ''); 
		$this->update(array('conditions' => array('twitter_id' => $profile['id'])));
		$_POST 		= $curPOST;
		
		// Complete POST data with retrieved info
		if ( !empty($profile['name']) )
		{
			$parts = explode(' ', $profile['name'], 2);
			$_POST['firstname'] = isset($parts[0]) ? $parts[0] : '';
			$_POST['lastname'] 	= isset($parts[1]) ? $parts[1] : '';
		}
		$_POST['twitter_id'] 					= $profile['id'];
		$_POST['twitter_oauth_token'] 			= $token;
		$_POST['twitter_oauth_token_secret'] 	= $tokenSecret;
		$_POST['avatar_url'] 					= isset($profile['profile_image_url_https']) ? $profile['profile_image_url_https'] : '';
		$_POST['password'] 						= sha1(time());
		
		return true;
	}

	private function createFromGoogle()
	{
		// Get token
		$refreshToken = Tools::sanitizeString($_POST['google_oauth_token']);
		
		// Do not continue any longer if the token is invalid
		if ( !$refreshToken ){ $this->errors[5001] = array('_ERR_INVALID_REFRESH_TOKEN'); return false; }
		
		// Request an access token from the refresh token
		$token = null;
		try 					{ $token = $this->getGoogleRefreshedToken($refreshToken); }
		catch (Exception $ex) 	{ $this->errors[5002] = array('_ERR_GOOGLE', $ex->getMessage()); return false; }
		
		// Get user profile
		$profile = null;
		try 					{ $profile = $this->getGoogleUserProfile(null, $token); }
		catch (Exception $ex) 	{ $this->errors[5002] = array('_ERR_GOOGLE', $ex->getMessage()); return false; }
		
		// Do not continue if the profile has not been found or if the email has not been returned
		if ( !$profile || !isset($profile['id']) ){ $this->errors[5001] = array('_ERR_GOOGLE'); return false;}

		// Check if the passed email already exists in the DB
		$emailExists = $this->retrieve(array('getFields' => 'id', 'conditions' => array('email' => $_POST['email'])));
	
		// Do not continue any longer if the email already exists 
		if ( $emailExists ){ $this->errors[] = 4030; return false; }

		// Disassociate any existing users already paired with this account
		$curPOST 	= $_POST;
		$_POST 		= array('google_id' => '', 'google_oauth_token' => ''); 
		$this->update(array('conditions' => array('google_id' => $profile['id'])));
		$_POST 		= $curPOST;
		
		// Complete POST data with retrieved info
		$_POST['google_id'] 			= $profile['id'];
		$_POST['google_oauth_token'] 	= $refreshToken;
		$_POST['birthdate'] 			= isset($profile['birthday']) ? DateTime::createFromFormat('Y-m-d', $profile['birthday'])->format('Y-m-d')  : '0000-00-00';
		$_POST['firstname'] 			= isset($profile['given_name']) ? $profile['given_name'] : '';
		$_POST['lastname'] 				= isset($profile['family_name']) ? $profile['family_name'] : '';
		$_POST['avatar_url'] 			= isset($profile['picture']) ? $profile['picture'] : '';
		$_POST['password'] 				= sha1(time());

		return true;
	}
	

	// https://developers.facebook.com/docs/howtos/login/extending-tokens/
	public function getFacebookExtendedToken($token)
	{	
		$params = array(
			'grant_type' 		=> 'fb_exchange_token',
			'client_id' 		=> _FACEBOOK_APP_ID,
			'client_secret' 	=> _FACEBOOK_APP_SECRET,
			'fb_exchange_token' => $token,
		);
		$url 	= _FACEBOOK_API_URL . 'oauth/access_token' . '?' . http_build_query($params);
		$res 	= $this->request($url, array('method' => 'get', 'output' => 'txt'));
		$data 	= array();
		parse_str($res['body'], $data);
		
		return isset($data['access_token']) ? $data['access_token'] : null;
	}

	public function getFacebookUserProfile($facebookUserId, $token, $options = array())
	{
		$who 		= isset($facebookUserId) ? $facebookUserId : 'me';
		$params  	= array(
			'access_token' 	=> $token,
			'fields' 		=> isset($options['fields']) ? $options['fields'] : '',
		);
		$url 	= _FACEBOOK_API_URL . $who . '?' . http_build_query($params);
		
		// Send the request
		$res 	= $this->request($url, array(
			'method' 		=> 'get',
			'output' 		=> 'json',
		));
		$profile = $res['body'];
		
		return $profile;
	}

	public function getFacebookFriends($facebookUserId, $token, $options = array())
	{
		$who 		= isset($facebookUserId) ? $facebookUserId : 'me';
		$params  	= array(
			'access_token' 	=> $token,
			'fields' 		=> isset($options['fields']) ? $options['fields'] : '',
		);		
		$url 	= _FACEBOOK_API_URL . $who . '/friends' . '?' . http_build_query($params);
		
		// Send the request
		$res 	= $this->request($url, array(
			'method' 		=> 'get',
			'output' 		=> 'json',
		));
		$friends = isset($res['body']['data']) ? $res['body']['data'] : false;
		
		return $friends;
	}
	
	public function getTwitterUserProfile($twitterUserId, $token, $tokenSecret, $options = array())
	{
		// Verify Credentials
		$method 		= 'GET';
		$queryParams 	= !empty($twitterUserId) ? array('id' => $twitterUserId) : array();
		$queryString 	= http_build_query($queryParams);
		$url 			= !empty($twitterUserId) 
							? _TWITTER_API_URL . 'users/show.json'
							: _TWITTER_API_URL . 'account/verify_credentials.json';
		$calledUrl		= $url . (!empty($queryString) ? '?' . $queryString : '');
		$bodyParams 	= array();
		$oAuthParams 	= array('oauth_token' => $token);
		$sigingOptions 	= array('token_secret' => $tokenSecret);
		$authHeader 	= $this->buildOauthAuthHeader($method, $url, $queryParams, $bodyParams, $oAuthParams, $sigingOptions);			
		$res 			= $this->request($calledUrl, array(
			'method' 	=> $method,
			'headers' 	=> array(
				'Authorization' => $authHeader,
			),
			'output' 	=> 'json',
		));
		$profile = $res['body'];
		
		return $profile;
	}
	
	// https://developers.google.com/accounts/docs/OAuth2WebServer?hl=fr#refresh
	public function getGoogleRefreshedToken($refreshToken)
	{
		class_exists('Google_Client') || require(_PATH_LIBS . 'services/google/google_api_client/Google_Client.php');
		
		// Instanciate Google Client if not already done
		if ( !isset($this->Google_Client) ){ $this->Google_Client = new Google_Client(); }

		$this->Google_Client->setApplicationName('Sipstir');
		$this->Google_Client->setClientId(_GOOGLE_CLIENT_ID);
		$this->Google_Client->setClientSecret(_GOOGLE_CLIENT_SECRET);
		
		// Get access token from the refresh token
		$this->Google_Client->refreshToken($refreshToken);
		$accessToken = $this->Google_Client->getAccessToken();
		
		return !empty($accessToken) ? $accessToken : null;
	}
	
	public function getGoogleUserProfile($googleUserId, $token, $options = array())
	{
		$who 		= isset($googleUserId) ? $googleUserId : 'me';
		$params  	= array(
		);
		$scopes 	= array(
			'https://www.googleapis.com/auth/userinfo.email', 
			'https://www.googleapis.com/auth/userinfo.profile',
			'https://www.googleapis.com/auth/plus.me',
			//'https://www.googleapis.com/auth/plus.login﻿',
		);
		
		class_exists('Google_Client') || require(_PATH_LIBS . 'services/google/google_api_client/Google_Client.php');
		
		// Instanciate Google Client if not already done
		if ( !isset($this->Google_Client) ){ $this->Google_Client = new Google_Client(); }
		
		$this->Google_Client->setApplicationName(_GOOGLE_APPLICATION_NAME);
		$this->Google_Client->setClientId(_GOOGLE_CLIENT_ID);
		$this->Google_Client->setClientSecret(_GOOGLE_CLIENT_SECRET);
		$this->Google_Client->setAccessToken($token);
		
		// Special case for currend logged user (from which we want to get the email)
		if ( $who = 'me' )
		{
			class_exists('Google_Oauth2Service') || require(_PATH_LIBS . 'services/google/google_api_client/contrib/Google_Oauth2Service.php');
			
			$this->Google_Client->setScopes($scopes);
			
			$oauth2 	= new Google_Oauth2Service($this->Google_Client);
			$profile 	= $oauth2->userinfo->get();	
		}
		// Otherwise, get the user google plus profile
		else
		{
			class_exists('Google_PlusService') || require(_PATH_LIBS . 'services/google/google_api_client/contrib/Google_PlusService.php');
			
			$plus 		= new Google_PlusService($client);
			$profile 	= $plus->people->get('me');	
			$profile 	= $plus->people->get($who);
		}
		
		return $profile;
	}

	public function getGoogleUserProfile2($googleUserId, $token, $options = array())
	{
		$who 		= isset($googleUserId) ? $googleUserId : 'me';
		$params  	= array(
		);
		
		class_exists('Google_Client') || require(_PATH_LIBS . 'services/google/google_api_client/Google_Client.php');
		
		// Instanciate Google Client if not already done
		if ( !isset($this->Google_Client) ){ $this->Google_Client = new Google_Client(); }
		
		$key 	= file_get_contents(_PATH_LIBS . 'services/google/' . 'd451e61a55256ca20ad94bed57544f6533749122-privatekey.p12');
		$scopes = array(
			'https://www.googleapis.com/auth/userinfo.email', 
			'https://www.googleapis.com/auth/userinfo.profile',
			'https://www.googleapis.com/auth/plus.me',
			//'https://www.googleapis.com/auth/plus.login﻿',
		);
		
		$this->Google_Client->setApplicationName('Sipstor');
		$this->Google_Client->setClientId(_GOOGLE_CLIENT_ID);
		//$this->Google_Client->setAssertionCredentials(new Google_AssertionCredentials(_GOOGLE_EMAIL_ADDRESS, $scopes, $key));
		$this->Google_Client->setClientSecret(_GOOGLE_CLIENT_SECRET);
		$this->Google_Client->setAccessToken($token);
		
		// Special case for currend logged user (from which we want to get the email)
		if ( $who = 'me' )
		{
			class_exists('Google_Oauth2Service') || require(_PATH_LIBS . 'services/google/google_api_client/contrib/Google_Oauth2Service.php');
			
			$this->Google_Client->setScopes($scopes);
			
			$oauth2 	= new Google_Oauth2Service($this->Google_Client);
			$profile 	= $oauth2->userinfo->get();	
		}
		// Otherwise, get the user google plus profile
		else
		{
			class_exists('Google_PlusService') || require(_PATH_LIBS . 'services/google/google_api_client/contrib/Google_PlusService.php');
			
			$plus 		= new Google_PlusService($client);
			$profile 	= $plus->people->get('me');	
			$profile 	= $plus->people->get($who);
		}
		
		return $profile;
	}
}
?>