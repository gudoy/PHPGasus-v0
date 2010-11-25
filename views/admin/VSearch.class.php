<?php

//class_exists('AdminView') || require(_PATH_LIBS . 'AdminView.class.php');

class VSearch extends AdminView
{
	public function __construct()
	{ 
		$this->filePath 		= dirname(__FILE__);
		
		parent::__construct();
		
		return $this;
	}
	
	public function index()
	{
		$args 			= func_get_args();
        $critereria     = array();

        // $criteria = array(
        //      'type'          => passed type || 'or',
        //      'resource'      => passed resource || current resource,
        //      'columns'       => passed columns || resource.searchableColumns,
        //      'operator'      => '=',
        //      'values'        => array(),
        //);
        
        // TODO: handle this dynamically. Use extended dataModel
        /*
        $searchable = array(
            array('resource' => 'machines', 'columns' => array('code', 'number', 'model')),
            array('resource' => 'users', 'columns' => array('firstname','lastname','email')),
        )*/
        
		
		// search/resourceName/queryString
		$sQuery 		= filter_var($_GET['searchQuery'], FILTER_SANITIZE_STRING);
		$values 		= $this->arrayify($sQuery);
        
        // TODO: handle search query properly
        $tmpCriteria    = explode(',', $sQuery);
        $criteria       = !empty($sQuery) ? $criteria + array(
            array('type' => 'or', 'resources' => 'machines', 'columns' => array('code', 'number', 'model'), 'operator' => '=', 'values' => $values),
            array('type' => 'or', 'resources' => 'technicians', 'columns' => array('firstname','lastname','email'), 'operator' => '=', 'values' => $values),
            array('type' => 'or', 'resources' => 'commercials', 'columns' => array('firstname','lastname','email'), 'operator' => '=', 'values' => $values),
        ) : $critera;
        
        // TODO: handle dynamicaly
		$machines 		= CMachines::getInstance()->search(array('by' => 'code, number, model', 'values' => $values));
		$users 			= CUsers::getInstance()->search(array('by' => 'firstname,lastname,email', 'values' => $values, 'reindexby' => 'group_name'));
		$clients 		= CClients::getInstance()->search(array('by' => 'name', 'values' => $values));
		$technicians 	= !empty($users['technicians']) ? $users['technicians'] : null;
		$commercials 	= !empty($users['commercials']) ? $users['commercials'] : null;		
        $counts         = array(
            'machines'      => is_array($machines) ? count($machines) : 0,
            'technicians'   => is_array($technicians) ? count($technicians) : 0,
            'commercials'   => is_array($commercials) ? count($commercials) : 0,
            'clients'       => is_array($clients) ? count($clients) : 0,
        );
        
		$this->data = array_merge($this->data, array(
			'search' => array(
				'query' 	=> $sQuery,
				'criteria' 	=> array(), // TODO
				'groups' 	=> array(
					'machines' 		=> array('results' => $machines, 'resource' => 'machines', 'count' => $counts['machines']),
					'technicians' 	=> array('results' => $technicians, 'resource' => 'users', 'count' => $counts['technicians']),
					'commercials' 	=> array('results' => $commercials, 'resource' => 'users', 'count' => $counts['commercials']),
					'clients' 		=> array('results' => $clients, 'resource' => 'clients', 'count' => $counts['clients']),
				),
				'totalResults' => array_sum($counts),
		)));
//var_dump($values);
//var_dump($machines);

$this->dump($users);

		$this->render();
	}
	
};

?>