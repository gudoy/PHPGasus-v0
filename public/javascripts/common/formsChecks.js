var FIELDS = [],
	FORMS = [];

var CHECKS =
{
	name:			function(jqObject){ return CHECKS.test(jqObject, /^[^&~#"\{\(\[\|`_\\\^@\)\]°=\}\+¤\$£\^¨€%\*µ!§:\/;\.,\?<>0123456789£¤¨µ§]{2,32}$/i); },
	email_1:		function(jqObject){ return CHECKS.test(jqObject, /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/); },
	login:			function(jqObject){ return CHECKS.test(jqObject, /^[a-zA-Z0-9_\.\-]{3,32}$/i); },
	email_2:		function(jqObject){ return $('input.check-email_1').val() == jqObject.val(); },
	login:			function(jqObject){ return CHECKS.test(jqObject, /^[a-zA-Z0-9_\.\-]{3,32}$/i); },
	password:		function(jqObject){ return CHECKS.test(jqObject, /^[a-zA-Z0-9_\.\-]{1,32}$/i); },
	password_1:		function(jqObject){ return CHECKS.test(jqObject, /^[a-zA-Z0-9_\.\-]{1,32}$/i); },
	password_2:		function(jqObject){ return $('input.check-password_1').val() == jqObject.val(); },
	captcha:		function(jqObject){ return CHECKS.test(jqObject, /^[0-9]{2}$/i); },
	year:			function(jqObject){ return CHECKS.test(jqObject, /^[0-9]{4}$/i); },
	ccownername:	function(jqObject){ return CHECKS.test(jqObject, /^[a-zA-Z\s]{3,64}$/i); },
	ccgroup:		function(jqObject){ return CHECKS.test(jqObject, /^[0-9]{4}$/i); },
	ccnumber:		function(jqObject){ return CHECKS.test(jqObject, /^[0-9]{16}$/i); },
	cccrypto:		function(jqObject){ return CHECKS.test(jqObject, /^[0-9]{3}$/i); },
	checked:		function(jqObject){ return jqObject.attr('checked') },
	
	//phone_1:		function(jqObject){	return jqObject.val() !== '06') && CHECKS.test(jqObject, /^((06)\d{8})$/); },
	notEmpty:		function(jqObject){ return jqObject.val() !== '' },
	selectOne:		function(jqObject){ return $('option:selected', jqObject).not('.notCorrectValue').length > 0 && $('option:selected', jqObject).val() !== ''; },
	
	test: function(jqObject, rule) { return rule.test(jqObject.val()); }
};

