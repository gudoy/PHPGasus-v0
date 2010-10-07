<?php

$jsAssoc = array(
	'jquery' 			=> array('common/libs/jquery-1.4.2.min.js'),
	'jqueryPlusUI' 		=> array('common/libs/jquery-1.4.2.min.js', 'common/libs/jquery-ui-1.8.2.custom.min.js'),
	'modernizr' 		=> _APP_USE_MODERNIZR ? array('common/libs/modernizr-1.5.min.js') : array(),
	'default' 			=> array('jqueryPlusUI', 'modernizr', 'common/tools.js', 'common/app.js', 'common/libs/jquery.easing.1.3.js'),
	'admin' 			=> array('default', 'common/pages/adminCommon.js', 'common/libs/timepicker.js'),
	'adminHome' 		=> array('default', 'common/pages/adminSpecifics.js'),
		
	//'adminSpecifics' 	=> array('controllers/admin/adminCommon.js','controllers/admin/adminSpecifics.js',),
	'apiHome' 			=> array('default', 'common/pages/api/home.js'),
);
?>
