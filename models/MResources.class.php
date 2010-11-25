<?php

class MResources extends Model
{
	public function __construct($application = null)
	{
		$this->setResource(array('class' => __CLASS__));
		
		return parent::__construct($application);
	}
	
	public function index($options = array())
	{
		$o 				= &$options;
		$o['sortBy'] 	= !empty($o['sortBy']) ? $o['sortBy'] : 'name';
		$o['orderBy'] 	= !empty($o['orderBy']) ? $o['orderBy'] : 'ASC';
		
		return parent::index($options);
	}
	
	public function retrieve($options = array())
	{
		$o 				= &$options;
		$o['sortBy'] 	= !empty($o['sortBy']) ? $o['sortBy'] : 'name';
		$o['orderBy'] 	= !empty($o['orderBy']) ? $o['orderBy'] : 'ASC';
		
		return parent::index($options);
	}

}
?>