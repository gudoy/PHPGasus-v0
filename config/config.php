<?php

define("_APP_CONTEXT", 					!getenv("APP_CONTEXT") ? 'prod' : getenv("APP_CONTEXT"));
define("_DOMAIN", 						preg_replace('/(.*\.)?(.*\..*)/', '$2', $_SERVER['SERVER_NAME']));
define("_SUBDOMAIN", 					str_replace('.' . _DOMAIN, '', $_SERVER['HTTP_HOST']));

###########
## LOCAL ##
###########

if ( _APP_CONTEXT === 'local' )
{
	define("_SMARTY_COMPILE_CHECK", 	true); 
	define("_SMARTY_FORCE_COMPILE", 	true); 
	
	// Is minification used for css & js
	define("_MINIFY_JS", 				false);
	define("_MINIFY_CSS", 				false);
}


#########
## DEV ##
#########

if ( _APP_CONTEXT === 'dev' )
{
	define("_ALLOW_FIREPHP_LOGGING", 	true);
	
	define("_SMARTY_COMPILE_CHECK", 	true); 
	define("_SMARTY_FORCE_COMPILE", 	false); 
	define("_SMARTY_CACHING", 			0);
	
	define("_MINIFY_JS",				false);
	define("_MINIFY_CSS", 				false);
	
    define("_DB_USER",                  'admin-dev');
	define("_DB_PASSWORD",  			'F4K3paSSw0rD');
}



##########
## PROD ##
##########
// PROD params are used as default ones and should only be overridden
// in the the other environnement's configuration ABOVE

# Get the projet full path on the server
//define("_PATH",							getcwd() . '/'); // does not return the expected path when called via CLI
define("_PATH",							realpath((dirname(realpath(__FILE__))) . '/../') . '/'); // 

# Get app name using base projet folder name
define("_APP_NAME", 					basename(_PATH));

# Get path relatively to server root
define("_PATH_REL", 					str_replace($_SERVER['DOCUMENT_ROOT'], '', _PATH));

define("_APP_PROTOCOL", 				'http' . ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 's' : '' ) . '://');

// If a server name has been defined, use it
// Otherwise, use the server ip and the project base folder path as the base URL 
define("_URL", 							_APP_PROTOCOL . ( $_SERVER['SERVER_NAME'] !== $_SERVER['SERVER_ADDR'] ? $_SERVER['SERVER_NAME'] . '/' : $_SERVER['SERVER_ADDR'] . _PATH_REL ));
											
define("_URL_REL", 						$_SERVER['SERVER_NAME'] !== $_SERVER['SERVER_ADDR'] ? '/' : _PATH_REL );


define("_URL_STATIC", 					_APP_PROTOCOL . 'static.' . _DOMAIN . '/');
define("_URL_STATIC_1", 				_APP_PROTOCOL . 'static1.' . _DOMAIN . '/');
define("_URL_ADMIN", 					_URL . 'admin/');
define("_URL_API", 						_URL . 'api/');


define("_IN_MAINTENANCE", 				false); // Set this to true to redirect all requests to the maintenance page (/maintenance)


# DATABASE PARAMETERS
define("_DB_SYSTEM",   					'mysql'); // mysql, mysqli, postgresql, sqlite, mongodb
define("_DB_HOST",    					'localhost');
define("_DB_USER",      				'admin');
define("_DB_PASSWORD",  				'F4K3paSSw0rD');
define("_DB_NAME",  					_APP_NAME);
define("_DB_PORT",  					'3306'); // mysql:3306 , postgresql: 5432, sqlite:
define('_DB_TABLE_PREFIX', 				''/*_APP_NAMESPACE*/);
define('_DB_CONNECTION_TIMEOUT', 		5); // In seconds. Better to set this in your php ini. let empty to use php.ini config (mysql=60)

# FTP PARAMETERS
define("_FTP_HOST",    					'localhost');
define("_FTP_USER_NAME",    			'userftp');
define("_FTP_USER_PASSWORD",    		'F4K3paSSw0rD');
define("_FTP_PORT",    					21);
define("_FTP_ROOT",    					'/');

# SMARTY CONF VARIABLES (for optimization and development purpose)
// Start deprecated
define("_SMARTY_COMPILE_CHECK", 		false);
define("_SMARTY_FORCE_COMPILE", 		false);
define("_SMARTY_CACHING", 				0);
define("_SMARTY_CACHE_LIFETIME", 		3600); // in seconds
// End deprecated
define("_TEMPLATES_ENGINE", 			'smarty');
define("_TEMPLATES_COMPILE_CHECK", 		_SMARTY_COMPILE_CHECK);
define("_TEMPLATES_FORCE_COMPILE", 		_SMARTY_FORCE_COMPILE);
define("_TEMPLATES_CACHING", 			_SMARTY_CACHING);
define("_TEMPLATES_CACHE_LIFETIME", 	_SMARTY_CACHE_LIFETIME); // in seconds

define("_ALLOW_FIREPHP_LOGGING", 		false);

# Is minification used for css & js
define("_MINIFY_JS", 					true);
define("_MINIFY_CSS", 					true);
define("_FLUSH_BUFFER_EARLY", 			true);

######################################
## 	COMMON APPLICATION PARAMETERS	##
######################################

define("_UNIQUE_VERSION", 				'1807111200');
define("_JS_VERSION", 					_UNIQUE_VERSION);
define("_CSS_VERSION", 					_UNIQUE_VERSION);
define("_FLASH_VERSION", 				_UNIQUE_VERSION);


?>