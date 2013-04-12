<?php

class VSamples extends View
{
    public function __construct(&$application)
    {
        $this->setResource(array('class' => __CLASS__, 'singular' => 'sample'));
		
		parent::__construct($application);
		
		return $this;
	}
	
};

?>