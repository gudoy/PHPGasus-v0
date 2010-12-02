<?php

class CPushregistrations extends Controller
{
	private static $_instance;
	
	public function __construct()
	{
        $this->setResource(array('class' => __CLASS__));
		
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