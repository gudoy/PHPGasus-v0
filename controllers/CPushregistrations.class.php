<?php

class_exists('Controller') 	|| require(_PATH_LIBS . 'Controller.class.php');
class_exists('MPushregistrations') || require(_PATH_MODELS . 'MPushregistrations.class.php');

class CPushregistrations extends Controller
{
	private static $_instance;
	
	public function __construct()
	{
		$this->resourceName = strtolower(preg_replace('/^C(.*)/','$1', __CLASS__));
		
		return parent::__construct();
	}
	
	public static function getInstance()
	{
		if ( !(self::$_instance instanceof self) ) { self::$_instance = new self(); } 
		
		return self::$_instance;
	}
	
	public function handleModelErrors()
	{
		$errs = (array) $this->model->errors;
		
		foreach ($errs as $err)
		{
			if ( strpos($err, 'Duplicate entry') !== false ){ $this->errors[] = 20260; } // Already registered device
		}
	}

}
?>