<?php

define("_APP_CONTEXT", 					!getenv("APP_CONTEXT") ? 'prod' : getenv("APP_CONTEXT"));
define("_DOMAIN", 						preg_replace('/(.*\.)?(.*\..*)/', '$2', $_SERVER['SERVER_NAME']));
define("_SUBDOMAIN", 					str_replace('.' . _DOMAIN, '', $_SERVER['HTTP_HOST']));


###########
## LOCAL ##
###########

if ( _APP_CONTEXT == 'local' )
{
	define("_SMARTY_COMPILE_CHECK", 	true); 
	define("_SMARTY_FORCE_COMPILE", 	true); 
	
	// Is minification used for css & js
	define("_MINIFY_JS", 				false);
	define("_MINIFY_CSS", 				false);
	
	define("_URL", 						'http://' . $_SERVER['SERVER_NAME'] . '/');
	define("_PATH",						'C:/Program Files/xampp/htdocs/mynewproject/');
}


###########
## DEV ##
###########

if ( _APP_CONTEXT === 'dev' )
{
	define("_SMARTY_COMPILE_CHECK", 	true); 
	define("_SMARTY_FORCE_COMPILE", 	false); 
	
	define("_MINIFY_JS",				false);
	define("_MINIFY_CSS", 				false);
	
	define("_URL", 						'http://dev.' . _DOMAIN . '/');
	define("_PATH",						'/var/www/mynewproject/');

	define("_URL_ADMIN", 				'http://dev.' . _DOMAIN . '/admin/');
	define("_URL_IPHONE", 				'http://dev.' . _DOMAIN . '/iphone/');
	define("_URL_ANDROID", 				'http://dev.' . _DOMAIN . '/android/');
	define("_URL_API", 					'http://dev.' . _DOMAIN . '/api/');
	
	// FTP PARAMETERS
	define("_FTP_USER_PASSWORD",    		'F4K3paSSw0rD');
}



############
## PROD ##
############
// PROD params are used as default ones and should only be overridden
// in the the other environnement's configuration ABOVE

define("_URL", 							'http://' . $_SERVER['SERVER_NAME'] . '/');
define("_PATH",							'/var/www/mynewproject/');
define("_URL_STATIC", 					'http://static.' . _DOMAIN . '/');
define("_URL_STATIC_1", 				'http://static1.' . _DOMAIN . '/');
define("_URL_ADMIN", 					_URL . 'admin/');
define("_URL_API", 						_URL . 'api/');
define("_URL_IPHONE", 					'http://iphone.' . _DOMAIN . '/');
define("_URL_ANDROID", 					'http://android.' . _DOMAIN . '/');
define("_URL_STORE", 					'http://store.' . _DOMAIN . '/');

// DATABASE PARAMETERS
define("_DB_SYSTEM",   					'mysql');
define("_DB_HOST",    					'localhost');
define("_DB_USER",      				'admin');
define("_DB_PASSWORD",  				'F4K3paSSw0rD');
define("_DB_NAME",  					'mynewproject');
define("_DB_PORT",  					'3306'); // mysql:3306 , postgresql: 5432, sqlite:
define('_DB_TABLE_PREFIX', 				''/*_APP_NAMESPACE*/);

// FTP PARAMETERS
define("_FTP_HOST",    					'localhost');
define("_FTP_USER_NAME",    			'userftp');
define("_FTP_USER_PASSWORD",    		'F4K3paSSw0rD');
define("_FTP_PORT",    					'21');
define("_FTP_ROOT",    					'/');

// SMARTY CONF VARIABLES (for optimization and development purpose)
define("_SMARTY_COMPILE_CHECK", 		false);
define("_SMARTY_FORCE_COMPILE", 		false);
define("_SMARTY_CACHING", 				0);
define("_SMARTY_CACHE_LIFETIME", 		3600); // in seconds

// Is minification used for css & js
define("_MINIFY_JS", 					true);
define("_MINIFY_CSS", 					true);

##################################
## 	COMMON APPLICATION PARAMETERS	##
##################################

define("_JS_VERSION", 					'1');
define("_CSS_VERSION", 					'1');
define("_FLASH_VERSION", 				'1');

// SESSION DATA
define('_SESSION_NAME', 				'SID');

define('_ADMIN_RESOURCES_NB_PER_PAGE', 	50);

?>