<?php

class VGroups extends AdminView
{
    public function __construct(&$application)
	{
        $this->setResource(array('class' => __CLASS__));
		
        parent::__construct($application);
		
		return $this;
	}
	
};

?>