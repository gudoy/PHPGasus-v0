<?php

$cssAssoc = array(
	'jqueryUI' 		=> array('jquery-ui-1.8.16.custom.css'),

	'app' 			=> array(_APP_NAME . '.css'),
	'theme' 		=> array('macaddict.css'),
	'base' 			=> array('reset.css', 'phpgasus.css', 'orichalque.css'),
	
	'default' 		=> array('base', 'theme', 'app'),
	'api' 			=> array('base', 'api.css', 'theme', 'macaddict_api.css', 'app'),
	'admin' 		=> array('base', 'theme', 'jqueryUI', 'app'),
	
	// App specific
);
?>