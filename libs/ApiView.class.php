<?php

class_exists('View') || require(_PATH_LIBS . 'View.class.php');

class ApiView extends View
{
	public function __construct(&$application = null)
	{
	    $this->application = &$application;
        
		parent::__construct($application);
	}
	
	public function dispatchMethods($args = array(), $params = array())
	{
		$params['isApi'] = true;
		
		return parent::dispatchMethods($args, $params);
	}
	
	public function hasAuth()
	{
		if ( empty($_SESSION['user_id']) ){ return false; }
		
		// Get the user id
		$uid = $_SESSION['user_id'];
		
		// Get logged user data
		$u 			= CUsers::getInstance()->retrieve(array('values' => $uid));
		$gpNames 	= !empty($u['group_admin_titles']) ? explode(',', $u['group_admin_titles']) : array(); 
		
		// Look for allowed group names
		$intersect = array_intersect($gpNames, array('gods','superadmin','admin','apiclient'));
		
		return !empty($intersect);
	}
	
	public function requireAuth()
	{
		if ( !$this->hasAuth() ){ $this->redirect(_URL_HOME); } 
	}
	
	public function validateRequest()
	{
		if ( empty($_GET['requestSign']) || empty($_GET['accessKeyId']) ){ $this->respondError(401); }
	
		class_exists('AES') 		|| require(_PATH_LIBS . 'security/AES.class.php');
		class_exists('CApiclients') || require(_PATH_CONTROLLERS . 'CApiclients.class.php');
		
		$sign 		= $_GET['requestSign']; // Store the request sign
		$akId		= $_GET['accessKeyId']; // Store the accessKey Id
		
		// Get the private key id for the passed access key id
		$apiClient 	= CApiclients::getInstance()->retrieve(array('values' => $akId));
		$pvk 		= $apiClient['private_key'];
		
		// Rebuild the current URL, get the part of if that was used to build the sign, and get OUR version of the sign 
		$curURI 	= $this->currentURL();
		$signedURI 	= preg_replace('/(.*)&$/', '$1', preg_replace('/(.*)requestSign=(.*)(&|$)/U','$1', $curURI));
		$xpctedSign = sha1(AES::getInstance()->encrypt($signedURI,$pvk));

		// Finally, if the passed one and our encode of the sign doest not match, refuse the access 
		if ( $sign !== $xpctedSign ){ $this->respondError(401); }
		
		return $this;
	}

	public function render()
	{
		$v = !empty($this->data['view']) ? $this->data['view'] : null; 	// Shortcut for view data
		$m = !empty($v['method']) ? $v['method'] : 'index'; 			// Shortcut for view method
		
		$this->data['view']['template'] 	= !empty($v['template']) ? $v['template'] : 'specific/pages/api/resource/' . $m . '.tpl';
		// DEPRECATED: use $data.current.resource instead
		//$this->data['view']['resourceName'] = !empty($this->resourceName) ? $this->resourceName : null;
		$this->data['view']['css'] 			= array('api');
		
		// Only for html/xhtml output, we want to be able to build a 'smart' form from the resource datamodel 
		//if ( in_array($this->options['output'], array('html','xhtml')) )
		if ( $this->application->env['type'] === 'dev' && in_array($this->options['output'], array('html','xhtml')) )
		{
			isset($dataModel) || include(_PATH_CONFIG . 'dataModel.php');
			
			$this->data['dataModel'] = $dataModel;
			$this->data['_resources'] = $resources;
			
			// For create and update views
			if ( in_array($m, array('create','update')) )
			{
		        // TODO: used? double bloom with $this->data['_resources'] & $this->data['resourcesFields']?
				$this->dataModel = array(
					'resources' 		=> &$resources,
					'resourcesFields' 	=> &$dataModel,
					//'resourceGroups' 	=> $resourceGroups,
				);
				
				$this->data = array_merge($this->data, array(
					'dataModel' 			=> &$this->dataModel['resourcesFields'], // TODO: deprecate in favor of _colums
					#'_dataModel'          	=> &$this->dataModel['resourcesFields'],
					//'_resources'             => &$this->dataModel['resources'],
					//'_resourcesGroups'       => &$_resourcesGroups,
				));
				
				// We have to handle relations
				$this->handleRelations();
			}
		}
		
		return parent::render();
	}
		
}

?>