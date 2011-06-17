<?php

class MMedias extends Model
{
	public function __construct($application = null)
	{
        $this->setResource(array('class' => __CLASS__, 'singular' => 'media'));
		
		return parent::__construct($application);
	}
	
	public function index($options = array())
	{
		$o 				= &$options;
		$o['sortBy'] 	= !empty($o['sortBy']) ? $o['sortBy'] : 'importance';
		$o['orderBy'] 	= !empty($o['orderBy']) ? $o['orderBy'] : 'desc';
		
		return parent::index($options);
	}
	
	public function retrieve($options)
	{
		$o 				= &$options;
		$o['sortBy'] 	= !empty($o['sortBy']) ? $o['sortBy'] : 'importance';
		$o['orderBy'] 	= !empty($o['orderBy']) ? $o['orderBy'] : 'desc';
		
		return parent::retrieve($options);
	}
}
?>