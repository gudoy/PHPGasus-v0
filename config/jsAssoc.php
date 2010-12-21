<?php

$jsAssoc = array(
    # Libs
	'jquery' 			=> array('common/libs/jquery-1.4.4.min.js'),
	'jqueryUI'             => array('common/libs/jquery-ui-1.8.2.custom.min.js'),
	'jqueryPlusUI'         => array('jquery', 'jqueryUI'),
	'jqueryEasing'         => array('common/libs/jquery.easing.1.3.js'),
	'timepicker'           => array('common/libs/timepicker.js'),
	'modernizr' 		=> _APP_USE_MODERNIZR ? array('common/libs/modernizr-1.6.min.js') : array(),
	'googleMaps'           => array('http://maps.google.com/maps/api/js?sensor=false'),
	'tools'                => array('common/tools.js'),
	
    # PHPGasus defaults
	//'default' 			=> array('jqueryPlusUI', 'modernizr', 'tools', 'common/app.js', 'jqueryEasing'),
	'default'              => array('jqueryPlusUI', 'modernizr', 'tools', 'common/app.js', /*'jqueryEasing',*/ 'specific/photomaton.js'),
	'admin'                => array('default', 'common/pages/adminCommon.js', 'timepicker'),
	'adminHome' 		=> array('default', 'common/pages/adminSpecifics.js'),
    //'apiHome'              => array('default', 'common/pages/api/home.js'),
		
    # App specifics
	//'adminSpecifics' 	=> array('controllers/admin/adminCommon.js','controllers/admin/adminSpecifics.js',),
);
?>
