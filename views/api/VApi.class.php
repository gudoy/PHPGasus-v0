<?php

class VApi extends ApiView
{
	public function __construct()
	{
		//$this->resourceName 	= strtolower(preg_replace('/^V(.*)/','$1', __CLASS__));
		$this->filePath 		= dirname(__FILE__);
		
		parent::__construct();
		
		return $this;
	}
	
	
	public function index($options = null)
	{
		$this->data['view']['name'] 	= 'apiHome';
		$this->data['view']['template'] = 'specific/pages/api/index.tpl'; 
		
		return $this->render();
	}
	
	
	public function sha1()
	{
		if ( $this->env['type'] !== 'dev' ){ $this->redirect(_URL_API); }
		
		if ( !empty($_POST['stringToHandle']) && isset($_GET['godmod']) )
		{
			sha1($_POST['stringToHandle']);
			
			unset($_POST);
		}

		$this->data['view']['name'] 	= 'apiHome';
		$this->data['view']['template'] = 'common/pages/api/sha1.tpl'; 
		$this->data['view']['method'] 	= __FUNCTION__;
		
		return $this->render();
	}
	
	
	public function encrypt()
	{
		if ( $this->env['type'] !== 'dev' ){ $this->redirect(_URL_API); }
		
		if ( !empty($_POST['stringToHandle']) && isset($_GET['godmod']) )
		{
			class_exists('CApiclients') || require(_PATH_CONTROLLERS . 'CApiclients.class.php');
			class_exists('AES') 		|| require(_PATH_LIBS . 'security/AES.class.php');

			$akId		= $_POST['accessKeyId']; // Store the accessKey Id
			
			// Get the private key id for the passed access key id
			$apiClient 	= CApiclients::getInstance()->retrieve(array('values' => $akId));
			$pvk 		= $apiClient['private_key'];
			
			$AESenc = AES::getInstance()->encrypt($_POST['stringToHandle'], $pvk);
			
			die($AESenc);
			
			unset($_POST);
		}

		$this->data['view']['name'] 	= 'apiHome';
		$this->data['view']['template'] = 'common/pages/api/encrypt.tpl'; 
		$this->data['view']['method'] 	= __FUNCTION__;
		
		return $this->render();
	}
	
	
	public function decrypt()
	{
		if ( $this->env['type'] !== 'dev' ){ $this->redirect(_URL_API); }
		
		if ( !empty($_POST['stringToHandle']) && isset($_GET['godmod']) )
		{
			class_exists('CApiclients') || require(_PATH_CONTROLLERS . 'CApiclients.class.php');
			class_exists('AES') 		|| require(_PATH_LIBS . 'security/AES.class.php');

			$akId		= $_POST['accessKeyId']; // Store the accessKey Id
			
			// Get the private key id for the passed access key id
			$apiClient 	= CApiclients::getInstance()->retrieve(array('values' => $akId));
			$pvk 		= $apiClient['private_key'];
			
			$AESenc = AES::getInstance()->decrypt($_POST['stringToHandle'], $pvk);
			
			$isURL = filter_var($AESenc, FILTER_VALIDATE_URL) !== false;
			
			$AESenc = $isURL ? '<a href="' . $AESenc .'">' . $AESenc . '</a>' : $AESenc;
			
			die($AESenc);
			
			unset($_POST);
		}
		
		$this->data['view']['name'] 	= 'apiHome';
		$this->data['view']['cssClasses'] 	= 'api';
		$this->data['view']['template'] = 'common/pages/api/decrypt.tpl';
		$this->data['view']['method'] 	= __FUNCTION__;
		
		return $this->render();
	}
	
	public function isItRunning()
	{		
		echo 'ok';
	}
	
};

?>