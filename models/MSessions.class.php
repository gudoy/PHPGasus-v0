<?php

class MSessions extends Model
{
	public function __construct(&$application = null)
	{
		$this->setResource(array('class' => __CLASS__));
		
		return parent::__construct($application);
	}
	
	public function index($options = array())
	{
		$o 			= &$options;
		$o['by'] 	= !empty($o['by']) ? $o['by'] : 'name';
		
		return parent::index($options);
	}

	public function update($resourceData = null, $options = array())
	{
		$o 			= &$options;
		$o['by'] 	= !empty($o['by']) ? $o['by'] : 'name';
		
		return parent::update($resourceData, $options);
	}
	
	public function delete($options = array())
	{
		$o 			= &$options;
		$o['by'] 	= !empty($o['by']) ? $o['by'] : 'name';
		
		return parent::delete($options);
	}
	
	public function retrieve($options = array())
	{
		$o 			= &$options;
		$o['by'] 	= !empty($o['by']) ? $o['by'] : 'name';
		
		return parent::retrieve($options);
	}
}
?>