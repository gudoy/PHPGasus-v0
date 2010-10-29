<?php

//class_exists('Controller') 	|| require(_PATH_LIBS . 'Controller.class.php');
//class_exists('MUsers') 		|| require(_PATH_MODELS . 'MUsers.class.php');

class CUsers extends Controller
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
	
	/*
	public function extendsData($options = array())
	{
//var_dump('extendsData');
		
		$o = array_merge(array(
			'extendsData' => true,
			'isCollection' => true,
		), $options);
		
		// Do not continue if there's no data to process or if data is not an array( ie: for count operations)
		if ( empty($this->data) || !is_array($this->data) || empty($o['extendsData']) ) { return $this; }
		
		$groupsauth = CGroupsauths::getInstance()->index(array('by' => 'id', 'reindexby' => 'group_id'));
		
//var_dump($this->data);

		// Handle data as a collection
		if ( $o['isCollection'] )
		{
			foreach ( $this->data as $user )
			{
//var_dump($user);
				
				// Get groups ids of the current user
				$gids = !empty($user['group_ids']) ? $user['group_ids'] : array();
				
var_dump($gids);
			} 
		}
		// Specific case for
		else
		{
			// Get groups ids of the current user
			$gids = !empty($this->data['group_ids']) ? $this->data['group_ids'] : array();
			
var_dump($gids);
		}
		

		
		return $this;
	}
	*/
}
?>