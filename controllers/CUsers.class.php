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
		
//var_dump($curDate);
//var_dump($today);
//die();
		
		// Groups that are exempted from password expiration
		$exmptGps = Tools::toArray(_APP_PASSWORDS_EXPIRATION_EXEMPTED_GROUPS);
		
		// Loop over them
		foreach ( (array) $users as $user )
		{
			// Do not continue if the current user password has no expiration
			if ( empty($user['password_expiration']) ){ continue; }
		
			// Get intersection between user group and password expiration exempted groups
			$uGps 		= !empty($user['group_admin_titles']) ? Tools::toArray($user['group_admin_titles']) : array();
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
}
?>