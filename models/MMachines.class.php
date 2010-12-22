<?php

class MMachines extends Model
{
	public function __construct($application = null)
	{
		$this->setResource(array('class' => __CLASS__));
		
		return parent::__construct($application);
	}
    
    /*
    public function index($options = array())
    {
        //$options['conditions'][] = array('uninstall_date','is','null');
        //$options['conditions'][] = array('uninstall_date','!=','0000-00-00 00:00:00');
        
        return parent::index($options);
    }
    
    public function retrieve($options = array())
    {
        //$options['conditions'][] = array('uninstall_date','is','null');
        //$options['conditions'][] = array('uninstall_date','!=','0000-00-00 00:00:00');
        
        
        return parent::retrieve($options);
    }*/

}
?>