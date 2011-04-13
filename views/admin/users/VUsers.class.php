<?php

class VUsers extends AdminView
{
    public function __construct(&$application)
	{
        $this->setResource(array('class' => __CLASS__)); 
		$this->filePath 		= dirname(__FILE__);
		
        parent::__construct($application);
		
		return $this;
	}
	
};

?>