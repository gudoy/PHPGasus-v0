<?php

class MKeywords extends Model
{
	public function __construct($application = null)
	{
        $this->setResource(array('class' => __CLASS__));
		
		return parent::__construct($application);
	}
	
	public function index($options = array())
	{
		// Set default params
		$o 					= $options;
		$options['sortBy'] 	= !empty($o['sortBy']) ? $o['sortBy'] : 'issue_number';
		$options['orderBy'] = !empty($o['orderBy']) ? $o['orderBy'] : 'DESC';
		
		return parent::index($options);
	}
	
	public function retrieve($options = array())
	{
		// Set default params
		$o 					= $options;
		$options['sortBy'] 	= !empty($o['sortBy']) ? $o['sortBy'] : 'issue_number';
		$options['orderBy'] = !empty($o['orderBy']) ? $o['orderBy'] : 'DESC';
		
		return parent::retrieve($options);
	}

}
?>