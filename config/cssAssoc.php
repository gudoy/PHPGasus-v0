<?php

//$pagesCSSassoc = array(
$cssAssoc = array(
	'app' 			=> array('phpgasus.css', 'orichalque.css', 'macaddict.css', _APP_NAME . '.css'),
	'default'      => array('reset.css', 'app'),

	'admin' 		=> array('reset', 'jquery-ui-1.8.9.custom.css', 'default'),
	'api' 			=> array('default', 'api.css'),
	
	// added automaticaly at the end of loaded css if necessary
);
?>