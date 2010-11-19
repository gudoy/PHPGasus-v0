<?php

//class_exists('AdminView') || require(_PATH_LIBS . 'AdminView.class.php');

class VSearch extends AdminView
{
	public function __construct()
	{
		//$this->resourceName 	= strtolower(preg_replace('/^V(.*)/','$1', __CLASS__));
		//$this->resourceSingular = 'sample'; // use only if: singular !== (resourceName - "s") 
		$this->filePath 		= dirname(__FILE__);
		
		parent::__construct();
		
		return $this;
	}
	
	public function index()
	{
		$args 			= func_get_args();
		
//var_dump($args);
		
		// search/resourceName/queryString
		$values 		= filter_var($_GET['searchQuery'], FILTER_SANITIZE_STRING);
		$values 		= $this->arrayify($values);
		$machines 		= CMachines::getInstance()->search(array('by' => 'code, number, model', 'values' => $values));
		$users 			= CUsers::getInstance()->search(array('by' => 'firstname,lastname,email', 'values' => $values, 'reindexby' => 'group_name'));
		$clients 		= CClients::getInstance()->search(array('by' => 'name', 'values' => $values));
		$technicians 	= !empty($users['technicians']) ? $users['technicians'] : null;
		$commercials 	= !empty($users['commercials']) ? $users['commercials'] : null; 
		
		$this->data = array_merge($this->data, array(
			'search' => array(
				'query' 	=> $values,
				'criteria' 	=> array(), // TODO
				'groups' 	=> array(
					'machines' 		=> array('results' => $machines, 'resource' => 'machines', 'count' => count($machines)),
					'technicians' 	=> array('results' => $technicians, 'resource' => 'users', 'count' => count($technicians)),
					'commercials' 	=> array('results' => $commercials, 'resource' => 'users', 'count' => count($machines)),
					'clients' 		=> array('results' => $clients, 'resource' => 'clients', 'count' => count($clients)),
				),
				//'count' => 0 // TODO
		)));
//var_dump($values);
//var_dump($machines);

$this->dump($users);

		$this->render();
	}
	
};

?>