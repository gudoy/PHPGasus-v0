<?php

class MBans extends Model
{
	public function __construct($application = null)
	{
        $this->setResource(array('class' => __CLASS__, 'singular' => 'ban'));
		
		return parent::__construct($application);
	}
}
?>