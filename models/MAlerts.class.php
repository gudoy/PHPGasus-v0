<?php

//class_exists('Model') || require(_PATH_LIBS . 'databases/Model_' . _DB_SYSTEM . '.class.php');

class MSamples extends Model
{
	public function __construct($application = null)
	{
		$this->setResource(array('class' => __CLASS__));
		
		return parent::__construct($application);
	}

}
?>