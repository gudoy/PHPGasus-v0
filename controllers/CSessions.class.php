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
		$ret = false;
		
		if ( isset($_POST['google_access_token']) )
		{
			$googLogged = $this->loginWithGoogle();
			
			if ( $googLogged ){ $ret = 'google'; }
		}

		if ( isset($_POST['twitter_access_token']) )
		{
			$twLogged = $this->loginWithTwitter();
			
			if ( $twLogged ){ $ret = 'twitter'; }
		}
		
		// Handle login with facebook
		if ( isset($_POST['facebook_access_token']) )
		{
			$fbLogged = $this->loginWithFacebook();
			
			if ( $fbLogged ){ $ret = 'facebook'; }
		}
		
		return $ret;
	}
	
	private function loginWithFacebook()
	{		
		// Get token
		$token = Tools::sanitizeString($_POST['facebook_access_token']);
		
		// Do not continue any longer if the token is invalid
		if ( !$token ){ return false; }
		
		// Instanciate users controller (for later use)
		$this->CUsers = new CUsers();
		
		// Request an extended token (valid 2 month instead of just 2 hours)
		$exToken = $this->CUsers->getExtendedToken($token);
		
		// Get user profile
		$fbProfile = $this->CUsers->getFacebookUserProfile(null, $exToken, array('fields' => 'email,birthday'));

		// Do not continue if the profile has not been found or if the email has not been returned
		if ( !$fbProfile || !isset($fbProfile['email']) ){ return false; }
		
		// Get current user data
		$this->user = $this->CUsers->retrieve(array('conditions' => array('email' => $fbProfile['email'])));
		
		// If the user has not been found
		if ( !$this->user ){ return false; } 
		
		// Complete user data
		$curPOST 	= $_POST;
		$_POST 		= array('facebook_access_token' => $exToken);
		if ( empty($this->user['birthdate']) )	{ $_POST['birthdate'] 	= DateTime::createFromFormat('m/d/Y', $fbProfile['birthday'])->format('U'); }
		if ( empty($this->user['facebook_id']) ){ $_POST['facebook_id'] = $fbProfile['id']; }
		
		$this->CUsers->update(array('conditions' => array('id' => $this->user['id'])));
		$_POST 		= $curPOST;
		
		// Complete POST data for session creation
		$_POST['email'] = $fbProfile['email'];
		
		return true;
	}

	private function loginWithGoogle()
	{
		return false;
	}
	
	private function loginWithTwitter()
	{
		return false;
	}
}
?>