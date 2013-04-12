<?php

class VSamples extends AdminView
{
    public function __construct(&$application)
    {
        $this->setResource(array('class' => __CLASS__, 'singular' => 'sample'));
		
		parent::__construct($application);
		
		return $this;
	}
	
};

?>