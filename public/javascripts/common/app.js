var ua 	= navigator.userAgent || null;
var app = 
{
	loadingBlock : '<div class="loadingMsgBlock"><span class="label">' + 'Loading...' + '</span></div>',
	isTabbee: ua.indexOf('tabbee') > -1,
	isWidget: ua.indexOf('tabbee') > -1,
	isIphone: ua.indexOf('iPhone') > -1 || ua.indexOf('iPod') > -1 || Tools.getURLParamValue(window.location.href,'isIphone') == true,
	isIpad: ( ua.indexOf('iPad') > -1 || Tools.getURLParamValue(window.location.href,'isIpad') == true ),
	isAndroid: ua.indexOf('Android') > -1,
	isMobile: window.screen.width <= 600,
	//isBackBerry: ua.indexOf('BlackBerry') > -1,
	support: {touch: (typeof Touch == "object")},
	fullscreen: window.navigator.standalone || false,
	orientation: 'landscape',
	
	init: function()
	{
		var self = this;
		
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
	}
};

var ui =
{
	init: function()
	{
		var self 		= this,
			detailSel 	= '#myAccountNavBlock',
			mainNavSel 	= '#mainNavBlock';
		
		//$('#mainNavTitle').click(function(e){ e.preventDefault(); e.stopPropagation(); $(mainNavSel).toggleClass('expanded'); });
		$('#mainNavTitle').click(function(e)
		{
			e.preventDefault(); e.stopPropagation(); 
			
			$(this).closest('nav').toggleClass('active');
		});
		
		$('#accountActions')
        	.bind('click', function(e)
	        {
	            e.stopPropagation();
	            e.preventDefault();
	            
	            var $dtl = $(detailSel); 			// Shortcut for my account nav block detail 
	            
	            // If the detail is collapsed
	            if ( !$dtl.hasClass('active') )
	            {
	            	// Make any click outside of the block to collapse it
					$('body').one('click', function(e)
					{
						var $t = $(e.target); 		// Shortcut for target 
						
						if ( !$t.closest(detailSel).length ){ $dtl.removeClass('active'); }
					});
	            }
	           
	           // Otherwise, just expand it 
				$dtl.addClass('active');
	        });
       
		$('#accountActions')
			//.bind('click', function(){ $(this).toggleClass('active'); });
       		.on('click', function(){ $(this).toggleClass('active'); });
        
        // Fix wrong flexbox layouting in Firefox when browser window is not fullscreen
        if ( $('html').hasClass('gecko') ){ self.fixFirefoxFlexbox(); }
		
		return this.handleIos().handleOrientation();
	},
	
	
	// Fix wrong flexbox layouting in Firefox when browser window does not use fullscreen
	fixFirefoxFlexbox: function()
	{
		var $html = $('html');
		
		if ( !$html.hasClass('admin') && !$html.hasClass('api') ){ return this; } 
		
        $(window).bind('resize load',function(e)
        {
        	var $window = $(window),
        		windowW = $window.width(),
        		maxH 	= $window.height() - $('#header').height() - $('#asideFooter').height();
        		
			if ( windowW < 980 ){ return; }
        	
        	$('#asideContent').css({'height':maxH, 'max-height':maxH});
        	$('#mainColContent').css({'height':maxH, 'max-height':maxH, 'overflow-y':'auto'});
        });
        
        return this;
	},
	
	handleOrientation: function()
	{
		if ( typeof window.orientation == 'undefined' && typeof window.onmozorientation == 'undefined' ){ return this; }
		
		var or 			= window.orientation || window.onmozorientation,
			orUrlParam 	= Tools.getURLParamValue(window.location.href, 'orientation') || '';
		
		app.orientation = Math.abs(or) === 90 || orUrlParam === 'landscape' ? 'landscape' : 'portrait';
		
		$('body').removeClass('landscape portrait').addClass(app.orientation);
		
		window.onorientationchange = function()
		{
			var or = window.orientation || window.onmozorientation;
			
			app.orientation = Math.abs(or) === 90 ? 'landscape' : 'portrait';
			$('body').removeClass('landscape portrait').addClass(app.orientation);
			
			window.scrollTo(0,0);

		};
		
		return this;
	},
	
	handleIos: function()
	{
		// Hide the url bar
		if ( app.isIphone || app.isIpad )
		{
			//window.scrollTo(0,0);
			window.addEventListener('load',function() { setTimeout(function(){ window.scrollTo(0, 1); }, 0); }); }
		
		return this;
	}
}

$(document).ready(function(){ app.init(); });
