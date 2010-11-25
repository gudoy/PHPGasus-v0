<?php

class VAdmin extends AdminView
{
	public function __construct()
	{
		// Deprecated
		//$this->authLevel = array('god','superadmin','admin','contributor');
		
		$this->filePath 		= dirname(__FILE__);
		
		parent::__construct();
		
		return $this;
	}

	public function index($options = null)
	{
		$this->data['view'] = array_merge((array) @$this->data['view'], array(
			//'name' 		=> 'admin',
			'name' 		=> 'adminHome',
			'js' 		=> 'adminHome',
			'method' 	=> __FUNCTION__,
			'template' 	=> 'specific/pages/admin/dashboard/index.tpl',
			'errorsBlock' 	=> false,
		));
		

        $this->resourcesData();
		$this->usersStats();
			
		$this->render();
	}
	
    
    public function resourcesData()
    {
        if ( !defined('_APP_ADMIN_GET_RESOURCES_DATA') || !_APP_ADMIN_GET_RESOURCES_DATA ){ return $this; }
        
        // Loop over the resources
        $r = &$this->dataModel['resources'];
        foreach ( array_keys($r) as $name )
        {
            $cName                      = 'C' . ucfirst($name);                         // Build the controller name
            $$cName                     = new $cName();                                 // Instanciate the controller
            $this->data['total'][$name] = $$cName->index(array('mode' => 'count'));     // Get the resource count
            
        }

        return $this; 
    }
	
    
	public function usersStats()
	{
	    if ( !defined('_APP_ADMIN_GET_USERS_STATS') || !_APP_ADMIN_GET_USERS_STATS ){ return $this; }
        
		$CSessions = new CSessions();
        
		$this->data['usersStats']['connected'] = $CSessions->index(array(
			'sortBy' 		=> 'expiration_time',
			'orderBy' 		=> 'DESC',
			'conditions' 	=> array(
				array('update_date', '>', ( ( !empty($_SERVER['REQUEST_TIME']) ? $_SERVER['REQUEST_TIME'] : time() ) - _APP_SESSION_DURATION ) ),
				
				
			)
		));
		$userIds 						= $CSessions->values('user_id');
		$this->data['connectedUsers'] 	= CUsers::getInstance()->index(array('values' => $userIds, 'reindexby' => 'id'));
        
        return $this; 
	}
	
	/*
     * Deprecated. Used for finder widget on Soundwalk
     */
	public function related($checkAgainstResource = '', $options = null)
	{
		$c 				= strtolower($checkAgainstResource); 			// Shortcut for resource to check against
		$filterValue 	= $this->options['values'];  
		$resources 		= &$this->dataModel['resourcesFields']; 		// Shortcut for resources
		$dmR 			= &$this->dataModel['resources'];
		$this->data 	= array(
			'related' 			=> array(),
			'siblings' 			=> array(),
		);
		
		// Loop over the resources
		foreach ((array) $resources as $rName => $cols)
		{			
			// Loop over their colums
			foreach ((array) $cols as $col => $props)
			{
				if ( $rName === $c && !empty($props['relResource']) )
				{
					$m = $this->meta($props['relResource']);
					
					// Load its controller
					class_exists($m['controllerName']) || require(_PATH_CONTROLLERS . $m['controllerPath']);
					
					// Instanciate it
					$ctrlr = new $m['controllerName']();
					
					// List the fields whe have to get
					$fields2get = array($props['relField'], $col);
					
					$this->data['siblings'][$props['relResource']] = array(
						'meta' 		=> $m,
						'relOn' 	=> $col,
						'relType' 	=> 'sibling',
						'items' => $ctrlr->index(array('values' => !empty($_GET[$col]) ? $_GET[$col] : null, )),
					);
				}
				
				// Only process relResource properties
				if ( empty($props['relResource']) || $props['relResource'] !== $c ) { continue; }
				
				$m = $this->meta($rName);

				// Load its controller
				class_exists($m['controllerName']) || require(_PATH_CONTROLLERS . $m['controllerPath']);
				
				// Instanciate if
				$ctrlr = new $m['controllerName']();

				// List the fields whe have to get
				$fields2get = array($props['relField'], $col);
				
				// If the default name field for this resource should be gotten via a JOIN, we have to add the column on which the join is done
				if ( !empty($m['defaultNameField']) && !isset($resources[$rName][$m['defaultNameField']]) )
				{					
					foreach ((array) $cols as $key => $val)
					{
						if ( empty($val['relGetAs']) || $val['relGetAs'] !== $m['defaultNameField']) { continue; }
								
						array_push($fields2get, $key);
					}
				}
				else { array_push($fields2get, $m['defaultNameField']); } 
				
				// Then build the output data
				$this->data['related'][$rName] = array(
					'meta' => $m,
					'relOn' => $col,
					'relType' => 'child',
					'items' => $ctrlr->index(array(
						'values' 	=> $filterValue, 
						'by' 		=> !empty($filterValue) ? $col : null,
						'getFields' => $fields2get,
					)),
				);
			}
		}

		$this->data['related'] += $this->data['siblings'];
		unset($this->data['siblings']);

		$this->render(__FUNCTION__);
	}

    public function importMachines()
    {
        CMachines::getInstance()->import();   
    }

	
};

?>