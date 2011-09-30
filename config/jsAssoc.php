<?php

$libs       = 'common/libs/';
$pages      = 'common/pages/';
$slibs      = 'specific/libs/';
$spages     = 'specific/pages/';

$jsAssoc    = array(
    # Libs
	'jquery'               => array($libs . 'jquery-1.6.4.min.js'),
	'jqueryUI'             => array($libs . 'jquery-ui-1.8.16.custom.min.js'),
	'jqueryPlusUI'         => array('jquery', 'jqueryUI'),
	'jqueryEasing'         => array($libs . 'jquery.easing.1.3.js'),
	'timepicker'           => array($libs . 'jquery-ui-timepicker-addon.js'),
	'modernizr' 			=> _APP_USE_MODERNIZR ? array($libs . 'modernizr.custom.js') : array(),
	'tools'                => array('common/tools.js'),
	
    # PHPGasus defaults
	'default'              => array('jqueryPlusUI', 'modernizr', 'tools', 'common/app.js'),
	'admin'                => array('default', 'timepicker', $pages . 'adminCommon.js'),
	'adminHome'            => array('admin', $pages . 'adminSpecifics.js'),
	'adminResourcesCreate' => array('default', $pages . 'admin/adminResources.js'),
	'adminResourcesUpdate' => array('default', $pages . 'admin/adminResources.js'),
	'accountLogin'         => array('default', $libs . 'jquery.cookie.js', $pages . 'account/login.js'),
	'apiHome'              => array('default', $pages . 'api/home.js'),

    # App specifics
	//'adminSpecifics' 	=> array('controllers/admin/adminCommon.js','controllers/admin/adminSpecifics.js',),
);
?>
