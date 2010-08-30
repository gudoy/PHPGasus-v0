<?php

//$pagesCSSassoc = array(
$cssAssoc = array(
	'default' 		=> array('reset.css', 'text.css', 'layout.css', 'app'),
	'app' 			=> array('phpgasus.css', 'greybox.css'),

	'admin' 		=> array('default', 'jquery-ui-1.8.2.custom.css', 'admin.css', '--clicmobile.css'),
	'api' 			=> array('default', '--greybox.css', 'api.css'),
	
	// added automaticaly at the end of loaded css if necessary
	'iphone' 		=> array('iphone.css', 'mynewprojectIphone.css'),
	//'ipad' 		=> array('ipad.css'),
	'android' 		=> array('iphone.css', 'android.css', 'mynewprojectIphone.css'),
);
?>