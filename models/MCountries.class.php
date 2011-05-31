<?php

class MCountries extends Model
{
	public function __construct($application = null)
	{
        $this->setResource(array('class' => __CLASS__, 'singular' => 'country'));
		
		return parent::__construct($application);
	}
}
?>