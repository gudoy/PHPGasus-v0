<?php

class MEntries extends Model
{
	public function __construct($application = null)
	{
		$this->resourceName = strtolower(preg_replace('/^M(.*)/','$1', __CLASS__));
		
		return parent::__construct($application);
	}
	
	public function index($options)
	{
		// Shortcut for options
		$o = $options;
		$options['sortBy'] 		= !empty($o['sortBy']) ? $o['sortBy'] : 'publication_date';
		$options['orderBy'] 	= !empty($o['orderBy']) ? $o['orderBy'] : 'DESC';
		
		return parent::index($options);
	}
	
	
	public function retrieve($options)
	{
		// Shortcut for options
		$o = $options;
		$options['sortBy'] 		= !empty($o['sortBy']) ? $o['sortBy'] : 'publication_date';
		$options['orderBy'] 	= !empty($o['orderBy']) ? $o['orderBy'] : 'DESC';
		
		return parent::retrieve($options);
	}

}
?>