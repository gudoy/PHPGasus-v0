<?php

//class_exists('View') || require(_PATH_LIBS . 'View.class.php');

class VSite extends View
{
    public function __construct(&$application)
    {
        parent::__construct($application);
        
        return $this;
    }
    
	/**
	 * This function builds the 404 page/block
	 * (depending of how the function is called, using tplSelf or not)
	 * 
	 * @author Guyllaume Doyer <guyllaume@clicmobile.com>
	 * @return null
	 */
	 public function error404()
	 {
		//header("HTTP/1.0 404 Not Found");
		$this->statusCode(404);
		
		$this->data['view']['name'] 	= 'error404';
		$this->data['view']['template'] = 'pages/site/404.tpl';
		
		// Then, render page
		return $this->render();
	 }
	 
	 
	/**
	 * This function builds the site in maintenance page/block
	 * (depending of how the function is called, using tplSelf or not)
	 * 
	 * @author Guyllaume Doyer <guyllaume@clicmobile.com>
	 * @return null
	 */
	 public function maintenance()
	 {
		$this->data['view']['name'] 	= 'maintenance';
		$this->data['view']['template'] = 'pages/site/maintenance.tpl';
		
		// Then, render page
		return $this->render();
	 }
	 
	 
	/**
	 * This function builds the site down page/block
	 * (depending of how the function is called, using tplSelf or not)
	 * 
	 * @author Guyllaume Doyer <guyllaume@clicmobile.com>
	 * @return null
	 */
	 public function down()
	 {
		$this->data['view']['name'] 	= 'maintenance';
		$this->data['view']['template'] = 'pages/site/down.tpl';
		
		// Then, render page
		return $this->render();
	 }
};
?>
