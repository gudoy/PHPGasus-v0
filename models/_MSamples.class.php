<?php

class MSamples extends Model
{
	public function __construct($application = null)
	{
        $this->setResource(array('class' => __CLASS__, 'singular' => 'sample'));
		
		return parent::__construct($application);
	}
}
?>