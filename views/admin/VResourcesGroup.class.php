<?php

class_exists('AdminView') || require(_PATH_LIBS . 'AdminView.class.php');

class VResourcesGroup extends AdminView
{
	public function __construct()
	{
		parent::__construct();
		
		return $this;
	}
	
	public function index($options = null)
	{
		//$r = $this->dataModel['resources'];
		//$r = $this->data['metas'];
		$r = $this->data['current']['groupResources'];
		
		// Loop over the resources
		foreach ($r as $key => $val)
		{
			$name = is_numeric($key) ? $val : $key;
			
			// Get the resource meta
			$m = $this->data['metas'][$name]; 
			
			// Load its controller
			class_exists($m['controllerName']) || require(_PATH_CONTROLLERS . $m['controllerPath']);
			
			// Instanciate it
			$resController = new $m['controllerName']();
			
			//$this->data['meta'][$name] 	= $this->meta($name);
			$this->data['total'][$name] = $resController->index(array('mode' => 'count'));
			
		}
		
		$this->appSpecifics();
		
		$this->data['view'] = array(
				'name' 					=> 'adminGroupHome',
				'template' 				=> 'pages/admin/common/resourcesGroup/index.tpl',
				'bodyTpl' 				=> 'layouts/bodyAdmin.tpl',
				'css' 					=> array('common', 'admin'),
				'jsKey' 				=> 'adminSpecifics',
		);
			
		$this->render(__FUNCTION__);
	}
	
	
	public function appSpecifics()
	{		
		return $this;
	}
	
	
	public function isItRunning()
	{		
		echo 'ok';
	}
};

?>