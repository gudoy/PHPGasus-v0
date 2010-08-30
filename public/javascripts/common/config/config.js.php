<?php

require($_SERVER['DOCUMENT_ROOT'] . "config/includes.php");

// Set language to have properly translated values
class_exists('Application') || require _PATH_LIBS . 'Application.class.php';
Application::setlanguage();

// App the proper header
header('Content-type: application/javascript; charset=utf-8');

?>
var CONFIG = 
{
	_URL: 				"<?php echo _URL; ?>",
	_URL_JAVASCRIPTS: 	"<?php echo _URL_JAVASCRIPTS; ?>",

	loading: 			"<?php echo _('Loading...'); ?>",

	/* Common Messages */
	name_err: 			"<?php echo _('2 to 32 characters expected.')?>",
	email_1_err: 		"<?php echo _('Email address not valid.')?>",
	login_err: 			"<?php echo _('Only letters or numerics (3 to 32 characters)'); ?>",
	password_err: 		"<?php echo _('Only letters and numbers are allowed.'); ?>",
	password_1_err: 	"<?php echo _('Only letters and numbers are allowed.'); ?>",
	password_2_err: 	"<?php echo _('The password and its confirmation are not the same.'); ?>",
	captcha_err: 		"<?php echo _('Figure of 2 numerics expected.'); ?>",
	year_err: 			"<?php echo _('Figure of 4 numerics expected.'); ?>",
	ccownername_err: 	"<?php echo _('Only letters and spaces. (3 to 64 characters)'); ?>",
	ccgroup_err: 		"<?php echo _('Figure of 4 numerics expected.'); ?>",
	ccnumber_err: 		"<?php echo _('Figure of 16 numerics expected.'); ?>",
	cccrypto_err: 		"<?php echo _('Figure of 3 numerics expected.'); ?>",
	checked_err: 		"<?php echo _('You have to check this checkbox.'); ?>"
};