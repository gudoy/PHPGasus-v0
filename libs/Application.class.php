<?php

//$appStartTime = microtime(true);

class Application
{
	var $debug 	= false;
	var $logged = null;
	var $inited = false;
	
	public function __construct()
	{
		return $this->init();
	}
	
	public function init()
	{
		if ( $this->inited ) { return $this; }
		
		// 
		spl_autoload_register('Application::__autoload'); 
		
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
        // Do not continue if the resourceName is already defined
        if ( !empty($this->resourceName) ){ return $this; }
        
        $o          = &$options;        

        $name       = !empty($o['name']) ? (string) $o['name'] : null;
        $singular   = !empty($o['singular']) ? (string) $o['singular'] : null;
        
        //if ( !empty($o['controller']) ){ $name = strtolower(preg_replace('/^C(.*)/','$1', $o['controller'])); }
        if ( !empty($o['class']) ){ $name = strtolower(substr($o['class'], 1)); }
        
        $this->resourceName     = $name;
        $this->resourceSingular = !empty($singular) ? $singular : $this->singularize((string) $name);
        
        return $this;
    }
	
	
	// TODO: clean & refactor
	public function setlanguage($lang = '')
	{
		//$this->log(__METHOD__);
		
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
		//putenv('LANG='.$lang);
		putenv('LANG='.$lang.'utf8');
		$lc = setlocale(LC_ALL, $locale.'.utf8', $locale, $lang);
		bindtextdomain(strtolower(_APP_NAME), _PATH_I18N);
		textdomain(strtolower(_APP_NAME));
		bind_textdomain_codeset(strtolower(_APP_NAME), 'UTF-8');
		
		$_SESSION['lang'] 	= $locale;
		
		return $this;
	}
	
	
	final public function handleSession()
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
				//array('expiration_time', '>', ("FROM_UNIXTIME('" . time() . "')")),
				//array('expiration_time', '>', time()),
				array('expiration_time', '>', ( !empty($_SERVER['REQUEST_TIME']) ? $_SERVER['REQUEST_TIME'] : time() ) ),
				
			)
		)); 
		
		// If rows have been affected, it means that the user is properly logged (cause the session exists and is not expired)
		$this->logged = $CSessions->success && $CSessions->model->affectedRows > 0;
		
		// Once this is done, unset previously setted POST
		foreach ($newPOST as $key => $val) { unset($_POST['session' . ucfirst($key)]); }
		
		return $this;
	}
	
	
	
	final public function isLogged()
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
		//$this->logged 	= !empty($sessExp) && $sessExp > time() && ( !empty($_SESSION['id']) && $_SESSION['id'] === $session['name'] );
		$time 			= !empty($_SERVER['REQUEST_TIME']) ? $_SERVER['REQUEST_TIME'] : time();
		$this->logged 	= !empty($sessExp) && $sessExp > $time && ( !empty($_SESSION['id']) && $_SESSION['id'] === $session['name'] );
		
		
		
//$this->dump('islogged: ' . $this->logged);
		
		return $this->logged;
	}
	
	
	final public function requireLogin()
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
			class_exists('FirePHP') || require(_PATH_LIBS . 'tools/FirePHPCore/FirePHP.class.php');
			FirePHP::getInstance(true)->log($data);
		}
		
		//class_exists('FirePHP') || require(_PATH_LIBS . 'tools/FirePHPCore/FirePHP.class.php');
		//FirePHP::getInstance(true)->log($data);
		
		return $this;
	}


	public function isInDebugMod()
	{
		return ($this->debug || $this->options['debug']) && in_array(_APP_CONTEXT, array('local','dev', 'preprod'));
	}

	
	public function log($data = null, $options = null)
	{
		if ( $this->debug && _ALLOW_FIREPHP_LOGGING && !empty($data) && ( in_array(_APP_CONTEXT, array('local','dev', 'preprod')) || $this->options['debug'] ) )
		{
			class_exists('FirePHP') || require(_PATH_LIBS . 'tools/FirePHPCore/FirePHP.class.php');
			
			//ob_start();
			
			FirePHP::getInstance(true)->log($data);
			
		}
		
		return $this;
	}
	
	
	public function configEnv()
	{		
		$this->env = array(
			'name' => _APP_CONTEXT,
			'type' => in_array(_APP_CONTEXT, array('local','dev')) && !isset($_GET['PRODJS']) ? "dev" : "prod",
		);
		
		if ( $this->env['type'] === 'dev' )
		{
			if ( _ALLOW_FIREPHP_LOGGING )
			{		
				//class_exists('FirePHP') || require(_PATH_LIBS . 'tools/FirePHPCore/FirePHP.class.php');
				ob_start();
				
				class_exists('FirePHP') || require(_PATH_LIBS . 'tools/FirePHP/FirePHPCore/FirePHP.class.php');	
			}
			
			error_reporting(E_ALL);
			
			//$this->Smarty->debugging 		= true;
			
			ini_set('xdebug.var_display_max_depth', 6);
			ini_set('xdebug.var_display_max_data', 4096);
		}
		else
		{
			// Report simple running errors
			error_reporting(E_ERROR | E_PARSE);
		}

		// Force timezone
		date_default_timezone_set('UTC');
		
		$this->log(__METHOD__);
		
		return $this;
	}
	
	
	/**
	 * Return the current URL
	 * 
	 * @return string
	 */	
	public function currentURL()
	{
		$this->log(__METHOD__);
		
		if ( isset($this->currentURL) ){ return $this->currentURL; }
		
		$this->currentURL = 'http' . ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 's' :'' ) . '://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
		
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
	
	
	/**
	 * Remove params (and theirs values) from a string (or url)
	 * 
	 * @param string|array $paramNames name of a param or array of params name
	 * @param string $replaceIn a string or URL in valid query format (param1=value1&param2=value2...)
	 * @return string cleaned string
	 */
	public function removeQueryParams($paramNames, $replaceIn)
	{
		$this->log(__METHOD__);
		
		$cleaned = $replaceIn;
		
		foreach ((array)$paramNames as $paramName)
		{
			$cleaned = preg_replace('/(.*)[&]$/', '$1', preg_replace('/(.*)' . $paramName . '[=|%3D|%3d](.*)(&|$)/U','$1', $cleaned));
		}
		
		return $cleaned;
	}
	
	
	public function singularize($plural)
	{		
		$len 	= strlen($plural);
		$sing 	= $plural;  		// Default
		
		if 		( $len >= 5 && substr($plural, -4) === 'uses' )		{ $sing = preg_replace('/(.*)uses/','$1us', $plural); }
		else if ( $len >= 4 && substr($plural, -3) === 'ses' )		{ $sing = preg_replace('/(.*)ses/','$1ss', $plural); }
		else if ( $len >= 4 && substr($plural, -3) === 'hes' )		{ $sing = preg_replace('/(.*)hes/','$1h', $plural); }
		else if ( $len >= 4 && substr($plural, -3) === 'ies' )		{ $sing = preg_replace('/(.*)ies$/','$1y', $plural); }
		else if ( $len >= 4 && substr($plural, -3) === 'oes' )		{ $sing = preg_replace('/(.*)oes$/','$1o', $plural); }
		else if ( $len >= 4 && substr($plural, -3) === 'ves' )		{ $sing = preg_replace('/(.*)ves$/','$1f', $plural); }
		else if ( $len >= 2 && $plural[$len-1] === 'a' ) 			{ $sing = preg_replace('/(.*)a$/','$1um', $plural); }
		else if ( $len >= 2 && $plural[$len-1] === 's' ) 			{ $sing = preg_replace('/(.*)s$/','$1', $plural); }
		
		return $sing;
	}
	
	public function pluralize($singular)
	{		
		$len = strlen($singular);
		$plu = $singular;  			// Default
		
		if 		( $len >= 3 && substr($singular, -2) === 'us' )		{ $plu = preg_replace('/(.*)us/','$1uses', $singular); }
		else if ( $len >= 3 && substr($singular, -2) === 'ss' )		{ $plu = preg_replace('/(.*)ss/','$1ses', $singular); }
		else if ( $len >= 3 && $singular[$len-1] === 'h' )			{ $plu = preg_replace('/(.*)h/','$1hes', $singular); }
		else if ( $len >= 3 && $singular[$len-1] === 'y' )			{ $plu = preg_replace('/(.*)y/','$1ies', $singular); }
		else if ( $len >= 3 && $singular[$len-1] === 'o' )			{ $plu = preg_replace('/(.*)o/','$1oes', $singular); }
		else if ( $len >= 3 && $singular[$len-1] === 'f' )			{ $plu = preg_replace('/(.*)f/','$1ves', $singular); }
		else if ( $len >= 3 && substr($singular, -2) === 'um' )		{ $plu = preg_replace('/(.*)um/','$a', $singular); }
		else if ( $len >= 2 )										{ $plu = $singular . 's'; }
		
		return $plu;
	}

	
	public function deaccentize($str)
	{
		$charsTable = array(
			'Š'=>'S', 'š'=>'s', 'Đ'=>'Dj', 'đ'=>'dj', 'Ž'=>'Z', 'ž'=>'z', 'Č'=>'C', 'č'=>'c', 'Ć'=>'C', 'ć'=>'c',
			'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
			'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
			'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss',
			'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e',
			'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o',
			'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b',
			'ÿ'=>'y', 'Ŕ'=>'R', 'ŕ'=>'r',
		);
		return strtr($str,$charsTable);
	}
	
	public function strtolower_utf8($string)
	{
		$convert_to = array(
		"a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u",
		"v", "w", "x", "y", "z", "à", "á", "â", "ã", "ä", "å", "æ", "ç", "è", "é", "ê", "ë", "ì", "í", "î", "ï",
		"ð", "ñ", "ò", "ó", "ô", "õ", "ö", "ø", "ù", "ú", "û", "ü", "ý", "а", "б", "в", "г", "д", "е", "ё", "ж",
		"з", "и", "й", "к", "л", "м", "н", "о", "п", "р", "с", "т", "у", "ф", "х", "ц", "ч", "ш", "щ", "ъ", "ы",
		"ь", "э", "ю", "я"
		);
		$convert_from = array(
		"A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U",
		"V", "W", "X", "Y", "Z", "À", "Á", "Â", "Ã", "Ä", "Å", "Æ", "Ç", "È", "É", "Ê", "Ë", "Ì", "Í", "Î", "Ï",
		"Ð", "Ñ", "Ò", "Ó", "Ô", "Õ", "Ö", "Ø", "Ù", "Ú", "Û", "Ü", "Ý", "А", "Б", "В", "Г", "Д", "Е", "Ё", "Ж",
		"З", "И", "Й", "К", "Л", "М", "Н", "О", "П", "Р", "С", "Т", "У", "Ф", "Х", "Ц", "Ч", "Ш", "Щ", "Ъ", "Ъ",
		"Ь", "Э", "Ю", "Я"
		);
		
		return str_replace($convert_from, $convert_to, $string); 
	}
	
	/*
	 * Always returns an array. If a string is passed, explodes it on ',' 
	 */
	public final function arrayify($value)
	{
		return is_array($value) ? $value : preg_split("/,+\s*/", (string) $value);
	} 
	
	
	// function found on http://forum.webrankinfo.com/fonctions-pour-creer-slug-seo-friendly-url-t99376.html
	/*
	public function slugify( $url, $type = '' )
	{
	    $url = preg_replace("`\[.*\]`U","",$url);
	    $url = preg_replace('`&(amp;)?#?[a-z0-9]+;`i','-',$url);
	    $url = htmlentities($url, ENT_NOQUOTES, 'utf-8');
	    $url = preg_replace( "`&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig);`i","\\1", $url );
	    $url = preg_replace( array("`[^a-z0-9]`i","`[-]+`") , "-", $url);
	    $url = ( $url == "" ) ? $type : strtolower(trim($url, '-'));
		
	    return $url;
	}*/
	
	// function found on http://forum.webrankinfo.com/fonctions-pour-creer-slug-seo-friendly-url-t99376.html
    public function slugify($string)
    {
        // remplace les caractères accentués par leur version non accentuée
        //$id = strtr($string,'ŠŽšžŸÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÑÒÓÔÕÖØÙÚÛÜÝàáâãäåçèéêëìíîïñòóôõöøùúûüýÿ', 'SZszYAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy');
        $id = $this->deaccentize($string);

        // remplace les caractères non standards
        $id = preg_replace(
                array(
                    '`^[^A-Za-z0-9]+`',
                    '`[^A-Za-z0-9]+$`',
                    '`[^A-Za-z0-9]+`' ),
                array('','','-'),
                $id );

        return $id;
    }
	
	
	public function generateUniqueID($options = array())
	{
		// Get passed options or default them
		$o 			= array_merge(array(
			'length' 			=> 8,
			//'check' 			=> true,
			'resource' 			=> null,
			'field' 			=> null,
			'preventNumsOnly' 	=> true,
			'preventAlphaOnly' 	=> false, // TODO
		), $options);
		
		$alpha 		= 'abcdefghjkmnpqrstuvwxyz'; 	// all letters except i,o,l (prevent reading confusions)
		$num 		= '23456789'; 					// all numerics except 1 (prevent reading confusions)
		$wref 		= '';
		while ( strlen($wref) < $o['length'] )
		{
			$wref .= mt_rand(1,2) === 1 ? $alpha[mt_rand(1, 23)-1] : $num[mt_rand(0, 7)];
		}
		
		// Prevents id having numerics only to prevent conflict with ids in database on "smart searchs" ( retrieve(array('by' => 'id,uid', 'value' => $value)) 
		if ( $o['preventNumsOnly'] && is_numeric($wref) ) { $this->generateUniqueID($o); }
		
		// TODO: check if resource & resource field exist in datamodel
		if ( !empty($o['resource']) && !empty($o['resource'])  )
		{
			$cName 		= 'C' . ucfirst($o['resource']);
			$ctrl 		= new $cName();
			$isUnique 	= $ctrl->retrieve(array('by' => $o['field'], 'values' => $wref, 'mode' => 'count'));
			
			if ( !empty($isUnique) || ($o['preventNumsOnly'] && is_numeric($wref)) ) { $this->generateUniqueID($o); }	
		}
		
		return $wref;
	}
	
	
	public function XML2Array($xml, $recursive = false, $options = array())
	{
		$this->log(__METHOD__);
		
		$o = array_merge(array(
			'type' => 'xml',
		), $options);
		
		$array 		= !$recursive ? (array) simplexml_load_file($xml) : $xml;

		$data 		= array();
		foreach ($array as $propName => $propVal)
		{
			if ( $o['type'] === 'rss' && $propName === 'description' )
			{
				$propVal = (string) $propVal;
			}
			
			$type 				= in_array(gettype($propVal), array('object','array')) ? 'multi' : 'simple';
			
			$data[$propName] 	= $type === 'multi' ? self::XML2Array((array) $propVal, true, $o) : $propVal;
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