<?php

class MTasks extends Model
{
	public function __construct(&$application = null)
	{
		$this->setResource(array('class' => __CLASS__));
		
		return parent::__construct($application);
	}
	
    public function index($options = array())
    {
        $o              = &$options;
        $o['sortBy']    = !empty($o['sortBy']) ? $o['sortBy'] : 'update_date';
        $o['orderBy']   = !empty($o['orderBy']) ? $o['orderBy'] : 'DESC';
        
        return parent::index($o);
    }
}
?>