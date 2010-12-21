<?php

class MCountries extends Model
{
	public function __construct($application = null)
	{
        $this->setResource(array('class' => __CLASS__));
		
		return parent::__construct($application);
	}
	
	public function index($options)
	{
		$o = $options;
		$options['sortBy'] 		= !empty($o['sortBy']) ? $o['sortBy'] : 'name';
		
		return parent::index($options);
	}
	
	public function retrieve($options)
	{
		$o = $options;
		$options['sortBy'] 		= !empty($o['sortBy']) ? $o['sortBy'] : 'name';
		
		return parent::retrieve($options);
	}
}
?>