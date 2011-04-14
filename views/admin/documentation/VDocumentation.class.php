<?php

class VDocumentation extends AdminView
{
    public function __construct(&$application)
    {
        //$this->setResource(array('class' => __CLASS__));
		$this->filePath 		= dirname(__FILE__);
		
		parent::__construct($application);
		
		return $this;
	}
	
	public function index()
	{
		$this->render();
	}
	
	public function datatypes()
	{
		$v = &$this->data['view'];
		
		$v['method'] 	= __FUNCTION__;
		$v['template'] 	= 'common/pages/admin/documentation/' . $v['method'] . '.tpl';
//$this->dump($this->data);
		
		$this->render();
	}
	
};

?>