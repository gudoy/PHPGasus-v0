<?php

//$appStartTime = microtime(true);

class Application
{
	public $debug 	= false;
	public $logged 	= false;
	public $inited 	= false;
	
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
		
        $this->configEnv();
		$this->handleSession();
		$this->setlanguage();
		
		$this->inited = true;
	}
	
	static function __autoload($className)
	{
        // Get first & secnd letter and check if second is uppercased
		$first 			= $className[0];
		$secondIsUpper 	= $className[1] === strtoupper($className[1]);
		
		// Known classes types
		$known = array('M' => 'model', 'V' => 'view', 'C' =>'controller');

		$type = isset($known[$first]) && $secondIsUpper ? $known[$first] : 'lib';
		$path = constant('_PATH_' . strtoupper($type  . 's'));
		
		class_exists($className) 
			|| ( $type === 'controller' 
					&& ($cFolder = preg_replace('/^c/', '', strtolower($className))) 
					&& is_dir($path . $cFolder) 
					&&  file_exists($path . $cFolder . '/' . $className . '.class.php') 
					&& require($path . $cFolder . '/' . $className . '.class.php')
				)
			|| ( file_exists($path . $className . '.class.php') 
					&& require($path . $className . '.class.php')
				);
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
        $this->resourceSingular = !empty($singular) ? $singular : Tools::singular((string) $name);
        
        return $this;
    }
	
	public function setLanguage($lang = null)
	{
		// Get known languages and force them into lowercase
		$known 		= defined('_APP_LANGUAGES') && is_array(_APP_LANGUAGES) 
						? explode(',', strtolower(join(',', _APP_LANGUAGES))) 
						: explode(',', strtolower(_APP_LANGUAGES));
		
		// Get  Accept-Language http header 
		// ex: fr,fr-fr;q=0.8,en-us;q=0.5,en;q=0.3
		$accptHeader = !empty($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? str_replace('-', '_', $_SERVER['HTTP_ACCEPT_LANGUAGE']) : '';
		
		// Try to find lang in GET param
		if ( empty($lang) && isset($_GET['lang']) && in_array(strtolower($_GET['lang']), $known) )			{ $lang = strtolower($_GET['lang']); }
		
		// Try to find lang in POST param
		if ( empty($lang) && isset($_POST['lang']) && in_array(strtolower($_POST['lang']), $known) )		{ $lang = strtolower($_POST['lang']); }
		
		// Try to find lang in SESSION param
		if ( empty($lang) && isset($_SESSION['lang']) && in_array(strtolower($_SESSION['lang']), $known) )	{ $lang = strtolower($_SESSION['lang']); }
		
		// If the lang has not been found and if there's an Accept-Llanguage http header
		if ( empty($lang) && !empty($accptHeader) )
		{
			$acptLangs 	= array(); 						//  
			$lgs 		= explode(',', $accptHeader); 	// Split it
			
			// Loop over them and build an array of the form (lang => priority)
			foreach ( (array) $lgs as $lg )
			{
				$pos 				= strpos($lg, ';q=');
				$key 				= $pos !== false ? substr($lg, 0, $pos) : $lg;
				$acptLangs[$key] 	= $pos ? substr($lg, $pos + 3) : 1;
			}
			
			// Sort array by value (priority)
			arsort($acptLangs);
			
			// Check for match between accepted languages and known ones
			foreach ($acptLangs as $lg => $priority){ if ( in_array(strtolower($lg), $known) ){ $lang = $lg; break; } }
		}
		
		// If the lang has still not been found, use the default language
		if ( empty($lang) && defined('_APP_DEFAULT_LANGUAGE') ) { $lang = strtolower(_APP_DEFAULT_LANGUAGE); }

		$parts 		= strpos($lang, '_') !== false ? explode('_', $lang) : array($lang);  
		$language 	= $parts[0];
		$territory 	= strtoupper( !empty($parts[1]) ? $parts[1] : $parts[0] );
		$codeset 	= 'UTF-8';
		$locale 	= $language . '_' . $territory . '.' . $codeset;
		
		// Set locale & gettext conf
		putenv('LANG=' . $locale);
		putenv('LC_ALL=' . $language . '_' . $territory);
		$lc = setlocale(LC_ALL, $locale, $language . '_' . $territory, $language);
		bindtextdomain(_APP_NAME, _PATH_I18N);
		textdomain(_APP_NAME);
		bind_textdomain_codeset(_APP_NAME, $codeset);
		
		// Store the current lang
		$_SESSION['lang'] 	= $language . '_' . $territory;
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
		$ua = !empty($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null;
		//if ( (strpos($ua, 'iPhone') !== false || strpos($ua, 'iPod') !== false || strpos($ua, 'iPad') !== false ) && !empty($_GET[_SESSION_NAME]) )
		//if ( _APP_ALLOW_GET_SID_FROM_URL && !empty($_REQUEST[_SESSION_NAME]) )
		if ( _APP_ALLOW_GET_SID_FROM_URL && ( !empty($_GET[_SESSION_NAME]) || !empty($_POST[_SESSION_NAME]) ) )
		{
			// Get the data of the passed session id
			//$s = CSessions::getInstance()->retrieve(array('values' => $_GET[_SESSION_NAME], 'sortBy' => 'expiration_time', 'orderBy' => 'DESC', 'limit' => 1));
			//$sid 	= filter_var($_REQUEST[_SESSION_NAME], FILTER_SANITIZE_STRING);
			$sid 	= ( !empty($_GET[_SESSION_NAME]) || !empty($_POST[_SESSION_NAME]) ) 
						? filter_var( !empty($_POST[_SESSION_NAME]) ? $_POST[_SESSION_NAME] : $_GET[_SESSION_NAME], FILTER_SANITIZE_STRING)
						: null;
			
			session_id($sid);
			session_start(); 
			$_SESSION['id'] 		= $sid;
		}
		
		// Start the session if not already started
		if ( session_id() === '') { session_start(); }
		
		// Get the current session id
		$sid = session_id();
		
		// TODO: if session passed (cookie or anything) check that ip match the stored one
		$CSessions = new CSessions();
		$session = $CSessions->retrieve(array( 
			'getFields' 	=> 'id,name,user_id,ip',
			'sortBy' 		=> 'expiration_time',
			'orderBy' 		=> 'DESC',
			'conditions' 	=> array('name' => $sid),
			'limit' 		=> 1, 
		));
		
		// Get user ip & clean it
		$clientIp = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) 
						? $_SERVER['HTTP_X_FORWARDED_FOR'] 
						: ( !empty($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null );
		$clientIp = filter_var($clientIp, FILTER_VALIDATE_IP) ? $clientIp : false;
		
		// If the client ip match the passed session's one, set it as the current session id
		if ( empty($session) || !empty($session['ipg']) || $clientIp !== $session['ip'] )
		{
			$this->logged = false;
			return $this;
		}
		
		// Store the user id in session
		$_SESSION['user_id'] 	= $session['user_id'];
		
		$curPOST 	= $_POST;
		$_POST 		= array(
			'expiration_time' 	=> ( !empty($_SERVER['REQUEST_TIME']) ? $_SERVER['REQUEST_TIME'] : time() ) + _APP_SESSION_DURATION,
			'last_url' 			=> $this->currentURL(),
		);
		
		// Try to update the session in db, if exists and not already expired
		$CSessions->update(array('isApi' => 1, 'conditions' => array('id' => $session['id'])));
		
		// If rows have been affected, it means that the user is properly logged (cause the session exists and is not expired)
		$this->logged = $CSessions->success && $CSessions->model->affectedRows > 0;
		
		// Once this is done, unset previously setted POST
		unset($_POST);
		$_POST = $curPOST;
		
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
		//date_default_timezone_set('UTC');
		
		// Get current page url
		$curURL = $this->currentURL();
		
		// Remove base url from it
		$curURL = str_replace(_URL, '', $curURL);
		
		$t 		= parse_url($curURL);
		//$redir 	= $t['scheme'] . '://' . $t['host'] . $t['path'] . ( !empty($t['query']) ? urlencode('?' . $t['query']) : '') . (!empty($t['fragment']) ? $t['fragment'] : '');
		//$redir 	= $t['scheme'] . '://' . $t['host'] . $t['path'] . ( !empty($t['query']) ? urlencode('?' . $t['query']) : '') . (!empty($t['fragment']) ? $t['fragment'] : '');
		
		$redir = ltrim(rtrim($t['path'], '/') . '/' . ( !empty($t['query']) ? urlencode('?' . $t['query']) : '') . (!empty($t['fragment']) ? $t['fragment'] : ''), '/');
		
		// TODO: add proper error. Require data/success/errors/warnings to be shared accross app
		if ( !$this->isLogged() )
		{
			$this->data['errors'][] = 10100;
			
			// In API views with output other than (x)html, do no redirect
			// but instead, just return an error with proper status code 
			if ( $this instanceof ApiView && !in_array($this->options['output'], array('html','xhtml')) )
			{
				return $this->statusCode(401); 
			}
				
			return $this->redirect(_URL_LOGIN . '?successRedirect=' . $redir);
		}
		
		return $this;
	}
	
	
	/* 
	 * This function gets, in an URL string, the value of the param given in the function call
	 * @return {String|Boolean} The value if found, otherwise false
	 */
	// TODO: refactor using parse_str() ???
	public function getURLParamValue($url, $param)
	{
        $this->log(__METHOD__);
		
		// Get start position of the param from the ?
		$markP 	= strpos($url, "?");
		$url 	= substr($url, $markP, strlen($url));
		$pos 	= strpos($url, $param);
		
		if ($pos != -1 && $param != "")
		{
			// Truncate the string from this position to its end
			$tmp = substr($url, $pos);
			
			// Get end position of the param value
			if 		( strpos($tmp, "&amp;") !== false ) { $end_pos = strpos($tmp, "&amp;"); } // case where there are others params after, separated by a "&amp;"
			else if ( strpos($tmp, "&") !== false ) 	{ $end_pos = strpos($tmp, "&"); } // case where there are others params after, separated by a "&"
			else if ( strpos($tmp, "#") !== false ) 	{ $end_pos = strpos($tmp, "#"); } // case where there are others params after, separated by a "#"
			else 										{ $end_pos = strlen($tmp); } // case where there are no others params after
			
			// Truncate the string from 0 to the end of the param value
			return substr($tmp, strlen($param) + 1, $end_pos);
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
		
		if ( _IN_MAINTENANCE === true )
		{
			require(_PATH_VIEWS . 'VHome.class.php');
			return call_user_func_array(array(new VHome($this), 'maintenance'), array());
		}
		
		// If the file is correctly loaded
		if( ($loaded = require($view['path'] . '/' . $view['fullname'] . '.class.php')) && $loaded && class_exists($view['fullname']) )
		{			
			// Get method and arguments
			// checking if method exists and does not start by an '_' char (used for methods that should not be exposed)
			$method		= !empty($s[0]) && method_exists($view['fullname'], $s[0]) && $s[0][0] !== '_' ? array_shift($s) : 'index';			
			$arguments 	= !empty($s) ? $s : array();
			
			// Call the proper function with the proper arguments after having instanciated the proper function
			return call_user_func_array(array(new $view['fullname']($this), $method), $arguments);
		}
		// If an error occured, we load the 404 page
		else
		{
			require(_PATH_VIEWS . 'VHome.class.php');
			return call_user_func_array(array(new VHome($this), '_404'), array());
		}
	}
	
    
	public function dump($data, $options = null)
	{
		//if ( in_array(_APP_CONTEXT, array('local','dev')) || $this->options['debug'] )
		if ( !in_array(_APP_CONTEXT, array('local','dev')) ){ return; }
			
		// Get user agent
		$ua = !empty($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null;
		
//var_dump($ua);
//var_dump(strpos($ua, 'Chrome'));
		
		if ( _APP_USE_CHROMEPHP_LOGGING && strpos($ua, 'Chrome') !== false )
		{
	        class_exists('ChromePhp') || require(_PATH_LIBS . 'tools/ChromePHP/ChromePhp.php');
	        
			//ChromePhp::useFile(_PATH . 'tmp/', 'chromelogs/');
			ChromePhp::useFile(_PATH_PUBLIC . 'logs/chromelogs', '/public/logs/chromelogs/');
			ChromePhp::log($data);	
		}
		elseif ( _APP_USE_FIREPHP_LOGGING && strpos($ua, 'Gecko') !== false )
		{
	        class_exists('FirePHP') || require(_PATH_LIBS . 'tools/FirePHP/FirePHPCore/FirePHP.class.php');
	        
	        //var_dump($data);
			FirePHP::getInstance(true)->log($data);	
		}
		else
		{
			// TODO
			// ????
		}
		
		return;
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
			}
			
			//error_reporting(E_ALL | E_STRICT | E_DEPRECATED);
			error_reporting(2147483647); 						// display all errors
			ini_set('display_errors', 1);
			
			//$this->Smarty->debugging 		= true;
		}
		else
		{
			// Report simple running errors
			error_reporting(E_ERROR | E_PARSE);
		}


		// Prevent cookie manipulation via javascript
		ini_set('session.cookie_httponly', 	1);
		
		// Secure session cookies if login is only activated through https,
		ini_set('session.cookie_secure', ( defined('_APP_HTTPSONLY_LOGIN') && _APP_HTTPSONLY_LOGIN && strpos(_APP_PROTOCOL, 'https') !== false ) ? 1 : 0);	
		
		// Always disable register globals 
		ini_set('register_globals', 0);
		
		ini_set('xdebug.var_display_max_depth', 6);
		ini_set('xdebug.var_display_max_data', 99999);
		ini_set('xdebug.var_display_max_children', 999);
		ini_set('xdebug.max_nesting_level', 500); // default is 100, which can be cumbersome with smarty


		// Force timezone
		//$old = date_default_timezone_get();
		date_default_timezone_set('UTC');
		
		return $this;
	}
	
	
	public function currentURL()
	{
        $this->log(__METHOD__);
		
		if ( isset($this->currentURL) ){ return $this->currentURL; }
		
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
			case 'libs': 		class_exists($name) || include_once(_PATH_LIBS . ( !empty($shortPath) ? $shortPath : '' ) . $name . '.class.php');
			//case 'controllers': class_exists($name) || require(_PATH_CONTROLLERS . ( !empty($shortPath) ? $shortPath : $name ) . '.class.php');
			case 'controllers': class_exists($name) || include_once(_PATH_CONTROLLERS . ( !empty($shortPath) ? $shortPath : '' ) . $name . '.class.php');
			//case 'models': 		class_exists($name) || require(_PATH_MODELS . ( !empty($shortPath) ? $shortPath : $name ) . '.class.php');
			case 'models': 		class_exists($name) || include_once(_PATH_MODELS . ( !empty($shortPath) ? $shortPath : '' ) . $name . '.class.php');
			//case 'views': 		class_exists($name) || require(_PATH_VIEWS . ( !empty($shortPath) ? $shortPath : $name ) . '.class.php');
			case 'views': 		class_exists($name) || include_once(_PATH_VIEWS . ( !empty($shortPath) ? $shortPath : '' ) . $name . '.class.php');
		}
		
		return $this;
	}
	
    
	public function requireControllers($names)
	{
        $this->log(__METHOD__);
		
		$names = is_string($names) ? explode(',',$names) : $names;
		
		foreach ( (array) $names as $key => $val )
		{
			$this->requireClass(is_numeric($key) ? trim($val) : $key, 'controllers', is_numeric($key) ? '' : $val);
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

	public function request($url, $params = array())
	{
		$p = &$params;
		
		// TODO: validate URL?
		//$url = !filter_var($url, FILTER_VALIDATE_URL) ? die('Error: Invalid wsCall Url') : $url;
		
		// Set default params
		$default = array(
			'method' 		=> 'GET',								// default method
			'content-type' 	=> 'application/x-www-form-urlencoded', // default output format
			'accept' 		=> 'text/html;', 						//
			'body' 			=> '',
		);
		$known = array(
			'methods' 	=> array('get','post','put','delete'),
			'accept' 	=> array(),
			'output' 	=> array(
				'json' 		=> 'application/json;',
				'xml' 		=> 'text/xml; application/xml;',
				'html' 		=> 'text/html;',
				'xhtml' 	=> 'application/xhtml+xml; text/html;',
			),
		);
		
		// Init final request params
		$rqP = array();
		
		// Output data
		$data = array();
		
		
		# Handle method
		// Get passed one of set it to default value
		$rqP['method'] = !empty($p['method']) && in_array(strtolower($p['method']), $kown['methods']) 
							? strtolower($p['method']) 
							: $default['method'];
		
		# Handle accept header (accepted output(s))
		// if 'output' param passed an if it's a known output, use id
		// otherwise get passed 'accept' params or set it to default value 
		$rqP['accept'] = !empty($p['output']) && isset($known['output'][strtolower($p['output'])])
							? $known['output'][strtolower($p['output'])]
							: (!empty($p['accept']) ? $p['accept'] : $default['method']);
		
		// Try to use HttpRequest extension (PECL extension)
		if ( extension_loaded('http') )
		{
			// Init the request
			$req = new HttpRequest($url, constant('HttpRequest::METH_' . strtoupper($rqP['method'])));
			
			$req->addHeaders(array('Content-Type'=> $p['content-type']));
			$req->addHeaders(array('Accept'=> $p['accept']));
			
			// Send the request
		    $req->send();
			
			// Get the status code
			$data = array(
				'statusCode' 	=> $req->getResponseCode(),
				'body' 			=> $req->getResponseBody()
			);
		}
		// Otherwise try to use CURL extension
		else if ( extension_loaded('curl') )
		{
			// TODO
		}
		// Otherwise
		else
		{
			// TODO
			// http://wezfurlong.org/blog/2006/nov/http-post-from-php-without-curl/
			// http://query7.com/http-request-without-curl
		}
		
		# Handle response
		
		return; // data? object?
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
		
		// What is the format of the WS
		switch($o['output'])
		{
			case 'json': 	$accept = 'application/json;'; break;
			case 'xml': 	$accept = 'text/xml; application/xml;'; break;
			case 'xhtml':
			case 'html':
			default: 		$accept = 'text/html;';  break;
		}
		
		$request->addHeaders(array('Content-Type'=> !empty($o['Content-Type']) ? $o['Content-Type'] : 'application/x-www-form-urlencoded'));
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
			//$data['body'] 		= !empty($body) ? json_decode($body, true) : null;
			
			$data['body'] = null;

			if ( !empty($body) )
			{
				if 		( $o['output'] === 'json' )	{ $data['body'] = json_decode($body, true); }
				else if ( $o['output'] === 'xml' )	{ $data['body'] = Tools::XML2array(simplexml_load_string($body), true); }
				else 								{ $data['body'] = $body; }
			}
			
			$data['errors'] 	= !empty($data['body']['ws']['error']) ? $data['body']['ws']['error'] : null;
			
			// If the request is successfull, just return data
		    if 	( $data['statusCode'] === 200 ) { return $data; }
		}
		//catch (HttpException $ex) { $data['errors']['code'] = 12000; /*echo 'TODO: exception on logout when lost session ?';*/ }
		catch (HttpException $ex) { }
		
		return $data;
	}

}

?>