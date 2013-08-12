<?php

define('_PHPGASUS_VERSION', 						'0.7.4.0');

### APP META
define('_APP_NAMESPACE', 							'pgas');
define("_APP_VERSION", 								'0.1.0.0');
define('_APP_TITLE', 								_APP_NAME); // App displayed name
define('_APP_BASELINE', 							'');
define('_APP_META_AUTHOR', 							false);
define('_APP_AUTHOR_NAME', 							'Guyllaume Doyer');
define('_APP_AUTHOR_MAIL', 							'doyer.guyllaume@gmail.com');
define('_APP_AUTHOR_URL', 							'http://www.diginja.com');
define('_APP_META_OWNER', 							true);
define('_APP_OWNER_NAME', 							'');
define('_APP_OWNER_MAIL', 							'');
define('_APP_META_REPLYTO', 						'');
define('_APP_OWNER_CONTACT_MAIL', 					'');
define('_APP_OWNER_URL', 							'');
define('_APP_OWNER_MAP_URL', 						'');

### SOME HTML RELATED OPTIONS
define('_APP_DEFAULT_LANGUAGE', 					'fr_FR'); 		// or en_US, en_GB, de_DE, es_EN, it_IT, ja_JP, zh_CN, ko_KR
define('_APP_LANGUAGES', 							'fr_FR,en_US');// List of languages (translatations) for the app, separated by comas
define('_APP_DOCTYPE', 								'html5');		// 'html5', 'xhtml-strict-1.1', 'xhtml-strict', 'xhtml-transitional', 
define('_APP_DEFAULT_OUTPUT_FORMAT', 				'html');		// Is there case where it won't be html?
define('_APP_DEFAULT_OUTPUT_MIME', 					'text/html');	// 
define('_APP_USE_MANIFEST', 						false);			//
define('_APP_MANIFEST_FILENAME', 					_APP_TITLE . '.manifest'); // 
define('_APP_META_DECRIPTION', 						'');
define('_APP_META_KEYWORDS', 						'');
define('_APP_META_ROBOTS_INDEXABLE', 				false);			// Allows/prevents pages to be indexed by Google & Friends?
define('_APP_META_ROBOTS_FOLLOW', 					false);			// Allows/prevents linked pages to be indexed by Google & Friends?
define('_APP_META_ROBOTS_ARCHIVABLE', 				false);			// Allows/prevents search engines to display "in cache" links in their search results
define('_APP_META_ROBOTS_IMAGES_INDEXABLE', 		false);			// Allows/prevents search engines to index your images
define('_APP_META_GOOGLE_TRANSLATABLE', 			true);			// Allows/prevents Google to offer translation link/feature for your pages
define('_APP_ALLOW_PAGE_PRERENDERING', 				true);			// Allows/prevents Google Chrome to prepender pages in background 
define('_APP_USE_CHROME_FRAME', 					true);			// Html pages require Google Chrome Frame plugin? (if yes, displays plugin installation popup)
define('_APP_USE_CSS_IE', 							false);
define('_APP_USE_CSS_IE6', 							false);
define('_APP_USE_CSS_IE7', 							false);
define('_APP_USE_CSS_OPERA', 						false);

### MISC SNIFFING & FEATURES DETECTION
define('_APP_SNIFF_PLATFORM', 						true); 			// Disable this if you don't want to try getting the platform data (prevent unnecessary processing)
define('_APP_SNIFF_BROWSER', 						true); 			// Disable this if you don't want to try getting the browser data (prevent unnecessary processing)
define('_APP_USE_MODERNIZR', 						true); 			// If allowed, the js lib Modernizr will be added to detect user browser capabilities adding subsenquent classes to the <HTML> tag


// IOS (iPhone/iPad/i???) OPTIONS
define('_APP_STORE_URL', 							'http://itunes.apple.com');
define('_APP_IOS_WEBAPP_CAPABLE', 					false); 		// 
define('_APP_IOS_INISCALE', 						'1.0'); 		// Default page scale for iphones (default = 1.0)
define('_APP_IOS_MAXSCALE', 						'3.0'); 		// Allow iphones to scale up/down pages (default = 1.0) 
define('_APP_IPHONE_INISCALE', 						'1.0'); 		// Default page scale for iphones (default = 1.0)
define('_APP_IPHONE_MAXSCALE', 						'3.0'); 		// Allow iphones to scale up/down pages (default = 1.0)
define('_APP_IPAD_INISCALE', 						'1.0'); 		// Default page scale for ipads (default = 1.0)
define('_APP_IPAD_MAXSCALE', 						'3.0'); 		// Allow ipads to scale up/down pages (default = 1.0)

### GOOGLE ANALYTICS  
define('_APP_USE_GOOGLE_ANALYTICS', 				false);
define('_APP_GOOGLE_ANALYTICS_UA', 					'UA-XXXXX-X');

### GOOGLE API KEYS (http://www.google.com/apis/maps/signup.html)
define('_APP_GOOGLE_MAPS_API_KEY',               	'');

### ACCOUNT SESSIONS HANDLING
define('_SESSION_NAME', 							'token'); 		// Name of the sessions
define('_APP_ALLOW_GET_SID_FROM_URL', 				false); 		// For security issues, it's recommanded not to allow passing session id in URLs, unless you use https and/or are sure of what you do 
define('_APP_USE_ACCOUNTS', 						true); 			// Disable this prevent app from trying to update sessions table on each page load
define('_APP_ALLOW_SIGNUP', 						false); 		// Allow users to sign up by themselves
define('_APP_SESSION_DURATION', 					60*15); 		// In seconds. (ex: 900s = 15 minutes)
define('_APP_IS_SESSION_CROSS_SUBDOMAIN', 			true); 			// 
define('_APP_HTTPSONLY_LOGIN', 						true); 			// Only allow login on HTTPS (add 'httpOnly' & 'secure' params to session/cookies)
define('_APP_KEEP_OLD_SESSIONS', 					false); 		// By default, when a user login, its sessions older than 1 day are deleted
define('_APP_USE_ACCOUNTS_CONFIRMATION', 			true);          // Will require accounts to be confirmed (email sent with activation link)
define('_APP_MAX_LOGIN_ATTEMPTS', 					5);          	// If the user tries to login more than X times, it's account will be blocked for some time (0 = no limit)
define('_APP_MAX_LOGIN_ATTEMPTS_BAN_TIME', 			60*60*2); 		// Duration (in seconds) of the ban of the account due to too many login attemps
define('_APP_ALLOW_LOST_PASSWORD_RESET', 			true); 			// Allow users to reset their's password (send a mail with a link to reset it)
define('_APP_IP_WHITELIST', 						''); 			// CSV list of IP adresses than could not be banned. Ex: 127.0.0.1,192.168.0.1
define('_APP_PASSWORDS_EXPIRATION_TIME', 			0); 			// In seconds. Use this to make password valid only for a specific duration (0 = no expiration)
define('_APP_PASSWORDS_EXPIRATION_EXEMPTED_GROUPS', 'gods,superadmins'); // Coma separated group names that won't suffer password expiration. 
define('_APP_PASSWORD_FORBID_LAST_TWO', 			false); 			// Prevents the user to use one of its previous two passwords when changing it.
define('_APP_PASS_MIN_TIME_BETWEEN_CHANGES', 		0); 			// In seconds. Min time between 2 password changes for the same user. (0 = no limit)
define('_APP_PASS_MIN_TIME_BETWEEN_CHANGES_H', 		''); 			// (Humanly readable value for above conf).
define('_APP_PASS_FORCE_DEFINE_ON_1ST_LOGIN', 		false); 		//
define('_APP_ALLOW_HTTP_AUTH', 						true); 			//


define('_APP_DEFAULT_TIMEZONE', 					'UTC'); 		// http://php.net/manual/en/timezones.php


### AMAZON WEB SERVICES
define('_AWS_ACCESSKEY', 							'yourAccessKeyHere');
define('_AWS_SECRET_KEY', 							'yourSecretKeyHere');
define('_AWS_BASE_BUCKET', 							'yourBucketName');

### FACEBOOK
define('_APP_USE_FACEBOOK_LOGIN', 					false);
define('_FACEBOOK_API_URL', 						'https://graph.facebook.com/');
define('_FACEBOOK_APP_ID', 							'');
define('_FACEBOOK_APP_SECRET', 						'');
define('_FACEBOOK_APP_DOMAIN', 						_SUBDOMAIN . _DOMAIN);


### TWITTER
define('_APP_USE_TWITTER_LOGIN', 					false);
define('_TWITTER_API_URL', 							'https://api.twitter.com/1.1/');
define('_TWITTER_CONSUMER_KEY', 					'');
define('_TWITTER_CONSUMER_SECRET', 					'');
define('_TWITTER_ACCESS_TOKEN', 					'');
define('_TWITTER_TOKEN_SECRET', 					'');



### GOOGLE
define('_APP_USE_GOOGLE_LOGIN', 					false);
define('_GOOGLE_CLIENT_ID', 						'');
define('_GOOGLE_CLIENT_SECRET', 					'');


### FEATURES
define('_ADMIN_RESOURCES_NB_PER_PAGE', 				50);
define('_APP_USE_EVENTS',                       	true);          // Disable this if you do not need to use events 
define('_APP_USE_SQL_TYPEFIXING',              		false);         // experimental.
define('_APP_USE_ONFETCH_TYPEFIXING', 				true);          // experimental.
define('_APP_TYPEFIX_ONETOONE_GETFIELDS',       	true);          // experimental.
define('_APP_TYPEFIX_MANYTOMANY_GETFIELDS',     	true);          // experimental.
define('_APP_SEARCH_ALWAYS_GLOBAL',             	true); 			// experimental.
define('_APP_USE_DEFERED_JS',                   	false);         // experimental.
define('_XML2ARRAY_FIX_TEXT_NODES_ATTRIBUTES',  	true);          // experimental.
define('_APP_USE_RESOURCESGROUPS',              	true);          // experimental.
//define('_APP_USE_SQL_REINDEXBY_V2',             	false); 		// experimental.
//define('_APP_ENABLE_SPLITED_ONE2ONE_COLS',      	true); 			// experimental.
define('_APP_USE_FIREPHP_LOGGING',      			true); 			// experimental. In local & dev environment, use FirePHP server lib to log data (using $this->dump()) into Firefox console (require related extension).
define('_APP_USE_CHROMEPHP_LOGGING',      			true); 			// experimental. In local & dev environment, use ChromePHP server lib to log data (using $this->dump()) into Chrome console (require related extension).
define('_APP_FETCH_RELATED_ONETOMANY', 				false); 		// experimental. Automatically fetch related onetomany items
define('_APP_USE_PATTERN_VALIDATION',              	true);          // experimental.
define('_APP_ADMIN_LIST_DEFAULT_DISPLAY_MODE',      'table');       // experimental. 'table', 'list', 'thumbs'
define('_APP_USE_EXTREMIST_REST_API',      			true);       	// experimental. Will only return collections and remove errors & warnings from outputs 


?>