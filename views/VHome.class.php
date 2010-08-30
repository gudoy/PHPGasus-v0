<?php

class VHome extends View
{
	public function __construct()
	{
		parent::__construct();
		
		return $this;
	}
	
	
	public function index($options = null)
	{		
		$this->data['view'] = array_merge((array) @$this->data['view'], array(
			'name' 			=> 'home',
			'method' 		=> __FUNCTION__,
			'template'		=> 'specific/pages/home/index.tpl',
		));
		
		
		// Then, render page
		return $this->render();
	}
	
};

?>