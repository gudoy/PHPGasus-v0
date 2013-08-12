<?php

class VAdmin extends AdminView
{
    public function __construct(&$application)
    {	
		parent::__construct($application);
		
		return $this;
	}

	public function index($options = null)
	{
		$this->data['view'] = array_merge((array) @$this->data['view'], array(
			//'name' 		=> 'admin',
			'name' 			=> 'adminHome',
			'js' 			=> 'adminHome',
			'cssid' 		=> 'adminHome',
			'method' 		=> __FUNCTION__,
			'template' 		=> 'specific/pages/admin/dashboard/index.tpl',
			'errorsBlock' 	=> false,
		));
		
        $this->dashboard();
			
		$this->render();
	}
	
	public function search()
	{
		parent::search();
	}
	
	
	public function fixadminlogs()
	{
		//foreach (CAdminlogs::getInstance()->index(array('conditions' => array('slug' => ''))) as $item)
		foreach (CAdminlogs::getInstance()->index() as $item)
		{
			$_POST = array('slug' => $item['action'] . ' ' . $item['resource_name'] . ' ' . @$item['resource_id']);
			CAdminlogs::getInstance()->update(array('isApi' => 1, 'conditions' => array('id' => $item['id']))); 
		}
	}
	
    public function dashboard()
    {
        $this->activity();
        
        $this->handleSearch();
    }
    
    
	public function activity()
	{
		$d = &$this->data;
		
		$d['adminlogs'] 	= CAdminlogs::getInstance()->index(array('limit' => 50, 'sortBy' => 'update_date', 'orderBy' => 'DESC'));
		
		$CSessions 			= new CSessions();
		$ssessions 			= $CSessions->index(array('manualQuery' => "SELECT id FROM ( SELECT * FROM sessions ORDER BY expiration_time DESC) as tmp GROUP BY user_id LIMIT 20"));
		$sIds 				= $CSessions->values('id');
		$d['activeUsers'] 	= $CSessions->index(array('by' => 'id', 'values' => $sIds, 'sortBy' => 'expiration_time', 'orderBy' => 'desc', 'limit' => 20));
	}
	
};

?>