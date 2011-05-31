<?php

class_exists('Application') || require(_PATH_LIBS . 'Application.class.php');

class View extends Application
{
	var $controller        = null;
	var $headers           = array();
	var $events            = null;
	var $data              = array(
		'success' 		=> false,
		'errors' 		=> array(), 
		'warnings' 		=> array()
	);
    
    var $application    = null;
    //var $logged             = false;
		
	public function __construct(&$application = null)
	{
	    $this->application = &$application;
        
		return $this->init();
	}
	
	public function init()
	{
        $this->log(__METHOD__);
        
		if ( $this->inited ) { return $this; }
		
		// If events are enabled
		if ( _APP_USE_EVENTS )
		{
			$this->requireLibs('Events');
			$this->events = new Events();
			
			// Triggered events:
			// onBeforeRender
			// onBeforeDisplay
			// onBeforeUpdate (admin)
			// onUpdateSuccess (admin)
			// onUpdateError (admin)
			// onAfterUpdate (admin)
			// onBeforeDelete (admin)
			// onAfterDelete (admin)
			// onDeleteSuccess (admin)
			// onDeleteError (admin)
			// onBeforeIndex (admin)
			// onAfterIndex (admin)
			// onBeforeRetrieve (admin)
			// onBeforeCreate (admin)
			// onCreateSuccess (admin)
			// onCreateError (admin)
			// onAfterCreate(admin)
		} 
		
		$this->configEnv();
		
		// TODO: use get_called_class if PHP 5.3
		// Use the class name to get the resource name
		//$this->resourceName 		= strtolower(preg_replace('/^V(.*)/','$1', __CLASS__));
		
		// Remove the expedted final 's' from the resource name to get it's singular (overload in proper class if needed) 
		if ( !empty($this->resourceName) )
		{
			$this->resourceSingular = !empty($this->resourceSingular) ? $this->resourceSingular : Tools::singularize((string) $this->resourceName);
			
			$cName = 'C' . ucfirst($this->resourceName);
			$cPath = !empty($this->controllerPath) ? $this->controllerPath : $cName . '.class.php';
			
			class_exists($cName) || require(_PATH_CONTROLLERS . $cPath);
			
			// Instanciate the resource controller
			$controllerClassname 	= 'C' . ucfirst($this->resourceName);
			$this->controller 		= new $controllerClassname();
			$this->C 				= &$this->controller;
		}
		
		$this
			//->configSmarty()
			->getPlatformData()
            ->getDeviceData()
            ->getBrowserData()
			->handleOptions()
			->handleRequest()
			->outputFormat();
		
		// Has the request been made via xhr	
		$this->isAjaxRequest = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
		
		$this->inited = true;
		
		return $this;
	}
	
	
	public function configSmarty()
	{
        $this->log(__METHOD__);
		
		// Don't reload anything if application is already loaded
		class_exists('Smarty') || require _PATH_SMARTY . 'libs/Smarty.class.php';
		
		// Instanciate a Smarty object and configure it
		$this->Smarty 						= new Smarty();
		$this->Smarty->compile_check 		= _TEMPLATES_COMPILE_CHECK;
		$this->Smarty->force_compile 		= _TEMPLATES_FORCE_COMPILE;
		$this->Smarty->caching 				= _TEMPLATES_CACHING;
		$this->Smarty->cache_lifetime 		= _TEMPLATES_CACHE_LIFETIME;
		$this->Smarty->template_dir 		= _PATH_TEMPLATES;
		$this->Smarty->compile_dir 			= _PATH_TEMPLATES . 'templates_c/';
		$this->Smarty->cache_dir 			= _PATH_SMARTY . 'cache/';
		$this->Smarty->config_dir 			= _PATH_SMARTY . 'configs/';
		$this->Smarty->allow_php_templates 	= true;
		$this->Smarty->allow_php_tag 		= true;
		
		return $this;
	}
	
	
	// Try to find which method to use in this order in POST || GET || SERVER REQUEST;
	// If none found, fallback to 'index'
	public function dispatchMethods($args = array(), $params = array())
	{
        $this->log(__METHOD__);
		
		if ( isset($args[__METHOD__]) && !$args[__METHOD__] ){ return $this; }
		
		// Known methods (alias => used)
		$known = array(
			'index' 	=> 'index',
			'put' 		=> 'update',
			'update' 	=> 'update',
			'post' 		=> 'create',
			'create'	=> 'create',
			'get' 		=> 'retrieve',
			'retrieve' 	=> 'retrieve',
			'delete' 	=> 'delete',
			'search'	=> 'search',
			'duplicate' => 'duplicate',
		);

		$id 		= !empty($args[0]) ? $args[0] : null;														// Shortcut for resource identifier(s)
		$p 			= &$params; 																				// Shortcut for params
		$allowed 	= !empty($p['allowed']) 
						? ( is_array($p['allowed']) ? $p['allowed'] : explode(',', $p['allowed']) ) 
						: array(); 																				// Get the allowed methods
		$gM 		= isset($this->options['method']) ? strtolower($this->options['method']) : null; 			// Shortcut for GET "method" param
		$pM 		= !empty($_POST['method']) 
						? strtolower(filter_var($_POST['method'], FILTER_SANITIZE_STRING)) 
						: null; 																				// Shortcut for POST "method" param
		$srM 		= isset($_SERVER['REQUEST_METHOD']) ? strtolower($_SERVER['REQUEST_METHOD']) : null; 		// Shortcut for request method
		$foundM 	= !empty($pM) ? $pM : ( !empty($gM) ? $gM : ( !empty($srM) ? $srM : null )); 				// 
		$m 			= !empty($foundM) && isset($known[$foundM]) ? $known[$foundM] : 'index';


/*
$this->dump('gM: ' . $gM);
$this->dump('pM: ' . $pM);
$this->dump('srM: ' . $srM);		
$this->dump('found: ' . $foundM);
$this->dump('m: ' . $m);
$this->dump('id: ' . $id);
$this->dump($allowed);
*/

		
		// Special case if method is 'retrieve' but resource id is not set
		// In this case, method is forced back to index 
		if ( $m === 'retrieve' && is_null($id) ) { $m = 'index'; }
		
//$this->dump('m: ' . $m);
		
		//$m 			= !empty($pM) 
		//					? $pM : !empty($gM) 
		//					? $gM : ( !isset($known[$srM]) || ( $known[$srM] === 'retrieve' && empty($id) ) 
		// 					? 'index' : $known[$srM] ); 														// Get the class method to use
		
		// Store the final method
		$this->data['view']['method'] = $m;
        
		// If the method is not index and belongs to the allowed methods, call it
		//if ( $m !== 'index' && in_array($m, $allowed) ) { return call_user_func_array(array($this, $m), $args); }
		if ( $m !== 'index' && in_array($m, $allowed) ) { return call_user_func_array(array($this, $m), $args); }
		// Otherwise, just continue
		else if ( $m === 'index' ) { /* just continue */ }
		// The following case should not append
		else
		{
			return $this->statusCode(405); // Method not allowed
		}
	}
	
	
	/*
	 * This function tries to find out platform data
	 */	
	public function getPlatformData()
	{
        $this->log(__METHOD__);
		
		// Default values
		$this->platform = array(
			'name' 				=> 'unknownPlatform',
			'version' 			=> 'unknownVersion',
		);
		
		// Do not continue if platform sniffing has been disabled
		if ( !_APP_SNIFF_PLATFORM ) { return $this; }
		
		// Shortcut for user agent
		$ua = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
		
		// List of known platforms
		$knownPlatforms = array('Windows','Mac OS','linux','freebsd','iPhone','iPod','iPad','Android','BlackBerry','Symbian','Bada','AdobeAIR','tabbee','mobile','j2me');
		
		foreach ( $knownPlatforms as $p )
		{
			$lower 		= strtolower($p);
			$urlParam 	= 'is' . ucfirst($lower);
			
			// Check platform identifier is present in the user agent or is the is{$platform} parameter is set in the url
			if ( strpos($ua, $p) !== false || ( isset($_GET[$urlParam]) && ( $_GET[$urlParam] === '' || $_GET[$urlParam] != false) ) )
			{
				$this->platform['name'] = str_replace(' ', '', $lower);
				
				//break;
			} 
		}

		return $this;
	}


    public function getDeviceData()
    {
        $this->log(__METHOD__);
        
        $this->device   = array();
        $d              = &$this->device;
		
        // Get resolution
        $resol          = !empty($_SESSION['resolution']) ? explode('x', strtolower($_SESSION['resolution'])) : array();
        $w              = !empty($resol[0]) ? (int) $resol[0] : null;
        $h              = !empty($resol[1]) ? (int) $resol[1] : null;
		
//var_dump($_SESSION);
//var_dump($resol);
        
        // Default values
        $d  = array(
            'resolution'    => array('width' => $w, 'height' => $h),
            'isMobile'      => isset($_GET['isMobile']) 
                                ? in_array($_GET['isMobile'], array('1', 'true',1,true))
                                //: ( !empty($w) ? ($w < 800) : null ),
                                : ( !empty($w) ? ($w < 767) : null ),
            'orientation' => !empty($_SESSION['orientation']) 
                                ? $_SESSION['orientation'] 
                                : ( $w && $h ? ( $w > $h ? 'landscape' : 'portrait') : null),
        );
        
        return $this;
    }


	/*
	 * This function tries to find out browser data like name, version, engine
	 */	
	public function getBrowserData()
	{
        $this->log(__METHOD__);
		
		// Default values
		$this->browser 	= array(
			'name' 				=> 'unknownBrowser',
			'alias' 			=> 'unknownBrowser',
			'version' 			=> 'unknownVersion',
			'engine' 			=> 'unknownEngine',
			'hasHTML5' 			=> false,
			//'hasHTML5Forms' 	=> false,
		);
		
		// Do not continue if browser sniffing has been disabled
		if ( !_APP_SNIFF_BROWSER ) { return $this; }

		// Shortcut for user agent
		$ua 			= isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
		
		// Known browsers data
		$data 			= $this->browser;
        //$knownEngines   = array('Trident' => 'trident', 'MSIE' => 'trident', 'AppleWebKit' => 'webkit', 'Presto' => 'presto', 'Gecko' => 'gecko', 'KHTML' => 'khtml', 'BlackBerry' => 'blackberry');
		$knownEngines 	= array('Trident' => 'trident', 'MSIE' => 'trident', 'AppleWebKit' => 'webkit', 'Presto' => 'presto', 'Gecko' => 'gecko', 'KHTML' => 'khtml', 'BlackBerry' => 'mango');
		$knownBrowsers 	= array(
			'MSIE' 						=> array('name' => 'internetexplorer', 'displayName' => 'Internet Explorer', 'alias' => 'ie', 'versionPattern' => '/.*(MSIE)\s([0-9]*\.[0-9]*);.*/'),
			'Firefox' 					=> array('name' => 'firefox', 'displayName' => 'Firefox', 'alias' => 'ff', 'versionPattern' => '/.*(Firefox|MozillaDeveloperPreview)\/([0-9\.]*).*/'),
			'Chrome' 					=> array('name' => 'chrome', 'displayName' => 'Chrome', 'alias' => 'chrome', 'versionPattern' => '/.*(Chrome)\/([0-9\.]*)\s.*/'),
			'Safari' 					=> array('name' => 'safari', 'displayName' => 'Safari', 'alias' => 'safari', 'versionPattern' => '/.*(Safari|Version)\/([0-9\.]*)\s.*/'),
			'Opera' 					=> array('name' => 'opera', 'displayName' => 'Opera', 'alias' => 'opera', 'versionPattern' => '/.*(Version|Opera)\/([0-9\.]*)\s?.*/'),
			'Konqueror'                 => array('name' => 'konqueror', 'displayName' => 'Konqueror', 'alias' => 'konqueror', 'versionPattern' => '/.*(Konqueror)\/([0-9\.]*)\s.*/'),
            'BlackBerry'                => array('name' => 'blackberry', 'displayName' => 'BlackBerry', 'alias' => 'blackberry', 'versionPattern' => '/.*(BlackBerry[a-zA-Z0-9]*)\/([0-9\.]*)\s.*/'),
		);
				
		// Try to get the browser data using the User Agent
		foreach ($knownBrowsers as $k => $b)
		{
			if ( isset($_GET['tabbee']) && $_GET['opera'] == true ){ $ua = 'Opera'; }
			
			if (strpos($ua, $k) !== false)
			{
				$data = array_merge($data, array(
					'name' 			=> $b['name'],
					'identifier' 	=> $k,  
					'displayName' 	=> $b['displayName'],
					'alias' 		=> $b['alias'],
				)); break;
			}
		}
		
		// Try to get the browser rendering engine
		foreach ($knownEngines as $k => $e) { if (strpos($ua, $k) !== false) { $data['engine'] = $e; break; } }
		
		// Try to get the browser version data
		$pattern 	= !empty($data['identifier']) && !empty($knownBrowsers[$data['identifier']]['versionPattern']) 
						? $knownBrowsers[$data['identifier']]['versionPattern'] 
						: null;
		if ( !empty($pattern) )
		{
			$v 			= preg_replace($knownBrowsers[$data['identifier']]['versionPattern'], '$2', $ua);
			$vParts 	= explode('.', $v);
			$scheme 	= array(
				'major' 	=> isset($vParts[0]) ? (int) $vParts[0] : $v,
				'minor' 	=> isset($vParts[1]) ? (int) $vParts[1] : '?',
				'build' 	=> isset($vParts[2]) ? (int) $vParts[2] : '?',
				'revision' 	=> isset($vParts[3]) ? (int) $vParts[3] : '?',
			);
			foreach ($scheme as $key => $val){ $data['version' . ucFirst($key)] = $val; }
			$data['version'] 		= $data['versionMajor'];
			$data['versionFull'] 	= $v;
		}
		
		// Features detection
		$maj              = &$data['versionMajor'];
		$min              = &$data['versionMinor'];
		$alias            = &$data['alias'];
		$data['hasHTML5'] = $alias === 'chrome' 
								|| ($alias === 'safari' && $maj >= 4)
								|| ($alias === 'ff' && ( ($maj === 3 && $min >= 5) || $maj >= 4 ))
								|| ($alias === 'opera' && $maj >= 9)
								|| ($alias === 'ie' && $maj >= 9)
                                || ($alias === 'konqueror' && $maj >= 4 && $min >= 4 && $data['build'] >= 4);
		
		if ( !empty($scheme) )
		{
			$v 		= $scheme;
			$data['support'] = array(
				'datalist' => 
								$alias === 'opera' && ($v['major'] > 10 || ($v['major'] == 10 && $v['minor'] == 5))
								|| $alias === 'ff' && $v['major'] > 4,
			);
		}
		
		$this->browser = $data;
		
		return $this;
	}


	public function handleRelations()
	{
        $this->log(__METHOD__);
        
		// Do not continue if the resource is not defined
		// or if it has already been handled (i.e: if a resource relates to another one on several columns)
		if ( empty($this->resourceName) ){ return $this; }
		
		
		$d 				= &$this->data; 												// Shortcut for data		 
		$relResources 	= array(); 														// Array of related resource for the current resource
		//$hr 			= array(); 														// Handled resources
		$rModel  		= &$this->dataModel['resourcesFields'][$this->resourceName]; 	// Set shortcut to resource columns
		
		// Loop over the resource colums
		//foreach ( $this->dataModel['resourcesFields'][$this->resourceName] as $name => $f )
		foreach ( array_keys($rModel) as $colName )
		{
			// Get col properties
			$p = &$rModel[$colName];
			
			// Do not continue if the type is not found and the field is not a foreign key
			if ( empty($p['type']) && empty($p['fk']) ){ continue; }
			
			// For onetoone & onetomany relations
			//else if ( $f['type'] === 'onetomany' || $f['type'] === 'onetoone' )
			else if ( $p['type'] === 'onetomany' || !empty($p['fk']) )
			{
				$relResName 	= !empty($p['relResource']) ? $p['relResource'] : $colName; // Get the related resource or default it to current column name
				
				// Do not continue if the related resource count has already been gotten 
				//if ( in_array($this->resourceName, $hr) ){ continue; }
				if ( in_array($relResources, $relResources) ){ continue; }
				
				$relResources[] = $relResName;												// Add it to the related resources array
				$ctrlrName 		= 'C' . ucfirst($relResName);								// Build its controller name
				$ctrlr 			= new $ctrlrName(); 										// Instanciate it
				$count 			= $ctrlr->index(array('mode' => 'count'));					// Count the records for the resource
				$d[$relResName] = $count < 100 ? $ctrlr->index() : null;
				
				// Store the related resource count
				$d['total'][$relResName] = $count;
				 
				// Store that we handled this related resource
				//$hr[] = $this->resourceName;
			}
		}

//$this->dump($relResources);
		
		return $this;
	}


	public function handleOptions($options = array())
	{
        $this->log(__METHOD__);
		
		//$o 	= (array) $options; 	// Shortcut for options
		$o        = &$options;                 // Shortcut for options

		// Known options
		$known    = array(
			'output', 'method','viewType','offset','limit','sortBy','orderBy','by','value','values','searchQuery','page',
			'reindexby','indexby','indexBy',
			'operation','debug','confirm',
			'errors','successes','warnings','notifications',
			'css', 'js', 'minify',
		);
		
		// Specific ones whose default value is 0 (false)
		$specZero = array('isIphone','iphone','isAndroid','android','offset','limit','debug', 'confirm');
        
        // TODO
        $specOne = array('css', 'js', 'minify',);
		
		// Assign the options default values
		foreach ( $known as $opt )
		{
			// TODO: use array_intersect, array_merge ???
			//$this->options[$opt] = isset($_GET[$opt]) ? $_GET[$opt] : ( !empty($o[$opt]) ? $o['opt'] : (in_array($opt, $specZero) ? 0 : null));
			$this->options[$opt] = isset($_GET[$opt]) 
									? filter_var($_GET[$opt], FILTER_SANITIZE_STRING) 
									: ( !empty($o[$opt]) ? $o['opt'] : (in_array($opt, $specZero) ? 0 : null));
		}
		
		// 
		$this->options['conditions'] = isset($_GET['conditions']) ? $_GET['conditions'] : null;
		
		if ( !empty($this->options['conditions']) )
		{
			$passedOps 	= explode(';', $this->options['conditions']);
			$finalOps 	= array();
			 
			foreach ( (array) $passedOps as $item)
			{
				$parts = explode('|', $item);
				
				if ( count($parts) < 2 ) { continue; }
				
				$field 		= filter_var($parts[0], FILTER_SANITIZE_STRING);
				$operator 	= filter_var( (count($parts) >= 3 ? $parts[1] : '='), FILTER_UNSAFE_RAW);
				$value 		= filter_var(count($parts) >= 3 ? $parts[2] : $parts[1], FILTER_SANITIZE_STRING);
				$finalOps[] = array($field, $operator, $value);
			}
			$this->options['conditions'] = $finalOps;
		}
		
		// Handle limit
		$tmpLim = (int) $this->options['limit'];
		$this->options['limit'] = $tmpLim > 0 ? $tmpLim : ( $tmpLim === -1 ? null : _ADMIN_RESOURCES_NB_PER_PAGE );
        
		// Handle Page
        if ( !empty($this->options['page']) )
        {
            $this->options['offset'] = ((int) $this->options['page'] - 1) * $this->options['limit'];
            //$offset = ((int) $this->options['page'] - 1) * $this->options['limit'];
        }

		// Handle indexBy & reindexby
		// Dreprecated
		if 		( !empty($this->options['indexby']) ){ $this->options['reindexby'] = $this->options['indexby']; }
		else if ( !empty($this->options['indexBy']) ){ $this->options['reindexby'] = $this->options['indexBy']; }
		
		return $this;
	}
	
	
	public function handleRequest($options = array())
	{
        $this->log(__METHOD__);
		
		$this->request = array(
			'method' 	=> !empty($_SERVER['REQUEST_METHOD']) ? strtoupper($_SERVER['REQUEST_METHOD']) : null,
			'rawData' 	=> null,
			'data' 		=> null,
		);
		
		$r = &$this->request; // Shortcut for request data
		
		// Only handle methods other than GET and POST
		// We have to emulate $_PUT and $_DELETE global vars for those case 
		if ( $r['method'] === 'GET' || $r['method'] === 'POST' ){ return $this; }
		
		// Get the raw input data
		$inputData = trim(file_get_contents('php://input'));
		
		// Do not continue if it's empty
		if ( empty($inputData) ){ return $this; }
		
		// Loop over those data, inserting earch input fied into the proper global value
		// TODO: use proper $_PUT and $_DELETE ???
		foreach (explode('&', $inputData) as $item) { $tmp = explode('=', $item); $_POST[$tmp[0]] = $tmp[1]; }
		
		return $this;
	}
	
	
	public function redirect($url = '', $options = null)
	{
        $this->log(__METHOD__);
		
		$tplSelf 	= !empty($_GET['tplSelf']) && $_GET['tplSelf'] != 0;
		$url 		= !empty($_GET['redirect']) ? $_GET['redirect'] : $url;
		
		// Prevent redirection loop
		//if ($_SERVER['HTTP_REFERER'] === $url)
		if ($this->currentURL() === $url)
		{
			// TODO : make specific error page ?
			$url = _URL_HOME;
			$url .= ( strpos($url, '?') !== false ? '&' : '?' ) . 'errors=9000';
		}
		
		if ( $this->isAjaxRequest )
		{
			$url = Tools::removeQueryParams('tplSelf', $url);
			$this->data['redirect'] = Tools::removeQueryParams('tplSelf', $url);
			return $this->render();
		}
		else
		{
			header("Location:" . $url);
			die();
		}
	}
	
	/**
	 * This function tries to guess what should be the output format of the response
	 * depending of the passed 'output' URI param and/or the 'accepted' header
	 * By default: uses the _APP_DEFAULT_OUTPUT_FORMAT constant value (see config)
	 * @return current object (this) 
	 */
	public function outputFormat()
	{
        $this->log(__METHOD__);
		
		// Prevent the method form being called twice (could happend in some cases) 
		// TODO: check why and fix?
		if ( !empty($this->outputHandled) ){ return $this; }
		
		$this->outputHandled              = true;
        
		// Shortcut for options
		$o                                = &$this->options;

		// Try to get the output format extension on the last resource in path
		$uriParts 						= @parse_url($_SERVER['REQUEST_URI']); 
        $urlExt 						= strpos($uriParts['path'], '.') !== false ? preg_replace('/(.*)\.(.*)/', '$2', $uriParts['path']) : null;
		$o['output'] 					= !empty($o['output']) ? $o['output'] : $urlExt;
		$o['outputExtension'] 			= $urlExt;
		
		$this->availableOutputFormats     = array('html','xhtml','json','xml','plist','yaml','csv','qr','plistxml','yamltxt','jsontxt','jsonreport');
		$this->knownOutputMime            = array(
			'text/html' 			=> 'html',
			'application/xhtml+xml' => 'xhtml',
			'application/json' 		=> 'json',
			'text/json' 			=> 'json',
			'text/xml' 				=> 'xml', 
			'application/xml' 		=> 'xml',
			'application/plist+xml' => 'plist',
			'text/yaml' 			=> 'yaml',
			'text/csv' 				=> 'csv',
			// TODO: RSS
			// TODO: ATOM
			// TODO: RDF
			// TODO: ZIP??
			// TODO: JPG??
			// TODO: PNG??
			// TODO: GIF??
			// TODO: BMP??
		);
		
		// If no 'output' param has been passed or if the passed one is not part of the available formats
		//if ( empty($o['output']) && !in_array($o['output'], $this->knownOutputMime) )
		if ( empty($o['output']) || !in_array($o['output'], $this->availableOutputFormats) )
		{
			// Get the 'accept' http header and split it to get all the accepted mime type with their prefered priority
			$accepts 	= !empty($_SERVER['HTTP_ACCEPT']) ? explode(',',$_SERVER['HTTP_ACCEPT']) : array();
			
			$prefs 		= array();
			$i 			= 1;
			$len 		= count($accepts);
			foreach ($accepts as $item)
			{				
				$mime 			= preg_replace('/(.*);(.*)$/', '$1', trim($item)); 										// just get the mime type (or like)
				
				// Do not process mime types already that have already found earlier in the loop (prevent priority conflicts)
				if ( !empty($prefs[$mime]) ){ continue; }
				
				$q 				= strpos($item, 'q=') !== false ? preg_replace('/.*q=()(,;\s)?/Ui','$1',$item) : 1; 	// get the priority (default=1)
				$prefs[$mime] 	= $q*100 + ($len);
				$len--;
			}
			
			// Fix this fucking webkit that prefer xml over html
			if ( $this->browser['engine'] === 'webkit' )
			{				
				if ( isset($prefs['application/xml']) && isset($prefs['application/xhtml+xml']) && isset($prefs['text/html']) )
				{		
					$prefs['application/xml'] 	= $prefs['application/xml']-(2);
					$prefs['text/html'] 		= 150;
					
					if ( isset($prefs['image/png']) ){ $prefs['image/png'] = $prefs['application/xml']-(5); }
				}
			}
			
			/*
			if ( $this->browser['engine'] === 'webkit' && $this->platform['name'] === 'symbian' )
			{
				$prefs['text/html'] = 150;
			}*/
			
			// Fix this damn big fucking shit of ie that even does not insert text/html as a prefered type 
			// and prefers being served in their own proprietary formats (word,silverlight,...). MS screw you!!!!  
			if ( $this->browser['engine'] === 'trident' )
			{
				//if ( !isset($prefs['text/html']) ) { $prefs['text/html'] = 150; }
			}
			
			// Now, we add the default output format if not present
			//$def = _APP_DEFAULT_OUTPUT_MIME;
			//if ( !isset($prefs[$def]) ) { $prefs[$def] = 150; }
			
			// Sort by type priority
			arsort($prefs);
			
			// Now, loop over the types and break as soon as we found a recognized type
			foreach ($prefs as $pref => $priority)
			{ 
				// If it's a known type, stop here
				//if ( isset($this->knownOutputMime[$pref]) ){ $this->options['output'] = $this->knownOutputMime[$pref]; break; }
				if ( isset($this->knownOutputMime[$pref]) ){ $o['output'] = $this->knownOutputMime[$pref]; break; }
			}
			
			// If nothing found, fallback to the default output format
			//if ( empty($this->options['output']) ){ $this->options['output'] = _APP_DEFAULT_OUTPUT_FORMAT; }
			if ( empty($o['output']) || !in_array($o['output'], $this->availableOutputFormats) ){ $o['output'] = _APP_DEFAULT_OUTPUT_FORMAT; }
            
			$this->outputHandled = true;
		}
		
		return $this;
	}
	
	
	public function display()
	{
        $this->log(__METHOD__);
		
		$this->events->trigger('onBeforeDisplay', array('source' => array('class' => __CLASS__, 'method' => __FUNCTION__)));
		
		if ( !empty($this->options['output']) ){ $this->outputFormat(); }
		$of = &$this->options['output']; // Shortcut for the ouptput format

		$this->getErrors();
		$this->getWarnings();
		
		/*
		// Download file
		if (isset($file))
		{
			header("Pragma: public");
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Cache-Control: private",false);
			header("Content-Transfer-Encoding: binary");
			header("Content-Type: ".$this->getContentType($ext));
			header("Content-type: application/force-download; filename=".$filename);
			header("Content-Disposition: attachment; filename=".$filename);
			header("Content-Length: ".filesize($file));
			$ret = readfile($file);
		}
		*/
		
		// Special case for HTML (default)
		if ( $of === 'html' || $of === 'xhtml' )
		{

//$this->dump('memory used: ' . memory_get_usage());
//$this->dump('memory allocated: ' . memory_get_usage(true));

			// If the output format is xhtml, set the correct xhtml header
			// Otherwise let the server serve the proper header based on the accept http header passed by the browser
			if ( $this->options['outputExtension'] === 'xhtml' ){ $this->headers[] = 'Content-type: application/xhtml+xml; charset=utf-8;'; }

			$this->configSmarty();
            
			$cacheId = !empty($this->data['view']['cacheId']) ? $this->data['view']['cacheId'] : null;
            
			$this
				->writeHeaders()
				->prepareTemplate();
				
			$this
				->Smarty->display($this->template, $cacheId);
				//->Smarty->display($this->data['view']['template']);
			exit();
		}
		
		$this->cleanOutput();
		
		if ( $of === 'json' )
		{
			$this->headers[] = 'Content-type: application/json; charset=utf-8;';
			$this->writeHeaders();
			$json = json_encode($this->data);
			//$json = htmlspecialchars_decode($json, ENT_QUOTES); 
			//$json = html_entity_decode($json, ENT_QUOTES, 'UTF-8');
			//$json = utf8_encode(str_replace(array('&#39;','&#34;'),array("'", '"'), $json));
			$json = utf8_encode($json);
			//$json = str_replace(array('&#39;','&#34;'),array("'", '\\"'), $json);
			$json = str_replace(array('&#39;','&#34;', '&amp;#39;', '&amp;#34;'), array("'", '\\"', "'", '\\"'), $json);
			exit($json);
		}
		if ( $of === 'jsontxt' )
		{
			$this->headers[] = 'Content-type: plain/text; charset=utf-8;';
			$this->writeHeaders();
			$json = json_encode($this->data);
			//$json = htmlspecialchars_decode($json, ENT_QUOTES); 
			//$json = html_entity_decode($json, ENT_QUOTES, 'UTF-8');
			//$json = utf8_encode(str_replace(array('&#39;','&#34;'),array("'", '"'), $json));
			$json = utf8_encode($json);
			//$json = str_replace(array('&#39;','&#34;'),array("'", '\\"'), $json);
			$json = str_replace(array('&#39;','&#34;', '&amp;#39;', '&amp;#34;'), array("'", '\\"', "'", '\\"'), $json);
			exit($json);
		}
		elseif ( $of === 'jsonreport' )
		{
			$this->headers[] = 'Content-type: text/html; charset=utf-8;';
			$this->writeHeaders();
			$json = json_encode($this->data);
			//$json = htmlspecialchars_decode($json, ENT_QUOTES); 
			//$json = html_entity_decode($json, ENT_QUOTES, 'UTF-8');
			//$json = utf8_encode(str_replace(array('&#39;','&#34;'),array("'", '"'), $json));
			$json = utf8_encode($json);
			//$json = str_replace(array('&#39;','&#34;'),array("'", '\\"'), $json);
			$json = str_replace(array('&#39;','&#34;', '&amp;#39;', '&amp;#34;'), array("'", '\\"', "'", '\\"'), $json);
			
			$html = '';
			$html .= '<script type="text/javascript" src="' . _URL_JS . 'common/libs/jsonreport.js' . '"></script>';
			$html .= '<script type="text/javascript">window.onload = function(){ var json = document.getElementById("json"); json.innerHTML = _.jsonreport(document.getElementById("json").innerHTML) };</script>';
			$html .= '<div id="json" class="jsonreport">' . $json . '</div>';
			exit($html);
		}
		else if ( $of === 'xml' )
		{
			//$this->data = array('ws' => array('response' => $this->data));
			$content = !empty($this->resourceName) && !empty($this->data[$this->resourceName]) ? $this->data[$this->resourceName] : array();
			$rootObj = !empty($this->resourceName) ? $this->resourceName : 'data';
			$this->data = array($rootObj => $content);
			
			class_exists('php2XML') || require(_PATH_LIBS . 'converters/php2XML.class.php');
			$php2XML 	= new php2XML();
			//$this->headers[] = 'Content-type: application/xml; charset=utf-8;';
			$this->headers[] = 'Content-type: text/xml; charset=utf-8;';
			$this->writeHeaders();
			$output = $php2XML->process($this->data); 
			exit($output);
		}
		else if ( $of === 'plist' )
		{
			class_exists('Plist') || require(_PATH_LIBS . 'converters/Plist.class.php');
			$Plist = new Plist();
			$this->headers[] = 'Content-type: application/plist+xml; charset=utf-8;';
			$this->writeHeaders();
			$output = $Plist->convertIntoPlist($this->data, false);
            //$output = html_entity_decode($output, ENT_COMPAT, 'UTF-8');
            //$output = html_specialchars_decode($output, ENT_COMPAT, 'UTF-8');
            //$output = str_replace(array('<;','>'), array("&lt;", '&gt;'), $output);
			//$output = str_replace(array("<", '>', "'", '"'), array('&lt;','&gt;', '&apos;', '&quot;'), $output);
			exit($output);
		}
		else if ( $of === 'plistxml' )
		{
			class_exists('Plist') || require(_PATH_LIBS . 'converters/Plist.class.php');
            
			$Plist = new Plist();
			$this->headers[] = 'Content-type: text/xml; charset=utf-8;';
			$this->writeHeaders();
			$output = $Plist->convertIntoPlist($this->data, false);
			$output = str_replace(array('&#39;','&#34;', '&amp;#39;', '&amp;#34;'), array("'", '"', "'", '"'), $output);
			exit($output);
		}
		else if ( $of === 'yaml' )
		{
			class_exists('Spyc') || require(_PATH_LIBS . 'converters/spyc/spyc.php');
            
			$this->headers[] = 'Content-type: text/yaml; charset=utf-8;';
			$this->writeHeaders();
			exit(Spyc::YAMLDump($this->data));
		}
		else if ( $of === 'yamltxt' )
		{
			class_exists('Spyc') || require(_PATH_LIBS . 'converters/spyc/spyc.php');
            
			$this->headers[] = 'Content-type: plain/text; charset=utf-8;';
			$this->writeHeaders();
			exit(Spyc::YAMLDump($this->data));
		}
		else if ( $of === 'csv' )
		{
			/*
			class_exists('php2CSV') || require(_PATH_LIBS . 'converters/php2CSV/php2CSV.class.php');
			
			// Just keep real data and remove any other elements
			foreach(array('success','errors','warnings') as $item) { if ( isset($this->data[$item]) ) { unset($this->data[$item]); } }
			
			$php2CSV = new php2CSV();
			$output = $php2CSV->process($this->data);
			//$output = $php2CSV->process($this->data['entries']);
						
			//$this->headers[] = 'Content-type: text/csv; charset=utf-8;';
			//$this->writeHeaders();
			//exit($output);
			*/
		}
		else if ( $of === 'qr' )
		{
			class_exists('QRcode') || require(_PATH_LIBS . 'converters/phpqrcode.php');
			$this->writeHeaders();
			//exit(QRcode::png($this->data));
			//$url = $this->removeQueryParams('output', $this->currentURL());
			$url = Tools::removeQueryParams('output', $this->currentURL());
			$url = str_replace('.qr','', $url);
			exit(QRcode::png($url));
		}
		else if ( $of == 'rss' ) { /* TODO */ }
		else if ( $of == 'atom' ) { /* TODO */ }
		else if ( $of == 'rdf' ) { /* TODO */ }
		
		return $this;
	}
	
	
	public function cleanOutput()
	{
        $this->log(__METHOD__);
		
		unset($this->data['view']);
		
		return $this;
	}
	
	
	public function breadcrumbs()
	{
        $this->log(__METHOD__);
		
		$data = array();
		
		$foobar = get_class($this);
		
		return $data;
	}
	
	public function getCSS()
	{
        $this->log(__METHOD__);
		
		$this->css = array();
        
        if ( isset($this->options['css']) && !$this->options['css']  ){ return $this->css; }
		
		// Shortcuts
		$v 			= &$this->data['view'];
		
		// If the view is explicitely specified as not containing css, do not continue
		if ( isset($v['css']) && $v['css'] === false ){ return $this->css; }

		// Load css associations file
		isset($cssAssoc) || require(_PATH_CONFIG . 'cssAssoc.php');
		
		// Get specific css if defined
		$specCss 		= !empty($v['css']) 
							? ( is_string($v['css']) ? explode(',',$v['css']) : $v['css'] ) 
							: array();
		// Default css group
		$defCssGroup 	= 'default';

		// Try to find smartGroups using smartClasses if found, otherwise try to use view name
		// If nothing is found, will keep defaut css group
		$smartGroups 	= !empty($v['smartclasses']) ? explode(' ',$v['smartclasses']) : ( !empty($v['name']) ? (array) $v['name'] : array() );
		$i 				= count($smartGroups);					
		while ($i--)
		{
			// Only process existing css groups 
			if ( empty($smartGroups[$i]) || empty($cssAssoc[$smartGroups[$i]]) ){ continue; }
			else { $defCssGroup = $smartGroups[$i]; break; }
		} 
		
		// If specific css have been defined
		if ( !empty($specCss) ) 
		{			
			foreach ( $specCss as $val )
			{
				// Do not process empty values
				if 		( empty($val) )													{ continue; }
				
				// If the value does not contains .css, assume it's a css group name
				//else if ( strpos($val, '.css') === false && !empty($cssAssoc[$val]) ) 	{ $this->css += $cssAssoc[$val]; }
				else if ( strpos($val, '.css') === false && !empty($cssAssoc[$val]) ) 	{ $this->getCSSgroup($val); }
				
				// If the value is prefixed by '--', remove the css from the list
				else if ( strpos($val, '--') !== false )
				{
					$k = array_search(str_replace('--','',$val), $this->css);
					if ($k !== false) { unset($this->css[$k]); }
				}
				
				// Otherwise, and if not already present, add it to the css array
				else if ( empty($this->css[$val]) )									{ $this->css[] = $val; }
			}	
		}
		// Otherwise, use css group
		else
		{
			$this->getCSSgroup($defCssGroup);
		}
		
		// Specific case
		if ( _SUBDOMAIN === 'iphone' || $this->platform['name'] === 'iphone' ){ $this->getCSSgroup('iphone'); }
		else if ( _SUBDOMAIN === 'ipad' || $this->platform['name'] === 'ipad' ){ $this->getCSSgroup('ipad'); }
		else if ( _SUBDOMAIN === 'android' || $this->platform['name'] === 'android' ){ $this->getCSSgroup('android'); }
		
		return $this->css;
	}
	
	public function getCSSgroup($groupeName)
	{
        $this->log(__METHOD__);
        
		// Load css associations file
		isset($cssAssoc) || require(_PATH_CONFIG . 'cssAssoc.php');
		
		// Do not continue if the group name does not exists or is empty
		if ( empty($groupeName) || empty($cssAssoc[$groupeName]) ) { return $this->css; }
		
		// Loop over the group items
		foreach ( $cssAssoc[$groupeName] as $val )
		{
			// Skip the item if it is empty ()
			if ( empty($val) ){ continue; }
			
			// If the value does not contains .css, assume it's a css group name
			//if ( strpos($val, '.css') === false && !empty($cssAssoc[$val]) ) 	{ $this->getCSSgroup($val); }
			if ( strpos($val, '.css') === false && isset($cssAssoc[$val]) ) 	{ $this->getCSSgroup($val); }
			
			// If the value is prefixed by '--', remove the css from the list
			else if ( strpos($val, '--') !== false )
			{
				$k = array_search(str_replace('--','',$val), $this->css);
				if ($k !== false) { unset($this->css[$k]); }
			}
		
			// Otherwise, and if not already present, add it to the css array
			else if ( empty($this->css[$val]) )								{ $this->css[]  = $val; }
		}
		
		return $this;
	}
	
	
	public function getJS()
	{
        $this->log(__METHOD__);
		
		$this->js = array();
        
        if ( isset($this->options['js']) && !$this->options['js']  ){ return $this->js; }
		
		// Shortcuts
		$v 			= &$this->data['view'];
		
		// If the view is explicitely specified as not containing js, do not continue
		if ( isset($v['js']) && $v['js'] === false ){ return $this->js; }

		// Load js associations file
		isset($jsAssoc) || require(_PATH_CONFIG . 'jsAssoc.php');
		
		// Get specific js if defined
		$specJs 		= !empty($v['js']) 
							? ( is_string($v['js']) ? explode(',',$v['js']) : $v['js'] ) 
							: array();
		// Default js group
		$defJsGroup 	= 'default';

		// Try to find smartGroups using smartClasses if found, otherwise try to use view name
		// If nothing is found, will keep defaut js group
		$smartGroups 	= !empty($v['smartclasses']) ? explode(' ',$v['smartclasses']) : ( !empty($v['name']) ? (array) $v['name'] : array() );
		$i 				= count($smartGroups);					
		while ($i--)
		{
			// Only process existing js groups 
			if ( empty($smartGroups[$i]) || empty($jsAssoc[$smartGroups[$i]]) ){ continue; }
			else { $defJsGroup = $smartGroups[$i]; break; }
		}
		
		// If specific js have been specified
		if ( !empty($specJs) ) 
		{			
			foreach ( $specJs as $val )
			{
				// Do not process empty values
				if 		( empty($val) )													{ continue; }
				
				// If the value does not contains .js, assume it's a js group name
				else if ( strpos($val, '.js') === false && isset($jsAssoc[$val]) ) 	{ $this->getJSgroup($val); }
				
				// If the value is prefixed by '--', remove the js from the list
				else if ( strpos($val, '--') !== false )
				{
					$k = array_search(str_replace('--','',$val), $this->js);
					if ($k !== false) { unset($this->js[$k]); }
				}
				
				// Otherwise, and if not already present, add it to the js array
				else if ( empty($this->js[$val]) )										{ $this->js[] = $val; }
			}	
		}
		// Otherwise, use js group
		else { $this->getJSgroup($defJsGroup); }

		return $this->js;
	}
	
	public function getJSgroup($groupeName)
	{
        $this->log(__METHOD__);
        
		// Load js associations file
		isset($jsAssoc) || require(_PATH_CONFIG . 'jsAssoc.php');
		
		// Do not continue if the group name does not exists or is empty
		if ( empty($groupeName) || empty($jsAssoc[$groupeName]) ) { return $this->js; }
		
		// Loop over the group items
		foreach ( $jsAssoc[$groupeName] as $val )
		{
			// Skip the item if it is empty ()
			if ( empty($val) ){ continue; }
			
			// If the value does not contains .js, assume it's a js group name (we then have to loop over this group name)
			if ( strpos($val, '.js') === false && isset($jsAssoc[$val]) ) 		{ $this->getJSgroup($val); }
		
			// Otherwise, and if not already present, add it to the js array
			else if ( empty($this->js[$val]) )									{ $this->js[] = $val; }
		}
		
		return $this;
	}
	
	
	public function getErrors()
	{
        $this->log(__METHOD__);
		
		// Store current errors (error codes)
		$urlErrors = !empty($this->options['errors']) ? explode(',',$this->options['errors']) : array();
		//$tmpErrors = array_merge((array) $this->data['errors'], $urlErrors);
		// array_merge fails on associative arrays whose keys are valid numerics
		// ie: array_merge(array(1001 => 'somevalue'), array('foo')) results int array(0 => 'somevalue') (expected: array('1001' => 'somevalue', 0 => 'foo')
		//$tmpErrors = (array) $this->data['errors'] + $urlErrors;
		$tmpErrors = !empty($this->data['errors']) ? (array) $this->data['errors'] + $urlErrors : null;
		
		// If there's no errors, do not continue
		if ( empty($tmpErrors) ) { return $this; }
		
		// Load errors association file
		isset($errorsAssoc) || require(_PATH_CONFIG . 'errorsAssoc.php');
		
		// Init the errors array
		$this->data['errors'] = array();
		
		// Loop over the errors
		foreach ($tmpErrors as $key => $val)
		{			
			// If the item index is not > 1000, assume that it's not a 'native' array index but a defined error code
			$hasParams 	= is_numeric($key) && $key > 1000;
			$errCode 	= $hasParams ? $key : $val;
			
			if ( !isset($errorsAssoc[$errCode]) ){ continue; }
			
			$err = $errorsAssoc[$errCode];
			
			// For each one of them, go get the related error message and reconstruct errors array associating codes to messages 
			//$this->data['errors'][] = array('id' => $errCode, 'message' => $errorsAssoc[$errCode]);
			$this->data['errors'][] = array(
				'id' 		=> $errCode, 
				'log' 		=> sprintf($err['back'], ($hasParams ? $val : null)),
				'message' 	=> sprintf($err['front'], ($hasParams ? $val : null)),
				// TODO: replace buttons by actions = (label => url)*
				'buttons' 	=> !empty($err['buttons']) ? $err['buttons'] : null, 
			);
		}
		
		return $this;
	}
	
	
	public function getWarnings()
	{
        $this->log(__METHOD__);
		
		// If there's no warnings, do not continue
		if ( empty($this->data['warnings']) ) { return $this; }
		
		// Load errors association file
		isset($errorsAssoc) || require(_PATH_CONFIG . 'errorsAssoc.php');
		
		// Store current errors (error codes)
		$tmpErrors = $this->data['warnings'];
		
		// Init the warnings array
		$this->data['warnings'] = array();
		
		// Loop over the error codes
		foreach ($tmpErrors as $key => $val)
		{
			// If the item index is not > 1000, assume that it's not a 'native' array index but a defined error code
			$hasParams 	= is_int($key) && $key > 1000;
			$errCode 	= $hasParams ? $key : $val;
			
			if ( !isset($errorsAssoc[$errCode]) ){ continue; }
			
			// For each one of them, go get the related error message and reconstruct errors array associating codes to messages 
			//$this->data['errors'][] = array('id' => $errCode, 'message' => $errorsAssoc[$errCode]);
			$this->data['warnings'][] = array(
				'id' 		=> $errCode, 
				'log' 		=> sprintf($errorsAssoc[$errCode]['back'], ($hasParams ? $val : null)),
				'message' 	=> sprintf($errorsAssoc[$errCode]['front'], ($hasParams ? $val : null)),
			);
		}
		
		return $this;
	}
	
	
	public function respondError($statusCode, $entityBody = '')
	{
        $this->log(__METHOD__);
	    
		return $this->statusCode($statusCode, $entityBody);
	}
	
	public function statusCode($statusCode, $entityBody = '')
	{
        $this->log(__METHOD__);
		
		switch($statusCode)
		{
			case 201: 	$h = '201 Created'; 				break;		
			case 204: 	$h = '204 No Content'; 				break;
			case 302: 	$h = '302 Found'; 					break;
			case 400: 	$h = '400 Bad Request'; 			break;
			case 401: 	$h = '401 Unauthorized'; 			break;
			case 402: 	$h = '402 Payment Required'; 		break;
			case 403: 	$h = '403 Forbidden'; 				break;
			case 404: 	$h = '404 Not Found'; 				break;
			case 405: 	$h = '405 Method Not Allowed'; 		break;
			case 406: 	$h = '406 Not Acceptable'; 			break;
			case 408: 	$h = '408 Request Timeout'; 		break;
			case 409: 	$h = '409 Conflict'; 				break;
			case 410: 	$h = '410 Gone'; 					break;
			case 412: 	$h = '412 Precondition Failed'; 	break;
			case 415: 	$h = '415 Unsupported Media Type'; 	break;
			case 417: 	$h = '417 Expectation Failed'; 		break;
			case 500: 	$h = '500 Internal server error'; 	break;
			case 503: 	$h = '503 Service unavailable'; 	break;
			case 200:
			default: 	$h = '200 OK'; 						break;
		}
		
		//header('HTTP/1.1 ' . $h); 
		//$this->headers[] = 'HTTP/1.1 ' . $h;
		
		// As long as the output format is not html and the code not 201
		// (sending) a 201 
		//if ( !in_array($this->options['output'], array('html','xhtml')) && !in_array($h, array(201)) )
		if ( !in_array($this->options['output'], array('html','xhtml')) || !in_array($h, array(201)) )
		{
			// Add it to the headers
			$this->headers[] = 'HTTP/1.1 ' . $h;
		}
		
		$this->data = array_merge($this->data, array(
			//'request' 	=> str_replace('&', '&amp;', $_SERVER['REQUEST_URI']),
			//'status' 	=> (int) $statusCode,
		));

		//return $this->display();
		//return in_array($this->options['output'], array('html','xhtml')) ? $this : $this->display();
		return $this->render();
	}
	
	
	public function writeHeaders()
	{
        $this->log(__METHOD__);
		
		foreach ($this->headers as $item){ header($item); }
		
		// Do not add anything in the response body if a 204 status code if thrown
		$s = !empty($this->data['status']) ? $this->data['status'] : null;
		if ( $s === 204 ){ die(); }
		
		return $this;
	}
	
	
	public function beforeRender($options = array())
	{
        $this->log(__METHOD__);
		
		return $this;
	}
	
	
	public function smartname()
	{
        $this->log(__METHOD__);
        
		$smartname = '';
		
		$v = !empty($this->data['view']) ? $this->data['view'] : array();
		
		/*
		if ( !empty($this->resourceName) )
		{
			$tmp = preg_replace('/-([a-z]{1})/e', "ucfirst('$1')", join('-', $this->data['metas'][$this->resourceName]['breadcrumbs']));
		}
		else if ( !empty($this->resourceGroupName) )
		{
			$tmp = $this->resourceGroupName;
		}
		else { $tmp = ''; }
		
		//$method = !empty($this->data['current']['method']) ? $this->data['current']['method'] : 'index';
		$method = !empty($v['method']) ? $v['method'] : 'index';
		*/
		
		return $smartname;
	}
	
	
	public function smartclasses()
	{
        $this->log(__METHOD__);

		// Set shortcuts
		$d = &$this->data;
		$v = &$d['view'];
				
		// Get uri parts
//var_dump(pathinfo($_SERVER['REQUEST_URI']));
//var_dump(parse_url($_SERVER['REQUEST_URI']));
		//$uriParts = parse_url($_SERVER['REQUEST_URI']);
		$uriParts = @parse_url(_URL . ltrim($_SERVER['REQUEST_URI'], '/'));
//var_dump(explode('/', ltrim(str_replace('.' . $this->options['output'], '', $uriParts['path']), '/')));
//die();
 		$pathParts 	= explode('/', ltrim(str_replace('.' . $this->options['output'], '', $uriParts['path']), '/'));
//$this->dump($pathParts);
		
        // Get user groups
        $uGps       = !empty($d['current']['user']['group_admin_titles']) ? explode(',',$d['current']['user']['group_admin_titles']) : array();
		foreach ($uGps as &$gp) { $gp = 'group' . ucfirst($gp); }

		// 
		$classes = array_merge(array(
			//_DOMAIN, 
			_SUBDOMAIN,																		// subdomain 
		), $pathParts, array(
			// TODO: keep only one of the 2 following items
			isset($this->resourceName) ? (string) $this->resourceName : '', 
			isset($d['current']['resource']) ? (string) $d['current']['resource'] : '', 	// current resource
			isset($v['method']) ? (string) $v['method'] : '', 								// current resource method
			isset($v['name']) 	? (string) $v['name'] 	: '', 								// deprecated
			isset($v['smartname']) 	? (string) $v['smartname'] 	: '', 						// 
		), $uGps);
		
		$classes = array_unique($classes);
		
//$this->dump($classes);
		
		//return $classes;
		return join(' ', $classes);
	}
	
	
	public function prepareTemplate()
	{
        $this->log(__METHOD__);
		
		$v                    = &$this->data['view'];
		$v['smartname']       = $this->smartname();
		$v['smartclasses']    = $this->smartclasses();
		$v['isAjaxRequest']   = $this->isAjaxRequest;
		
		$curURL     = $this->currentURL();

		// Merge resourceData, processingData and viewData
		$this->data = array_merge($this->data, array(
			'platform' 			=> $this->platform,
			'browser'			=> $this->browser,
			'device'            => $this->device,
			'env' 				=> $this->env,
			'options'			=> $this->options,
			'css' 				=> $this->getCSS(),
			'js' 				=> $this->getJS(),
			'debug' 			=> $this->debug,
			//'logged'             => $this->logged,
			'logged' 			=> $this->application->logged,
			'current' 			=> array_merge((array) @$this->data['current'], array(
                'url'                       => $curURL,
                'urlParams'                 => Tools::getURLParams($curURL),
                'resource' 					=> !empty($this->resourceName) ? $this->resourceName : null,
			)),
		));
		
		if ( isset($v['cache']) && !$v['cache'] ){ $this->Smarty->caching = 0; }  

		// Pass vars to the templates 
		$this->Smarty->assign(array(
			'data' 			=> $this->data,
		));
        
        // Fix required since smarty 3.0.5 that use defined error reporting level by
        $this->Smarty->error_reporting  = E_ALL & ~E_NOTICE;
		
		// Get the layout/template to use
		$v['template']    = $this->smartTemplate();
		$this->template   = $v['template'];
	
		return $this;	
	}
	
	
	public function smartTemplate()
	{
        $this->log(__METHOD__);
        
		$v = &$this->data['view'];
		
		if ( !empty($v['template']) )
		{
			$tpl         = $v['template'];
		}
		else
		{
			// TODO: try to gess template folders using breadcrumb ?
			$folders     = !empty($this->breadcrumbs) ? join('/', $this->breadcrumbs) : '';
			$v['method'] = !empty($v['method']) ? $v['method'] : 'index';
			$v['name']   = !empty($v['name']) ? $v['name'] : 'home';
			
			// Otherwise
			$tpl         = 'common/pages/' . ( !empty($folders) ? '/' : '' ) . $v['name'] . '/' . $v['method'] . '.tpl';
		}
		
		return $tpl;
	}
		
	
	public function render()
	{
        $this->log(__METHOD__);
		
		$this->events->trigger('onBeforeRender', array('source' => array('class' => __CLASS__, 'method' => __FUNCTION__)));
		
		return $this->display();
	}
		
}

?>