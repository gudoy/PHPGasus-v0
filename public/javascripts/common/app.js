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
		
		return this.handleIos().handleOrientation().notifications();
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
	},
	
	
	notifications: function()
	{
		var ctx = '#errorsBlock'; 
		
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
}

$(document).ready(function(){ app.init(); });
