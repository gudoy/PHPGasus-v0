// USAGE:
$(selector)formValidator
var formsValidator =
{
	init: function()
	{
		
	}
	
	checks:
	{ 
		'email': 		/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/,
		'tel': 			/(\+?\d[- .]*){7,13}/,
		'phone': 		/(\+?\d[- .]*){7,13}/,
		'phone-FR': 	/^(?:0|\(?\+33\)?\s?|0033\s?)[1-79](?:[\.\-\s]?\d\d){4}$/,
		'phone-UK': 	/^\s*\(?(020[7,8]{1}\)?[ ]?[1-9]{1}[0-9{2}[ ]?[0-9]{4})|(0[1-8]{1}[0-9]{3}\)?[ ]?[1-9]{1}[0-9]{2}[ ]?[0-9]{3})\s*$/,
		'phone-US': 	/\d{3}[\-]\d{3}[\-]\d{4}/,
		'year': 		/^[0-9]{4}$/,
		'date-w3c': 	/^[0-9]{4}\-[0-9]{2}\-[0-9]{2}$/,
		'datetime': 	/([0-2][0-9]{3})\-([0-1][0-9])\-([0-3][0-9])T([0-5][0-9])\:([0-5][0-9])\:([0-5][0-9])(Z|([\-\+]([0-1][0-9])\:00))/,
		'zipcode-FR': 	/^((0[1-9])|([1-8][0-9])|(9[0-8])|(2A)|(2B))[0-9]{3}$/
		'zipcode-US': 	/(\d{5}([\-]\d{4})?)/
		'zipcode-UK': 	/[A-Za-z]{1,2}[0-9Rr][0-9A-Za-z]? [0-9][ABD-HJLNP-UW-Zabd-hjlnp-uw-z]{2}/,
		'color': 		'TODO'
		'color-rgb': 	'TODO',
		'color-rgba': 	'TODO',
		'color-hsl': 	'TODO',
		'color-hex': 	/^#?([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/,
		'ip-v4': 		/((^|\.)((25[0-5])|(2[0-4]\d)|(1\d\d)|([1-9]?\d))){4}$/,
		'ip-v6': 		/((^|:)([0-9a-fA-F]{0,4})){1,8}$/,
		'json': 		'TODO',
		'uri': 			'TODO',
		'url': 			'TODO'
	},
	
	sanitize: function(val, type)
	{
		switch(type)
		{
			//case 'number': val = val + 0; break;
			case 'number': val = parseInt(val, 10); break;
			case 'year': val = val.length === 2 ? '00' + val : val; break;
			case 'float': val = parseFloat(val); break;
			case 'json:' 
			case 'html':
			case 'uri':
			case 'url':
			case 'color':
			case 'tel':
				// TODO 
			case 'email': 
			default:
				//val = val; break;
				break;
		}
		
		return val;
	},
	
	valida
}
