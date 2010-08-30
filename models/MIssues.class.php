<?php

class_exists('Model') || require(_PATH_LIBS . 'databases/Model_' . _DB_SYSTEM . '.class.php');

class MIssues extends Model
{
	public function __construct($application = null)
	{
		$this->resourceName = strtolower(preg_replace('/^M(.*)/','$1', __CLASS__));
		
		return parent::__construct($application);
	}
	
	public function index($options = array())
	{
		// Set default params
		$o 					= $options;
		$options['sortBy'] 	= !empty($o['sortBy']) ? $o['sortBy'] : 'number';
		$options['orderBy'] = !empty($o['orderBy']) ? $o['orderBy'] : 'DESC';
		
		return parent::index($options);
	}
	
	public function retrieve($options = array())
	{
		// Set default params
		$o 					= $options;
		$options['sortBy'] 	= !empty($o['sortBy']) ? $o['sortBy'] : 'number';
		$options['orderBy'] = !empty($o['orderBy']) ? $o['orderBy'] : 'DESC';
		
		return parent::retrieve($options);
	}

}
?>