<?php

class MAppsplatforms extends Model
{
	public function __construct($application = null)
	{
        $this->setResource(array('class' => __CLASS__));
		
		return parent::__construct($application);
	}
}
?>