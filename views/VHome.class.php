<?php

class VHome extends View
{
	public function __construct(&$application)
	{
		parent::__construct($application);
		
		return $this;
	}
	
	
	public function index($options = null)
	{		
		$this->data['view'] = array_merge((array) @$this->data['view'], array(
			'name' 			=> 'home',
			'method' 		=> __FUNCTION__,
			'template'		=> 'specific/pages/home/' . __FUNCTION__ . '.tpl',
		));
		
		// Then, render page
		return $this->render();
	}
	

	/**
	 * This function builds the 404 page/block
	 * (depending of how the function is called, using tplSelf or not)
	 * 
	 * @author Guyllaume Doyer <guyllaume@clicmobile.com>
	 * @return null
	 */
	 public function _404()
	 {
		//$this->statusCode(404);
		
		$this->data['view'] = array_merge((array) @$this->data['view'], array(
			'name' 			=> __FUNCTION__,
			'method' 		=> __FUNCTION__,
			'template'		=> 'specific/pages/home/' . __FUNCTION__ . '.tpl',
		));
		
		// Then, render page
		return $this->render();
	 }
	 
	 
	 public function maintenance()
	 {
		$this->data['view'] = array_merge((array) @$this->data['view'], array(
			'name' 			=> __FUNCTION__,
			'method' 		=> __FUNCTION__,
			'template'		=> 'specific/pages/home/' . __FUNCTION__ . '.tpl',
		));
		
		// Then, render page
		return $this->render();
	 }
	 
	 
	 public function down()
	 {
		$this->data['view'] = array_merge((array) @$this->data['view'], array(
			'name' 			=> __FUNCTION__,
			'method' 		=> __FUNCTION__,
			'template'		=> 'specific/pages/home/' . __FUNCTION__ . '.tpl',
		));
		
		// Then, render page
		return $this->render();
	 }
	
};

?>