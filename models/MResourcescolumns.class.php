<?php

class MResourcescolumns extends Model
{
	public function __construct($application = null)
	{
        $this->setResource(array('class' => __CLASS__, 'singular' => 'resourcescolumn'));
		
		return parent::__construct($application);
	}
}
?>