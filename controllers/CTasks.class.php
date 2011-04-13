<?php

class CTasks extends Controller
{
	private static $_instance;
	
	public function __construct()
	{
        $this->setResource(array('class' => __CLASS__, 'task' => null));
		
		return parent::__construct();
	}
	
	public static function getInstance()
	{
		if ( !(self::$_instance instanceof self) ) { self::$_instance = new self(); } 
		
		return self::$_instance;
	}
}
?>