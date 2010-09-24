<?php

//class_exists('Model') || require(_PATH_LIBS . 'databases/Model_' . _DB_SYSTEM . '.class.php');

class MPlatforms extends Model
{
	public function __construct($application = null)
	{
		$this->resourceName = strtolower(preg_replace('/^M(.*)/','$1', __CLASS__));
		
		return parent::__construct($application);
	}
	
	public function index($options)
	{
		$o = $options;
		$options['sortBy'] 		= !empty($o['sortBy']) ? $o['sortBy'] : 'position';
		$options['orderBy'] 	= !empty($o['orderBy']) ? $o['orderBy'] : 'desc';
		
		return parent::index($options);
	}
	
	
	public function retrieve($options)
	{
		$o = $options;
		$options['sortBy'] 		= !empty($o['sortBy']) ? $o['sortBy'] : 'position';
		$options['orderBy'] 	= !empty($o['orderBy']) ? $o['orderBy'] : 'desc';
		
		return parent::retrieve($options);
	}

}
?>