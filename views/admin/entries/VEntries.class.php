<?php

class VEntries extends AdminView
{
	public function __construct()
	{
        $this->setResource(array('class' => __CLASS__, 'singular' => 'entry'));
		$this->filePath 		= dirname(__FILE__);
		
		parent::__construct();
		
		return $this;
	}
	
};

?>