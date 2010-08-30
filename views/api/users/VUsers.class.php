<?php

class VUsers extends ApiView
{
	public function __construct()
	{
		$this->resourceName 	= strtolower(preg_replace('/^V(.*)/','$1', __CLASS__));
		$this->filePath 		= dirname(__FILE__);
		
		parent::__construct();
		
		return $this;
	}
	
	public function index($resourceId = null, $options = null)
	{		
		$this->data['view']['method'] 		= __FUNCTION__;
		
		$args = func_get_args();
		
		if ( !empty($_POST['ids']) )
		{
			$resourceId 				= join(',', $_POST['ids']);
			$_SERVER['REQUEST_METHOD'] 	= 'GET';
			$_GET['method'] 			= $_POST['method'];
		}
		
		$m = $_SERVER['REQUEST_METHOD'];
		$a = isset($_GET['method']) ? $_GET['method'] : null;
		
		if 		( $m === 'POST' || $a === 'create' )		{ return $this->create($resourceId, $options); }
		
		// Special case to get products, filtered by user id
		else if ( $m === 'GET' && !empty($args[1]) && $args[1] === 'products' )
		{			
			$this->options['by'] 		= is_numeric($resourceId) ? 'id' : 'email';
			$this->options['values'] 	= $resourceId; 
			$user 						= $this->C->retrieve($this->options);
			
			// If the user doest not exists, do not continue
			if ( empty($user)) { $this->data['errors'][] = '10200'; return $this->respondError(417); }

			$this->requireViews(array('VProducts' => 'api/products/'));

			$VProducts 						= new VProducts();
			$VProducts->options['by'] 		= 'users_id';
			$VProducts->options['values'] 	= $user['id'];
			
			return $VProducts->index();
		}
			
		return $this->render(__FUNCTION__);
	}
	
	public function create($options = null)
	{
		$this->data['view']['method'] 		= __FUNCTION__;
		$this->data['view']['template'] 	= 'pages/api/users/create.tpl';
		$this->data['view']['resourceName'] = $this->resourceName;
		$this->data['view']['css'] 			= array('common','api');
		
		// Before anything, check if the request is valid (use proper credentials)
		$this->validateRequest();
		
		// If the resource creation form has been posted
		if ( !empty($_POST) )
		{
			// Check for the required params
			$reqParams = array('email' => 20010, 'password' => 20012, 'first_name' => 20016, 'last_name' => 20017, 'address' => 20020, 'country' => 20021, 'city' => 20022, 'zipcode' => 20023);
			foreach ($reqParams as $key => $val)
			{
				if ( empty($_POST[$key]) ) { $this->data['errors'][] = $val; $this->statusCode(400); }
			}
			
			// Check if the user does not already exists
			$email 		= !empty($_POST['email']) ? filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) : '';
			$userExists = $this->C->retrieve(array('by' => 'email', 'values' => $email, 'mode' => 'count'));

			if ( $userExists >= 1 ) { $this->data['errors'][] = 10021; $this->statusCode(409); }
			
			$_POST = $_POST + array(
				'userBilling_address' 	=> $_POST['userAddress'],
				'userBilling_zipcode' 	=> $_POST['userZipcode'],
				'userBilling_city' 		=> $_POST['userCity'],
				'userBilling_country' 	=> $_POST['userCountry'],
			);
						
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
			$from 		= 'Collector Serie <info@collectorserie.com>';
			$to			= $email;
			$subject 	= _('Welcome to Collectorserie!');
			$content 	= $this->Mailer->fetch(array(
				'template' 	=> 'common/mails/account/signup/success.tpl',
				'data' 		=> array('user' => $user)
			));			

			// Send the mail				
			$this->Mailer->send(array('from' => $from, 'to' => $to, 'subject' => $subject, 'content' => $content));
			
			if ( !$this->Mailer->success ) 	{ $this->errors = array_merge($this->data['errors'], $this->Mailer->errors); }
			
			$this->respondError(201);
			
			unset($_POST);
		}
		
		return $this->render(__FUNCTION__);
	}

}

?>