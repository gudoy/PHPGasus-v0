<?php

class MSessions extends Model
{
	public function __construct($application = null)
	{
		$this->resourceName = strtolower(preg_replace('/^M(.*)/','$1', __CLASS__));
		
		return parent::__construct($application);
	}
	
	public function index($options)
	{
		$o = $options;
		$options['by'] 		= !empty($o['by']) ? $o['by'] : 'name';
		
		return parent::index($options);
	}

	public function update($resourceData = null, $options = array())
	{
		$o = $options;
		$options['by'] 		= !empty($o['by']) ? $o['by'] : 'name';
		
		return parent::update($resourceData, $options);
	}
	
	public function delete($options)
	{
		$o = $options;
		$options['by'] 		= !empty($o['by']) ? $o['by'] : 'name';
		
		return parent::delete($options);
	}
	
	public function retrieve($options)
	{
		$o = $options;
		$options['by'] 		= !empty($o['by']) ? $o['by'] : 'name';
		
		return parent::retrieve($options);
	}

}
?>