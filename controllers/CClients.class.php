<?php

class_exists('Controller') 	|| require(_PATH_LIBS . 'Controller.class.php');
class_exists('MClients') 	|| require(_PATH_MODELS . 'MClients.class.php');

class CClients extends Controller
{
	private static $_instance;
	
	public function __construct()
	{
		$this->resourceName 	= strtolower(preg_replace('/^C(.*)/','$1', __CLASS__));
		//$this->resourceSingular = 'sample'; // use only if: singular !== (resourceName - "s")
		
		return parent::__construct();
	}
	
	public static function getInstance()
	{
		if ( !(self::$_instance instanceof self) ) { self::$_instance = new self(); } 
		
		return self::$_instance;
	}
}
?>