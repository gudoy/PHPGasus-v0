<?php

class CSessions extends Controller
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
	
	public function handleLoginWithServices()
	{		
		$ret = null;
		
		if ( !empty($_POST['google_oauth_token']) )
		{
			$ret = $this->loginWithGoogle() ? 'google' : false;
		}

		else if ( !empty($_POST['twitter_oauth_token']) && !empty($_POST['twitter_oauth_token_secret']) )
		{
			$ret = $this->loginWithTwitter() ? 'twitter' : false;
		}
		
		// Handle login with facebook
		else if ( !empty($_POST['facebook_oauth_token']) )
		{
			$ret = $this->loginWithFacebook() ? 'facebook' : false;
		}
		
		return $ret;
	}
	
	private function loginWithFacebook()
	{
		// Get token
		$token = Tools::sanitizeString($_POST['facebook_oauth_token']);
		
		// Do not continue any longer if the token is invalid
		if ( !$token ){ $this->errors[5001] = array('_ERR_INVALID_TOKEN'); return false; }
		
		// Instanciate users controller (for later use)
		$this->CUsers = new CUsers();
		
		// Request an extended token (valid 2 month instead of just 2 hours)
		$exToken = $this->CUsers->getFacebookExtendedToken($token);
		
		// Get user profile
		$profile = $this->CUsers->getFacebookUserProfile(null, $exToken, array('fields' => 'id,name,first_name,last_name,email,birthday,picture.width(300)'));

		// If no profile has been retrieved
		if ( !$profile || !empty($profile['error']) || !isset($profile['id']) )
		{
			if 		( isset($profile['error']['message']) )	{ $this->errors[5002] = array('_ERR_FACEBOOK', $profile['error']['message']); }
			else 											{ $this->errors[5001] = array('_ERR_FACEBOOK'); }
			 
			return false;
		}
		
		// First, try to find the user from the profile id
		$this->user = $this->CUsers->retrieve(array('conditions' => array('facebook_id' => $profile['id'])));
		
		if ( !$this->user ){ $this->errors[5001] = array('_ERR_USER_NOT_FOUND'); return false; }

		// Complete user data with retrieved info
		$bDate 							= $this->user['birthdate'];
		$curPOST 						= $_POST;
		$_POST['facebook_oauth_token'] 	= $exToken;
		
		if ( !$bDate || $bDate === '0000-00-00' )	{ $_POST['birthdate'] 	= DateTime::createFromFormat('m/d/Y', $profile['birthday'])->format('Y-m-d'); }
		if ( empty($this->user['facebook_id']) )	{ $_POST['facebook_id'] = $profile['id']; }
		if ( empty($this->user['firstname']) )		{ $_POST['firstname'] 	= isset($profile['first_name']) ? $profile['first_name'] : ''; }
		if ( empty($this->user['lastname']) )		{ $_POST['lastname'] 	= isset($profile['last_name']) ? $profile['last_name'] : ''; }
		if ( empty($this->user['avatar_url']) ) 	{ $_POST['avatar_url'] 	= isset($profile['picture']['data']['url']) ? $profile['picture']['data']['url'] : ''; }
		
		$this->CUsers->update(array('conditions' => array('id' => $this->user['id'])));
		
		// Restaure previous POST content
		$_POST 			= $curPOST;
		$_POST['email'] = $this->user['email'];
		
		return true;
	}

	private function loginWithGoogle()
	{
		// Get token
		$refreshToken = Tools::sanitizeString($_POST['google_oauth_token']);
		
		// Do not continue any longer if the token is invalid
		if ( !$refreshToken ){ $this->errors[5001] = array('_ERR_INVALID_REFRESH_TOKEN'); return false; }

		// Instanciate users controller (for later use)
		$this->CUsers = new CUsers();
		
		// Request an access token from the refresh token
		$token = null;
		try 					{ $token = $this->CUsers->getGoogleRefreshedToken($refreshToken); }
		catch (Exception $ex) 	{ $this->errors[5002] = array('_ERR_GOOGLE', $ex->getMessage()); return false; }
		
		// Get user profile
		$profile = null;
		try 					{ $profile = $this->CUsers->getGoogleUserProfile(null, $token); }
		catch (Exception $ex) 	{ $this->errors[5002] = array('_ERR_GOOGLE', $ex->getMessage()); return false; }
		
		// Do not continue if the profile has not been found or if the email has not been returned
		if ( !$profile || !isset($profile['id']) ){ $this->errors[5001] = array('_ERR_GOOGLE'); return false;}
		
		// First, try to find the user from the profile id
		$this->user = $this->CUsers->retrieve(array('conditions' => array('google_id' => $profile['id'])));
		
		if ( !$this->user ){ $this->errors[5001] = array('_ERR_USER_NOT_FOUND'); return false; }		

		// Complete user data with retrieved info
		$bDate 							= $this->user['birthdate'];
		$curPOST 						= $_POST;
		$_POST['google_oauth_token'] 	= $refreshToken;
		
		if ( !$bDate || $bDate == '0000-00-00' )	{ $_POST['birthdate'] 	= isset($profile['birthday']) ? DateTime::createFromFormat('Y-m-d', $profile['birthday'])->format('Y-m-d')  : '0000-00-00'; }
		if ( empty($this->user['google_id']) )		{ $_POST['google_id'] 	= $profile['id']; }
		if ( empty($this->user['firstname']) )		{ $_POST['firstname'] 	= isset($profile['given_name']) ? $profile['given_name'] : ''; }
		if ( empty($this->user['lastname']) )		{ $_POST['lastname'] 	= isset($profile['family_name']) ? $profile['family_name'] : ''; }
		if ( empty($this->user['avatar_url']) ) 	{ $_POST['avatar_url'] 	= isset($profile['picture']) ? $profile['picture'] : ''; }
		
		$this->CUsers->update(array('conditions' => array('id' => $this->user['id'])));
		
		// Restaure previous POST content
		$_POST 			= $curPOST;
		$_POST['email'] = $this->user['email'];

		return true;
	}
	
	private function loginWithTwitter()
	{
		// Get token
		$token 			= Tools::sanitizeString($_POST['twitter_oauth_token']);
		$tokenSecret 	= Tools::sanitizeString($_POST['twitter_oauth_token_secret']);
		
		// Do not continue any longer if the token is invalid
		if ( !$token )		{ $this->errors[5001] = array('_ERR_INVALID_TOKEN'); return false; }
		if ( !$tokenSecret ){ $this->errors[5001] = array('_ERR_INVALID_TOKEN_SECRET'); return false; }
		
		// Instanciate users controller (for later use)
		$this->CUsers = new CUsers();
		
		// Get user profile
		$profile = $this->CUsers->getTwitterUserProfile(null, $token, $tokenSecret);

		// If no profile has been retrieved
		if ( !$profile || !empty($profile['errors']) || !isset($profile['id']) )
		{
			if 		( isset($profile['errors'][0]['message']) )	{ $this->errors[5002] = array('_ERR_TWITTER', $profile['errors'][0]['message']); }
			else 												{ $this->errors[5001] = array('_ERR_TWITTER'); }
			 
			return false;
		}
		
		// First, try to find the user from the profile id
		$this->user = $this->CUsers->retrieve(array('conditions' => array('twitter_id' => $profile['id'])));

		if ( !$this->user ){ $this->errors[5001] = array('_ERR_USER_NOT_FOUND'); return false; }

		// Complete user data
		$curPOST 								= $_POST;
		$_POST['twitter_oauth_token'] 			= $token;
		$_POST['twitter_oauth_token_secret'] 	= $tokenSecret;
		
		if ( empty($this->user['twitter_id']) )		{ $_POST['twitter_id'] = $profile['id']; }
		if ( empty($this->user['firstname']) || empty($this->user['firstname']) && !empty($profile['name']) )
		{
			$parts = explode(' ', $profile['name'], 2);
			$_POST['firstname'] = isset($parts[0]) ? $parts[0] : '';
			$_POST['lastname'] 	= isset($parts[1]) ? $parts[1] : '';
		}
		if ( empty($this->user['avatar_url']) ) 	{ $_POST['avatar_url'] 	= isset($profile['profile_image_url_https']) ? $profile['profile_image_url_https'] : ''; }
		
		$this->CUsers->update(array('conditions' => array('id' => $this->user['id'])));
		
		// Restaure previous POST content
		$_POST 			= $curPOST;
		$_POST['email'] = $this->user['email'];
		
		return true;
	}
}
?>