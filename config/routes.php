<?php 

# BASE PATHS
define("_PATH_DB",							_PATH . 'db/');
define("_PATH_CONFIG",						_PATH . 'config/');
define("_PATH_LIBS",						_PATH . 'libs/');
define("_PATH_SMARTY",						_PATH_LIBS . 'templating/smarty3/');
define("_PATH_LOG",							_PATH . 'logs/');
define("_PATH_CONTROLLERS",					_PATH . 'controllers/');
define("_PATH_VIEWS",						_PATH . 'views/');
define("_PATH_MODELS",						_PATH . 'models/');
define("_PATH_PUBLIC",						_PATH . 'public/');
define("_PATH_IMAGES",						_PATH . 'public/media/images/');
define("_PATH_TEMPLATES",					_PATH . 'templates/');
define("_PATH_JAVASCRIPTS",					_PATH . 'public/javascripts/');
define("_PATH_STYLESHEETS",					_PATH . 'public/stylesheets/default/');
define("_PATH_TMP", 						_PATH . 'tmp/');
define("_PATH_I18N", 						_PATH . 'i18n/');


# BASE URIS
define("_URL_HOME", 						_URL);
define("_URL_PUBLIC", 	 					_URL . 'public/');
define("_URL_PUBLIC_REL", 	 				'/public/');
define("_URL_STYLESHEETS_REL",				'/public/stylesheets/default/');
define("_URL_JAVASCRIPTS_REL", 	 			'/public/javascripts/');
define("_URL_STYLESHEETS",					_URL . 'public/stylesheets/default/');
define("_URL_JAVASCRIPTS", 	 				_URL . 'public/javascripts/');
define("_URL_DESIGN", 	 					_URL . 'public/stylesheets/default/images/');
define("_URL_MEDIA", 	 	 				_URL . 'public/media/');
define("_URL_FLASHS", 	 	 				_URL . 'public/media/flash/');
//define("_URL_FLASHS", 	 	 				_URL_STATIC_1 . 'media/flash/');
define("_URL_IMAGES", 						_URL_STATIC_1 . 'media/images/');
//define("_URL_IMAGES_STATIC", 				_URL . 'public/media/images/');
define("_URL_AUDIOS", 	 	 				_URL . 'public/media/audios/');
define("_URL_WIDGET_INSTALL", 				_URL . 'public/widget/builds/' . _APP_WIDGET_NAME);


# SOME COMMON URIs
define("_URL_CATEGORIES", 					_URL . 'categories/');
define("_URL_CATEGORY", 					_URL . 'category/');
define("_URL_SEARCH", 						_URL . 'search/');
define("_URL_SEARCH_ADVANCED", 				_URL . 'search/advanced');
define("_URL_ACCOUNT", 						_URL . 'account/');
define("_URL_LOGIN", 						_URL . 'account/login');
define("_URL_LOGOUT", 						_URL . 'account/logout');
define("_URL_SIGNUP", 						_URL . 'account/signup');
define("_URL_SIGN_SUCCESS", 				_URL . 'account/signup/success');
define("_URL_FORGOTTEN_PASSWORD", 			_URL . 'account/password/forgotten');
define("_URL_EDIT_PASSWORD", 				_URL . 'account/password');
define("_URL_EDIT_EMAIL", 					_URL . 'account/email');
define("_URL_NOT_CONFIRMED", 				_URL . 'account/notconfirmed');
define("_URL_RESEND_CONFIRMATION_MAIL", 	_URL . 'account/confirmation');
define("_URL_SITE_DOWN", 					_URL . 'site/down');
define("_URL_SITE_MAINTENANCE", 			_URL . 'site/maintenance');
define("_URL_404", 							_URL . 'site/error404');
define("_URL_ABOUT", 						_URL . 'about');
define("_URL_SITEMAP", 						_URL . 'about/sitemap');
define("_URL_ABOUT_TU", 					_URL . 'about/termsofuse');
define("_URL_ABOUT_TCS", 					_URL . 'about/termsofsale');
define("_URL_HELP", 						_URL . 'about/help');
define("_URL_CONTACT", 						_URL . 'about/contact');
define("_URL_ABOUT_CONTACT", 				_URL . 'about/contact');
define("_URL_REFERENCE", 					_URL . 'references/');
define("_URL_REFERENCES", 					_URL . 'references/');

define("_URL_ADMIN_DASHBOARD", 				_URL_ADMIN . 'dashboard/');
define("_URL_ADMIN_SETUP", 					_URL_ADMIN . 'setup/');
define("_URL_ADMIN_SETUP_RESOURCES", 		_URL_ADMIN_SETUP . 'resources/');


# APP SPECIFIC URIS


?>