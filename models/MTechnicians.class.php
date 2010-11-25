<?php

class MTechnicians extends MUsers
{
	public function __construct($application = null)
	{
		$this->setResource(array('class' => __CLASS__));
		
		parent::__construct($application);
        
        return $this;
	}
    
    /*
    public function handleOptions($options = array())
    {
        $options['conditions'][] = array('groups.admin_title','=','technicians');
        
        return parent::handleOptions($options);
    }*/
    
    public function index($options = array())
    {
        $options['conditions'][] = array('groups.admin_title','=','technicians');
        
        return parent::index($options);
    }

    public function create($options = array())
    {
        $options['conditions'][] = array('groups.admin_title','=','technicians');
        
        return parent::create($options);
    }
    
    public function retrieve($options = array())
    {
        $options['conditions'][] = array('groups.admin_title','=','technicians');
        
        return parent::retrieve($options);
    }

    public function update($options = array())
    {
        $options['conditions'][] = array('groups.admin_title','=','technicians');
        
        return parent::update($options);
    }

    public function delete($options = array())
    {
        $options['conditions'][] = array('groups.admin_title','=','technicians');
        
        return parent::delete($options);
    }
}
?>