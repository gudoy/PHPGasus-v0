<?php

$cssAssoc = array(
	'jqueryUI' 		=> array('jquery-ui-1.8.16.custom.css'),

	'app' 			=> array(_APP_NAME . '.css'),
	'theme' 		=> array('layout.css', 'macaddict.css'),
	/*'theme' 		=> array('layout.new.css', 'macaddict.css'),*/
	//'base' 			=> array('reset.css', 'phpgasus.css', 'orichalque.css'),
	//'base' 			=> array('reset.css', 'phpgasus.css', 'orichalque.css', 'forms.css'),
	'base' 			=> array('reset.css', 'phpgasus.css', 'orichalque.css', 'forms.new.css'),
	
	'default' 		=> array('base', 'theme', 'app'),
	'api' 			=> array('base', 'theme', 'macaddict_api.css', 'app'),
	'admin' 		=> array('base', 'theme', 'jqueryUI', 'app'),
	
	// App specific
);
?>