<?php

class VProducts extends ApiView
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
		if ( !empty($_POST['ids']) )
		{
			$resourceId 				= join(',', $_POST['ids']);
			$_SERVER['REQUEST_METHOD'] 	= 'GET';
			$_GET['method'] 			= $_POST['method'];
		}
		
		$m = $_SERVER['REQUEST_METHOD'];
		$a = isset($_GET['method']) ? $_GET['method'] : null;
		
		if ( $m === 'GET' && !empty($resourceId))			{ return $this->retrieve($resourceId, $options); }

		if ( $this->options['by'] === 'users_id' )
		{
			$this->requireControllers('COrders');
			
			$COrders 					= new COrders();
			$orders 					= $COrders->index(array('reindexby' => 'products_id'));
			$pids 						= $COrders->values('products_id');
			
			$this->options['values'] 	= $pids;
			$this->options['by'] 		= null;
		}
		
		// Set output data		
		$this->data = array_merge($this->data, array(
			$this->resourceName => $this->C->index($this->options),
			'success' 			=> $this->C->success, 
			'errors'			=> $this->C->errors,
			'warnings' 			=> $this->C->warnings,
		));
		
		if ( !count($this->data[$this->resourceName]) ){ $this->respondError(204); }
		
		// Loop over the items to crypt URLs columns
		$this->requireLibs(array('AES' => 'security/'));
		foreach ( $this->data[$this->resourceName] as $key => $val )
		{
			$pvk 	= 'OyMARes3upqAiO57'; 	// Private key for 'clicmobileInternal' apiclient
			$urls 	= array('iphone_pics_URL', 'iphone_video_URL', 'iphone_video_tn_URL', 'ipad_video_URL', 'ipad_video_tn_URL');
			foreach ($urls as $name)
			{
				// Do not process the data if it's empty
				if ( empty($this->data[$this->resourceName][$key][$name]) ){ continue; }

				$curVal = $this->data[$this->resourceName][$key][$name];
				$this->data[$this->resourceName][$key][$name] =  AES::getInstance()->encrypt($curVal,$pvk);
			}
		}
			
		return $this->render(__FUNCTION__);
	}

	
	public function retrieve($resourceId = null, $options = null)
	{		
		$this->resourceId 	= $resourceId;
		
		// Set output data		
		$this->data = array_merge($this->data, array(
			$this->resourceName 	=> $this->C->retrieve(array('values' => $this->resourceId)),
			'resourceId' 			=> $this->resourceId,
		));
		
		return $this->render(__FUNCTION__);
	}

}

?>