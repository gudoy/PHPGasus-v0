<?php

//class_exists('AdminView') || require(_PATH_LIBS . 'AdminView.class.php');

class VCollections extends AdminView
{
	public function __construct()
	{
		$this->resourceName 	= strtolower(preg_replace('/^V(.*)/','$1', __CLASS__));
		//$this->resourceSingular = 'sample'; // use only if: singular !== (resourceName - "s") 
		$this->filePath 		= dirname(__FILE__);
		
		parent::__construct();
		
		return $this;
	}
	
};

?>