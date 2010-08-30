<?php

class VEntries extends ApiView
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
		$this->data['view']['method'] = __FUNCTION__;
		
		if ( !empty($_POST['ids']) )
		{
			$resourceId 				= join(',', $_POST['ids']);
			$_SERVER['REQUEST_METHOD'] 	= 'GET';
			$_GET['method'] 			= $_POST['method'];
		}
		
		$m = $_SERVER['REQUEST_METHOD'];
		$a = isset($_GET['method']) ? $_GET['method'] : null;
		
		if ( $m === 'GET' && !empty($resourceId))			{ return $this->retrieve($resourceId, $options); }
		
		// Set output data		
		$this->data = array_merge($this->data, array(
			$this->resourceName => $this->C->index($this->options),
			'success' 			=> $this->C->success, 
			'errors'			=> $this->C->errors,
			'warnings' 			=> $this->C->warnings,
		));
		
		if ( !count($this->data[$this->resourceName]) ){ $this->respondError(204); }
			
		//return $this->render(__FUNCTION__);
		return $this->render();
	}

	
	public function retrieve($resourceId = null, $options = null)
	{
		$this->data['view']['method'] = __FUNCTION__;
		
		$this->resourceId 	= $resourceId;
		
		// Set output data		
		$this->data = array_merge($this->data, array(
			$this->resourceName 	=> $this->C->retrieve(array_merge($this->options, array('values' => $this->resourceId))),
			'success' 				=> $this->C->success, 
			'errors'				=> $this->C->errors,
			'warnings' 				=> $this->C->warnings,
			//'resourceId' 			=> $this->resourceId,
		));
		
		//return $this->render(__FUNCTION__);
		return $this->render();
	}

}

?>