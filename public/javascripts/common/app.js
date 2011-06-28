/** 
 * @projectDescription	This files defines the global app object
 *
 * @author Guyllaume Doyer guyllaume@clicmobile.com
 * @version 	0.1 
 */
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
	orientation: 'landscape',
	
	init: function()
	{
		var self = this;
		
		ui.init();
		
		return this;
	}	
};

var ui =
{
	init: function()
	{
		var detailSel 	= '#myAccountNavBlock',
			mainNavSel 	= '#mainNavBlock';
		
		$('#mainNavTitle').click(function(e){ e.preventDefault(); e.stopPropagation(); $(mainNavSel).toggleClass('expanded'); });
		
        $('#goToMyAccountDetailsLink').bind('click', function(e)
        {
            e.stopPropagation();
            e.preventDefault();
            
            var $dtl = $(detailSel); 			// Shortcut for my account nav block detail 
            
            // If the detail is collapsed
            if ( !$dtl.hasClass('expanded') )
            {
            	// Make any click outside of the block to collapse it
				$('body').one('click', function(e)
				{
					var $t = $(e.target); 		// Shortcut for target 
					
					if ( !$t.closest(detailSel).length ){ $dtl.removeClass('expanded'); }
				});
            }
           
           // Otherwise, just expand it 
			$dtl.addClass('expanded');
        });
		
		return this.langChooser().handleIphone().handleOrientation();
	},
	
	langChooser: function()
	{
		if ( !app.isIphone && !app.isAndroid ){ return this; }
		
		$('#languagesBlock').click(function(e)
		{
			e.preventDefault();
			
			$(this)
				.dialog(
				{
					width: '80%',
					height: 'auto',
					minHeight: 200,
					modal: true,
					close: function() { $(this).dialog('destroy'); },
					open: function()
					{
						$('a', this).click(function(e)
						{
							e.preventDefault();
							
							var t = $(this).attr('href') || '';
							
							if ( t !== '' ){ window.location.href = t; }  
						});
					}
				})
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
	
	handleIphone: function()
	{
		// Hide the url bar
		if ( app.isIphone || app.isIpad )
		{
			window.scrollTo(0,0);
		}
		
		return this;
	}
}

$(document).ready(function(){ app.init(); });
