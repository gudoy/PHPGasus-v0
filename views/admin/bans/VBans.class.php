<?php

class VBans extends AdminView
{
    public function __construct(&$application)
    {
        $this->setResource(array('class' => __CLASS__, 'singular' => 'ban'));
		
		parent::__construct($application);
		
		return $this;
	}
	
};

?>