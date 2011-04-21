<?php

class AdminView extends View
{
	//protected 	$debug 	= false;
	var $resourceGroupName = null;
	
    public function __construct(&$application)
	{
		//$this->log(__METHOD__);
		
		// User levels authorized to access the current view (overload in proper view(s) for specific authorizations
		$this->authLevel 			= !empty($this->authLevel) ? $this->authLevel : array('god','superadmin','admin');
		$this->authFailureRedirect 	= _URL_ADMIN;
		
		isset($dataModel) || include(_PATH_CONFIG . 'dataModel.php');
		
        // TODO: used? double bloom with $this->data['_resources'] & $this->data['resourcesFields']?
		$this->dataModel = array(
			'resources' 		=> &$resources,
			'resourcesFields' 	=> &$dataModel,
			//'resourceGroups' 	=> $resourceGroups,
		);
		
		parent::__construct($application);
		
		$this
			->requireLogin()								// Require that the user is logged
			->requireAuth(); 								// and has admin rights for the current view
		
		$this->data['meta'] = !empty($this->resourceName) ? $this->meta($this->resourceName) : null;
		
		// Try to get the current admin group and base path
		if ( !empty($this->filePath) )
		{
			$pos 							= isset($this->resourceName) 
												? strpos($this->filePath, (!empty($this->data['meta']['ancestorsPath']) ? $this->data['meta']['ancestorsPath'] : $this->data['meta']['name'] )) 
												: false;
			$tmp 							= explode('/', str_replace(_PATH_VIEWS, '', ($pos ? substr($this->filePath, 0, $pos-1) : $this->filePath)));
			$tmpGroupName 					= $tmp[count((array) $tmp)-1];
			$this->resourceAdminBasePath 	= join('/', $tmp) . (!empty($tmp) ? '/' : '');
			//$this->resourceGroupName 		= !empty($tmpGroupName) && !empty($resourceGroups) && !empty($resourceGroups[$tmpGroupName]) ? $tmpGroupName : '';
		}
		
		// Get the metadata for each of the resources of the current admin group (or all resources if no groups defined)
		//$this->data['current']['groupResources'] = !empty($this->resourceGroupName) ? $resourceGroups[$tmpGroupName]['resources'] : $this->dataModel['resources'];
		
		
		// TODO: remove when no longer needed for backward compat
		if ( !defined('_APP_USE_ADMIN_METAS') || _APP_USE_ADMIN_METAS )
        {
            // Deprecated
            // Compute the metadata for each of the resources
            foreach((array) $this->dataModel['resources'] as $key => $val)
            {
                $rName                          = is_numeric($key) ? $val : $key;
                $this->data['metas'][$rName]    = $this->meta($rName);
            }            
        }		
		
        // TODO: clean this
		$this->data = array_merge($this->data, array(
			'dataModel' 			=> &$this->dataModel['resourcesFields'], // TODO: deprecate in favor of _colums
			//'_dataModel'          	=> &$this->dataModel['resourcesFields'],
			'_resources'             => &$this->dataModel['resources'],
			'_resourcesGroups'       => &$_resourcesGroups,
		));
		
//$this->dump($this->data);
		
		return $this;
	}
	
	public function configSmarty()
	{
        $this->log(__METHOD__);
        
		parent::configSmarty();
		
        // TODO: really needed?
		// Force cache disabling in admin
		$this->Smarty->caching = 0;
		
		return $this;
	}
	
	
	public function meta($resourceName = null)
	{
        $this->log(__METHOD__);
        
		if ( empty($resourceName) ){ return array(); }
		
		$r 							= &$resourceName;
		$dmR 						= &$this->dataModel['resources'];
		//$dmGp						= &$this->dataModel['resourceGroups'];
		
		$m 							= array();
		$m['name'] 					= $r;
		$m['displayName'] 			= !empty($dmR[$r]['displayName']) ? $dmR[$r]['displayName'] : $m['name'];
		$m['singular'] 				= $dmR[$r]['singular'];
		$m['hasAncestors'] 			= !empty($dmR[$r]['childOf']);
		$m['hasChildren'] 			= !empty($dmR[$r]['children']);
		$m['hasParentGroups'] 		= !empty($dmR[$r]['parentGroups']);
		$m['mainParentGroup'] 		= $m['hasParentGroups'] ? $dmR[$r]['parentGroups'][0] : '';
		//$m['parent'] 				= $m['hasAncestors'] ? $dmR[$r]['childOf'][0] : array();
		$m['parent'] 				= $m['hasAncestors'] ? $dmR[$r]['childOf'][0] : '';
		$m['parentSingular']		= !empty($m['parent']) ? $dmR[$m['parent']]['singular'] : '';
		// TODO: get ancestors recursively (parent and parent or parent, ...)
		$m['ancestors'] 			= $m['hasAncestors'] ? $dmR[$r]['childOf'] : array();
		$m['children'] 				= $m['hasChildren'] ? $dmR[$r]['children'] : array();
		// TODO: get clean recursively (remove parent and parent or parent, ...) ???
		$m['shortname'] 			= $m['hasAncestors'] ? str_replace($m['parentSingular'], '', $r) : $m['name'];
		$m['ancestorsPath'] 		= $m['hasAncestors'] ? join('/', $m['ancestors']) : '';
		$m['shortPath'] 			= $m['ancestorsPath'] . (!empty($m['ancestorsPath']) ? '/' : '')  . $m['shortname'];
		$m['fullAdminPath'] 		= _URL_ADMIN . ( !empty($m['mainParentGroup']) ? $m['mainParentGroup'] . '/' : '' ) . $m['shortPath'] . '/';
		//$m['breadcrumbs'] 			= array_merge($m['ancestors'], array($m['name']));
		$m['breadcrumbs'] 			= !empty($this->resourceGroupName) 
										? array_merge(array($this->resourceGroupName), $m['ancestors'], array($m['name']))
										: array_merge($m['ancestors'], array($m['name']));
		$m['controllerName'] 		= 'C' . ucfirst($m['name']);
		$m['controllerFilename'] 	= 'C' . ucfirst($m['shortname']) . '.class.php';
		$m['controllerPath'] 		= ( $m['hasAncestors'] ? join('/', $m['ancestors']) . '/' : ( $m['hasChildren'] ? $m['name'] . '/' : '' ) ) . $m['controllerFilename'];
		$m['defaultNameField'] 		= !empty($dmR[$r]['defaultNameField']) ? $dmR[$r]['defaultNameField'] : '';
		$m['crudability'] 			= !empty($dmR[$r]['crudability']) ? $dmR[$r]['crudability'] : 'CRUD';
		
		return $m;
	}
		
	
	public final function requireAuth($options = null)
	{
		$this->log(__METHOD__);
		
		// Shortcut for options
		$o 						= &$options;
		
		// 
		$o['authLevel'] 		= !empty($o['authLevel']) ? $o['authLevel'] : ( isset($this->authLevel) ? $this->authLevel : null );
		$o['authLevel'] 		= !empty($o['authLevel']) && !is_array($o['authLevel']) ? (array) $o['authLevel'] : $o['authLevel'];
		$o['failureRedirect'] 	= !empty($o['redirection']) ? $o['redirection'] : ( isset($this->authFailureRedirect) ? $this->authFailureRedirect : _URL_HOME );
        
        $knownActions   = array('display','create','retrieve','update','delete','search');    // List of knowns auth
		
		$curURL 		= $this->currentURL();
		$t 				= parse_url($curURL); 
		$redir 			= $t['scheme'] . '://' . $t['host'] . $t['path'] . ( !empty($t['query']) ? urlencode('?' . $t['query']) : '') . (!empty($t['fragment']) ? $t['fragment'] : '');
		
		// Get the user id
		$uid = !empty($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
		
		// If no user id is found, redirect to login
		if ( empty($uid) )
		{			
			$auth 		= false;
			$redir		= _URL_LOGIN . ( strpos($redir, '?') !== false ? '&' : '?' ) . 'errors=10101';
			$this->redirect($redir);
		}
		else
		{
            // Get the user data
            $u              = CUsers::getInstance()->retrieve(array('values' => $uid));
            
            # Get user credentials
            $gids           = !empty($u['group_ids']) ? $u['group_ids'] : array();          // Get user group ids
            $opts           = array('by' => 'group_id', 'values' => $gids);                 // Set options
            $gpsAuths       = CGroupsauths::getInstance()->index($opts);                        // Try to get user groups auth
            //$actionAuths    = array();                                                        // Init user auths actions indexed array
            
            // Can the user access the admin
            $ugps           = !empty($u['group_admin_titles']) ? explode(',', $u['group_admin_titles']) : array();
            $isGod          = in_array('gods', $ugps);
            $u['auths']     = array(
                '__can_access_admin' => $isGod || in_array('superadmins', $ugps) || in_array('admins', $ugps) 
            );
            $uAuths         = &$u['auths'];                                                      


            // Gods are allmighty
            if ( $isGod )
            {
                $resList = array_keys($this->dataModel['resources']);
                
                foreach ($knownActions as $action)
                {
                    $cN             = '__can_' . $action;
                    
                    // Do not handle search action auths here
                    if ( $action === 'search' ){ $uAuths[$cN] = array(); continue; }
                    
                    $uAuths[$cN]    = $resList;
                }
                
                foreach ( $resList as $rName )
                {
                    foreach ($knownActions as $action)
                    {
                        $aN                     = 'allow_' . $action;       // Shortcut for auth name 
                        $uAuths[$rName][$aN]    = true;                     // Update the auth for the current resource
                    } 
                    
                    // Special case for search action that should be allowed if retrieve action is allowed
                    // AND if the resource is searchable
                    if ( $action === 'search' && !empty($this->dataModel['resources'][$rName]['searchable']) )
                    {
                        $uAuths[$rName][$aN]        = true;
                        $uAuths['__can_search'][]   = $rName;
                    }
                }
            }
            else
            {
                // Loop over the group auths
                foreach ( (array) $gpsAuths as $gpAuths )
                {
                    // Shortcut for the group auth resource name
                    $rName              = !empty($gpAuths['resource_name']) ? $gpAuths['resource_name'] : null;
                    
                    // Do not continue if the resource name has not been found 
                    if ( empty($rName) ) { continue; }
                    
                    // Loop over the known auths
                    foreach ($knownActions as $action)
                    {
                        $aN                     = 'allow_' . $action;                              // Shortcut for auth name
                        $cN                     = '__can_' . $action;                              // Shortcut for auth resources list for the current action 
                        $uAuths[$rName][$aN]    = isset($gpAuths[$aN]) && $gpAuths[$aN] == true;    // Update the auth for the current resource
                        $uAuths[$cN]            = !isset($uAuths[$cN]) ? array() : $uAuths[$cN];
                        
                        if ( !empty($gpAuths[$aN]) ) { $uAuths[$cN][] = $rName; }
                        
                        // Special case for search action that should be allowed if retrieve action is allowed
                        // AND if the resource is searchable                        
                        if ( $action === 'search' && $uAuths[$rName]['allow_retrieve'] && !empty($this->dataModel['resources'][$rName]['searchable']) )
                        {
                            $uAuths[$rName][$aN]    = true;
                            $uAuths[$cN][]          = $rName;
                        }
                    }
                }
            }

            $match  = !empty($uAuths['__can_access_admin']) && ( empty($this->resourceName) || in_array($this->resourceName, $uAuths['__can_display']) );            
        }
		
		// Store the current user, after having remove sensitive data (password, .... ?)
		// TODO: find a way to clean this properly (calling something like a cleanSensitive function???)
		unset($u['password']);
		$this->data['current']['user'] = $u;

//var_dump($this->data['current']['user']);

		// TODO: redirect + notify ('you dont have credentials to access this area')???
		$redir = $o['failureRedirect'];
		$redir .= ( strpos($redir, '?') !== false ? '&' : '?' ) . 'errors=9000';
		return !$match ? $this->redirect($redir) : true;
	}
	
    
    public function handleSearch()
    {
        $this->log(__METHOD__);
        
        $args           = func_get_args();
        $criteria       = array();                                                  // Initialise search criteria array
        $searchable     = array();                                                  // Initialise searchable resources array
        $s              = &$this->data['search'];                                   // Shortcut for search data
        $s['type']      = isset($this->resourceName) 
                            && ( !defined('_APP_SEARCH_ALWAYS_GLOBAL') || !_APP_SEARCH_ALWAYS_GLOBAL ) 
                          ? 'contextual' : 'global';
        //$s['type']      = $sType;

        // $criteria = array(
        //      'type'          => passed type || 'or',
        //      'resource'      => passed resource || current resource,
        //      'columns'       => passed columns || resource.searchableColumns,
        //      'operator'      => '=',
        //      'values'        => array(),
        //);
        
        // Handle URIs like
        // search/{resourceName}/{queryString} 
        // search/{resourceName}?method=search&queryString={$queryString}
        $sQuery         = !empty($_GET['searchQuery']) ? filter_var($_GET['searchQuery'], FILTER_SANITIZE_STRING) : null;
        $values         = Tools::toArray($sQuery);
        $s['query']     = $sQuery;
        
        // Do not continue if no search query has been found
        if ( empty($sQuery) ){ return $this; }
        //if ( empty($sQuery) ){ return $s['type'] === 'contextual' ? $this->index($args) : $this; }
        //if ( empty($sQuery) ){ return $s['type'] === 'contextual' ? $this->index(array('dispatch' => false)) : $this; }
        //if ( empty($sQuery) ){ return $s['type'] === 'contextual' ? $this->C->index($this->options) : $this; }
        
        // If the search is contextual, just use the current resource
        // Otherwise, use the resources that the current user is allowed to display 
        //$rList          = $s['type'] === 'contextual' ? array($this->resourceName) : array_keys($this->data['_resources']);
        $rList          = $s['type'] === 'contextual' 
                            ? array($this->resourceName) 
                            : !empty($this->data['current']['user']['auths']['__can_display'])
                                ? $this->data['current']['user']['auths']['__can_display'] 
                                //? array_intersect(array_keys($this->data['_resources']), $this->data['current']['user']['auths']['__can_display']) 
                                : array();
        
        // Get searchable resources and searchable colums for each one of them
        foreach ( $rList as $resource )
        {
            // For contextual search
            //if ( empty($this->data['_resources'][$resource]['searchable']) ){ continue; }
            if ( $s['type'] === 'global' && empty($this->data['_resources'][$resource]['searchable']) ){ continue; }
            
            $r              = &$resource;                       // Shortcut for the current resource name
            $rModel         = &$this->data['dataModel'][$r];    // Shortcut for the current resource model
            $sCols          = array();                          // Initialise the searchable colums array for the current resource
            
            // Loop ovet the resource columns
            foreach( array_keys((array) $rModel) as $column )
            {
                if ( empty($rModel[$column]['searchable']) ) { continue; }
                
                // Add the column to the searchable ones
                $sCols[] = $column;
            }
            
            $searchable[$r] = array( 'resource' => $r, 'columns' => $sCols, );
        }
        
        $this->events->trigger('onBeforeSearch', array('source' => array('class' => __CLASS__, 'method' => __FUNCTION__)));
        
        // First case, contextual search on a defined resource
        if ( $s['type'] === 'contextual' )
        {
            $rName          = $this->resourceName;

            $this->events->trigger('onBeforeSearch' . ucfirst($rName), array('source' => array('class' => __CLASS__, 'method' => __FUNCTION__)));

            // Get searchable cols for the current resource
            $cols       = !empty($searchable[$rName]['columns']) ? $searchable[$rName]['columns'] : array();
            
            $colsCount  = count($cols);
            $i          = 0;
            foreach ($cols as $col)
            {
                //$cond                           = array($col,'contains',$s['query'],'or');
                $cond                           = array($col,'contains',$s['query']);
                
                # Handle parenthesis wrappers for 'OR' conditions
                if 		( $i === 0 && $colsCount = 1 ) 	{ $cond[] = ''; }
				elseif 	( $i === 0 && $colsCount > 1 )  { $cond[] = ''; $cond[] = 'first'; }
				else if ( $i === $colsCount-1 )     	{ $cond[] = 'or'; $cond[] = 'last'; }
				else                                	{ $cond[] = 'or'; }
                
                $this->options['conditions'][]  = $cond;
                $i++;
            }
            
            // Get results with the search criteria
            $results = $this->C->index($this->options);
            
            $curURL     = $this->currentURL();

            // Set output data      
            $this->data = array_merge($this->data, array(
                $this->resourceName     => $results,
                'success'               => $this->C->success, 
                'errors'                => $this->C->errors,
                'warnings'              => $this->C->warnings,
                'current'               => array_merge($this->data['current'], array(
                    'url'                       => $curURL,
                    'urlParams'                 => Tools::getURLParams($curURL),
                    'offset'                    => $this->options['offset'],
                    'limit'                     => $this->options['limit'],
                    'sortBy'                    => $this->options['sortBy'],
                )),
                'total'                 => array(
                    $this->resourceName     => $this->C->index(array_merge($this->options, array('mode' => 'count'))),
                ),
                'search' => array_merge($s, array(
                    'allowed'       => !empty($cols),
                    //'criteria'  => array(), // TODO
                    'totalResults'  => count($results),
                )),
            ));
            
//var_dump($results);
        }
        // Second case, global search on every searchable resource on every searchable columns
        else
        {
            // Instanciate searchable resources and get search results for each one of them
            foreach ( array_keys($searchable) as $rName )
            {
                $cName  = 'C' . ucfirst($rName);            // Build controller name
                $$cName = new $cName();                     // Instanciate controller
                
                $cols   = $searchable[$rName]['columns'];     // Get searchable cols for the current resource
                $this->options['conditions'] = array();     // Force conditions to be empty (only handle search conditions)
                
                $colsCount  = count($cols);
				
				// Do not continue if there's no col to search against
				if ( $colsCount === 0 ) { continue; }
				
                $i          = 0;
	            foreach ($cols as $col)
	            {
	                //$cond                           = array($col,'contains',$s['query'],'or');
	                $cond                           = array($col,'contains',$s['query']);
	                
	                # Handle parenthesis wrappers for 'OR' conditions
	                if 		( $i === 0 && $colsCount = 1 ) 	{ $cond[] = ''; }
					elseif 	( $i === 0 && $colsCount > 1 )  { $cond[] = ''; $cond[] = 'first'; }
					else if ( $i === $colsCount-1 )     	{ $cond[] = 'or'; $cond[] = 'last'; }
					else                                	{ $cond[] = 'or'; }
	                
	                $this->options['conditions'][]  = $cond;
	                $i++;
	            }
             
                $this->events->trigger('onBeforeSearch' . ucfirst($rName), array('source' => array('class' => __CLASS__, 'method' => __FUNCTION__)));

                $count      = $$cName->index(array_merge($this->options, array('mode' => 'count')));
                //$results    = $$cName->search(array_merge($this->options, array('limit' => '-1')));
                $results    = $$cName->search(array_merge($this->options, array('limit' => 25)));
                //$count      = count($results);
                
//var_dump($rName . ' count: ' . $count);
                
                $s['groups'][$rName] = array(
                    'results'   => $results,
                    'resource'  => $rName,
                    'count'     => $count,
                );
                $s['totalResults'] = (isset($s['totalResults']) ? $s['totalResults'] : 0) + $count;
                
                // If the current resource is the current one (in case of global search on a resource page)
                if ( !empty($this->resourceName) && $rName === $this->resourceName && empty($this->data[$rName]) )
                {
                    //$this->data[$rName] = &$results;
                    // Set output data                         
                    $this->data[$rName] = $results;
                }
            }

            $curURL     = $this->currentURL();
            
            /*
            $this->data = array_merge($this->data, array(
                'current'               => array_merge($this->data['current'], array(
                    'url'                       => $curURL,
                    'urlParams'                 => Tools::getURLParams($curURL), 
                    'offset'                    => $this->options['offset'],
                    'limit'                     => $this->options['limit'],
                    'sortBy'                    => $this->options['sortBy'],
                )),
                //'total'                 => array(
                //    $this->resourceName     => $this->C->index(array_merge($this->options, array('mode' => 'count'))),
                //),
                //'search' => array_merge($s, array(
                    //'query'         => $sQuery,
                    //'criteria'  => array(), // TODO
                    //'totalResults' => count($results),
                //)),
            ));
            */

            /*
            // TODO: handle search query properly
            $tmpCriteria    = explode(',', $sQuery);
            $criteria       = !empty($sQuery) ? $criteria + array(
                array('type' => 'or', 'resources' => 'machines', 'columns' => array('code', 'number', 'model'), 'operator' => '=', 'values' => $values),
                array('type' => 'or', 'resources' => 'technicians', 'columns' => array('firstname','lastname','email'), 'operator' => '=', 'values' => $values),
                array('type' => 'or', 'resources' => 'commercials', 'columns' => array('firstname','lastname','email'), 'operator' => '=', 'values' => $values),
            ) : $critera;
            */
        }
        
//$this->dump($this->data['search']);
        
        $hasRes     = !empty($s['totalResults']);
        $evtName    = 'onSearchReturned' . ($hasRes ? '' : 'no') . 'results'; // onSearchReturned or onSearchReturnedNoResults
        
        $this->events->trigger($evtName, array('source' => array('class' => __CLASS__, 'method' => __FUNCTION__)));
        $this->events->trigger('onAfterSearch', array('source' => array('class' => __CLASS__, 'method' => __FUNCTION__)));
        
        return $this;
    }
    
    
    public function search()
    {
        $this->log(__METHOD__);
        
        $this->handleSearch();
        
        if ( $this->data['search']['type']['contextual'] ){ $this->data['view']['template'] = 'specific/pages/admin/resource/search.tpl'; }
        
        $this->handleRelations();
        $this->beforeRender(array('function' => __FUNCTION__));
            
        return $this->render();
    }
    
	
	public function index()
	{
        $this->log(__METHOD__);
        
		$args 	= func_get_args();
		$p    	= !empty($args[0]) && is_array($args[0]) ? $args[0] : array();
		$p 		= array_merge(array( 
            'dispatch' => true,
        ), $p);
		
        
        // TODO: handle this properly using. Extract everything after the call to the 'dispatchMethods' into a 'listAll' method???
        if ( $p['dispatch'] )
        {
            $this->dispatchMethods($args, array('allowed' => 'create,retrieve,update,delete,duplicate,search'));    
        }
		
		$this->log(__METHOD__);
        
        $this->events->trigger('onBeforeIndex', array('source' => array('class' => __CLASS__, 'method' => __FUNCTION__)));
        
        //$curURL     = $this->currentURL();
		
		// Set output data		
		$this->data = array_merge($this->data, array(
			$this->resourceName 	=> $this->C->index($this->options),
			'success' 				=> $this->C->success, 
			'errors'				=> $this->C->errors,
			'warnings' 				=> $this->C->warnings,
			'total'					=> array(
				$this->resourceName 	=> $this->C->index(array_merge($this->options, array('mode' => 'count'))),
			),
		));
		
		$this->events->trigger('onAfterIndex', array('source' => array('class' => __CLASS__, 'method' => __FUNCTION__)));
        
        $this->handleRelations();
		
		$this
			->beforeRender(array('function' => __FUNCTION__));
			
		return $this->render();
	}
	
	
	//public function create($options = null)
	public function create()
	{
		// Log current method
		$this->log(__METHOD__);
		
		$this->events->trigger('onBeforeCreate', array('source' => array('class' => __CLASS__, 'method' => __FUNCTION__)));
		
		// Set the current method
		//$this->data['view']['method'] 	= __FUNCTION__;
		
		// Check for crudability
		$meta 		= !empty($this->data['meta']) ? $this->data['meta'] : null;
		if ( !empty($meta) && strpos($meta['crudability'], 'C') === false ){ $this->redirect($meta['fullAdminPath']); }
		
		$referer 	= !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;
		$cleanURL 	= _URL . preg_replace('/^\/(.*)/','$1',$_SERVER['REQUEST_URI']);
		
		$this->handleRelations();
		
		// If the resource creation form has been posted
		if ( !empty($_POST) )
		{			
			// Launch the creation
			//$this->C->create();
			$this->resourceId = $this->C->create(array('returning' => 'id'));
		}
		else if ( !empty($referer) && strpos($cleanURL, $referer) !== false && empty($_POST) )
		{
			$this->data['errors'][] = 10000;
		}
		
		// Set output data		
		$this->data = array_merge($this->data, array(
			'success' 		=> $this->C->success, 
			'errors'		=> $this->C->errors,
			'warnings' 		=> $this->C->warnings,
		));
		
		// If the operation succeed, reset the $_POST
		if ( $this->data['success'] )
		{
			$this->logAdminAction(array('action' => __FUNCTION__));
						
			$successRedir = !empty($_POST['successRedirect']) ? $_POST['successRedirect'] : false;
			
			$this->events->trigger('onCreateSuccess', array('source' => array('class' => __CLASS__, 'method' => __FUNCTION__)));
		
			//if ( !empty($_POST['successRedirect']) ) { $this->redirect($_POST['successRedirect']); }
			if ( $successRedir ) { $this->redirect($successRedir); }
			
			
			
			$this->statusCode(201);
			
			unset($_POST);
		}
		else
		{
			$this->events->trigger('onCreateError', array('source' => array('class' => __CLASS__, 'method' => __FUNCTION__)));
		}
		
		$this->events->trigger('onAfterCreate', array('source' => array('class' => __CLASS__, 'method' => __FUNCTION__)));
		
		$this
			//->paginate()
			->beforeRender(array('function' => __FUNCTION__));
		
		return $this->render();
	}
	
	
	public function duplicate($resourceId = null, $options = null)
	{
        $this->log(__METHOD__);
        
		$args 				= func_get_args(); 						// Get the passed arguments
        //$this->resourceId     = !empty($args[0]) ? $args[0] : null;   // Assume that the first argument passed if the resource identifier
        $this->resourceId     = !empty($args[0]) 
                                  ? ( is_array($args[0]) && count($args[0]) === 1 ? $args[0][0] : $args[0] )
                                  : null;           // Assume that the first argument passed if the resource identifier
		
		// Log current method
		$this->log(__METHOD__);
		
		// Set the current method
		//$this->data['view']['method'] 	= __FUNCTION__;
		
		$data = $this->C->retrieve(array('values' => $this->resourceId));
		
		if ( !empty($data) )
		{
			// Remove id from data
			unset($data['id']);
			
			// Rebuild proper $_POST value
			foreach ($data as $key => $val){ $_POST[$this->resourceSingular . ucfirst($key)] = $val; }

			// Create the duplicata from POST data and get returned id
			//$this->resourceId = $this->C->create(array('returning' => 'id'))->data;
			$this->resourceId = $this->C->create(array('returning' => 'id'));
		}
		
		$this->data = array_merge($this->data, array(
			$this->resourceName		=> $this->C->retrieve(array('values' => $this->resourceId)),
			'success' 				=> $this->C->success,
			'errors'				=> $this->C->errors,
			'warnings' 				=> $this->C->warnings,
		));
		
//var_dump($this->data);

		$this
			//->paginate()
			->beforeRender(array('function' => __FUNCTION__));
			
		return $this->render();
	}
	
	
	//public function retrieve($resourceId = null, $options = null)
	public function retrieve()
	{
        $this->log(__METHOD__);
        
		$args 				= func_get_args(); 						// Get the passed arguments
        //$this->resourceId     = !empty($args[0]) ? $args[0] : null;   // Assume that the first argument passed if the resource identifier
        $this->resourceId     = !empty($args[0]) 
                                  ? ( is_array($args[0]) && count($args[0]) === 1 ? $args[0][0] : $args[0] )
                                  : null;           // Assume that the first argument passed if the resource identifier
		
		// Log current method
		$this->log(__METHOD__);
        
        $this->events->trigger('onBeforeRetrieve', array('source' => array('class' => __CLASS__, 'method' => __FUNCTION__)));
		
		// Set the current method
		//$this->data['view']['method'] 	= __FUNCTION__;
		
		// Check for crudability
		$meta = !empty($this->data['meta']) ? $this->data['meta'] : null;
		if ( !empty($meta) && strpos($meta['crudability'], 'R') === false ){ $this->redirect($meta['fullAdminPath']); }
		
		// Set output data		
		$this->data = array_merge($this->data, array(
			//$this->resourceName 	=> $this->C->retrieve(array('values' => $this->resourceId)),
			$this->resourceName  => $this->C->retrieve(array('by' => 'id', 'values' => $this->resourceId)),
			'resourceId' 			=> $this->resourceId,
		));
		
		$this
			->paginate()
			->beforeRender(array('function' => __FUNCTION__));
		
//$this->dump($this->data);
		
		return $this->render();
	}
	
	
	//public function update($resourceId = null, $options = null)
	public function update()
	{
        $this->log(__METHOD__);
        
		$args 				= func_get_args(); 						// Get the passed arguments
		//$this->resourceId 	= !empty($args[0]) ? $args[0] : null; 	        // Assume that the first argument passed if the resource identifier 
		$this->resourceId     = !empty($args[0]) 
		                          ? ( is_array($args[0]) && count($args[0]) === 1 ? $args[0][0] : $args[0] )
                                  : null;           // Assume that the first argument passed if the resource identifier
		
		$this->events->trigger('onBeforeUpdate', array('source' => array('class' => __CLASS__, 'method' => __FUNCTION__)));
		
		// Set the current method
		//$this->data['view']['method'] 	= __FUNCTION__;
		
		// Check for crudability
		$meta = !empty($this->data['meta']) ? $this->data['meta'] : null;
		if ( !empty($meta) && strpos($meta['crudability'], 'U') === false ){ $this->redirect($meta['fullAdminPath']); }
		
		$this->handleRelations();
		
		// Handle file deletion
		if ( !empty($_GET['forceFileDeletion']) && !empty($args[1]) )
		{
			//$rName 			= $this->resourceName; 		// Shortcut for resourceName
			$fName 			= $args[1]; 					// Shortcut for file field name
			$rFields 		= !empty($this->resourceName) ? $this->dataModel['resourcesFields'][$this->resourceName] : null;
			
			if ( isset($rFields[$fName]) && $rFields[$fName]['subtype'] === 'file' )
			{
				$isApi 			= strpos($_SERVER['PATH_INFO'], '/api/') !== false;
				$pfn 			= $isApi ? $fName : $this->resourceSingular . ucFirst($fName); // Shortcut for posted field name
				$_POST[$pfn] 	= '';
			}
		}
		
		
		// If the resource update form has been posted
		if ( !empty($_POST) )
		{			
			$this->C->update(array('values' => $this->resourceId));
		}

		$this->data = array_merge($this->data, array(
			'success' 				=> $this->C->success, 
			'errors'				=> $this->C->errors,
			'warnings' 				=> $this->C->warnings,
			$this->resourceName 	=> $this->C->retrieve(array('values' => $this->resourceId)),
			'resourceId' 			=> $this->resourceId,
		));
		
		// If the operation succeed, reset the $_POST
		if ( $this->data['success'] )
		{
			$this->logAdminAction(array('action' => __FUNCTION__));
			
			// Try to get success redirect URL
			$successRedir = !empty($_POST['successRedirect']) ? $_POST['successRedirect'] : false;
			
			// Trigger proper events
			$this->events->trigger('onUpdateSuccess', array('source' => array('class' => __CLASS__, 'method' => __FUNCTION__)));
			
			if ( !empty($_GET['forceFileDeletion']) )
			{
				$curURL 	= $this->currentURL();
				//$cleanURL 	= $this->removeQueryParams('forceFileDeletion', $curURL);
				$cleanURL   = Tools::removeQueryParams('forceFileDeletion', $curURL);
				
				$this->redirect($cleanURL);
			}
			
			else if ( $successRedir ) { $this->redirect($successRedir); }
			
			unset($_POST);
		}
		else
		{
			$this->events->trigger('onUpdateError', array('source' => array('class' => __CLASS__, 'method' => __FUNCTION__)));
		}
		
		$this->events->trigger('onAfterUpdate', array('source' => array('class' => __CLASS__, 'method' => __FUNCTION__)));

		$this
			->paginate()
			->beforeRender(array('function' => __FUNCTION__));
			
//$this->dump($this->data);
		
		return $this->render();
	}
	
	
	//public function delete($resourceId = null, $options = null)
	public function delete()
	{
        $this->log(__METHOD__);
        
		$args 				= func_get_args(); 						// Get the passed arguments
		//$this->resourceId 	= !empty($args[0]) ? $args[0] : null; 	// Assume that the first argument passed if the resource identifier
        $this->resourceId     = !empty($args[0]) 
                                  ? ( is_array($args[0]) && count($args[0]) === 1 ? $args[0][0] : $args[0] )
                                  : null;           // Assume that the first argument passed if the resource identifier
		
		$this->events->trigger('onBeforeDelete', array('source' => array('class' => __CLASS__, 'method' => __FUNCTION__)));
		
		// Set the current method
		//$this->data['view']['method'] 	= __FUNCTION__;
		
		// Check for crudability
		$meta = !empty($this->data['meta']) ? $this->data['meta'] : null;
		if ( !empty($meta) && strpos($meta['crudability'], 'D') === false ){ $this->redirect($meta['fullAdminPath']); }		
		
		// If the confirmation param has been passed
		//if ( $_SERVER['REQUEST_METHOD'] === 'DELETE' || (isset($_GET['confirm']) && $_GET['confirm']) )
		if ( $_SERVER['REQUEST_METHOD'] === 'DELETE' || (int) $this->options['confirm'] === 1 )
		{
			// Launch the deletion
			$this->C->delete(array('values' => $this->resourceId));
		}
		
		// Set output data		
		$this->data = array_merge($this->data, array(
			'success' 		=> $this->C->success, 
			'errors'		=> $this->C->errors,
			'resourceId' 	=> $this->resourceId,
		));
		
		if ( $this->data['success'] )
		{
			$this->logAdminAction(array('action' => __FUNCTION__));
			
			$this->events->trigger('onDeleteSuccess', array('source' => array('class' => __CLASS__, 'method' => __FUNCTION__)));
		}
		else
		{
			$this->events->trigger('onDeleteError', array('source' => array('class' => __CLASS__, 'method' => __FUNCTION__)));
		}
		
		$this->events->trigger('onAfterDelete', array('source' => array('class' => __CLASS__, 'method' => __FUNCTION__)));
		
		$this->beforeRender(array('function' => __FUNCTION__));
				
		return $this->render();
	}
	
	
	public function paginate()
	{
		$this->log(__METHOD__);
        
        // Do not continue if the resourceId is not found
        if ( empty($this->resourceId) ) { return $this; }
		
		$id                       = (int) $this->resourceId;
        $this->data['pagination'] = array();
        
        // If the new conditions handler is not activated
        if ( !defined('_APP_USE_CONDITIONS_HANDLER_V2') || !_APP_USE_CONDITIONS_HANDLER_V2 )
        {
            $this->C->retrieve(array('getFields' => 'id', 'values' => $id, 'limit' => 1, 'operation' => 'valueIsLower', 'sortBy' => 'id', 'orderBy' => 'DESC'));
            //$this->C->retrieve(array('getFields' => 'id', 'conditions' => array(array('id','>',$id)), 'sortBy' => 'id', 'orderBy' => 'DESC'));
            $this->data['pagination']['prev'] = !empty($this->C->data['id']) ? $this->C->data['id'] : null;
            
            $this->C->retrieve(array('getFields' => 'id', 'values' => $id, 'limit' => 1, 'operation' => 'valueIsGreater', 'sortBy' => 'id', 'orderBy' => 'ASC'));
            //$this->C->retrieve(array('getFields' => 'id', 'conditions' => array(array('id','>',$id)), 'sortBy' => 'id', 'orderBy' => 'DESC'));
            $this->data['pagination']['next'] = !empty($this->C->data['id']) ? $this->C->data['id'] : null;
        }
        else
        {
        	// Define common options
        	$opts = array('getFields' => 'id', 'sortBy' => 'id', 'limit' => 1);
			
			// Get prev and next id
        	$prev = $this->C->retrieve(array_merge($opts, array('conditions' => array(array('id', '<', $id)), 'orderBy' => 'DESC')));
			$next = $this->C->retrieve(array_merge($opts, array('conditions' => array(array('id', '>', $id)), 'orderBy' => 'ASC')));
			
			// Assign the values to pagination data
            $this->data['pagination']   = array(
                'prev' => is_array($prev) && isset($prev['id']) ? $prev['id'] : ( is_numeric($prev) ? $prev : null),
                'next' => is_array($next) && isset($next['id']) ? $next['id'] : ( is_numeric($next) ? $next : null),
            );
        }

		return $this;
	}
	
	
	public function beforeRender($options = array())
	{
		$this->log(__METHOD__);
		
		if ( !in_array($this->options['output'], array('html','xhtml')) )
		{
			unset(
                $this->data['_dataModel'],
                $this->data['_resources'],
                $this->data['dataModel'],
                //$this->data['resources'],
                $this->data['_resourcesGroups']
            );
			
			//if ( empty($this->resourceName) || $this->resourceName !== 'resources' )  unset($this->data['resources']);
		}
		
		return parent::beforeRender($options);
	}
	
	
	public function smartname()
	{
        $this->log(__METHOD__);
        
		if ( !empty($this->resourceName) )
		{
			//$tmp = preg_replace('/-([a-z]{1})/e', "ucfirst('$1')", join('-', $this->data['metas'][$this->resourceName]['breadcrumbs']));
            $tmp = preg_replace('/-([a-z]{1})/e', "ucfirst('$1')", join('-', $this->data['meta']['breadcrumbs']));
		}
		else if ( !empty($this->resourceGroupName) )
		{
			$tmp = $this->resourceGroupName;
		}
		else { $tmp = ''; }
		
		//$method = !empty($this->data['current']['method']) ? $this->data['current']['method'] : 'index';
		$method = !empty($this->data['view']['method']) ? $this->data['view']['method'] : 'index';
		
		return 'admin' . ucfirst($tmp) . ucfirst($method);
	}
	
	
	/*
	public function smartclasses()
	{
        $this->log(__METHOD__);
        
		$tmp = '';
		
		if ( !empty($this->resourceName) )
		{
			//foreach ($this->data['metas'][$this->resourceName]['breadcrumbs'] as $item){ $tmp .= 'admin' . ucfirst($item) . ' '; }
			foreach ($this->data['meta']['breadcrumbs'] as $item){ $tmp .= 'admin' . ucfirst($item) . ' '; }
		}
		
		//$method = !empty($this->data['current']['method']) ? $this->data['current']['method'] : 'index';
		$method       = !empty($this->data['view']['method']) ? $this->data['view']['method'] : 'index';
		
        // Add user groups
        $gpClasses  = '';
        $uGps       = !empty($this->data['current']['user']['group_admin_titles']) 
                        ? explode(',',$this->data['current']['user']['group_admin_titles']) 
                        : array();
        foreach ( $uGps as $gp ){ $gpClasses .= ' group' . ucfirst($gp); }
        
		return 'admin ' . ( 'admin' . ucfirst($method) ) . ' ' . $tmp . $this->data['view']['smartname'] . ' ' . $gpClasses;
	}
	*/
	
	
	public function render()
	{		
		$this->log(__METHOD__);
				
		return parent::render();
	}
	
	public function prepareTemplate()
	{
        $this->log(__METHOD__);
        
		$v = !empty($this->data['view']) ? $this->data['view'] : null; 	// Shortcut for view data
		$m = !empty($v['method']) ? $v['method'] : 'index'; 			// Shortcut for view method
		
		if ( !empty($m) )
		{
			$this->data['view'] = array_merge(array(
				'name' 					=> 'admin' . ucfirst($m),
				'template' 				=> 'specific/pages/admin/' . ( !empty($this->resourceName) ? 'resource/' . $m : 'default' ) . '.tpl',
				'resourceName' 			=> isset($this->resourceName) ? $this->resourceName : '',
			), ( isset($this->data['view']) ? (array) $this->data['view'] : array()) );
		}
		
		//$this->data['view']['smartname'] 	= $this->smartname();
		//$this->data['view']['smartclasses'] = $this->smartclasses();

		// Update current meta
		// TODO: remove when resource group getting will have been move to meta()
		//$this->data['meta'] 					= !empty($this->resourceName) ? $this->data['metas'][$this->resourceName] : null;
		
		// TODO: already assigned in the __construct()
		// safe to remove? 
		//$this->data['meta']                       = !empty($this->resourceName) ? $this->meta($this->resourceName) : null;
		
        //$curURL     = $this->currentURL();
								
        $this->data = array_merge($this->data, array(
            'current'               => array_merge($this->data['current'], array(
            	// url & urlParams have been moved to View class
                //'url'                       => $curURL,
                //'urlParams'                 => Tools::getURLParams($curURL),
                'offset'                    => $this->options['offset'],
                'limit'                     => $this->options['limit'],
                'sortBy'                    => $this->options['sortBy'],
                'orderBy'                   => $this->options['orderBy'],
                'resource'                  => !empty($this->resourceName) ? $this->resourceName : null,
                
                // TODO, handle this properly via dispatcher/breadcrumbs
                'menu'                      => !empty($this->resourceName) ? $this->resourceName : 'admin',
                
                // Deprecated. Safe to be removed?
                // TODO: remove
                'resourceGroup'             => $this->resourceGroupName,
            )),
        ));
								
		//$this->data['current']['resource'] 		= !empty($this->resourceName) ? $this->resourceName : null;
        //$this->data['current']['menu']          = &$this->data['current']['resource'];
		
		// Deprecated. Safe to be removed?
		// TODO: remove
		//$this->data['current']['resourceGroup'] = $this->resourceGroupName;
        
		//if ( $m === 'update' || $m === 'delete' )
		if ( in_array($m, array('update','delete')) )
		{
			$this->data['resourceId'] = $this->resourceId;
		}
		
$this->dump($this->data);
		
		return parent::prepareTemplate();
	}


	private function logAdminAction($params = array())
	{
		$p = array_merge(array(
			'resource_name' => $this->resourceName,
			'resource_id' 	=> $this->resourceId,
			'user_id' 		=> $_SESSION['user_id'], 
		), $params);
		
		// Log the performed action
		$oldPOST = $_POST;
		$log = array(
			//'admin_title' 		=> '',
			'action' 			=> $p['action'],
			'resource_name' 	=> $p['resource_name'],
			'resource_id' 		=> $p['resource_id'],
			'user_id' 			=> $p['user_id'],
			//'revert_query' 	=> ''
		);
		$_POST = $log;
		CAdminlogs::getInstance()->create(array('isApi' => 1));
		$_POST = $oldPOST;
		
		return $this;
	}
		
}

?>