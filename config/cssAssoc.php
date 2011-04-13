<?php

//$pagesCSSassoc = array(
$cssAssoc = array(
	//'app' 			=> array('phpgasus.css', 'greybox.css'),
	'app' 			=> array('phpgasus.css', 'admin_new.css', 'macaddict.css'),
	'default' 		=> array('reset.css', 'text.css', 'layout.css', 'app'),
	'default'      => array('reset.css', 'app'),

	//'admin' 		=> array('default', 'jquery-ui-1.8.9.custom.css', 'admin.css', '--greybox.css'),
	//'admin' 		=> array('default', 'jquery-ui-1.8.9.custom.css', 'admin_new.css', 'macaddict.css', '--greybox.css'),
	'admin' 		=> array('default', 'jquery-ui-1.8.9.custom.css',),
	'api' 			=> array('default', '--greybox.css', 'api.css'),
	
	// added automaticaly at the end of loaded css if necessary
);
?>