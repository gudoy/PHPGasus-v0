<?php

class_exists('View') || require(_PATH_LIBS . 'View.class.php');

class ApiView extends View
{
	
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
		
		$this->data['view']['template'] 	= !empty($v['template']) ? $v['template'] : 'common/pages/api/resource/' . $m . '.tpl';
		$this->data['view']['resourceName'] = !empty($this->resourceName) ? $this->resourceName : null;
		$this->data['view']['css'] 			= array('api');
		
		// Only for html/xhtml output, we want to be able to build a 'smart' form from the resource datamodel 
		if ( in_array($this->options['output'], array('html','xhtml')) )
		{
			isset($dataModel) || include(_PATH_CONFIG . 'dataModel.php');
			
			$this->data['dataModel'] = $dataModel;
			$this->data['resources'] = $resources;
		}
		
		return parent::render();
	}

		
}

?>