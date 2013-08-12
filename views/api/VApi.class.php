<?php

class VApi extends ApiView
{
	public function __construct(&$application = null)
	{
	    $this->application = &$application;
        
		parent::__construct($application);
	}
	
	
	public function index($options = null)
	{
		// Prevent public access to api reference
		$this->requireLogin();
		
		$this->requireAuth();
		
		$this->data['view']['name'] 	= 'apiHome';
		$this->data['view']['template'] = 'specific/pages/api/index.tpl'; 
		
		return $this->render();
	}
	
	public function doc()
	{		
		// Prevent public access to api reference
		$this->requireLogin();
		
		$this->requireAuth();
		
		$this->data['view']['name'] 	= 'apiDocumentation';
		$this->data['view']['template'] = 'specific/pages/api/doc.tpl';
		
		include(_PATH_CONF . 'apis.php');
		//$DataModel = new DataModel();

		
		$apis 		= isset($apis) ? $apis : array();
		//$apiData 	= array();
		
		// Loop over apis groups
		foreach($apis as $gpName => &$gpProps)
		{
var_dump('groupName: ' . $gpName);
var_dump('found res:' . DataModel::searchResource(Tools::slug($gpName)));
			
			// Get current group name
			$gpProps['apis'] 	= isset($gpProps['apis']) ? $apis[$gpName]['apis'] : array();
			$gpApis 			= &$gpProps['apis'];
			
			$gpProps['displayName'] = !empty($gpProps['displayName']) ? $gpProps['displayName'] : $gpName;
			//$gpProps['resource'] 	= !empty($gpProps['resource']) ? $gpProps['resource'] : str_replace(array('-'), (''), Tools::slug($gpName));
			$gpProps['resource'] 	= !empty($gpProps['resource']) 
				? $gpProps['resource'] 
				//: str_replace(array('-'), (''), $DataModel->searchResource(Tools::slug($gpName)));
				: str_replace(array('-'), (''), DataModel::searchResource(Tools::slug($gpName)));

//var_dump($gpProps);
var_dump('resource1: ' . $gpProps['resource']);
//die();

			// Loop over group apis
			//$apiData = array();
			/*
			foreach ($gpProps['apis'] as &$api)
			{
//var_dump($api);
				$api['method'] 			= strtoupper($api['method']);
				$api['summary'] 		= !empty($api['summary']) ? $api['summary'] : null;
				$api['status'] 			= !empty($api['status']) ? $api['status'] : 'ready';
				
				$url 					= !empty($api['url']) ? $api['url'] : $gpProps['resource'] . '/';
				$api['url'] 			= str_replace('$resource', $gpProps['resource'], $url);
				
				$api['requireLogin'] 	= isset($api['requireLogin']) ? Tools::sanitizeBool($api['requireLogin']) : true;
				
				// Handle required fields
				$required = array();
				if ( $api['method'] === 'POST' )
				//if ( $api['method'] === 'POST' && !empty($gpProps['resource']) )
				{
//var_dump('resource2: ' . $gpProps['resource']);
					foreach((array) $this->_columns[$gpProps['resource']] as $columns)
					{
						// Is the column required
						$isRequired = !empty($colProps['required']);
										
						// If the column is required, and not passed or empty, mark it has missing
						if ( $isRequired  && ( !isset($_POST[$colName]) || $_POST[$colName] === '' ) ){ $missing[] = $colName; }
					}
				}
					
				$api['expects'] 		= isset($api['expects']) 
				? Tools::toArray(str_replace('$default', join(',',$required), join(',',Tools::toArray($api['expects']))))
				: $required;
				
				
			}*/
			
		}
		
var_dump($apis);
		
		$this->data['apis'] = $apis;
		
		return $this->render();
	}
	
	
	public function sha1()
	{
		if ( $this->application->env['type'] !== 'dev' ){ $this->redirect(_URL_API); }
		
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
		if ( $this->application->env['type'] !== 'dev' ){ $this->redirect(_URL_API); }
		
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