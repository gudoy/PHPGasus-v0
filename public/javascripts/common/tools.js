
var Tools = 
{	
	/** 
	 * @projectDescription	This files defines the global app object of our application and a tools object for containing some misc helpers
	 *
	 * @author Guyllaume Doyer
	 * @version 	0.3
	 */
	// usage: Tools.loadJS('/path/to/your/file.js'})
	// usage: Tools.loadJS('/path/to/your/file.js', function(){ /* global calback function code here */ })
	// usage: Tools.loadJS({url:'/path/to/your/file.js'})
	// usage: Tools.loadJS({url:'/path/to/your/file.js'}, function(){ /* global calback function code here */ })
	// usage: Tools.loadJS({id:'anyIdHere', url:'/path/to/your/file.js', success:function(){ /* some local callback code here */}}, function(){ /* global calback function code here */ })
	// usage: Tools.loadJS([{url:'/path/to/your/file1.js'}, {url:'/path/to/your/file2.js'}], function(){ /* global calback function code here */ })
	// usage: Tools.loadJS([{id:'file1', url:'/path/to/your/file1.js', success: function(){ alert('hello world!'); }}, {url:'file2', '/path/to/your/file2.js'}], function(){ /* global calback function code here */ })
	loadJS: function(jsFiles)
	{
		// 
		//var jsFiles = (typeof(jsFiles) === 'array' || typeof(jsFiles) === 'object' ) 
		//				? jsFiles 
		//				: ( (typeof(jsFiles) === 'String' ) ? [{url:jsFiles}] : [] ),
		
		// Force jsFiles to be an array 
		var jsFiles = typeof jsFiles === 'object' ? (jsFiles instanceof Array ? jsFiles : [jsFiles]) : ( typeof jsFiles === 'string' ? [{url:jsFiles}] : [] ),
		
		// Main callback (fired when all scripts have been loaded)
			//mainCallback = (arguments.length >= 1) ? arguments[1] : null,
			mainCallback = arguments[1] || function(){},
			
		// Number of successfully loaded files
			nb = 0,
			
			fCallback = function(){ if (nb == jsFiles.length) { mainCallback.call(null); } },
			
		// Default params for js files
			fileDefault = { id:null, url:null, success:function(){} };
			
		// For each required js file
		$.each(jsFiles, function()
		{
			var file = $.extend(fileDefault, this);
			
			if (file.url === null) { return; }

			// If the script already exists in the page, breaks and launch the callback
			if ( $('script[src*="' + file.url + '"]').length > 0 )
			{
				nb++;
				
				file.success.call(null); 
				
				fCallback.call(null);
				
				return;
			}
			
			//$.getScript(file.url, function() { loadedFiles.push(true); file.success.call(null); handleCallback(); });
			
            var script = document.createElement('script'),
				head = document.getElementsByTagName('head')[0];
            script.setAttribute('type', 'text/javascript');
            script.setAttribute('src', file.url);

			// Attach handlers for all browsers
			script.onload = script.onreadystatechange = function()
			{
				if ( (!this.readyState || this.readyState == "loaded" || this.readyState == "complete") ) 
				{					
					nb++;
					
					file.success.call(null);
					
					fCallback();

					// Handle memory leak in IE
					script.onload = script.onreadystatechange = null;
					head.removeChild( script );
				}
			};

			head.appendChild(script);
		});
		
		return this;
	},

	
	ucfirst:function(str)
	{
		return (str + '').replace(/^(.)|\s(.)/g, function($1){ return $1.toUpperCase(); });
	},
	
	lcfirst:function(str)
	{
		return (str + '').replace(/^(.)|\s(.)/g, function($1){ return $1.toLowerCase(); });
	},
	
	log: function(msg)
	{	
		typeof(console) != "undefined" && console.log ? console.log(msg) : (typeof(opera) != "undefined" && opera.postError ? opera.postError(msg) : false );
		//console && console.log ? console.log(msg) : (opera && opera.postError ? opera.postError(msg) : function(){} ) 

		return this;
	},
	
	trim: function(str)
	{
		return str.replace(/^\s+/g,'').replace(/\s+$/g,'');
	},
	
	/*
	strtr: function (str, from, to)
	{
    	var subst;
    	
		for (i = 0; i < from.length; i++)
		{
        	subst 	= (to[i]) ? to[i] : to[to.length-1];
        	str 	= str.replace(new RegExp(str[str.indexOf(from[i])], 'g'), subst);
    	}
		
	    return str;
	},*/
	
	
	strtr: function (str, charsTable)
	{
		for (var i in charsTable)
		{
			str = str.replace(new RegExp(i, 'g'), charsTable[i]);
		}
		
	    return str;
	},
	
	
	deaccentize: function(str)
	{
		var charsTable = {
			'Š':'S', 'š':'s', 'Đ':'Dj', 'đ':'dj', 'Ž':'Z', 'ž':'z', 'Č':'C', 'č':'c', 'Ć':'C', 'ć':'c',
			'À':'A', 'Á':'A', 'Â':'A', 'Ã':'A', 'Ä':'A', 'Å':'A', 'Æ':'A', 'Ç':'C', 'È':'E', 'É':'E',
			'Ê':'E', 'Ë':'E', 'Ì':'I', 'Í':'I', 'Î':'I', 'Ï':'I', 'Ñ':'N', 'Ò':'O', 'Ó':'O', 'Ô':'O',
			'Õ':'O', 'Ö':'O', 'Ø':'O', 'Ù':'U', 'Ú':'U', 'Û':'U', 'Ü':'U', 'Ý':'Y', 'Þ':'B', 'ß':'Ss',
			'à':'a', 'á':'a', 'â':'a', 'ã':'a', 'ä':'a', 'å':'a', 'æ':'a', 'ç':'c', 'è':'e', 'é':'e',
			'ê':'e', 'ë':'e', 'ì':'i', 'í':'i', 'î':'i', 'ï':'i', 'ð':'o', 'ñ':'n', 'ò':'o', 'ó':'o',
			'ô':'o', 'õ':'o', 'ö':'o', 'ø':'o', 'ù':'u', 'ú':'u', 'û':'u', 'ý':'y', 'ý':'y', 'þ':'b',
			'ÿ':'y', 'Ŕ':'R', 'ŕ':'r'
		};
		return Tools.strtr(str,charsTable);
	},
	
	
    slugify: function(str)
    {
        var str = Tools.deaccentize(str);

        // Replace non-standard chars
		/*
        id = preg_replace(
                array(
                    '`^[^A-Za-z0-9]+`',
                    '`[^A-Za-z0-9]+$`',
                    '`[^A-Za-z0-9]+`' ),
                array('','','-'),
                $id );
         */
		str = str.replace(/^[^A-Za-z0-9]+/g, '');
		str = str.replace(/[^A-Za-z0-9]+$/g, '');
		str = str.replace(/[^A-Za-z0-9]+/g, '-');

        return str;
    },
	
	
	/* 
	 * This function gets, in an URL, the value of the param given in the function call
	 * @author Guyllaume Doyer guyllaume@clicmobile.com
	 * @return {String|Boolean} The value if found, otherwise false
	 */
	getURLParamValue: function (url, paramName)
	{
		url = url.substring(url.indexOf("?"), url.length);
		
		var pos = url.indexOf(paramName);
		
		if (pos != -1 && paramName != "")
		{
			// Truncate the string from this position to its end
			var tmp 	= url.substr(pos),
			
			// Gets the start position of the param value
				start 	= pos + paramName.length,
			
			// Get end position of the param value
				end_pos;
			
				if 		(tmp.indexOf("&amp;") != -1){ end_pos = tmp.indexOf("&amp;"); } // case where there are others params after, separated by a "&amp;"
				else if (tmp.indexOf("&") != -1 ) 	{ end_pos = tmp.indexOf("&"); } 	// case where there are others params after, separated by a "&"
				else if (tmp.indexOf("#") != -1 ) 	{ end_pos = tmp.indexOf("#"); } 	// case where there are others params after, separated by a "#"
				else 								{ end_pos = tmp.length; } 			// case where there are no others params after
			
			// Truncate the string from 0 to the end of the param value			
			return tmp.substring(paramName.length + 1,end_pos);
		}
		else { return false; }
	},
	
	removeQueryParam: function(url, paramName)
	{
		var reg = new RegExp('(.*)' + paramName + '=([^\&\#\?]*)(.*)', 'g');
		//return url.replace(reg,'$1').replace(/(.*)[&|?]$/,'$1');
		return url.replace(reg,'$1$3').replace(/(.*)\?&(.*)/,'$1?$2').replace(/(.*)[&|?]$/,'$1');
		
		// test?values=1&id=1&applications_versions_id=1&releases_id=1.replace(new RegExp('(.*)' + 'value' + '=(.*)[&|$]')), '$1')
		//alert('test?values=1&id=1&applications_versions_id=1&bar=1&foobar=3'.replace(new RegExp('(.*)values=([^\&\#\?]*)(.*)','g'), '$1$3'))
	},
	
	var_dump: function(data)
	{
		var var_dump = function var_dump(data,addwhitespace,safety,level)
		{
		    var rtrn = '',
				dt,
				it,
				spaces = '';
				
		    if (!level) {level = 1;}
			
		    for (var i=0; i<level; i++) { spaces += '   '; }
			
		    if (typeof(data) != 'object')
			{
		       dt = data;
			   
		       if (typeof(data) == 'string') 
			   {
		          if (addwhitespace == 'html')
				  {
		             dt = dt.replace(/&/g,'&amp;');
		             dt = dt.replace(/>/g,'&gt;');
		             dt = dt.replace(/</g,'&lt;');
		          }
				  
		          dt = dt.replace(/\"/g,'\"');
		          dt = '"' + dt + '"';
		       }
			   
		       if(typeof(data) == 'function' && addwhitespace)
			   {
		          dt = new String(dt).replace(/\n/g,"\n"+spaces);
				  
		          if(addwhitespace == 'html')
				  {
		             dt = dt.replace(/&/g,'&amp;');
		             dt = dt.replace(/>/g,'&gt;');
		             dt = dt.replace(/</g,'&lt;');
		          }
				  
		       }
		       
			   if (typeof(data) == 'undefined') { dt = 'undefined'; }
		       
			   if (addwhitespace == 'html')
			   {
		          if(typeof(dt) != 'string') {  dt = new String(dt);  }
		          
				  dt = dt.replace(/ /g,"&nbsp;").replace(/\n/g,"<br>");
		       }
			   
		       return dt;
		    }
		    
			for (var x in data)
			{
		       if (safety && (level > safety))
			   {
		          dt = '*RECURSION*';
		       }
			   else
			   {
		          try { dt = var_dump(data[x],addwhitespace,safety,level+1); } catch (e) {continue;}
		       }
		       
			   it = var_dump(x,addwhitespace,safety,level+1);
		       rtrn += it + ':' + dt + ',';
		       
			   if (addwhitespace) {  rtrn += '\n'+spaces; }
		    }
		    
			if (addwhitespace)
			{
		       rtrn = '{\n' + spaces + rtrn.substr(0,rtrn.length-(2+(level*3))) + '\n' + spaces.substr(0,spaces.length-3) + '}';
		    }
			else
			{
		       rtrn = '{' + rtrn.substr(0,rtrn.length-1) + '}';
		    }
		    
			if (addwhitespace == 'html') {  rtrn = rtrn.replace(/ /g,"&nbsp;").replace(/\n/g,"<br>"); }
		    
			return rtrn;
		 };
		
		return this.log(var_dump(data));
	}
};

