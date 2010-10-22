<?php

class VAdmin extends AdminView
{
	public function __construct()
	{
		$this->authLevel = array('god','superadmin','admin','contributor');
		
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
		
		// Loop over the resources
		$r = $this->data['current']['groupResources'];
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
		
		$this->usersStats();
		
//$this->dump($this->data);
			
		$this->render();
	}
	
	
	public function usersStats()
	{
		$CSessions = new CSessions();
		$this->data['usersStats']['connected'] = $CSessions->index(array(
			'sortBy' 	=> 'expiration_time',
			'orderBy' 	=> 'DESC',
			'conditions' 	=> array(
				//array('expiration_time', '>', ("FROM_UNIXTIME('" . time() . "')")),
				//array('update_date', '>', ("FROM_UNIXTIME('" . (time() - _APP_SESSION_DURATION) . "')")),
				//array('update_date', '>', (time() - _APP_SESSION_DURATION)),
				array('update_date', '>', ( ( !empty($_SERVER['REQUEST_TIME']) ? $_SERVER['REQUEST_TIME'] : time() ) - _APP_SESSION_DURATION ) ),
				
				
			)
		));
		$userIds 						= $CSessions->values('user_id');
		$this->data['connectedUsers'] 	= CUsers::getInstance()->index(array('values' => $userIds, 'reindexby' => 'id')); 
	}
	
	
	public function related($checkAgainstResource = '', $options = null)
	{
		$c 				= strtolower($checkAgainstResource); 			// Shortcut for resource to check against
		$filterValue 	= $this->options['values'];  
		$resources 		= $this->dataModel['resourcesFields']; 		// Shortcut for resources
		$dmR 			= $this->dataModel['resources'];
		$this->data 	= array(
			'related' 			=> array(),
			'siblings' 			=> array(),
		);
		
		// Loop over the resources
		foreach ((array) $resources as $rName => $cols)
		{			
			// Loop over their colums
			foreach ((array) $cols as $cName => $props)
			{
				if ( $rName === $c && !empty($props['relResource']) )
				{
					$m = $this->meta($props['relResource']);
					
					// Load its controller
					class_exists($m['controllerName']) || require(_PATH_CONTROLLERS . $m['controllerPath']);
					
					// Instanciate it
					$ctrlr = new $m['controllerName']();
					
					// List the fields whe have to get
					$fields2get = array($props['relField'], $cName);
					
					$this->data['siblings'][$props['relResource']] = array(
						'meta' 		=> $m,
						'relOn' 	=> $cName,
						'relType' 	=> 'sibling',
						'items' => $ctrlr->index(array('values' => !empty($_GET[$cName]) ? $_GET[$cName] : null, )),
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
				$fields2get = array($props['relField'], $cName);
				
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
					'relOn' => $cName,
					'relType' => 'child',
					'items' => $ctrlr->index(array(
						'values' 	=> $filterValue, 
						'by' 		=> !empty($filterValue) ? $cName : null,
						'getFields' => $fields2get,
					)),
				);
			}
		}

		$this->data['related'] += $this->data['siblings'];
		unset($this->data['siblings']);

		$this->render(__FUNCTION__);
	}

	
};

?>