if ( typeof Modernizr !== 'undefined' )
{
	Modernizr.addTest("overflowscrolling",function(){
	    return Modernizr.testAllProps("overflowScrolling");
	});
	
	Modernizr.addTest("flexboxlegacy",function(){
    	return Modernizr.testAllProps('boxDirection');
	});
}


var ua 	= navigator.userAgent || null;
var app = 
{
	loadingBlock : '<div class="loadingMsgBlock"><span class="label">' + 'Loading...' + '</span></div>',
	//isMobile: window.screen.width <= 600,
	//isBackBerry: ua.indexOf('BlackBerry') > -1,
	fullscreen: window.navigator.standalone || false,
	orientation: 'landscape',
	
	init: function()
	{
		var self = this;
		
		this.sniff();
		this.notifier.init();
		ui.init();
		
		return this;
	},
	
	// Dynamic plugins loading
	// name => {}
	// 		status: 0=not loaded, 1=loaded, 2=loading
	plugins: {}, 
	require: function()
	{
		var that 	= this,
			args 	= arguments,
			plugin 	= args[0] || null,
			loaded 	= {};
		
		if ( plugin === 'notifier' )
		{
			that.plugins.notifier = {status:2};		
			Tools.loadCSS('/public/stylesheets/default/jquery.noty.css', {'id':'jqueryNotyCSS'});
			Tools.loadJS(
			{
				id:'jqueryNotyJS', 
				url:'/public/javascripts/common/libs/jquery.noty.js', 
				success:function(){ that.plugins.notifier.status = 1;}
			});
		}
		
		return this;
	},
	
    conf:
    {
    	env: 'dev', // dev, preprod, prod 
		debug: true,
    	
    	hideUrlBar: true,
    	//fullscreen: true
    	allowFullScreen: true
    },
    
    support:
    {
        init: function()
        {
        	this.hasOverflowScroll();
			
			return this;	
        },
        
		touch: (typeof Touch === 'object'),
        
        hasOverflowScroll: function()
        {
			// For those that cannot handle overflow scrolling with a good ux (either natively or using javascript fallback)
			if ( app.os === 'wpos' && app.osVersion.major == 7 ){ $('html').addClass('no-touchscroll'); }
        },
        
        builtInDateWidget: function()
        {
        	var ret = false;
        	
        	if 		( app.browser === 'chrome' ){ ret = true; }
        	else if ( app.browser === 'opera' )	{ ret = true; }
        	else if ( app.os === 'ios' )		{ ret = true; }
        	
        	return ret;  
        },
        
        builtInDatetimeWidget: function()
        {
        	var ret = false;
        	
        	if 		( app.browser === 'opera' )	{ ret = true; }
        	else if ( app.os === 'ios' )		{ ret = true; }
        	
        	return ret;  
        }
    },

    prepare: function()
    {
    	var self 	= this,
    		$window = $(window);
    	
        this
        	.sniff() 				// Will try to detect things like os, platform, engine, browser, ... and some others
        	.handleOrientation() 	// Will handle orientation change 
    		.makeFullScreen() 		// Will force the app to use the full size of the screen and hide the url bar
    		.preventBouncing() 		//
    	
    	// For iOS and Android > 4, do it also when the orientation change 
        if ( app.os === 'ios' )
        {
			$window.on('orientationchange', function(){ self.makeFullScreen(); })
        }
    	// For iOS and Android > 4 
        else if ( app.os === 'android' && app.osVersion.major >= 4 )
        {        	
        	// Android support orientationchange event since version 3
        	// But it seems not to reliabily updates the window dimensions after the orientation changed. So we cannot use it safely for the moment
        	// Instead, fallback using the resize event
			//$window.on('orientationchange', function(){ self.makeFullScreen(); })
        	$window.on('resize', function(){ self.makeFullScreen(); })
			
			// Required for Android < 4.1
			if ( app.osVersion.minor == 0 ){ $window.on('load', function(){ self.makeFullScreen(); }) }
        }
        // But for Android < 4 that does not support this event, use the resize event instead
        else if ( app.os === 'android' && app.osVersion.major < 4 )
        {
    		//$window.on('load', function(){ self.makeFullScreen(); })
    		$window
    			.on('resize', function()
    			{
    				// Let at least 1 second before trying to make fullscreen since android trigger resize events when the page is scrolled by the user 
    				// With a smaller delay, the user will no longer be able to display the url bar
    				//window.setTimeout(function(){ self.makeFullScreen(); }, 1);
    				window.setTimeout(function(){ self.makeFullScreen(); }, 1000);
    			})
        }
        
        this.support.init();
        
        // Virtual & crossbrowser events
		this.vtap 				= typeof $.zepto !== 'undefined' ? 'tap click' : 'click';
		this.vmousedown         = 'touchstart mousedown';
		this.vmousemove         = 'touchmove mousemove';
		this.vmouseup 			= 'touchend mouseup';
		this.vmousecancel 		= 'touchcancel';
		
		this.transStartEvent 	= 'webkitTransitionStart transitionstart';
        this.transEndEvent   	= 'webkitTransitionEnd transitionend';
        
        return this;
    },

    sniff: function()
    {
//alert(navigator.userAgent);
		var ucfirst 		= function(str){ return str.substr(0,1).toUpperCase() + str.substr(1,str.length); },
			pad 			= function(arr,s,v){ var l = s - arr.length; for (var i = 0; i<l; i++){ arr.push(v); } return arr; }, 
        	ua 				= navigator.userAgent || 'unknown',
            classes 		= '',
            checks 			= ['platform','browser','engine','os','osVersion','browserVersion'],
            platforms 		= ['iPhone','iPad','iPod','android','Android','Windows Phone','Windows', 'Mac OS X','Linux','BlackBerry','BB10','Bada','webOS','Tizen'],
            engines 		= {'AppleWebKit':'Webkit','Gecko':'Gecko','Trident':'Trident','MSIE':'Trident','Presto':'Presto','BlackBerry':'Mango','wOSBrowser':'Webkit'}, 
            browsers 		= {'Chrome':'Chrome','CriOS':'Chrome','Firefox':'Firefox','BlackBerry':'BB Browser', 'BB10':'BB Browser', 'Safari':'Safari','Opera':'Opera','MSIE':'IE','Dolfin':'Dolfin','Silk':'Amazon Silk'},
            version 		= {'full': '?', 'major': '?', 'minor': '?', 'build': '?', 'revision': '?'},
            vRegExp 		= {
                'default': '.*(default)\\/([0-9\\.]*)\\s?.*',
                'ie': '.*(MSIE)\\s([0-9\\.]*)\\;.*',
                'opera': '.*(Version)\\/([0-9\\.]*)\\s?.*',
                'blackberry': '.*(BlackBerry[a-zA-Z0-9]*)\\/([0-9\\.]*)\\s.*',
                'bbbrowser': '.*(Version)\\/([0-9\\.]*)\\s?.*',
                'safari': '.*(Version)\\/([0-9\\.]*)\\s?.*'
            },
            osVRegExp 		= {
            	'android': '.*Android\\s([\\d\\.]*)\\;.*',
            	'ios': '.*CPU\\s(?:iPhone\\s)?OS\\s([\\d\\_]*)\\s.*',
            	'wpos': '.*Windows\\sPhone\\s(?:OS\\s)?([\\d\\.]*)\\;.*',
            	'bbos': '.*Version\\/([0-9\\.]*)\\s?.*'
            }

        // Set Default values
        for (var i=0, l=checks.length; i<l; i++)    { var k = checks[i]; app[k] = 'unknown' + ucfirst(k); }
            
        // Look for platform, browser & engines
        for (var i=0, l=platforms.length; i<l; i++) { if ( ua.indexOf(platforms[i]) !== -1 ){ app.platform 	= platforms[i].toLowerCase().replace(/\s/,''); break; } }
        for (var name in browsers) 					{ if ( ua.indexOf(name) !== -1 )		{ app.browser 	= browsers[name].toLowerCase().replace(/\s/,''); break; } }
        for (var name in engines) 					{ if ( ua.indexOf(name) !== -1 )		{ app.engine 	= engines[name].toLowerCase(); break; } }

		// Specific cases
		// Android stock browser UA may include "Mobile Safari" while it's not
		if 		( app.platform === 'android' && app.browser === 'safari' )	{ app.browser = "unknownBrowser"; }
		// Blackberry 10 no longer contains the blackberry string 
		else if ( app.platform === 'bb10' )									{ app.platform = "blackberry"; }
		// 
		else if ( app.platform === 'tizen' )	{ app.browser = "tizenBrowser"; }
		
		// TODO: is app.browser === 'firefox' && app.os !== 'android ==> app.os = 'firefoxos' 
		
        // Look for os
        //if      ( ['iphone','ipad','ipod'].inArray(app.platform) ) 		{ app.os = 'ios'; }
        if      ( /ip(hone|ad|od)/.test(app.platform) ) 				{ app.os = 'ios'; }
        else if ( app.platform === 'android' ) 							{ app.os = 'android'; }
        else if ( app.platform === 'windows phone' ) 					{ app.os = 'wpos'; }
        else if ( app.platform === 'blackberry' ) 						{ app.os = 'bbos'; }
        else if ( app.platform === 'windows' ) 							{ app.os = 'windows'; }
        else if ( app.platform === 'macosxx' ) 							{ app.os = 'macos'; }
        //else if ( app.plafform !== 'unknownPlatform' ) 					{ app.os = app.platform.toLowerCase(); }

        // Try to get the browser version data
        if ( app.browser !== 'unknownBrowser' )
        {
            var pattern = vRegExp[app.browser] || vRegExp['default'].replace('default', ucfirst(app.browser)), // Get regex pattern to use 
                p 		= ua.replace(new RegExp(pattern, 'gi'), '$2').split('.'); 								// Split on '.'

                p.unshift(p.join('.') || '?'); 		// Insert the full version as the 1st element
                pad(p, 5, '?'); 					// Force the parts & default version arrays to have same length
            
            // Assoc default version array keys to found values
            app.browserVersion = {'full': p[0], 'major':p[1], 'minor':p[2], 'build':p[3], 'revision':p[4]};
        }
        else { app.browserVersion = version; }
        
        // Look for os version
        if ( app.os !== 'unknownOs' && osVRegExp[app.os] )
        {
        	var pattern = ua.replace(new RegExp(osVRegExp[app.os], 'i'), '$1').replace(/_/g,'.'),
        		p 		= ( pattern.indexOf(' ') === -1 ? pattern : '?.?.?.?').split('.');
        		
        		p.unshift(p.join('.') || '?'); 		// Insert the full version as the 1st element
        		pad(p, 5, '?'); 					// Force the parts & default version arrays to have same length
        		
            // Assoc default version array keys to found values
        	app.osVersion = {'full': p[0], 'major':p[1], 'minor':p[2], 'build':p[3], 'revision':p[4]};
        }
        else { app.osVersion = version; }

        // Get viewport dimensions        	
        app.pixelRatio 		= window.devicePixelRatio;
        app.vw 				= Math.round(window.outerWidth/app.pixelRatio);
        app.vh 				= Math.round(window.outerHeight/app.pixelRatio);
        
        // Get or test some usefull properties 
        app.device 			= { 'screen':{w:window.screen.width, h:window.screen.height} };
        app.isSimulator 	= ua.indexOf('XDeviceEmulator') > -1;
        app.isStandalone 	= typeof navigator.standalone !== 'undefined' && navigator.standalone;
        app.isRetina 		= (window.devicePixelRatio && window.devicePixelRatio > 1) || false;
        app.isTv 			= false;
        app.isMobile 		= ua.indexOf('Mobile') !== -1 || ( app.os === 'android' && !app.isTV ); // Touch only
        app.isDesktop 		= !app.isMobile && !app.isTv; 											// Mouse (+touch)
        
//alert(window.innerWidth + 'x' + window.innerHeight)
//alert(window.outerWidth + 'x' + window.outerHeight)
//alert(window.devicePixelRatio)

//alert('isDesktop:' + app.isDesktop);
        
		var attrs 	= {},
			classes = '',
			props 	= ['platform', 'os', 'engine', 'browser', 'pixelRatio', 'vw','vh'],
			tests 	= ['simulator','standalone','retina','mobile','desktop'],
			vtests 	= {'os':'os', 'browser':'b'};
		
		// Add retrieved data as classnames & data attributes on the html tag
		for (var i=0, len=props.length; i<len; i++){ var k = props[i]; classes += app[k]  + ' '; attrs['data-' + k] = app[k]; }
		for (var i=0, len=tests.length; i<len; i++){ var k = tests[i]; classes += ( app['is' + ucfirst(k)] ? ' ' : ' no-' ) + k; }
		for ( var k in vtests)
		{
			var alias 	= vtests[k],
				prop 	= k + 'Version'; 
			attrs['data-' + prop] = app[prop].full;
			for (var p in version){ attrs['data-' + alias + 'v' + ucfirst(p)] = app[prop][p]; }
		}
        
        $('html').addClass(classes).removeClass('no-js').attr(attrs);

        return this;
    },
    
    handleOrientation: function()
    {    	
    	var self 			= this,
    		$html 			= $('html'),
    		updateClasses 	= function(){ $html.removeClass('landscape portrait').addClass(app.orientation); }, 
    		getOrient 		= function()
	        {
	            if ( typeof window.orientation == 'undefined' && typeof window.onmozorientation == 'undefined' ){ return this; }
	            
	            app.orientation = Math.abs(window.orientation || window.onmozorientation) === 90 ? 'landscape' : 'portrait';
	        };
        
        // Get the current orientation
        getOrient();
        
        // Store it as the initial orientation
        this.initialOrientation = this.orientation;
        
        // And when it changes, get the new one
        $(window).on('orientationchange', function(e)
        {
        	getOrient(); 
        	updateClasses();
        })
        
        return this;
    },
    
	notifier: 
	{
		init: function()
		{
			$(document)
				.on('click', '.notification', function(e)
				{
					var $this = $(this),
						$ctnr = $this.closest('.notificationsBlock');
					
					$this.remove();
					
					if ( $ctnr.length && !$ctnr.find('.notification').length ){ $ctnr.remove(); }
				})
			
			return this;
		},
		
		add: function(msg)
		{
			var args 	= arguments,
				msg 	= args[0] || 'An error occured',
				o 		= $.extend(
				{
					type: 'info' // 'success | error | warning | info'
				}, args[1] || {}),
				$notifier = $('body').children('.notificationsBlock');
			
			if ( !$notifier.length ){ $notifier = $('<div class="notificationsBlock">').appendTo('body'); }
			
			$notifier.append('<p class="notification ' + o.type + '">' + msg + '</p>');
		}
	}
};

var ui =
{
	init: function()
	{
		var self 		= this,
			detailSel 	= '#myAccountNavBlock',
			mainNavSel 	= '#mainNavBlock';
		
		$('#mainNavTitle')
			.on('click',function(e)
		{
			e.preventDefault(); e.stopPropagation(); 
			
			var $nav = $(this).closest('nav'); 
			
			$nav.toggleClass('active');
			
			self.setBlockToFullscreen($nav.find('> ul'));
		});
       
		$('#accountActions')
       		.on('click', function()
       		{
       			var $this = $(this); 
       			$this.toggleClass('active');
       			self.setBlockToFullScreen($this.find('> .groups'));
       		});
		
		//return this.handleIos().handleOrientation().notifications();
		return this;
	},
	
	setBlockToFullscreen: function($item)
	{
		if ( $('body').width() < 980 )
		{
			var bodyH 		= $('#body').outerHeight() || '100%',
				viewportH 	= window.innerHeight || bodyH,
				headerH 	= $('#header').outerHeight();
				ulPadding 	= (parseInt($item.css('padding-top')) || 0) + (parseInt($item.css('padding-bottom')) || 0),
				newH 		= (bodyH + headerH) < viewportH ? viewportH - headerH + ulPadding : bodyH + ulPadding + 10;
			
			$item.css({'height': newH, 'min-height':newH});
		}
		
		return this;
	}
}

app.onLoginWith = function(serviceName)
{
	switch(serviceName)
	{
		case 'facebook':
			// TODO: Update user profile with facebook data
			
			break;
		case 'twitter':
		case 'google':
		default: break;
	}
}

$(document).ready(function(){ app.init(); });
