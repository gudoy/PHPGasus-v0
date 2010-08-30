<?php

class VPushregistrations extends ApiView
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
		$m = $_SERVER['REQUEST_METHOD'];
		$a = isset($_GET['method']) ? $_GET['method'] : null;
		
		//if 		( $m === 'PUT' 		|| $a === 'create' )	{ return $this->create($resourceId, $options); }
		//else if ( $m === 'POST' 	|| $a === 'create' )	{ return $this->create($resourceId, $options); }
		if ( $m === 'POST' 	|| $a === 'create' )	{ return $this->create($resourceId, $options); }
	}
	
		
	public function create($resourceId = null, $options = null)
	{
		$this->data['view'] = array(
			'name' 					=> 'api' . ucfirst(__FUNCTION__),
			'template' 				=> 'specific/pages/api/pushregistrations/' . __FUNCTION__ . '.tpl',
		);
		
		// If the resource creation form has been posted
		if ( !empty($_POST) )
		{
			// Loop over to check for the required params
			$reqParams = array('token' => 20061, 'device_id' => 20055);
			foreach ($reqParams as $key => $val){ if ( empty($_POST[$key]) ) { $this->data['errors'][] = $val; $this->respondError(400); }}
			
			// Clean token string
			$_POST['token'] = str_replace('>', '', str_replace('<','',$_POST['token']));
					
			// Launch the creation
			$this->C->create();
		}
		
		// Set output data		
		$this->data = array_merge($this->data, array(
			'success' 		=> $this->C->success, 
			'errors'		=> $this->C->errors,
			'warnings' 		=> $this->C->warnings,
		));
		
		if ( in_array(20260, (array) $this->C->errors) ){ $this->data = array_merge($this->data, array('success' => true, 'errors' => array(), 'warnings' => $this->C->errors)); }
		
		
		// If the operation succeed, reset the $_POST
		if ( $this->data['success'] ){ unset($_POST); $this->respondError(201); }
		
		// Then, render page
		return $this->render();
	}
	
	
};

?>