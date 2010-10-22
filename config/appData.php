<?php

### APP META
define("_APP_NAME", 							'mynewproject');
define("_APP_VERSION", 							'0.1.0.0');
define('_APP_NAMESPACE', 						'pgas');
define('_APP_TITLE', 							'mynewproject');
define('_APP_BASELINE', 						'');
define('_APP_AUTHOR_NAME', 						'Clicmobile');
define('_APP_AUTHOR_MAIL', 						'contact@clicmobile.com');
define('_APP_AUTHOR_URL', 						'http://www.clicmobile.com');
define('_APP_OWNER_NAME', 						'Clicmobile');
define('_APP_OWNER_MAIL', 						'info@clicmobile.com');
define('_APP_OWNER_CONTACT_MAIL', 				'info@clicmobile.com');
define('_APP_OWNER_URL', 						'http://www.clicmobile.com');
define('_APP_OWNER_MAP_URL', 					'');

### SOME HTML RELATED OPTIONS
define('_APP_DEFAULT_LANGUAGE', 				'fr_FR'); 		// or en_US, en_GB, de_DE, es_EN, it_IT, ja_JP, zh_CN, ko_KR
define('_APP_DOCTYPE', 							'html5');		// 'html5', 'xhtml-strict-1.1', 'xhtml-strict', 'xhtml-transitional', 
define('_APP_DEFAULT_OUTPUT_FORMAT', 			'html');		// Is there case where it won't be html?
define('_APP_DEFAULT_OUTPUT_MIME', 				'text/html');	// 
define('_APP_USE_MANIFEST', 					false);			//
define('_APP_MANIFEST_FILENAME', 				_APP_TITLE . '.manifest'); // 
define('_APP_META_DECRIPTION', 					'');
define('_APP_META_KEYWORDS', 					'mynewproject');
define('_APP_META_ROBOTS_INDEXABLE', 			true);			// Allows/prevents pages to be indexed by Google & Friends?
define('_APP_META_ROBOTS_ARCHIVABLE', 			true);			// Allows/prevents search engines to display "in cache" links in their search results
define('_APP_META_ROBOTS_IMAGES_INDEXABLE', 	true);			// Allows/prevents search engines to index your images
define('_APP_META_GOOGLE_TRANSLATABLE', 		true);			// Allows/prevents Google to offer translation link/feature for your pages
define('_APP_USE_CHROME_FRAME', 				true);			// Html pages require Google Chrome Frame plugin? (if yes, displays plugin installation popup)
define('_APP_USE_CSS_IE', 						false);
define('_APP_USE_CSS_IE6', 						false);
define('_APP_USE_CSS_IE7', 						false);
define('_APP_USE_CSS_OPERA', 					false);

### MISC SNIFFING & FEATURES DETECTION
define('_APP_SNIFF_PLATFORM', 					true); 			// Disable this if you don't want to try getting the platform data (prevent unnecessary processing)
define('_APP_SNIFF_BROWSER', 					true); 			// Disable this if you don't want to try getting the browser data (prevent unnecessary processing)
define('_APP_USE_MODERNIZR', 					true); 			// If allowed, Modernizr (js lib) will be added to detect user browser capabilities adding subsenquent classes to the <HTML> tag


// IOS (iPhone/iPad/i???) OPTIONS
define('_APP_IOS_WEBAPP_CAPABLE', 				false); 		// 
define('_APP_IOS_INISCALE', 					'1.0'); 		// Default page scale for iphones (default = 1.0)
define('_APP_IOS_MAXSCALE', 					'3.0'); 		// Allow iphones to scale up/down pages (default = 1.0) 
define('_APP_IPHONE_INISCALE', 					'1.0'); 		// Default page scale for iphones (default = 1.0)
define('_APP_IPHONE_MAXSCALE', 					'3.0'); 		// Allow iphones to scale up/down pages (default = 1.0)
define('_APP_IPAD_INISCALE', 					'1.0'); 		// Default page scale for ipads (default = 1.0)
define('_APP_IPAD_MAXSCALE', 					'3.0'); 		// Allow ipads to scale up/down pages (default = 1.0)

define('_APP_BREADCRUMB_SEPARATOR', 			'/');

### GOOGLE ANALYTICS  
define('_APP_USE_GOOGLE_ANALYTICS', 			false);
define('_APP_GOOGLE_ANALYTICS_UA', 				'UA-XXXXX-X');

### ACCOUNT SESSIONS HANDLING
define('_APP_USE_ACCOUNTS', 					true); 			// Disable this prevent app from trying to update sessions table on each page load
define('_APP_ALLOW_SIGNUP', 					false); 		// Allow users to sign up by themselves
define('_APP_SESSION_DURATION', 				900); 			// In seconds. (ex: 900s = 15 minutes)
define('_APP_IS_SESSION_CROSS_SUBDOMAIN', 		true); 			// 
define('_APP_KEEP_OLD_SESSIONS', 				false); 		// By default, when a user login, its sessions older than 1 day are deleted


define('_APP_USE_EVENTS', 						true); 			// Disable this if you do not want to use events

### WIDGETS
define('_APP_HAS_RELATED_WIDGET', 				false);
define('_APP_WIDGET_VERSION', 					'0.1.1');
define('_APP_WIDGET_NAME', 						_APP_NAMESPACE . '_' .  _APP_CONTEXT . '_' . _APP_WIDGET_VERSION . '.wgt');

define('_PHPGASUS_VERSION', 					'0.5.3.0');

### AMAZON WEB SERVICES
define('_AWS_ACCESSKEY', 						'yourAccessKeyHere');
define('_AWS_SECRET_KEY', 						'yourSecretKeyHere');
define('_AWS_BASE_BUCKET', 						'yourBucketName');

### IPHONE
define('_APP_STORE_URL', 						'http://itunes.apple.com');
define('_APP_IPHONE_PUSH_GATEWAY_TEST', 		'ssl://gateway.sandbox.push.apple.com:2195');
define('_APP_IPHONE_PUSH_GATEWAY_PROD', 		'ssl://gateway.push.apple.com:2195');

?>