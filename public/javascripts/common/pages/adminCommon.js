var admin =
{
	resourceName: $('#resourceName').attr('class') || '',
	resourceSingular: $('#resourceName').attr('data-singular') || '',
	
	relResTimeout: null,
	relResHideTimeout: null,
	
	init: function()
	{
//Tools.log('isMobile: ' + app.isMobile);
		
		// We do not need selectable rows on mobile
		/*
		if ( !app.isMobile )
		{
		    Tools.loadJS([
	            {url:'/public/javascripts/common/libs/jquery-ui-1.8.9.custom.min.js'},
	            {url:'/public/javascripts/common/libs/jquery-ui-timepicker-addon.js'},
	        ], function()
	        {
	        });
		}*/
		
		//Tools.loadCSS('/public/stylesheets/default/jquery-ui-1.8.9.custom.css');
        
	    adminSearch.init();
	    
	    this
	    	.menu()
	    	.handleMultiLangFields();
	    
		return this;
	},
	
	menu: function()
	{
		//app.intercept('#adminMenuBlock, #topPaginationBlock, #bottomPaginationBlock', {action:'clickDelegate', dest:'#mainCol'});
		
		var $menu = $('#adminMainNav');
		
		$menu.bind('click',function(e)
		{
			e.stopPropagation();
			
		    var $this     = $(this),
		        t         = e.target,
		        $t        = $(t),
		        $LIlv1    = $t.closest('li.item-lv1', $this);
		      
            if ( !$LIlv1.hasClass('expanded') )
            {	
				$('body').one('click', function(e)
				{
					e.preventDefault();
					
					if ( !$(e.target).closest('#adminMainNav').length ){ $LIlv1.removeClass('expanded'); }
				});            	
            }
		        
            $LIlv1
                .toggleClass('expanded')
                .siblings().removeClass('expanded');
		}); 
		
		return this;
	},
	
	subResources: function()
	{
		return this;
	},
	
	relatedResource: function(jqObj, e)
	{
		var self 		= this, 
			objBubble 	= jqObj.siblings('.adminRelResBubble');		
		
		if ( objBubble.length > 0 ) { return objBubble.removeClass('ninja'); }

		// Launch ajax request
		$.ajax(
		{
			url: jqObj.attr('href'),
			data: 'tplSelf=1&viewType=bubble',
			dataType: 'html',
			type: 'GET',
			success: function(response)
			{
				var r = response || {};
				
				$(r)
					.addClass('bubble adminBubble adminRelResBubble')
					//.css({left:jqObj.width()+20})
					.hover(function(e){ clearTimeout(self.relResHideTimeout); $(this).removeClass('ninja'); }, function(e)
					{
						$(this).addClass('ninja');
					})
					.appendTo(jqObj.parent().css('position','relative'));
			}
		});
		
		return this;
	},
	
	duplicate: function(jqObj)
	{	
		var self = this;
		
		// Ask for confirmation
		if ( !confirm('Duplicate resource?') ) { return; }
		
		// Launch ajax request
		$.ajax(
		{
			url: $(jqObj).attr('href'),
			data: 'confirm=1&output=json',
			dataType: 'json',
			type: 'GET',
			success: function(response)
			{
				var r 			= response || {}, 					// Shortcut for the response
					success 	= r.success || false,				// Did the request did what it was expected to?
					createdId 	= r[self.resourceName].id || '', 	// Get the id of the created item
					//cloneId 	= null,
					jTR 		= $(jqObj).closest('tr'),
					cloneId 	= adminIndex.getResourceId(jTR) || '';
				
				// If the request did not succeed, we do not continue
				if ( !success ){ return; }
				
				// Get the parent TR of the clicked element
				jTR
					.clone(true)
					//.appendTo('table.adminTable tbody').toggleClass('odd').toggleClass('even').attr('id','row' + createdId)
					//.insertBefore('table.adminTable tbody tr:last').toggleClass('odd').toggleClass('even').attr('id','row' + createdId)
					.insertAfter('table.adminTable tbody tr.dataRow:last').toggleClass('odd').toggleClass('even').attr('id','row' + createdId)
					.find('td.colSelectResources :checkbox').val(createdId).attr('checked','false').end()
					.find('td.actionsCol a')
						//.attr('href', function(){ return $(this).attr('href').replace(new RegExp('\/'+ cloneId + '\\?'),'/' + createdId + '?'); }).end()
						.attr('href', function(i,value){ return value.replace(new RegExp('\/'+ cloneId + '\\?'),'/' + createdId + '?'); }).end()
					.find('td.idCol .value')
						//.attr('id', function(i,value){ return value.replace(new RegExp(cloneId), createdId); }).end()
						.attr('id', function(i,value){ return value.replace(new RegExp(cloneId), createdId); })
						//.text(createdId).siblings('.exactValue').text(createdId).end()
						.text(createdId).attr('data-exactvalue','createdId')
					.find('td.dataCol .value')
						//.attr('id', function(){ return $(this).attr('id').replace(new RegExp(cloneId), createdId); }).end()
						.attr('id', function(i,value){ return value.replace(new RegExp(cloneId), createdId); }).end()
					.find('> td').effect('highlight', {}, 5000)
						.find('.fullAdminPath')
							.text(function(i,value){ Tools.log('createdId'); return value.replace(new RegExp('\/'+ cloneId + '([\/|\?.*|$])?'),'/' + createdId + '$1'); })
					;
			}
		});
		
		return this;
	},
	
	del: function(jqObj)
	{
		var self 		= this
			multiple 	= jqObj.length >= 1,							// Is there several objects to delete
			url 		= !multiple ? $(jqObj).attr('href') : null; // If not use the object href as url otherwise we will set it later
			
		// When handling multiple resources, we need to get all theirs id to be able to build the proper request URL 
		if ( multiple )
		{
			// Loop over the objects to get their ids
			var ids = [];
			jqObj.each(function(){ ids.push(adminIndex.getResourceId($(this))); });
			
			// Get the last data row and its id
			var tmp 	= $(adminIndex.context).find('tr.dataRow:last'),
				tmpId 	= adminIndex.getResourceId(tmp);
				
			// Then set the proper url for the deletion of all the objects
			url = tmp.find('td.actionsCol a.deleteLink').attr('href').replace(new RegExp('\/' + tmpId + '\\?'), '/' + ids.join(',') + '?');
		}
		
		// Ask for confirmation
		if ( !confirm('Delete resource(s) ' + ((ids || []).join(',') || '') +  '?') ) { return; }
		
		// Launch ajax request
		$.ajax(
		{
			url: url,
			data: 'confirm=1&output=json',
			dataType: 'json',
			type: 'GET',
			success: function(response)
			{
				var r 		= response || {},		// Shortcut for the response
					success = r.success || false; 	// Did the request did what it was expected to?
				
				// If the request did not succeed, we do not continue
				if ( !success )
				{
					//var errs = 
					
					$('table.adminTable')
						.parents('.adminListBlock')
						.insertBefore('<div class="notificationsBlock errorsBlock"></div>')
					
					return;
				}
				
				// Delete the row
				$(jqObj)
					.closest('tr')
					.animate({ opacity:0.2}, 1500, 'swing', function(){ $(this).remove(); } );
			}
		});
		
		return this;
	},
	
	edit: function(jqObj)
	{
        var self = this;
	    
	    if ( !jqObj.length )           { return this; }
	    else if ( jqObj.length === 1 )
	    {
	        jqObj.find('td.actionsCol a.editLink').click();
	    }
	    else
	    {
	        alert('Sorry, for the moment, edit is only available for 1 item at a time');
	    }
	    
        return this;  
	},
	
	handleForeigKeyFields: function()
	{
		var self = this;
		
		$('a.changeValBtn', 'form').click(function(e)
		{
			e.preventDefault();
			
			var $a 				= $(this),											// Store a reference to the clicked anchor
				curVal 			= $a.find('> .fieldCurrentVal:first').text(), 		// Get the current value of the fied
				relResource 	= $a.find('> .relResource:first').text(),			// Get the related table name
				fName 			= $a.find('> .formFieldName:first').text(),			// Get the form field name
				relField 		= $a.find('> .relField:first').text() || null,		// Get the relation field
				relDisplayAs 	= $a.find('> .relDisplayAs:first').text() || null,	// Get the relation field 
				relGetFields 	= $a.find('> .relGetFields:first').text() || null;	// Get the related fields to get
			
			$.ajax(
			{
				url: $(this).attr('href'),
				data:'limit=-1',
				type: "GET",
				dataType: 'json',
				beforeSend: function()
				{
				    $a
				        .siblings('.relDisplayVal')
				        .andSelf()
				        .addClass('hidden')
				        .parent()
				            .append($('<span />', {'class':'loading','text':'loading...'}));
				},
				success: function(response)
				{
					var r = response;									// Shortcut for response
					
					$('#' + fName).remove();
					$('<select />').attr({id:fName, name:fName}).appendTo( $a.closest('.relField').hide().parent() );					
					
					$.each(r[relResource], function(i,item)
					{

						var relDisplayVal = '';
						
						if ( relGetFields.indexOf('-') === -1){ relDisplayVal += item[Tools.trim(relGetFields)]; }
						else
						{
							$.each(relGetFields.split('-') || {}, function(j,strPart) { relDisplayVal += ' ' + item[Tools.trim(strPart) || strPart]; });	
						}

						var t 	= item.id + ' - ' + (relDisplayVal !== '' ? relDisplayVal : '[untitled]'),
							opt = $('<option' + ( curVal == item[relField] ? ' selected="selected"' : '' ) + '>')
									.attr('value',item.id)
									.text(t);
						
						$('#' + fName).append(opt);
					});
				}
			});
		});
		
		$('a.addRelatedItemsLink', 'form')
			.live('click', function(e)
		{
			e.preventDefault();
			
			var $this 			= $(this),										// jQuery reference to the clicked button
				$select 		= $this.siblings('select'), 					// jQuery reference of the matching select input
				dialog 			= $('body > .ui-dialog.adminCreateDialog'), 	// Try to find the matching dialog
				urlDialog 		= $this.attr('href') || '', 					// Get the url of the dialog content (clicked button href)
				relResource 	= $this.attr('data-relResource') || '', 		// Get the related resource name
				relGetFields 	= $this.attr('data-relGetFields') || ''; 		// Get the related resource getFields (~default name field)
			
			// If the dialog already exists, just open it
			if ( dialog.length ) { return dialog.dialog('open'); }
			
			// Do not continue if the url of the dialog content if not found
			if ( !urlDialog ) { return self; }
			
			// Get the creation form block for the related resource
			$.ajax(
			{
				url: urlDialog,
				type: 'GET',
				dataType: 'html',
				success: function(r)
				{
					// Append it to the body and make a modal dialog of it
					$(r).appendTo('body').dialog({
						width:'50%',
						minWidth:200,
						autoOpen: false,
						dialogClass: 'adminDialog adminCreateDialog',
						modal: true,
						close: function(){ $(this).dialog('destroy').remove(); },
						open: function()
						{
							var that = this;
							
							// TO DO: use event delegation?
							$(that)
								// Get the cancel button and make it close the dialog
								.find('a.cancelBtn').click(function(e){ e.preventDefault(); $(that).dialog('close'); })
								.end()
								// Get the form and intercept the submit event
								.find('form').bind('submit', function(e)
							{
								e.preventDefault();
								
								var $form 		= $(this),						// jQuery reference to the form
									urlForm 	= $form.attr('action') || ''; 	// action url of the form 
								
								$.ajax(
								{
									url: urlForm,
									type: 'POST',
									data: $form.serialize(),
									dataType: 'html',
									success: function(r)
									{		
										// Update the dialog content
										$(that).html( $(r).html() );
										
										// Build the related resource url
										var relResourceUrl = urlDialog.replace(/\?(.*)/,'') || '';
										
										// Do no continue if there's no url
										if ( !relResourceUrl ){ return self; }
										
										// Get the related resource items
										$.ajax(
										{
											url: relResourceUrl,
											type: 'GET',
											dataType: 'json',
											success: function(r)
											{	
												if ( r && r[relResource] )
												{
													// Empty the mathing select input
													$select.empty();
													
													// Loop over the related resource items and add them as options of the matching select input
													$.each(r[relResource], function(i,item)
													{										
														$opt = $('<option />').attr(
														{
															'value':item.id,
															'text':relGetFields && item[relGetFields] ? item[relGetFields] : item.id
														});
														$select.append($opt);
													});	
												}
											}
										});
									}
								});
							})
						}
					}).dialog('open');
				}
			});
		});
		
		return this;
	},
	
	handleOneToManyFields: function()
	{
        $('a.addOneToManyItemLink').click(function(e)
        {
            $(this).addClass('hidden').next('.suggestBlock').removeClass('hidden');
        });
		
		var suggestFields =
		{
			context: '.suggestBlock',
			inputSel: 'input[type=text], input[type=search]',
			
			init: function()
			{
				var self = this;
				
				$(self.inputSel, self.context)
					.each(function()
					{
						var jqOjb = $(this);
						
						jqOjb						
							.bind('keypress', function(e)
							{
								self.update(e,jqOjb);
							})
							.closest(self.context)
							.siblings('a.addLink')
								.click(function(e)
								{
									e.preventDefault();
		
									$(this).addClass('hidden');
									
									self.open(e,jqOjb);
								})
						;			
					});
				
				return this;
			},
			
			open: function(e,jqOjb)
			{
//Tools.log('open');
				
				var self 		= this;
					//suggest 	= jqOjb.siblings('.suggest');
					
				self.goNext(jqOjb);
				
				jqOjb
					.focus()
					.siblings('.suggest')
						.bind('click', function(e)
						{
							e.preventDefault();
							
							var t 				= e.target,
								jT 				= $(t),
								item 			= jT.closest('.item', $(this)).addClass('selected'),
								label 			= item.find('.label').text() || '',
								value 			= item.find('.value').text() || '',
								relPostField 	= jqOjb.siblings('input[type=hidden]:first'),
								curPostVal 		= relPostField.val() || '',
								curPostValIds 	= (curPostVal && curPostVal.split(',') ) || [],
								newPostValIds 	= value && curPostValIds.push(value) ? curPostValIds : curPostValId,
								newPostVal 		= newPostValIds.join() || curPostVal;
							
//Tools.log(value);
//Tools.log(curPostVal);
//Tools.log(curPostValIds);
//Tools.log(newPostValIds);
//Tools.log(newPostVal);
								
							jqOjb.val(label);
							relPostField.val(newPostVal);
							
							self.close(jqOjb);
						})
						.show()
					.closest('.suggestBlock')
					.show()
					;
				
				return this;
			},
			
			update: function(e,jqOjb)
			{
				//e.preventDefault();
								
				var self 		= this,
					val 		= jqOjb.val() || '',
					k 			= e.keyCode || null,
					suggest 	= jqOjb.siblings('.suggest');
									
//Tools.log(val.length);
//Tools.log(k);
								
				if ( k === 27 )
				{
					self.close(jqOjb);
				}
				else if ( k === 38 || (k === 9 && e.shiftKey) )
				{
					e.preventDefault();
					
					suggest.show();
					
					self.goPrev(jqOjb);
				}
				else if ( k === 40 || k === 9 )
				{
					e.preventDefault();
					
					suggest.show();
					
					self.goNext(jqOjb);
				}
				else if ( val.length >= 1 )
				{
					
				}
				
				return this;
			},
			
			goPrev: function(jqOjb)
			{
				var self 		= this,
					suggest 	= jqOjb.siblings('.suggest');
					items 		= suggest.find('.item'),
					current 	= items.filter('.hover');
					prev 		= !current.length || ( items.length >=2 && current.length > 0 && items.index(current) === 0 ) ? items.last() : current.prev();
					
				prev.addClass('hover').siblings().removeClass('hover');
					
				return this;
			},
			
			goNext: function(jqOjb)
			{
				var self 		= this,
					suggest 	= jqOjb.siblings('.suggest');
					items 		= suggest.find('.item'),
					current 	= items.filter('.hover');
					next 		= !current.length || ( items.length >=2 && current.length > 0 && items.index(current) === items.length-2 ) ? items.first() : current.next();
					
				next.addClass('hover').siblings().removeClass('hover');
					
				return this;
			},
			
			close: function(jqOjb)
			{
				jqOjb
					.siblings('.suggest')
					.fadeOut()
					.find('.item')
						.removeClass('hover selected')
						.end()
					.end()
				.focus();
				
				return this;
			}
		};
		
		suggestFields.init();
		
		return this;
	},
	
	handleMultiLangFields: function()
	{
		$('nav.translationsNav')
			.each(function()
			{
				var $nav = $(this);
				
				$nav.bind('click', function(e)
				{ 
					var t 		= e.target,
						$t 		= $(t),
						$lang 	= $t.closest('li.lang', $nav),
						code 	= $lang.data('code') || 'all';
					
					if ( !$nav.hasClass('expanded') ) { return $nav.addClass('expanded'); }
					
					$lang.addClass('active').siblings().removeClass('active');
					$nav.removeClass('expanded');
					
					// Set the input selector (if not 'all', append proper attribute selector)
					var slctr = ':input' + ( code === 'all' ? '' : '[lang=' + code + ']') ;
					
					$nav.siblings('.fieldsGroup').find(slctr).addClass('active').siblings().removeClass( code === 'all' ? '' : 'active' )
					;
				});
			});
		
		return this;
	},
	
	handleSlugFields: function()
	{
		$('.subtypeSlug', 'form').each(function()
		{
			var from 		= $(this).attr('data-from') || $(this).find('.from').text() || '',
				fromContext = '#' + admin.resourceSingular + Tools.ucfirst(from),
				input 		= $('input', this);
			
			$(fromContext)
				.bind('keyup blur', function()
				{
					var val 	= $(this).val() || '',
						newSlug = Tools.slugify(val);
					
					input.val(newSlug);
				});
		});
		
		return this;
	},
	
	handlePasswordFields: function()
	{
//Tools.log('handlePasswordFields');
		
		//$('button.changePassBtn', 'form')
		$('.changePassBtn', 'form')
			.click(function(e)
			{
				e.preventDefault();
				
				var input = $(this).closest('.fieldBlock').find('input[type=password]'),
					curVal = input.val() || ''; 
				
				//input.removeAttr('disabled').attr({'value':'', 'type':'text'});
				input.removeAttr('disabled').attr({'value':''});
			});
		
		return this;
	},
	
	handleDateFields: function()
	{
		// If the datepicker module is loaded
		if ( $.datetimepicker )
		{
			$('input.datetime', 'form')
				//.datepicker({
	            .datetimepicker({ 
					duration: '',  
					//dateFormat: 'yy-mm-dd',
					dateFormat: $.datepicker.W3C,
					showTime: true,  
					constrainInput: false,  
					stepMinutes: 1,  
					stepHours: 1,  
					altTimeField: '',  
					time24h: true  
			});	
		}
		
		return this;
	},
	
	handleFileFields: function()
	{
		$('.replaceFileLink', 'form').click(function(e)
		{
			e.preventDefault();
			
			$(this).closest('.fieldBlock', 'form').removeClass('editFileUrlMode').addClass('replaceFileMode');
		});
		
		$('.cancelFileActionLink', 'form').click(function(e)
		{
			e.preventDefault();
			
			var context 	= $(this).closest('.fieldBlock', 'form'),
				fileInput 	= context.find('input[type=file]'),
				oldName 	= fileInput.attr('data-oldName') || '',
				oldId 		= fileInput.attr('data-oldId') || '';
			
			context
				.removeClass('editFileUrlMode replaceFileMode')
				.find('input[type=text]').removeAttr('name').removeAttr('id');
				
			if ( oldName !== '' ){ fileInput.attr('name', oldName); }
			if ( oldId !== '' ){ fileInput.attr('id', oldId); } 
		});
		
		$('.editFileLink', 'form').click(function(e)
		{
			e.preventDefault();
			
			var context 	= $(this).closest('.fieldBlock', 'form'),
				fileInput 	= context.find('input[type=file]')
				name 		= fileInput.attr('name') || '',
				id 			= fileInput.attr('id') || '';
				
			fileInput.attr({'data-oldName': name, 'data-oldId': id}).removeAttr('name').removeAttr('id');
			
			context
				.removeClass('replaceFileMode')
				.addClass('editFileUrlMode')
				.find('input[type=text]').attr({id:id, name:name});
		});
		
		$('.deleteFileLink', 'form').click(function(e)
		{			
			e.preventDefault();
			
			var context 	= $(this).closest('.fieldBlock', 'form'),
				current 	= context.find('.currentItem'),
				fileName 	= current.find('.filename:first').text() || '',
				url 		= $(this).attr('href') || null
			
			// Ask for confirmation
			if ( !confirm('Delete file: ' + $.trim(fileName) + '?') ) { return; }
			
			// Do not continue if the url has not been found
			if ( !url ){ return }
			
			// Launch ajax request
			$.ajax(
			{
				url: url,
				dataType: 'json',
				type: 'POST',
				success: function(response)
				{
					var r = response || {};		// Shortcut for the response
						
					if ( r.success ) { Tools.log('success'); context.addClass('emptyValueMode'); current.remove(); }
				}
			});
		});
		
		return this;
	}
}

var adminIndex =
{
	context: 'table.adminTable',
	
	init: function()
	{	    
		var self 	= this;

		admin.init();
		
		$('#deleteSelectionTopBtn, #deleteSelectionBottomBtn, a.deleteAllLink').click(function(e) { e.preventDefault(); admin.del($('tbody tr.ui-selected:visible', self.context)); });
        $('a.editAllLink').click(function(e) { e.preventDefault(); admin.edit($('tbody tr.ui-selected:visible', self.context)); });
        $('a.duplicateAllLink').click(function(e) { e.preventDefault(); admin.duplicate($('tbody tr.ui-selected:visible', self.context)); });

		$('.filterLink')
		.bind('click', function(e)
		{
		    e.preventDefault();
		    
		    var destId = $(this).attr('href');
            
            //$(destId).fadeToggle( function(){} );
            //$(destId).show();
            $(destId).toggleClass('active');
		});

		// We do not need selectable rows on mobile
		if ( !app.isMobile )
		{
			$(self.context)
				.find('tbody')
				.selectable(
				{
					filter: 'tr.dataRow',
					distance: 20,
					cancel: 'div.value, input',
					selecting: function(event, ui)
					{
						$(ui.selecting)
							.find('td.colSelectResources input:checkbox')
							.attr('checked','checked')
					},
					unselecting: function()
					{
						$(ui.selecting)
							.find('td.colSelectResources input:checkbox')
							.removeAttr('checked');
					}
				});
		}

		// Loop over all the delete buttons in the table
		$(self.context)
			.parent()
			.click(function(e)
			{

				var t 			= e.target, 						// Shortcut for event target
					jt 			= $(t),								// Shortcut for event target jqueryfied
					tmpA 		= jt.closest('a'),					// Try to get closest anchor tag
					a			= tmpA.length > 0 ? tmpA : false, 	// or set if to false
					href 		= a ? a.attr('href') : false, 			// Try to get the href of the link
					//intercept 	= a, 							// Do we need to intercept click (no if no an anchor)
					jCel 		= jt.closest('td');					// jQuery Reference to the closest <td>
					
				jt.focus(); 
				
				if ( jt.hasClass('dataValue') || jt.hasClass('validity') )	{ return self.inlineEdit(jCel); }
				
				// If the target is an input, just return
				else if ( jt.is(':input') )
				{	
					if 		( jt.is('#toggleAll') ){ return self.toggleAll(jt); }
					//else if ( jt.is(':checkbox') ) { jt.closest('tr').toggleClass('ui-selected'); }
					else if ( jt.is(':checkbox') )
					{
						var jTR = jt.closest('tr');
						//jt.closest('tr').toggleClass('ui-selected');
						jt.is(':checked') ? jTR.addClass('ui-selected') : jTR.removeClass('ui-selected ui-selectee') 
					}
					
					return true;
				}
				
				// Prevent default action
				e.preventDefault();
					
				// Just return if we do not need to intercept the click
				//if ( !cel.hasClass('dataCol') || !intercept  ){ return self; }
				if ( !a ){ return self; }
				
				// Handle specific link types
				if 		( a.hasClass('deleteLink') )			{ return admin.del(a); }
				else if ( a.hasClass('duplicateLink') )			{ return admin.duplicate(a); }
				else if ( a.hasClass('selectAll') ) 			{ return self.toggleAll('check'); }
				else if ( a.hasClass('selectNone') ) 			{ return self.toggleAll('uncheck'); }
				//else if ( a.hasClass('relResourceLink') )		{ admin.relatedResource(a, e); }
				else if ( href )								{ window.location.href = href; }
				
				//window.location.href = a.attr('href');
			})
			;
			
		self
		  .handleToolbars()
		  .handleFilters()
		  .handleTableCols();
		
		
		return this;
	},
	
	handleTableCols: function()
	{
		var self      = this,
		    list      = $('#colsHandlerManagerBlock');
		    
		$('a.colsHandlerLink', self.context).each(function()
		{
		    var $this     = $(this),
                cBlock    = $this.next('.colsHandlerManagerBlock'),   // jQuery reference to the columns handler block
		        tbodyH    = $('tbody', self.context).outerHeight(),
		        newH      = ( tbodyH 
		                      - parseInt(cBlock.css('padding-top')) - parseInt(cBlock.css('padding-bottom')) 
		                      - parseInt(cBlock.css('border-top-width')) - parseInt(cBlock.css('border-bottom-width'))
		                    ) || cBlock.css('height');
		    
		    // Display the block
		    $this.click(function(e){ e.preventDefault(); e.stopPropagation(); cBlock.toggleClass('hidden') });
		    
		    cBlock
		      // TODO: open a dialog instead of handling height dynamically 
		      // (will prevents overflow issues when the table only contains few results)
		      .height(newH)
		      .bind('click', function(e)
		    {
		        //e.preventDefault();
		        e.stopPropagation();
		        
		        var t         = e.target,     // Get the target
		            $t        = $(t);         // jQuery reference to the target
		            
		        // Do not continue if the target is neither an input nor a label element
		        if ( !$t.is('label') && !$(t).is('input') ){ return; }
		        
		        var $input    = $(t).is('input') ? $t : $t.parent().find('input'),        // jQuery reference to the input
		            colName   = $input.attr('id').replace(/Display/,'') || '',            // Get the related column name
		            $cols     = $('th.' + colName + ', td.' + colName, self.context);     // Get the matching cols and store the jQuery reference 
		            
		        if    ( $input.is(':checked') ){ $cols.removeClass('hidden'); }
		        else  { $cols.addClass('hidden'); }
		    });
		});
		
		return this;
	},
	
	handleFilters: function()
	{
	    var self   = this,
	        $tr    = $('tbody tr', self.context);                      // Store a jquery reference containing all the rows
	    
	    // Loop over the filters inputs, listening for keyup events
	    $('thead tr.filtersRow', self.context)
	       .bind('keyup change', function(e)
	    {
	        e.preventDefault();
	        e.stopPropagation();
	        
            var t       = e.target,
                $t      = $(t);
                
            // Does not handle targets that are not inputs
            if ( !$t.is(':input') ){ return; }
                
            var $input  = $t,
                $td     = $input.closest('td'),
                val     = $td.hasClass('typeRel') || $td.hasClass('typeFk') || $td.hasClass('typeOneToOne') ? $input.find(':selected').text().trim() : $input.val(),
                colName = $td.attr('headers') || '';
                
            // Do not continue if no input has been found
            if ( !$input.length ) { return; }
            
            // Loop over the rows, re-displaying them by the way
            $tr.each(function()
            {
                var $this   = $(this);
                
                // If the filter value is empty
                if ( val === '' )
                {
                    // Re-display the previously hidden rows for the current filter
                    $this.filter('.' + colName + 'Filtered').removeClass(colName + 'Filtered').show();
                    
                    return;
                }
                // Otherwise, only handle rows that were not already hidden (assuming they have already been filtered)
                // and that are not filtered by the current column 
                else if ( !$this.is(':visible') && !$this.hasClass(colName + 'Filtered') ){ return; }
                
                var $td     = $this.find('td.' + colName),
                    match   = $td.find('.value:contains(' + val + ')').length; //
                    
                // If the 
                if ( !match )
                {
                    // Hide the row adding a class of the name by which it has been filtered  
                    $this.hide().addClass(colName + 'Filtered');
                }
                else { $this.show(); }
            });

	    });
	    
	    return this;
	},
	
	toggleAll: function()
	{
		var self 	= this,
			args 	= arguments,
			action 	= args[0] && typeof args[0] === 'string' 
						? (args[0] === 'check' ? 'check' : 'uncheck')
						: ($(args[0]).is(':checked') ? 'check' : 'uncheck'),
			all 	= $('input:checkbox:visible', self.context);
		
		if ( action === 'check' )	{ all.attr('checked','checked').closest('tr').addClass('ui-selected'); }
		else 						{ all.removeAttr('checked').closest('tr').removeClass('ui-selectee ui-selected'); }
		
		return this;
	},
	
	getResourceId: function(jqObj)
	{
		var tr = jqObj.closest('tr');
		
		return tr.attr('id').replace(/row/,'');
	},
	
	handleToolbars: function()
	{
	    var self           = this;
	       //toolbarsContext = '.adminListToolbar';
	       toolbarsContext = '#adminListToolbarTop, #adminListToolbarBottom';
	       
	    $(toolbarsContext)
		    .each(function()
		    {
		    	var $this = $(this);
		    	
		        $this
		           .find('select')
		               .bind('change', function(e)
	                   {
	                       e.preventDefault();
	                       
	                       var $t   = $(this);
	                           
	                       if ( $t.is('#itemsPerPageTop') || $t.is('#itemsPerPageBottom') )
	                       {
	                            var newLimit   	= $t.val(), 
	                                curURL      = window.location.href,
	                                cleaned     = Tools.removeQueryParam(curURL, 'limit'),
	                                newURL      = cleaned + ( cleaned.indexOf('?') > -1 ? '&' : '?') + 'limit=' + newLimit;
	    
	                            window.location.href = newURL;
	                       }
	                   })
	               .end()
	               .find('input.pageNb')
	               .bind('change', function(e)
	               {
	               		e.preventDefault();
	               		e.stopPropagation();
	               	
                        var input 		= $(this);
                        	newPage   	= input.val(), 
                            curURL      = window.location.href,
                            cleaned     = Tools.removeQueryParam(curURL, 'page'),
                            newURL      = cleaned + ( cleaned.indexOf('?') > -1 ? '&' : '?') + 'page=' + newPage,
                            form 		= input.closest('form');
                           
                        //if ( !Modernizr.input.formaction )
                        {
                        	form.attr('action',newURL).append($('<input />', {'type':'hidden','name':'method','value':'index'})).submit();	
                        }
                        // Otherwise, the formaction attribute should override the one of the form
                        // everything should go well
						
	               })
	               //.find('> .paginationButtons').bind('click', function(e){ $(this).toggleClass('expanded'); })
		    });
	    
	    return this;  
	},
	
	inlineEdit: function(jqObj)
	{		
		this.init = function(jqObj)
		{
			var self = this;
			
			this.context = jqObj;
			
			if ( this.context.hasClass('ui-inlineedit-active') || this.context.hasClass('typeRel') ) { return this; }
			
			this.classes 		= this.context.attr('class') || '';
			this.typeClass 		= this.classes.match(/type[A-Z]{1}\w*/g) ? this.classes.match(/type[A-Z]{1}\w*/g)[0] : '',
			this.subtypeClass 	= this.classes.match(/subtype[A-Z]{1}\w*/g) ? this.classes.match(/subtype[A-Z]{1}\w*/g)[0] : '',
			this.type 			= this.typeClass.replace(/type/g,'').toLowerCase(); 					// Get the column data type
			this.subtype 		= this.subtypeClass.replace(/subtype/g,'').toLowerCase(); 					// Get the column data type
			//this.valCtnr 		= this.context.find('> .dataValue:first'); 								// Store a reference to the value container
			this.valCtnr 		= this.context.find('> .dataValue'); 								// Store a reference to the value container
			this.curVal 		= this.valCtnr.text() || ''; 											// Get the current value
			//this.exactVal 		= this.valCtnr.siblings('.exactValue').text() || '';
			this.exactVal 		= this.valCtnr.attr('data-exactvalue') || '';
			this.boolVal		= this.valCtnr.find('.validity').hasClass('valid') || false
			//this.colName 		= $('> .columName:first', this.context).text() || ''; 							// Get the column name
			this.colName 		= admin.resourceSingular + Tools.ucfirst(this.context.attr('headers').split(' ')[1] || '').replace(/(.*)Col/,'$1'); 							// Get the column name
			this.resId 			= this.context.closest('tr').attr('id').replace(/row/,'') || '';
			//this.url 			= $('> .fullAdminPath:first', this.context).text() || false; 						// Get the resource url
			this.url 			= window.location.href.replace(/(.*)[\?|$](.*)/,'$1').replace(/(.*)\/$/,'$1') + '/' + this.resId;
			this.saving 		= false;
			//this.inputType 	= this.type === 'timestamp' ? 'datetime' : 'text';
			this.inputType 		= 'text';

			if ( !this.url ){ return this; }
			
			if ( this.subtype === 'password' ){ this.inputType = 'password'; }
			
			switch (this.type)
			{
				case 'bool':
					this.fieldHTML = '<label class="multi span">Y</label>'
									+ '<input type="radio" class="multi" name="' + this.colName + '" id="' + this.colName + 'Y' + this.resId + '" value="1" ' + (this.boolVal ? 'checked="checked"' : '') + '/>'
									+ '<label class="multi span">N</label>'
									+ '<input type="radio" class="multi" name="' + this.colName + '" id="' + this.colName + 'N' + this.resId + '" value="0" ' + (!this.boolVal ? 'checked="checked"' : '') + '/>';
					break;
				case 'varchar' :
					this.curVal = this.exactVal;
				case 'timestamp':
				case 'float':
				case 'int':
					this.fieldHTML = '<input type="' + this.inputType + '" name="' + this.colName + '" id="' + this.colName + this.resId + '" value="' + this.curVal + '" />';
					break;
				default:
					this.fieldHTML = false;
					break;
			}

			this.buttonsHTML 	= '<div class="actions actionsBlock">'
									+ '<button class="action save adminLink saveLink" type="submit" id="saveLink' + this.resId +'">save</button>'
									+ '<button class="action cancel adminLink cancelLink" type="cancel" id="cancelLink' + this.resId +'">cencel</button>'
								+ '</div>';
			this.HTML 			= '<div class="ui-inlineedit-form"><form>' + this.fieldHTML + this.buttonsHTML + '</form></div>';

			// Do not continue if the data type is not a varchar
			if ( !this.fieldHTML ) { return this; }

			return this.create();
		};
		
		this.closeAll = function()
		{
			var self = this;
			
			$('td.ui-inlineedit-active', 'table.adminTable').each(function() { self.destroy($(this)); });
		};
		
		this.create = function()
		{
			var self 	= this;
			
			self.context
				.addClass('ui-inlineedit-active')
				.append(this.HTML)
				.find(':input')
				.not('button')
					.each(function(){ self.inputField = $(this); })
					.bind('click', function(e){ e.stopPropagation(); $(this).focus(); })
					.bind('focus', function(e)
					{
						//e.preventDefault()
						e.stopPropagation();
						
						// Select the text
						$(this)[0].select();
					})
					.bind('keypress', function(e)
					{
						//e.preventDefault()
						e.stopPropagation();
						
						var input 	= $(this),
							k 		= e.keyCode; 			// Shortcut for pressed keycode
	
						// If 'esc' key has been pressed, we have to destroy the inlineeditor for this field
						if 		( k === 27 ){ self.destroy(); }
						// Since when enter is hit, the focus goes on the save button which is clicked by the way
						// We no longer need to fire save() from here
						//else if ( k === 13 ){ self.save(); }
					})
					.focus()
				.end()
				.siblings('.actionsBlock')
					.find('.saveLink')
					.bind('click', function(e){ e.preventDefault(); e.stopPropagation(); self.save(); })
					.siblings('.cancelLink')
					.bind('click', function(e){ e.preventDefault(); e.stopPropagation(); self.destroy(); })
				;
			
			return this;
		};
		
		this.destroy = function()
		{
			var self 	= this,
				args 	= arguments || [],
				context = args[0] || self.context,
				what 	= $('.ui-inlineedit-form', context); 	// What to destroy
			
			// Remove the inlineedition form
			what.remove();
			
			// Remove the active inlineedit class on the context (parent <td>)
			//self.context.removeClass('ui-inlineedit-active');
			context.removeClass('ui-inlineedit-active');
			
			return this;
		};
		
		this.save = function()
		{
			// Prevent the saving request from being called twice
			//if ( this.saving ) { return this; }
			
			var self 			= this,
				//input 		= $('.ui-inlineedit-form :input', self.context),
				input 			= $('.ui-inlineedit-form :input:not(button)', self.context);
			
			// Do not continue if the url if empty
			if ( !self.url ){ return self; }
			
			if ( self.type === 'float' )
			{
				var newVal = parseFloat(input.val().replace(/\,/,'.')) || 0;
				
				input.val(newVal);
					
				//self.valCtnr.text(newVal);
			}
			
			 $.ajax(
			 {
			 	url: self.url + '?method=update&tplSelf=1',
				data: input.serialize(),
				type: 'POST',
				dataType: 'json',
				beforeSend: function()
				{
					this.saving = true;
					
					$('.ui-inlineedit-form', self.context).addClass('loading');
				},
				error: function(xhr, txtStatus, err)
				{
					//$('.ui-inlineedit-form', self.context).removeClass('loading').addClass('status ' + status);
				},
				success: function(response)
				{
					var r 			= response,
						warnings 	= r.warnings || [],
						errors		= r.errors || [],
						status 		= warnings.length ? 'warning' : (r.success ? 'valid' : 'error');
					
					$('.ui-inlineedit-form', self.context)
						.removeClass('loading')
						.addClass('status ' + status);
					
					if ( warnings.length )
					{						
						// TODO: use proper notifier
						// $.each(warnings, function(i,item){ notifier.add({type:'warning', id:item.id, data:item.message})
						$.each(warnings, function(i,item)
						{
							$('#body').prepend($('<p />', {
								'class':'notification warning',
								'text':item.message,
								'click': function(e){ $(this).fadeOut(1000, function(){ $(this).remove(); }); }
							}));							
						});
					
						//self.context.addClass('warning', 2000, function(){ self.context.removeClass('warning', 2000); self.destroy(); });
						self.context.addClass('warning').removeClass('warning', 3000, function(){ self.destroy(); window.location.href = '#body'; });
					}
					
					if ( errors.length )
					{					
						// TODO: use proper notifier
						// $.each(warnings, function(i,item){ notifier.add({type:'warning', id:item.id, data:item.message})
						$.each(errors, function(i,item)
						{
							var btnsHTML = '';
							
							$.each(item.buttons || [], function(i,btn)
							{
								btnsHTML += '<a class="actionBtn" ' + ( btn.id ) + ' href="' + btn.href + '"><span class="value"></span></a>'
							});	
							
							$('#body').prepend($('<p />', {
								'class':'notification error',
								'text':item.message,
								'click': function(e){ $(this).fadeOut(3000, function(){ $(this).remove(); }); }
							}));				
						});
					
						//self.context.addClass('warning', 2000, function(){ self.context.removeClass('warning', 2000); self.destroy(); });
						self.context.addClass('error').removeClass('error', 1000, function(){ self.destroy(); window.location.href = '#body'; });
					}
					
					if ( r.success )
					{
						if ( self.type === 'bool' )
						{
							var newVal = $(':checked', self.context).val();
							
							self.valCtnr
								.find('.validity')
								.removeClass('valid invalid')
								.addClass(newVal == 1 ? 'valid' : 'invalid')
								.find('.label')
								.text(newVal == 1 ? 'yes' : 'no'); }
						//else if ( self.type === 'varchar' && self.context.hasClass('passwordCol') )
						// buggy <=== why? TODO : debug
						/*
						else if ( self.type === 'float' )
						{
							//input.attr('value', function(){ var test = parseFloat($(this).attr('value').replace(/\,/,'.')) || 0; alert(test); })
							input.attr('value', function(i,value){ parseFloat(value.replace(/\,/,'.')) || 0; })
							
							
							self.valCtnr.text(newVal);
						}*/
						/*
						else if ( self.type === 'varchar' && self.subtype === 'url' )
						{
							var newVal = input.val() || '';
							
							self.valCtnr.html( 
								$('<a>', {class:'file', href: newVal})
									.append( $('span', {class:'value'}).text( '../' + input.val().replace(/.*\//, '')) )
							);
						}
						*/
						else if ( self.subtype === 'password' )
						{
							Tools.loadJS([{id:'sha1', url:'/public/javascripts/common/libs/sha1.js'}], function()
							{					
								self.valCtnr.text(SHA1(input.val()));
							});
						}
						//else { self.valCtnr.text(input.val()).siblings('.exactValue').text(input.val()); }
						else { self.valCtnr.text(input.val()).attr('data-exactvalue',input.val()); }
						
						//self.context.addClass('success', 2000, function(){ self.context.removeClass('success', 2000); self.destroy(); });
						self.context.addClass('success').removeClass('success', 5000, function(){ self.destroy(); });
					}
					else
					{
						//self.context.addClass('error', 2000, function(){ self.context.removeClass('error', 2000); self.destroy(); });
						self.context.addClass('error').removeClass('error', 5000, function(){ self.destroy(); });
					}
				}
			 });
			
			return this;
		}

		// Close every already opened inline editor cell
		this.closeAll();
		this.init(jqObj);
	}
};


var adminRetrieve =
{
	init: function()
	{
		var subResContext 	= '#adminSubResourcesBlock',
			context 		= 'table.adminTable',
			self 			= this;
			
		adminIndex.init();
			
		// Loop over all the delete buttons in the table
		$(context)
			.find('a')
			.hover(function(e)
			{
				var t 			= e.target, 					// Shortcut for event target
					a			= $(t).closest('a') || null, 	// Try to get closest anchor tag
					intercept 	= a !== null ? true : false; 	// Do we need to intercept click (no if not an anchor)
					
				// Just return if we do not need to intercept the click
	
				
				if ( a.hasClass('relResourceLink') )
				{
					admin.relResTimeout = setTimeout(function(){ admin.relatedResource(a,e); }, 250);
				}
				
			}, function(e)
			{
				var that = this;
				
				clearTimeout(admin.relResTimeout);
				
				admin.relResHideTimeout = setTimeout(function(){ $(that).siblings('.adminRelResBubble').addClass('ninja').parent(); }, 250);
			});
		
		return this;
	}
}


var adminCreate = 
{
	init: function()
	{
		admin.init();
		
		admin
			.handleForeigKeyFields()
			.handleSlugFields()
			.handleDateFields()
			.handleOneToManyFields()
			.handleFileFields();
		
		return this;
	}
};


var adminUpdate = 
{
	init: function()
	{
		admin.init();
		
		admin
			.handleForeigKeyFields()
			.handleSlugFields()
			.handleDateFields()
			.handlePasswordFields()
			.handleOneToManyFields()
			.handleFileFields();
		
		return this;
	}
};

var adminSearch = 
{
    formContext: '#adminSearchForm',
    
    init: function()
    {
        //adminIndex.init();
        
        return this.updateResults();
    },
    
    updateResults: function()
    {
        var self = this;
        
        $('#adminSearchResultsBlock').live('click', function(e)
        {
            var $this 	= $(this),
                t   	= e.target,
                $t  	= $(t);
                
            if ( $t.closest('header', $this).length )
            {
                $t.closest('header').parent().removeClass('current').toggleClass('expanded');
            }
        });
        
        $(self.formContext)
            .bind('submit', function(e)
            {
                e.preventDefault();
                e.stopPropagation();
                
                var $this   = $(this),
                    url     = $this.attr('action') || window.location.href,
                    reqData = $this.serialize();
                    $tbody  = $('tbody', self.context);
                    
                // TODO: handle this server-side instead
                // If the the search query is empty, force the request to attack the 'index' method instead of the 'search' one
                //if ( $('#searchQuery').val() === '') { url = url.replace(/\?(.*)/,''); reqData = null; alert(url); }
                
                // For touch devices, forces keyboard to disappear
                if ( app.support.touch ) { $this.find('input[type=search]').blur(); }
                    
                $.ajax(
                {
                    url: url,
                    type: 'GET',
                    data: reqData,
                    dataType: 'html',
                    beforeSend: function()
                    {
                        $('body').append($(app.loadingBlock).attr('id','loadingBlock'));
                        
                        if ( $('#adminSearchResultsBlock').length )
                        {
                            
                            $('#adminSearchResultsBlock').empty();
                            $('#searchDynamicCSS').empty();
                        }
                        else
                        {
                            $('#mainCol').empty();
                        }
                        
                        /*
                        var $tr         = $tbody.find('tr'),
                            colsNb      = $tbody.find(':first-child').find('td').length,
                            $loading    = $('<tr class="loading"><td colspan="' + colsNb + '"><span class="message loading">loading...</span></td></tr>');
    
                        $tbody.empty().append($loading);
                        */
                    },
                    error: function()
                    {
                        $('#loadingBlock').remove();
                    },
                    success: function(response)
                    {
                        var r               = response,
                            query           = $('#searchQuery').val() || '',
                            rule            = ".commonTable.adminTable td .dataValue[data-exactValue*='" + query  + "'] { background:lightyellow; }",
                            $resultsCtnr    = $('#adminSearchResultsBlock'), 
                            $dest           = $resultsCtnr.length ? $resultsCtnr : $('#mainCol'); 
                        
                        $dest.html($(r));
                        
                        $('#loadingBlock').remove();
                        
                        $('#searchDynamicCSS').html(rule);
                    }
                });
            });
        
        return this;
    }
};
