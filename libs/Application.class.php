<?php

//$appStartTime = microtime(true);

class Application
{
	var $debug 	= false;
	//var $logged = null;
	var $logged = false;
	var $inited = false;
	
	public function __construct()
	{
		return $this->init();
	}
	
	public function init()
	{
        $this->log(__METHOD__);
        
		if ( $this->inited ) { return $this; }
		
		// 
		spl_autoload_register('Application::__autoload'); 
		
        //$this->configEnv();
		$this->handleSession();
		$this->setlanguage();
        
		
		$this->inited = true;
	}
	
	static function __autoload($className)
	{
        
		$firstLetter 	= $className[0];
		$secondIsUpper 	= $className[1] === strtoupper($className[1]);
		
		if 		( $firstLetter === 'M' && $secondIsUpper)	{ $type = 'model'; 		$path = _PATH_MODELS; }
		elseif 	( $firstLetter === 'C' && $secondIsUpper)	{ $type = 'controller'; $path = _PATH_CONTROLLERS; }
		elseif 	( $firstLetter === 'V' && $secondIsUpper)	{ $type = 'view'; 		$path = _PATH_VIEWS; }
		else 												{ $type = 'lib'; 		$path = _PATH_LIBS; }
		
		class_exists($className) || (file_exists($path . $className . '.class.php') && require($path . $className . '.class.php'));
	}
    
    
    public function setResource($options = array())
    {
        //$this->log(__METHOD__);
        
        // Do not continue if the resourceName is already defined
        if ( !empty($this->resourceName) ){ return $this; }
        
        $o          = &$options;        

        $name       = !empty($o['name']) ? (string) $o['name'] : null;
        $singular   = !empty($o['singular']) ? (string) $o['singular'] : null;
        
        //if ( !empty($o['controller']) ){ $name = strtolower(preg_replace('/^C(.*)/','$1', $o['controller'])); }
        if ( !empty($o['class']) ){ $name = strtolower(substr($o['class'], 1)); }
        
        $this->resourceName     = $name;
        //$this->resourceSingular = !empty($singular) ? $singular : $this->singularize((string) $name);
        $this->resourceSingular = !empty($singular) ? $singular : Tools::singularize((string) $name);
        
        return $this;
    }
	
	
	// TODO: clean & refactor
	public function setlanguage($lang = '')
	{
		$this->log(__METHOD__);
		
		$accept 	= !empty($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : null;
		$detected 	= array(
						'primary' 	=> $accept[0].$accept[1],
						'secondary' => strlen($accept) > 5 
							? substr($accept,3,2)."_".strtoupper(substr($accept,6,2)) 
							: substr($accept,0,2)."_".strtoupper(substr($accept,3,2)),
		);
		
		$locale 	= ( !empty($_GET['lang']) && strlen($_GET['lang']) === 5 		// Second priority level: lang url GET param
							? $_GET['lang'] 
							: ( !empty($_SESSION['lang']) && strlen($_SESSION['lang']) === 5 // Third priority level: lang session value
								? $_SESSION['lang'] 
								: ( !empty($detected)	// Fourth priority level: try to use detection through
									? strtolower($detected['primary']) . "_" . strtoupper($detected['primary']) 
									: _APP_DEFAULT_LANGUAGE 							// Otherwise, use default configurated app language
								)  
							)
						);
		
		// Special case when coming from iphone app
		if ( !empty($_GET['referer']) )
		{
			$appLang 	= $this->getURLParamValue($_GET['referer'], 'appLang');
			$locale 	= strtolower($appLang) . '_' . strtoupper($appLang);
		}
		 
		$lang 		= substr($locale,0,2);
		putenv('LANG='.$lang.'utf8');
		$lc = setlocale(LC_ALL, $locale.'.utf8', $locale, $lang);
		bindtextdomain(_APP_NAME, _PATH_I18N);
		textdomain(_APP_NAME);
		bind_textdomain_codeset(_APP_NAME, 'UTF-8');
		
		$_SESSION['lang'] 	= $locale;
		
		return $this;
	}
	
	
	public function handleSession()
	{
        $this->log(__METHOD__);
		
		// Do not continue if the accounts system (and so db sessions) is not used
		if ( !_APP_USE_ACCOUNTS ){ return $this; }
		//if ( !_APP_USE_ACCOUNTS || ( isset($this->noSession) && $this->noSession ) ){ return $this; }
		
		// If setted to true, allow sessions to be available for other subdomains
		if ( _APP_IS_SESSION_CROSS_SUBDOMAIN ) { ini_set('session.cookie_domain', '.' . _DOMAIN); /*session_set_cookie_params(0, '/', '.' . _DOMAIN);*/  }
		
		// Set the session name accordingly to the conf
		session_name(_SESSION_NAME);
		
		// Specific case for session forwarding from iphone/ipod/ipad app to safari where the session id is 
		// passed in the URL.
		$ua = $_SERVER['HTTP_USER_AGENT'];
		if ( (strpos($ua, 'iPhone') !== false || strpos($ua, 'iPod') !== false || strpos($ua, 'iPad') !== false ) && !empty($_GET[_SESSION_NAME]) )
		{			
			// Get the data of the passed session id
			$s = CSessions::getInstance()->retrieve(array('values' => $_GET[_SESSION_NAME], 'sortBy' => 'expiration_time', 'orderBy' => 'DESC', 'limit' => 1));
			
			// If the client ip match the passed session's one, set it as the current session id
			if ( !empty($s) && $_SERVER['REMOTE_ADDR'] === $s['ip'] )
			{
				session_id($_GET[_SESSION_NAME]);
				session_start(); 
				$_SESSION['id'] 		= $_GET[_SESSION_NAME];
				$_SESSION['user_id'] 	= $s['user_id'];
			}
		}
		
		// Start the session if not already started 
		if ( session_id() === '') { session_start(); }
		
		// Get the current session id
		$sid = session_id();
		
		// Set session updated POST data
		$newPOST = array(
			'expiration_time' 	=> (time() + _APP_SESSION_DURATION),
			'last_url' 			=> $this->currentURL(),
		);
		foreach ($newPOST as $key => $val) { $_POST['session' . ucfirst($key)] = $val; }
		
		// Try to update the session in db, if exists and not already expired
		$CSessions = CSessions::getInstance()->update(array(
			'sortBy' => 'expiration_time',
			'orderBy' => 'DESC',
			'limit' => 1,
			'conditions' 	=> array(
				'name' => $sid,
				array('expiration_time', '>', ( !empty($_SERVER['REQUEST_TIME']) ? $_SERVER['REQUEST_TIME'] : time() ) ),
				
			)
		)); 
		
		// If rows have been affected, it means that the user is properly logged (cause the session exists and is not expired)
		$this->logged = $CSessions->success && $CSessions->model->affectedRows > 0;
		
		// Once this is done, unset previously setted POST
		foreach ($newPOST as $key => $val) { unset($_POST['session' . ucfirst($key)]); }
		
		return $this;
	}
	
	
	
	public function isLogged()
	{
        $this->log(__METHOD__);
		
		// Do not continue if the accounts system (and so db sessions) is not used
		if ( !_APP_USE_ACCOUNTS ){ return $this; }
		
		$this->logged = false;
		
		$session 	= CSessions::getInstance()->retrieve(array(
			'values' 	=> session_id(), 
			'sortBy' 	=> 'expiration_time',
			'orderBy' 	=> 'DESC', 'limit' => 1
		));

		// Has the session been found and is it always valid (not expired)
		$sessExp	 	= !empty($session) 
							? (is_numeric($session['expiration_time']) ? $session['expiration_time'] : strtotime($session['expiration_time']) )
							: null;
		$time 			= !empty($_SERVER['REQUEST_TIME']) ? $_SERVER['REQUEST_TIME'] : time();
		$this->logged 	= !empty($sessExp) && $sessExp > $time && ( !empty($_SESSION['id']) && $_SESSION['id'] === $session['name'] );
		
		return $this->logged;
	}
	
	
	public function requireLogin()
	{
        $this->log(__METHOD__);
		
		// Do not continue if the accounts system (and so db sessions) is not used
		if ( !_APP_USE_ACCOUNTS ){ return $this; }
		
		// Force timezone
		date_default_timezone_set('UTC');
		
		$curURL = $this->currentURL();
		$t 		= parse_url($curURL);
		$redir 	= $t['scheme'] . '://' . $t['host'] . $t['path'] . ( !empty($t['query']) ? urlencode('?' . $t['query']) : '') . (!empty($t['fragment']) ? $t['fragment'] : '');
        
		// TODO: add proper error. Require data/success/errors/warnings to be shared accross app
		if ( !$this->isLogged() )
		{	
			$this->data['errors'][] = 10100;		
			return $this->redirect(_URL_LOGIN . '?successRedirect=' . $redir);
		}
		
		return $this;
	}
	
	
	/* 
	 * This function gets, in an URL string, the value of the param given in the function call
	 * @author Guyllaume Doyer guyllaume@clicmobile.com
	 * @return {String|Boolean} The value if found, otherwise false
	 */
	// TODO: refactor using parse_str() ???
	public function getURLParamValue($requestedURL, $requestedParamName)
	{
        $this->log(__METHOD__);
		
		// Get start position of the param from the ?
		$markP 			= strpos($requestedURL, "?");
		$requestedURL 	= substr($requestedURL, $markP, strlen($requestedURL));
		$pos 			= strpos($requestedURL, $requestedParamName);
		
		if ($pos != -1 && $requestedParamName != "")
		{
			// Truncate the string from this position to its end
			$tmp = substr($requestedURL, $pos);
			
			// Get end position of the param value
			if 		( strpos($tmp, "&amp;") !== false ) { $end_pos = strpos($tmp, "&amp;"); } // case where there are others params after, separated by a "&amp;"
			else if ( strpos($tmp, "&") !== false ) 	{ $end_pos = strpos($tmp, "&"); } // case where there are others params after, separated by a "&"
			else if ( strpos($tmp, "#") !== false ) 	{ $end_pos = strpos($tmp, "#"); } // case where there are others params after, separated by a "#"
			else 										{ $end_pos = strlen($tmp); } // case where there are no others params after
			
			// Truncate the string from 0 to the end of the param value
			$requestedParamValue = substr($tmp, strlen($requestedParamName) + 1, $end_pos);
			
			return $requestedParamValue;
		}
		else { return false; }
	}
	
	
	public function dispatch($uri = '')
	{
        $this->log(__METHOD__);
		
		$uri 			= $uri === '' ? $_SERVER["PATH_INFO"] : $uri;
		$s 				= explode("/", $uri);				// Split the URI into segments
		$nothing 		= array_shift($s); 								// Remove the array's first & last elements (always empty)
		$nothing 		= array_pop($s);

		// View class handling
		$view 			= array('folders' => array(), 'path' => '', 'name' => 'home');

		// Remove extension from path resource name, if present
		foreach($s as &$item) { $item = preg_replace('/(.*)\.(.*)/', '$1', $item); }
		
		// Loop over segments parts (URI parts) to find the deeper existing view folder
		// and then set the proper view to use
		$i 		= 0;
		$tmp 	= array();
		while ( !empty($s[$i]) )
		{			
			// Temp value for view folder, view name, view path 
			$tmp['f'] 		= strtolower($s[$i]);
			$tmp['v'] 		= !empty($s[$i+1]) ? $s[$i+1] : $tmp['f'];
			$tmp['path'] 	= _PATH_VIEWS . ( !empty($view['folders'] ) ? join('/', $view['folders']) . '/' : '');
			
			// If the folder and at least a view named 'V{foldername}.class.php' exist, we can continue
			//if ( is_dir($tmp['path'] . '/' . $tmp['f'] ) && file_exists($tmp['path'] . $tmp['f'] . '/V' . ucfirst($tmp['f']) . '.class.php') )
			if ( is_dir($tmp['path'] . $tmp['f'] ) && file_exists($tmp['path'] . $tmp['f'] . '/V' . ucfirst($tmp['f']) . '.class.php') )
			{
				$view['folders'][] 	= $tmp['f'];
				$fileExists 		= file_exists( $tmp['path'] . '/V' . ucfirst($tmp['v']) . '.class.php' );
				$view['name'] 		= $fileExists ? $tmp['v'] : $tmp['f'];
				$i++;
			}
			else if ( file_exists($tmp['path'] . 'V' . ucfirst($tmp['f']) . '.class.php') )
			{
				$view['name'] 		= $tmp['f'];
				break;
			}
			// Otherwise, we have to break here
			else
			{
				break;
			}
		}

		// Set final values
		$view['path'] 		= _PATH_VIEWS . (!empty($view['folders']) ? join('/', $view['folders']) . '/' : '');
		$view['fullname'] 	= 'V' . ucfirst($view['name']);
		// TODO: handle uppercase in view name more properly (strolower on $segments + create ini segments copy to keep correct case for method params ??)
		$lim 				= in_array($view['name'], $s) 		
								? array_search($view['name'], $s) // handle /viewname/.../method/param URIs (full lower case) 
								: ( in_array(ucfirst($view['name']), $s) ? array_search(ucfirst($view['name']), $s) : 0 ); // handle /Viewname/.../method/param URIs (capitalised)
		$s 					= array_slice($s,  $lim+1);
		
		
		// If the file is correctly loaded
		if( require($view['path'] . '/' . $view['fullname'] . '.class.php') )
		{
			// Get method and arguments
			$method		= !empty($s[0]) && method_exists($view['fullname'], $s[0]) ? array_shift($s) : 'index';			
			$arguments 	= !empty($s) ? $s : array();
			
			// Call the proper function with the proper arguments after having instanciated the proper function
			return call_user_func_array(array(new $view['fullname']($this), $method), $arguments);
		}
		// If an error occured, we load the 404 page
		else
		{
			class_exists('controller') || require _PATH_LIBS . 'Controller.class.php';
			
			Controller::redirect(_URL_404);
		}
	}
	
    
	public function dump($data, $options = null)
	{        
		//if ( in_array(_APP_CONTEXT, array('local','dev')) || $this->options['debug'] )
		if ( in_array(_APP_CONTEXT, array('local','dev')) )
		{
			//class_exists('FirePHP') || require(_PATH_LIBS . 'tools/FirePHPCore/FirePHP.class.php');
			//class_exists('FirePHP') || require(_PATH_LIBS . 'tools/FirePHP/FirePHP/fb.php'); 
            //class_exists('FirePHP') || require(_PATH_LIBS . 'tools/FirePHP/FirePHP/Init.php');
            class_exists('FirePHP') || require(_PATH_LIBS . 'tools/FirePHP/FirePHPCore/FirePHP.class.php');
            
            //var_dump($data);
			FirePHP::getInstance(true)->log($data);
		}
		
		return $this;
	}
    
    
    public function log($data = null, $options = null)
    {        
        return ($this->debug || (isset($this->options['debug']) && $this->options['debug'])) ? $this->dump($data, $options) : null;
    }


	public function isInDebugMod()
	{
        $this->log(__METHOD__);
        
		return ($this->debug || $this->options['debug']) && in_array(_APP_CONTEXT, array('local','dev', 'preprod'));
	}

	
	public function configEnv()
	{
        $this->log(__METHOD__);
        
		$this->env = array(
			'name' => _APP_CONTEXT,
			'type' => in_array(_APP_CONTEXT, array('local','dev')) && !isset($_GET['PRODJS']) ? "dev" : "prod",
		);
        
		if ( $this->env['type'] === 'dev' )
		{
			if ( _ALLOW_FIREPHP_LOGGING )
			{		
				ob_start();
				
				class_exists('FirePHP') || require(_PATH_LIBS . 'tools/FirePHP/FirePHPCore/FirePHP.class.php');
                //class_exists('FirePHP') || require(_PATH_LIBS . 'tools/FirePHP/FirePHP/Init.php');
				
                //define('INSIGHT_IPS', '*');
                //define('INSIGHT_AUTHKEYS', 'AAC8FCFBD667AE9AC51A54D3318CA411');
                //define('INSIGHT_PATHS', _PATH_LIBS . 'tools/FirePHP/Insight');
                //define('INSIGHT_SERVER_PATH', '/index.php');	
			}
			
			error_reporting(E_ALL);
			
			//$this->Smarty->debugging 		= true;
			
			ini_set('xdebug.var_display_max_depth', 6);
			//ini_set('xdebug.var_display_max_data', 4096);
			ini_set('xdebug.var_display_max_data', 40000);
		}
		else
		{
			// Report simple running errors
			error_reporting(E_ERROR | E_PARSE);
		}

		// Force timezone
		//$old = date_default_timezone_get();
		date_default_timezone_set('UTC');
		
		return $this;
	}
	
	
	public function currentURL()
	{
        $this->log(__METHOD__);
		
		if ( isset($this->currentURL) ){ return $this->currentURL; }
		
		//$this->currentURL = 'http' . ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 's' :'' ) . '://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
		$this->currentURL = Tools::getCurrentURL();
		
		return $this->currentURL;
	}
	
    
	public function isSubdomain($subdomain)
	{
        $this->log(__METHOD__);
		
		$domain = _DOMAIN;
		$sn 	= $_SERVER['SERVER_NAME']; // Shortcut for server name
		
		// Specific case
		if ( _APP_CONTEXT === 'dev' || _APP_CONTEXT === 'prod' )
		{
			$sn .= $_SERVER['REQUEST_URI'];
			
			//if ( strpos($sn, $domain . '/' . $subdomain . '/') > -1 ) { return true; }
			if ( preg_match('/' . $domain . '\/+' . $subdomain . '/i', $sn) ) { return true; }
		}
		
		return strpos($sn, $subdomain . '.' . $domain) > -1 ? true : false;
	}
	
	
	public function requireClass($name, $type, $shortPath = '')
	{
        $this->log(__METHOD__);
		
		switch ($type)
		{
			//case 'libs': 		class_exists($name) || require(_PATH_LIBS . ( !empty($shortPath) ? $shortPath : $name ) . '.class.php');
			case 'libs': 		class_exists($name) || require(_PATH_LIBS . ( !empty($shortPath) ? $shortPath : '' ) . $name . '.class.php');
			//case 'controllers': class_exists($name) || require(_PATH_CONTROLLERS . ( !empty($shortPath) ? $shortPath : $name ) . '.class.php');
			case 'controllers': class_exists($name) || require(_PATH_CONTROLLERS . ( !empty($shortPath) ? $shortPath : '' ) . $name . '.class.php');
			//case 'models': 		class_exists($name) || require(_PATH_MODELS . ( !empty($shortPath) ? $shortPath : $name ) . '.class.php');
			case 'models': 		class_exists($name) || require(_PATH_MODELS . ( !empty($shortPath) ? $shortPath : '' ) . $name . '.class.php');
			//case 'views': 		class_exists($name) || require(_PATH_VIEWS . ( !empty($shortPath) ? $shortPath : $name ) . '.class.php');
			case 'views': 		class_exists($name) || require(_PATH_VIEWS . ( !empty($shortPath) ? $shortPath : '' ) . $name . '.class.php');
		}
		
		return $this;
	}
	
    
	public function requireControllers($names)
	{
        $this->log(__METHOD__);
		
		$names = is_string($names) ? explode(',',$names) : $names;
		
		foreach ( (array) $names as $key => $val )
		{			
			$this->requireClass(is_int($key) ? trim($val) : $key, 'controllers', is_int($key) ? '' : $val);
		}
		
		return $this;	
	}
	
    
	public function requireModels($names)
	{
        $this->log(__METHOD__);
		
		$names = is_string($names) ? explode(',',$names) : $names;
		
		foreach ( (array) $names as $key => $val )
		{
			$this->requireClass(is_int($key) ? trim($val) : $key, 'models', is_int($key) ? '' : $val);
		}
		
		return $this;	
	}


	public function requireViews($names)
	{
        $this->log(__METHOD__);
		
		$names = is_string($names) ? explode(',',$names) : $names;
		
		foreach ( (array) $names as $key => $val )
		{
			$this->requireClass(is_int($key) ? trim($val) : $key, 'views', is_int($key) ? '' : $val);
		}
		
		return $this;	
	}	
	
    
	public function requireLibs($names)
	{
        $this->log(__METHOD__);
		
		foreach ( (array) $names as $key => $val )
		{
			$this->requireClass(is_int($key) ? $val : $key, 'libs', is_int($key) ? '' : $val);
		}
		
		return $this;	
	}
	
	
	public function XML2Array($xml, $recursive = false, $options = array())
	{
        $this->log(__METHOD__);
		
		$o = array_merge(array(
			'type' => 'xml',
			'parent' => null,
		), $options);
		
		$array                = !$recursive ? (array) simplexml_load_file($xml) : $xml;
		//$array              = !$recursive ? (array) simplexml_load_file($xml, 'SimpleXMLElement', LIBXML_COMPACT) : $xml;
		$fixTextNodesAttr     = defined('_XML2ARRAY_FIX_TEXT_NODES_ATTRIBUTES') && _XML2ARRAY_FIX_TEXT_NODES_ATTRIBUTES;
		$data 		          = array();
        
		foreach ($array as $propName => $propVal)
		{
			if ( $o['type'] === 'rss' && $propName === 'description' )
			{
				$propVal = (string) $propVal;
			}
			
			$type 				= in_array(gettype($propVal), array('object','array')) ? 'multi' : 'simple';
			
            # Fix for text nodes having attributes that are ignored
            // If the element is an object
            if ( $fixTextNodesAttr && is_object($propVal) )
            {
                $fixed = array();
            
                // Loop over its childens    
                foreach ( $propVal as $k => $v )
                {
                    // Only handle text nodes which have both @attributes and a 0 indexed property        
                    if ( ($v = (array) $v) && isset($v['@attributes']) && isset($v[0]) )
                    {
                        $fixed[$k][] = array('@attributes' => $v['@attributes'], 'text' => $v[0]);
                    }
                }
            
                $propVal = array_merge((array)$propVal, $fixed);
            } 
            # End of the fix
			
			$data[$propName] 	= $type === 'multi' ? self::XML2Array((array) $propVal, true, $o + array('parent' => $propVal)) : $propVal;
		}
		
		return $data;
	}
	
	
	public function wsCall($uri, $options = array())
	{
        $this->log(__METHOD__);
		
		$o = $options; // Shortcut for options
		
		$uri = !filter_var($uri, FILTER_VALIDATE_URL) ? die('Error: Invalid wsCall Url') : $uri;
		
		// Set default conf
		//$tmpMethod 	= !empty($options['method']) ? $options['method'] : 'GET';
		$sentData 		= !empty($o['data']) ? $o['data'] : null;
		$data 			= array( 'statusCode' => null, 'body' => null );
		$m 				= !empty($o['method']) ? strtolower($o['method']) : null; // Shortcut for method
		$o['output'] 	= !empty($o['output']) ? $o['output'] : 'xhtml';

		// What method should we use? default = get
		switch($m)
		{
			case 'post': 	$httpMethod = HttpRequest::METH_POST; 	break;
			case 'put': 	$httpMethod = HttpRequest::METH_PUT; 	break;
			case 'delete': 	$httpMethod = HttpRequest::METH_DELETE; break;
			case 'get':
			default:		$httpMethod = HttpRequest::METH_GET; 	break;
		}

		// Create the request object
		$request 	= new HttpRequest($uri, $httpMethod);
		
		// What is the format of the 
		switch($o['output'])
		{
			case 'json': 	$accept = 'application/json;'; break;
			case 'xhtml':
			case 'html':
			default: 		$accept = 'text/html;';  break;
		}
		
		$request->addHeaders(array('Content-Type'=>'application/x-www-form-urlencoded'));
		$request->addHeaders(array('Accept'=> $accept));
		
		// For POST, PUT, requests, add the query data
		// setRawPostData is now deprecated and should be replaced by setBody
		//if 		( $m === 'post' ) 	{ $request->setRawPostData( is_string($sentData) ? $sentData : http_build_query((array)$sentData) ); }
		if 		( $m === 'post' ) 	{ $request->setBody( is_string($sentData) ? $sentData : http_build_query((array)$sentData) ); }
		//if 		( $m === 'post' ) 	{ $request->setRawPostData( $sentData ); }
		elseif 	( $m === 'put' ) 	{ $request->setPutData($sentData); }
		
		try
		{			
			// Send the request
		    $request->send();
			
			// Get the status code
			$data['statusCode'] = $request->getResponseCode();

			$body 				= $request->getResponseBody();
			
			// Decode the ws response json body transforming it into an associative array
			$data['body'] 		= !empty($body) ? json_decode($body, true) : null;
			$data['errors'] 	= !empty($data['body']['ws']['error']) ? $data['body']['ws']['error'] : null;
			
			// If the request is successfull, just return data
		    if 	( $data['statusCode'] === 200 ) { return $data; }
			
			// Otherwise, try to handle errors
			
			/*
			// Session expired
			if ( $data['errors']['code'] == 140102 )
			{
				class_exists('CUsers') || require(_PATH_CONTROLLERS . 'CUsers.class.php');
				
				// Force logout (session has to be properly emptied)
				CUsers::logout();
				
				// Then return redirect to the login, with
				return $this->redirect(_URL_LOGIN . '?errors=10100');
			}
			*/
		}
		catch (HttpException $ex) { $data['errors']['code'] = 12000; /*echo 'TODO: exception on logout when lost session ?';*/ }
		
		return $data;
	}

}

?>