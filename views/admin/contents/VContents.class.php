<?php

class VContents extends AdminView
{
	public function __construct()
	{
        $this->setResource(array('class' => __CLASS__)); 
		$this->filePath 		= dirname(__FILE__);
		
		parent::__construct();
		
		return $this;
	}
	
};

?>