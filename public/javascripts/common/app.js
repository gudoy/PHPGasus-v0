/** 
 * @projectDescription	This files defines the global app object of our application and a tools object for containing some misc helpers
 *
 * @author Guyllaume Doyer guyllaume@clicmobile.com
 * @version 	0.1 
 */
var ua = navigator.userAgent || null;
var app = 
{
	loadingBlock : '<div class="loadingMsgBlock"><span class="label">' + 'Loading...' + '</span></div>',
	isTabbee: ua.indexOf('tabbee') > -1,
	isWidget: ua.indexOf('tabbee') > -1,
	isIphone: ua.indexOf('iPhone') > -1 || ua.indexOf('iPod') > -1 || Tools.getURLParamValue(window.location.href,'isIphone') == true,
	isIpad: ( ua.indexOf('iPad') > -1 || Tools.getURLParamValue(window.location.href,'isIpad') == true ),
	isAndroid: ua.indexOf('Android') > -1,
	support: {touch: (typeof Touch == "object")},
	orientation: 'landscape',
	
	init: function()
	{
		var self = this;
		
		ui.init();
		
		$('input:first', '#frmLogin').focus();
		
		return this;
	},
	
	/**
	 * This function handles URL trying to find out/gess the page name in order to launch proper actions
	 * 
	 * @author Guyllaume Doyer guyllaume@clicmobile.com
	 * @param {String} URL of the content to get
	 * @return {Object} Returns the current object (for chaining)	
	 */
	handleURL: function ()
	{
//Tools.log('handleURL');
		
		var self 		= this,
			args		= arguments || null,
			url			= args[0] || location.pathname || '';
			tmp			= url
							.replace(new RegExp('http://' + window.location.host, 'i'), '/')
							.replace(/\?.*/, '')
							.split('/');
																								// Parse the URL
			ctrlr 	= tmp[1] || 'Home',
			mthd	= tmp[2] /*&& isNaN(parseInt(tmp[2]))*/ 
						? tmp[2] : (ctrlr.toLowerCase() === 'home' ? 'home' 
						: 'index'),																// Get method name (or set it to index)
			params		= tmp.slice(3,tmp.length).join('/') || [];										// Get params (or set them to empty)
			pageName	= ctrlr.toLowerCase() + Tools.ucwords( !isNaN(parseInt(mthd)) ? '' : mthd ),	// Construct the pageName
			urlParams 	=
			{
				controller: 	ctrlr,
				method: 		mthd,
				methodParams: 	params,
				pageName: 		pageName
			};
			
			this.page = urlParams;
		
		return urlParams;
	},
	
	handleHash: function()
	{		
		var args 	= arguments || {},													// Shortcut for arguments
			hash 	= args[0] || '',													// Get hash (or set to empty)
			tmp		= hash.replace(/#/,'').split('/'),									// Split it
			ctrlr	= tmp[0] || 'home',													// Get the controller name (or set it to home)
			mthd	= tmp[1] || (ctrlr === 'home' ? 'home' : 'index'),					// Get method name (or set it to index)
			params	= tmp.slice((!isNaN(tmp[1]) ? 1 : 2),tmp.length).join('/') || [],	// Get params (or set them to empty)
			uri		= this.dBaseURL + ctrlr + '/' + mthd + '/' + params; 				// Finally buils the uri to call
				
		return this.loadContent(uri);
	},
	
	
	/**
	 * This function makes an ajax request and updates the main frame using the response
	 * 
	 * @author Guyllaume Doyer guyllaume@clicmobile.com
	 * @param {String} URL of the content to get
	 * @return {Object} Returns the current object (for chaining)	
	 */
	loadContent: function(url)
	{
		var self 	= this,
			args	= arguments,											// Shortcut for arguments
			dO		= {
						options: null,
						tplSelf: true,
						//urlParams: 	this.handleURL(url),
						isForm: false,
						formId: '',
						addLoadingMsg: true,
						success: null
					},														// default options
			o 		= args[1] !== null ? $.extend(dO, args[1]) : dO, 		// set final options,
			dest 	= o.dest && o.dest !== null 							// Set content destination
						? $(o.dest)											// use options dest param if passed
						: ( o.tplSelf ? $('#body > .pageContent') : $('body') ); 	// otherwise, use defined destination (body or bodycontent)

		// Launch the ajax request
		$.ajax(
		{
			url: url + ( url.indexOf('?') > -1 ? '' : '?' ) +  ( o.isForm ? '&tplSelf=' + o.tplSelf : ''),
			data: o.isForm
					? $(o.formId).serialize()
					: 'tplSelf=' + o.tplSelf,
			type: (!o.isForm || (o.isForm && $(o.formId).attr('method').toLowerCase() === 'get') )
					? 'GET' 
					: 'POST',
			dataType: 'html',
			beforeSend: function()
			{
				if (o.addLoadingMsg) { dest.addClass('loading').not(':has(.loadingMsgBlock)').prepend(self.loadingBlock); }
			},
			error: function(xhr, textStatus, errorThrown)
			{
				//self.handleErrors(xhr, textStatus, errorThrown);
			},
			success: function(response, textStatus)
			{
				var jResp = $(response);
				
				// DOM insertion
				o.dest && o.dest !== null && !$(o.dest).hasClass('ui-dialog-content') 	// If the destination has been passed in the function arguments
					? dest.replaceWith($(o.dest, jResp))								// replace the whole body / or body content by the whole response
					: dest.html(response);												// otherwise, replace the destination by its content in the response
				
				/*
				// Try to find out pagename
				var pageBlockId 	= jResp.hasClass('commonMainContentBlock') 
										? jResp.attr('id')
										: ( $('#bodyContent > .commonMainContentBlock').attr('id') || ''),
					loadedPageName 	= Tools.lcfirst(pageBlockId.replace(/PageContent/, ''));
				
				// Update the body id (usefull for css specifics)
				$('body').attr('id', 'page' + Tools.ucwords(loadedPageName));
				*/

				// Update the hash
				if ( !self.isWidget /*&& window.location && window.location.hash*/ )
				{
					/*
					self.handleURL(url);
					
					window.location.hash = o.urlParams.controller +
											(o.urlParams.method != '' ? '/' + o.urlParams.method : '') +
											(o.urlParams.methodParams != '' ? '/' + o.urlParams.methodParams : '');
					*/
				}
				
				/*
				// If callback is specified, launch it, otherwise, try to init the loaded page
				if 		( o.success !== null ) 				{ o.success.call(); }
				else if ( page && page[loadedPageName] ) 	{ page[loadedPageName].init(); }
				*/
				
				dest.removeClass('loading');
			}
		});
		
		return this;
	},
	
	intercept: function(jqueryCollection)
	{
		var self 	= this,
			items 	= $(jqueryCollection),									// Shortcut for the items whe are working on
			args 	= arguments || null,									// Shortcut for arguments
			dO		= {
						action:'default',
						open:function(){},
						launch:null,
						dest:null
					  },													// Default options
			o 		= args[1] !== null ? $.extend(dO, args[1]) : dO; 		// Set final options (merge default options and passed options arrays),
		
		items.each(function()
		{
			$(this)
//.css('border','1px solid red')
			.click(function(e)
			{
				e.preventDefault();
				
				if (o.action == 'default')
				{
					self.loadContent($(this).attr('href'), {dest:o.dest});
					
					// Launch success callback function
					o.open.call(null);
				}
				else if (o.action == 'silentClick')
				{
					$.ajax({ url: $(this).attr('href'), type: 'GET' });

					// Launch success callback function					
					o.open.call(null);
				}
				// Event delegation (for list of links)
				else if (o.action == 'clickDelegate')
				{
					var t 				= $(e.target),											// Shortcut for event target
						itemCheckClass 	= o.itemCheckClass || 'itemLink',						// Class to check the target on
						a 				= (t.hasClass(o.itemCheckClass) ) ? t : t.closest('a');	// Link to trigger click on

					// If the launch action has not been defined, use default one which is to update the default frame
					if ( o.launch === null )
					{
						// If the target clicked is an issuelink, launch the frame update
						if (a.length > 0) { self.loadContent(a.attr('href'), {dest:o.dest}); }						
					}
					// Otherwise, execute the launch function passing the event target and closest anchor (as a jqueryElement) as parameters
					else { o.launch.call(null, t, a); }
					
					// onOpen success callback function passing closest anchor (as a jqueryElement) as parameters
					o.open.call(null, a);
				}
				
				// Open dialog
				else if (o.action == 'openDialog' || o.action == 'openFormDialog')
				{	
					var b = $(o.id) || null // dialog content block
					
					// If the dialog already exists, do not continue and just open the dialog
					if ( b !== null && b.hasClass('ui-dialog-content') ) { b.dialog('open'); return this; }
					
					$.ajax(
					{
						url: $(this).attr('href'),
						type: 'GET',
						data: 'tplSelf=true&output=xhtml',
						dataType:'html',
						success: function(response)
						{
							var //pageBlockId 	= $('#bodyContent > .commonMainContentBlock').attr('id') || '',
								pageBlockId 	= $(response).is('.commonMainContentBlock')
													? $(response).attr('id')
													: ($('.commonMainContentBlock', $(response)).attr('id') || ''),
								d				= $(pageBlockId), 												// Shortcut for dialog window content
								loadedPageName 	= Tools.lcfirst(pageBlockId.replace(/PageContent/, ''));
							
							// If the dialog has already been created, just return opening it
							if ( d.length > 0 ) { d.dialog('open'); return this; }
							
							$(response).
								dialog(
								{
									width:	600,
									height: 'auto',
									//position: self.isTabbee ? 'center' : [(800/2-600/2), 100],
									position: 'center'
								});
								
							// For form dialog windows, handle the proper form
							var dest = '#' + $(o.formId).closest('.commonBlock').attr('id');

							if ( o.action == 'openFormDialog' ) { self.handleForm(o.formId, {dest:dest}); }
								
							// onOpen success callback
							o.open.call(null);
								
							// success callback
							if ( typeof page != 'undefined' && page[loadedPageName] ) { page[loadedPageName].init(); }
						}
					});	
				}
			})
		});
		
		return this;
	}
	
};

var ui =
{
	init: function()
	{		
		$('#closeBtn')
			.click(function() { window.close(); });
		
		return this.langChooser().handleIphone().handleOrientation();
	},
	
	langChooser: function()
	{
		if ( !app.isIphone && !app.isAndroid ){ return this; }
		
		$('#languagesBlock').click(function(e)
		{
			e.preventDefault();
			
			$(this)
				//.dialog('open')
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
