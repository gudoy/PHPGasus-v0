<?php

class VAbout extends View
{
	public function __construct()
	{
		parent::__construct();
		
		return $this;
	}
	
	
	public function index($options = null)
	{			
		$this->data['view'] = array_merge((array) @$this->data['view'], array(
			'name' 			=> 'about',
			'method' 		=> __FUNCTION__,
			//'cssclasses'	=> 'igourmand',
			'template'		=> 'specific/pages/about/index.tpl',
			'current' 		=> array('menu' => 'about'),
			'cacheId' 		=> 'about',
		));
		
		// Get all apps
		$this->requireControllers(array('CApps','CPlatforms'));
		$this->data['platforms'] 			= CPlatforms::getInstance()->index(array('reindexby' => 'admin_title', 'isUnique' => true));
		$this->data['apps'] 				= CApps::getInstance()->index(array(
			'groupBy' 		=> 'platforms_id', 
			'conditions' 	=> array('is_displayable' => 1), 
			'reindexby' 	=> 'id',
			'sortBy' 		=> 'platforms_id, release_date',
			'orderBy' 		=> 'DESC',
		));
		
		// Then, render page
		return $this->render();
	}
	
	
	public function contact($options = null)
	{
		$this->requireLibs(array('MathCaptcha' => 'security/'));
		
		$this->data['view'] = array_merge((array) @$this->data['view'], array(
			'name' 				=> 'contact',
			'method' 			=> __FUNCTION__,
			//'cssclasses'		=> 'igourmand',
			'template'			=> 'specific/pages/about/contact.tpl',
			'current' 			=> array('menu' => 'contact'),
			'errorsBlock' 		=> false,
			'cache' 			=> false,
		));
		

		// Get all apps
		$this->requireControllers('CApps');
		$this->data['apps'] 				= CApps::getInstance()->index(array(
			'groupBy' 		=> 'platforms_id', 
			'conditions' 	=> array('is_displayable' => 1), 
			'reindexby' 	=> 'id',
			'sortBy' 		=> 'platforms_id, release_date',
			'orderBy' 		=> 'DESC',
		));
		
		if ( !empty($_POST) )
		{
			$this->requireControllers('CContacts');
			$CContacts = new CContacts();
			
			$CContacts->handleContactMail();
			$this->data['success'] = $CContacts->success;
			$this->data['errors'] = $CContacts->errors;
			
			if ( $this->data['success'] ){ $_POST = null; }
		}
		else
		{
			

		}
		//$data['view']['captchaOperation'] = MathCaptcha::create();
		
		if ( !$this->data['success'] ){ $data['view']['captchaOperation'] = MathCaptcha::create(); }
		
//$this->dump('new captcha op');
//$this->dump($data['view']['captchaOperation']);
		
		// Then, render page
		return $this->render();
	}
	
	
};

?>