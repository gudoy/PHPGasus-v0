<?php

class VApiclients extends AdminView
{
	public function __construct()
	{
		$this->resourceName 	= strtolower(preg_replace('/^V(.*)/','$1', __CLASS__));
		$this->filePath 		= dirname(__FILE__);
		
		parent::__construct();
		
		return $this;
	}
	
};

?>