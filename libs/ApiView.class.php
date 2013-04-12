<?php

class_exists('View') || require(_PATH_LIBS . 'View.class.php');

class ApiView extends View
{
	public function __construct(&$application = null)
	{
	    $this->application = &$application;
        
		$this->loadDataModel();
		
		parent::__construct($application);
		
		$this->options['filterNotExposed'] = true;
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
		$gpNames 	= !empty($u['group_slugs']) ? explode(',', $u['group_slugs']) : array();
		
		// Look for allowed group names
		$intersect = array_intersect($gpNames, array('gods','superadmins','admins','apiclients'));
		
		return !empty($intersect);
	}
	
	public function requireAuth()
	{
		if ( !$this->hasAuth() ){ $this->redirect(_URL_401); } 
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

	public function getMissingRequiredFields()
	{
		// Do not continue if the resource's datamodel is not defined 
		if ( empty($this->C->application->_columns[$this->resourceName]) );
		
		$missing 	= array();
		
		// Loop over resource columns to check for missing required fields
		$rCols 		= $this->C->application->_columns[$this->resourceName];
//var_dump($rCols);
		foreach( (array) $rCols as $colName => $colProps )
		{
			// Is the column required
			$isRequired = !empty($colProps['required']);
							
			// If the column is required, and not passed or empty, mark it has missing
			if ( $isRequired  && ( !isset($_POST[$colName]) || $_POST[$colName] === '' ) ){ $missing[] = $colName; }
		}
		
		return $missing;
	}

	public function render()
	{
		$v = !empty($this->data['view']) ? $this->data['view'] : null; 	// Shortcut for view data
		$m = !empty($v['method']) ? $v['method'] : 'index'; 			// Shortcut for view method
		
		$this->data['view']['template'] 	= !empty($v['template']) ? $v['template'] : 'specific/pages/api/resource/' . $m . '.tpl';
		$this->data['view']['css'] 			= array('api');
		
		// TODO: Merge this with AdminView same code and move this into a method in the View class. Maybe cleanOutput() ??????
		// Except for (X)HTML output, prevents dataModel from being returned
		if ( in_array($this->options['output'], array('html','xhtml'))
			&& ($this->application->env['type'] === 'dev' || !empty($this->data['view']['allowApiHTMLFormView']))
		)
		{
			// For create and update views, We have to handle relations
			if ( in_array($m, array('create','update')) ){ $this->handleRelations(); }	
			
			// TODO: Remove not exposed columns
			$this->data['_groups'] = $this->_groups;
			$this->data['_resources'] = $this->_resources;
			$this->data['_columns'] = $this->_columns;
		}
		else
		{
			// Prevent datamodel 
			unset($this->data['_groups'], $this->data['_resources'], $this->data['_columns']);
			
			if ( defined('_APP_USE_EXTREMIST_REST_API') && _APP_USE_EXTREMIST_REST_API )
			{	
				// TODO: get errors & warnings & send them via an http header instead???
				//$this->data = !empty($this->data[$this->resourceName]) ? (array) $this->data[$this->resourceName] : array();
				//if ( isset($this->data[$this->resourceName]) )
				if ( isset($this->resourceName) && isset($this->data[$this->resourceName]) )
				{
					$this->data = (array) $this->data[$this->resourceName];
				}
				else
				{
					unset($this->data);
				}
			}
		}		

		
		return parent::render();
	}
		
}

?>