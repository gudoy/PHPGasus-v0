<?php

//class_exists('Controller') 	|| require(_PATH_LIBS . 'Controller.class.php');

class CContacts extends Controller
{
	private static $_instance;
	
	public function __construct()
	{
		//$this->resourceName 	= strtolower(preg_replace('/^C(.*)/','$1', __CLASS__));
		//$this->resourceSingular = 'sample'; // use only if: singular !== (resourceName - "s")
		
		return parent::__construct();
	}
	
	public static function getInstance()
	{
		if ( !(self::$_instance instanceof self) ) { self::$_instance = new self(); } 
		
		return self::$_instance;
	}
	
	public function handleContactMail()
	{
		class_exists('Mailer') 		|| require(_PATH_LIBS . 'Mailer.class.php');
		
		$this->success 	= false;
		$this->errors 	= array();
		
		$rf 		= array('subject','email','name','message','captchaResult');
		$of 		= array('title','phone','company','website','address','city','zipcode','country');
		$fields 	= array_merge($rf,$of);
		
		// Filters data
		$contact = array();
		foreach ($fields as $field)
		{
			$fName = 'contact' . ucfirst($field);
			
			if ( empty($_POST[$fName]) ){ continue; }
			
			$filter 			= $field === 'email' ? FILTER_VALIDATE_EMAIL : FILTER_SANITIZE_STRING;
			$contact[$field] 	= filter_var($_POST[$fName], $filter);
		}
		
		// Email format not correct
		if ( empty($contact['email']) ) { $this->errors[] = 10008; return $this; }
		
		// Are all the required fields filled
		$requirements = true;
		foreach ( $rf as $field ){ if ( empty($contact[$field]) ) { $requirements = false; break; } }
		if ( !$requirements ){ $this->errors[] = 10000; return $this; }
		
		// Check if captcha value is correct
		if ( empty($contact['captchaResult']) || (int) $contact['captchaResult'] !== $_SESSION['captchaResult'] ) { $this->errors[] = 10010; return $this; }
		
		// Send data to Salesforce (CRM)
		$url_crm 	= _URL_SALESFORCE_CONTACTS_HANDLER;
		$crm_data 	= array(
			'email' 		=> @$contact['email'],
			'lead_source' 	=> @$contact['subject'],
			//'description' 	=> @$contact['message'],
			//'description' 	=> @utf8_encode(html_entity_decode($contact['message'])),
			'description' 	=> @html_entity_decode($contact['message']),
			'title' 		=> @$contact['title'],
			'last_name' 	=> @$contact['name'],
			'company' 		=> @$contact['company'],
			'URL' 			=> @$contact['website'],
			'street' 		=> @$contact['address'],
			'country' 		=> @$contact['country'],
			'city' 			=> @$contact['city'],
			'zip' 			=> @$contact['zipcode'],
			'phone' 		=> @$contact['phone'],
			'oid' 			=> '00D20000000N04P', 
			//'retURL' 		=> 'http://dev.clicmobile.com/about/contact',
		);
		$result 	= $this->wsCall($url_crm, array('method' => 'post', 'data' => $crm_data));
		
		// Instanciate proper controllers
		$this->Mailer 	= new Mailer();

		$from 			= $contact['email'];
		$to				= _APP_OWNER_CONTACT_MAIL . ', loan@clicmobile.com';
		//$to				= 'guyllaume@clicmobile.com, sebastien@clicmobile.com';
		//$to				= 'guyllaume@clicmobile.com';
		$subject 		= '[' . $contact['subject'] . '] ';
		$content 		= $contact['message'];

		// Send the mail				
		$this->Mailer->send(array('from' => $from, 'to' => $to, 'subject' => $subject, 'content' => $content));
		
		if ( $this->Mailer->success ) 	{ $this->success 	= true; }
		else 							{ $this->errors 	= array_merge($this->errors,$this->Mailer->errors); }
	}
}
?>


