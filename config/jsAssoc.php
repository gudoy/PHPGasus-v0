<?php

$libs       = 'common/libs/';
$pages      = 'common/pages/';
$slibs      = 'specific/libs/';
$spages     = 'specific/pages/';

$jsAssoc    = array(
    # Libs
	//'jquery' 				=> array($libs . 'jquery-1.8.1.min.js'),
	'jquery' 				=> array($libs . 'jquery-1.9.1.min.js', $libs . 'jquery-migrate-1.1.1.min.js'),
	'jqueryUI' 				=> array($libs . 'jquery-ui-1.8.23.custom.min.js'),
	'jqueryPlusUI' 			=> array('jquery', 'jqueryUI'),
	'jqueryEasing' 			=> array($libs . 'jquery.easing.1.3.js'),
	'timepicker' 			=> array($libs . 'jquery-ui-timepicker-addon.js'),
	'modernizr' 			=> _APP_USE_MODERNIZR ? array($libs . 'modernizr.custom.js') : array(),
	'tools' 				=> array('common/tools.js'),
	'tinyMCE' 				=> array($libs . 'tiny_mce/jquery.tinymce.js'),
	
    # PHPGasus defaults
	'default' 				=> array('jqueryPlusUI', 'modernizr', 'tools', 'common/app.js'),
	//'admin' 				=> array('default', 'timepicker', $pages . 'adminCommon.js'),
	'admin' 				=> array('default', 'timepicker', 'tinyMCE', $pages . 'adminCommon.js'),
	'adminHome' 			=> array('admin', $pages . 'adminSpecifics.js'),
	'adminResources' 		=> array('admin', $pages . 'admin/adminResources.js'),
	//'adminResourcesCreate' 	=> array('admin', $pages . 'admin/adminResources.js'),
	//'adminResourcesUpdate' 	=> array('admin', $pages . 'admin/adminResources.js'),
	'accountLogin' 			=> array('default', $libs . 'jquery.cookie.js', $pages . 'account/login.js'),
	'apiHome' 				=> array('default', $pages . 'api/home.js'),

    # App specifics
);
?>