<?php

class VSearch extends AdminView
{
    public function __construct(&$application)
    {
        //$this->setResource(array('class' => __CLASS__));
        $this->filePath         = dirname(__FILE__);
		
		parent::__construct($application);
		
		return $this;
	}
	
	public function index()
	{        
	    //$this->Events->register('onBeforeSearchMachines', array('class' => &$this, 'method' => 'filtersMachines'));
        
		$this->handleSearch();
        
        return $this->render();
	}
    
};

?>