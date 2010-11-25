var admin =
{
	//resourceName: ($('table.adminTable').attr('id') || '').replace(new RegExp('Table','g'),''),
	resourceName: $('#resourceName').attr('class') || '',
	resourceSingular: $('#resourceSingular').attr('class') || '',
	
	relResTimeout: null,
	relResHideTimeout: null,
	
	init: function()
	{
		return this.menu();
	},
	
	menu: function()
	{
		//app.intercept('#adminMenuBlock, #topPaginationBlock, #bottomPaginationBlock', {action:'clickDelegate', dest:'#mainCol'});
		
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
					.insertBefore('table.adminTable tbody tr:last').toggleClass('odd').toggleClass('even').attr('id','row' + createdId)
					.find('td.colSelectResources :checkbox').val(createdId).end()
					.find('td.actionsCol a')
						//.attr('href', function(){ return $(this).attr('href').replace(new RegExp('\/'+ cloneId + '\\?'),'/' + createdId + '?'); }).end()
						.attr('href', function(i,value){ return value.replace(new RegExp('\/'+ cloneId + '\\?'),'/' + createdId + '?'); }).end()
					.find('td.idCol .value')
						//.attr('id', function(i,value){ return value.replace(new RegExp(cloneId), createdId); }).end()
						.attr('id', function(i,value){ return value.replace(new RegExp(cloneId), createdId); })
						.text(createdId).siblings('.exactValue').text(createdId).end()
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
			multiple 	= jqObj.length >= 1,							// Is their several objects to delete
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
	
	handleForeigKeyFields: function()
	{
		var self = this;
		
		$('a.changeValBtn', 'form').click(function(e)
		{
			e.preventDefault();
			
			var a 				= $(this),											// Store a reference to the clicked anchor
				curVal 			= a.find('> .fieldCurrentVal:first').text(), 		// Get the current value of the fied
				relResource 	= a.find('> .relResource:first').text(),			// Get the related table name
				fName 			= a.find('> .formFieldName:first').text(),			// Get the form field name
				relField 		= a.find('> .relField:first').text() || null,		// Get the relation field
				relDisplayAs 	= a.find('> .relDisplayAs:first').text() || null,	// Get the relation field 
				relGetFields 	= a.find('> .relGetFields:first').text() || null;	// Get the related fields to get
			
			$.ajax(
			{
				url: $(this).attr('href'),
				data:'limit=-1',
				type: "GET",
				dataType: 'json',
				success: function(response)
				{
					var r = response;									// Shortcut for response
					
					$('#' + fName).remove();
					$('<select />').attr({id:fName, name:fName}).appendTo( a.closest('.relField').hide().parent() );					
					
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
//.css('border','1px solid red')
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
//Tools.log('handleOneToManyFields');
		
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
Tools.log(k);
								
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
		$('input.datetime', 'form')
			.datepicker({ 
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
		
		if ( $(self.context).width() > $(self.context).closest('.adminListingBlock', '#mainCol').width() ){ $(self.context).css({'display':'block','overflow':'hidden','overflow-x':'scroll'}); }
		
		$('#deleteSelectionTopBtn, #deleteSelectionBottomBtn')
			.click(function(e)
			{
				e.preventDefault();
				
				admin.del($('tbody tr.ui-selected', self.context));
			});

		// Loop over all the delete buttons in the table
		$(self.context)
			.selectable(
			{
				filter: 'tr',
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
			})
			.parent()
			.click(function(e)
			{
//Tools.log('index click handler');
				
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
				else if ( a.hasClass('relResourceLink') )		{ admin.relatedResource(a, e); }
				else if ( a.hasClass('colsHandlerLink') )		{ return self.handleTableCols(a , e); }
				else if ( href )								{ window.location.href = href; }
				
				//window.location.href = a.attr('href');
			})
			.find('a')
			.hover(function(e)
			{
				var t 			= e.target, 					// Shortcut for event target
					a			= $(t).closest('a') || null, 	// Try to get closest anchor tag
					intercept 	= a !== null ? true : false; 	// Do we need to intercept click (no if no an anchor)
					
				// Just return if we do not need to intercept the click
	
				
				if ( a.hasClass('relResourceLink') )
				{
					//admin.relResTimeout = setTimeout(function(){ admin.relatedResource(a,e); }, 250);
				}
				
			}, function(e)
			{
				var that = this;
				
				//clearTimeout(admin.relResTimeout);
				
				//admin.relResHideTimeout = setTimeout(function(){ $(that).siblings('.adminRelResBubble').addClass('ninja').parent(); }, 250);
			});
		
		
		return this;
	},
	
	handleTableCols: function()
	{
		var self 	= this,
			args 	= arguments,
			a 		= args[0] || null,
			context = '.colsHandlerManagerBlock';
			
		 $(a).siblings(context).toggleClass('hidden');
		
		return this;
	},
	
	toggleAll: function()
	{
		var self 	= this,
			args 	= arguments,
			action 	= args[0] && typeof args[0] === 'string' 
						? (args[0] === 'check' ? 'check' : 'uncheck')
						: ($(args[0]).is(':checked') ? 'check' : 'uncheck'),
			all 	= $('input:checkbox', self.context);
		
		if ( action === 'check' )	{ all.attr('checked','checked').closest('tr').addClass('ui-selected'); }
		else 						{ all.removeAttr('checked'); }
		
		return this;
	},
	
	getResourceId: function(jqObj)
	{
		var tr = jqObj.closest('tr');
		
		return tr.attr('id').replace(/row/,'');
	},
	
	inlineEdit: function(jqObj)
	{		
		this.init = function(jqObj)
		{
//Tools.log('inlineedit init');
			
			var self = this;
			
			this.context = jqObj;
			
			if ( this.context.hasClass('ui-inlineedit-active') || this.context.hasClass('typeRel') ) { return this; }
			
			this.classes 		= this.context.attr('class') || '';
			this.typeClass 		= this.classes.match(/type[A-Z]{1}\w*/g) ? this.classes.match(/type[A-Z]{1}\w*/g)[0] : '',
			this.subtypeClass 	= this.classes.match(/subtype[A-Z]{1}\w*/g) ? this.classes.match(/subtype[A-Z]{1}\w*/g)[0] : '',
			this.type 			= this.typeClass.replace(/type/g,'').toLowerCase(); 					// Get the column data type
			this.subtype 		= this.subtypeClass.replace(/subtype/g,'').toLowerCase(); 					// Get the column data type
			this.valCtnr 		= this.context.find('> .dataValue:first'); 								// Store a reference to the value container
			this.curVal 		= this.valCtnr.text() || ''; 											// Get the current value
			this.exactVal 		= this.valCtnr.siblings('.exactValue').text() || '';
			this.boolVal		= this.valCtnr.find('.validity').hasClass('valid') || false
			//this.colName 		= $('> .columName:first', this.context).text() || ''; 							// Get the column name
			this.colName 		= admin.resourceSingular + Tools.ucfirst(this.context.attr('headers').split(' ')[1] || '').replace(/(.*)Col/,'$1'); 							// Get the column name
			this.resId 			= this.context.closest('tr').attr('id').replace(/row/,'') || '';
			//this.url 			= $('> .fullAdminPath:first', this.context).text() || false; 						// Get the resource url
			this.url 			= window.location.href.replace(/(.*)[\?|$](.*)/,'$1') + this.resId;
			this.saving 		= false;
			//this.inputType 	= this.type === 'timestamp' ? 'datetime' : 'text';
			this.inputType 		= 'text';

//Tools.log(this.colName);
//Tools.log(this.url);

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

			this.buttonsHTML 	= '<div class="actionsBlock">'
									+ '<button class="adminLink saveLink" type="submit" id="saveLink' + this.resId +'">' 
									+ '</button>'
									+ '<button class="adminLink cancelLink" type="cancel" id="cancelLink' + this.resId +'">' 
									+ '</button>'
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
//Tools.log('create');
			
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
							
//Tools.log('k:' + k);
	
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
//Tools.log('save');
			
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
						else { self.valCtnr.text(input.val()).siblings('.exactValue').text(input.val()); }
						
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
			relResContext 	= '#relatedResourcesFinder',
		
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
			
		this.finder();
		
		return this;
	},
	
	finder: function()
	{
		this.context 		= '#relatedResourcesFinder';
		this.listContext 	= '#adminRelatedListingBlock';
		this.displayed 		= [];
		this.maxGroupsNb 	= 0; // Maximum number of groups found in 1 col
		this.headersH 		= 0; // Outer height of the group headers
		this.gpContentH 	= 0; // Outer height of the displayed group content
		this.defaultH 		= 200;
		this.listViewInited = false;
			
		this.init 			= function()
		{
			var self = this;
			
			$(self.context)
				// Set finder height 
				.height(self.defaultH)
				//.bind('accordionchange click', function(e, ui)
				.bind('click', function(e, ui)
				{
					// Prevent default action
					e.preventDefault();
					
					var t 	= e.target, 							// Shortcut for event target
						jT 	= $(t),									// Store a reference to the jquery target object
						a	= jT.is('a') 
								? jT 
								: (ui ? ui.newHeader.find('a:first') : $('.current a:first', self.context));
						
					if 		( a.hasClass('relatedResourceLink') ) 		{ self.updateGroup(a); }
					else if ( a.hasClass('relatedResourceItemLink') ) 	{ self.updateList(a); }
				})
				// Loop over the headers
				.find('.ui-finder-group-header')
					// And store group resource name into the displayed ones list, for later use 
					.each(function(){ var resName = $(this).find('a:first').text().replace(/\s/g,'_'); self.displayed[resName] = 1; })
				.end()
				// Loop over the cols
				.find('.ui-finder-col')
					.accordion(
					{
						autoHeight: false,
						clearStyle: true,
						header: '.ui-finder-group-header'
					})
					//.find('.ui-finder-group:first .ui-finder-group-header a:first').click()
					.end()
				.end()
				;
			
			return this;	
		};
		
		this.updateSize = function()
		{
			var self 		= this;
			
			$('.ui-finder-col', self.context).each(function()
			{
				// Get the current of groups in the current col
				var gpsNb = $('.ui-finder-group', this).length;
				
				// If the current item has more group than the stored maxGroupsNb   
				if ( gpsNb > self.maxGroupsNb )
				{
					// Update this maxGroupsNb
					self.maxGroupsNb = gpsNb;
					
					// Get the headers height, active group height and calculate total finder height accordingly
					var headersH 	= self.headersH || $('.ui-finder-group:first .ui-finder-group-header', this).outerHeight();
						gpContentH 	= self.gpContentH || $('.ui-accordion-content-active', this).outerHeight(),
						tmpfH 		= self.maxGroupsNb * headersH + gpContentH,
						fH 			= (tmpfH > self.defaultH ? tmpfH : self.defaultH) + 17; // 17 = scroll height
						
					// Then, update the finder height
					$(self.context).height(fH);
				} 
			});
			
			return this;
		}
		
		this.initCols = function(jCollection)
		{
			var self = this;
			
			jCollection.each(function()
			{
				$(this)
					.resizable(
					{
						//alsoResize: $(this).siblings('.ui-finder-col')
					});
			});
			
			return this;
		}
		
		this.updateGroup = function()
		{
			var self 			= this,
				args 			= arguments, 										// Shortcut for arguments
				a 				= args[0],											// Shortcut clicked <A> tag, wrapped by jquery
				o				= args.length > 1 
									? args[1] 
									: {updateList:true, relParams:''},  			// Shortcut for options
				c 				= self.context,										// Shortcut for context
				group 			= a.closest('.ui-finder-group'),					// Shortcut for the current group
				col 			= group.closest('.ui-finder-col'),					// Shortcut for the current column
				curColIndex 	= $('.ui-finder-col', c).index(col), 				// Index of the current find column
				col2createIndex = curColIndex + 1, 									// Index of the colum to create/update
				url 			= a.attr('href') || '';
			
			if ( o.updateList ) { this.updateList(a, {fullList:true, updateGroup:false}); }
			
			$.ajax(
			{
				url: url,
				//data: 'tplSelf=1' + ( self.valueFilter ? '&values=' + self.valueFilter : '' ),
				data: 'tplSelf=1' + ( self.valueFilter ? '&values=' + self.valueFilter : '') + '&' + o.relParams,
				type: 'GET',
				dataType: 'html',
				success: function(response)
				{
					var r		= response,
						jR 		= $(r).css('visibility','hidden'),
						jNewCol = $('.ui-finder-col:eq(' + col2createIndex + ')', c); 
					
					// If the columm to create already exists, update id
					if ( jNewCol.length ){ jNewCol.replaceWith(jR); } 
					
					// Otherwise, create it
					else
					{ jR.appendTo(c); }
					
					// Reselect the newCol since it may not exists when we selected it first
					jNewCol = $('.ui-finder-col:eq(' + col2createIndex + ')', c);
					
					// Before adding the data, we have to clean them (remove already displayed resource nodes)
					self.cleanCols(jNewCol);
					
					jNewCol.accordion({ autoHeight: false, clearStyle: true, header: '.ui-finder-group-header'});

					self
						.initCols(jNewCol)
						.resizeCol(jNewCol)
						.updateSize();


					jNewCol
						.css('visibility','visible') 									// Make the new col visible
						.resizable();

					var newColGps 	= jNewCol.find('.ui-finder-group'), // Get the new col groups
						newColGpsNb = newColGps.length; 				// Get their count
						
					// If there's only one group in the new col, force cascading
					if ( newColGpsNb === 1 )
					{
						self.updateGroup(newColGps.eq(0).accordion('activate',0).find('.ui-finder-group-header a:first'), {updateList:false});
						
					}
				}
			});
			
			return this;
		};
		
		this.cleanCols = function(jCollection)
		{
			var self = this;

			// Loop over the passed cols
			jCollection.each(function()
			{
				// For each one
				$(this)
					// loop over the group headers
					.find('.ui-finder-group-header')
						.each(function()
						{
							// Get the resource name and make it array index friendly
							var resName 	= $(this).find('a:first').text().replace(/\s/g,'_'),
								colIndex 	= $('.ui-finder-col', self.context).index($(this).closest('.ui-finder-col'));

							// If the resource is not already displayed in the finder, add it to the displayed ones list
							if ( !self.displayed[resName] ) { self.displayed[resName] = colIndex+1; }
							// Othewise
							else
							{
								// We have to delete it, but only if it is present in another col than the current one 
								if ( self.displayed[resName] !== colIndex+1 ){ $(this).closest('.ui-finder-group').remove(); }
							}
						});
			});
			
			return this;
		};
		
		this.resizeCol = function(jCol)
		{
			var self 		= this,
				maxColsW 	= 0;		// Store the max group width found
			
			// Loop over the group contents
			jCol.find('.ui-finder-group-content').each(function()
			{
				var curMaxW = $(this).width(); // Get the current group width
				
				// If the current group width is highger than the store max group width
				if ( curMaxW > maxColsW )
				{
					// Update the store one
					maxColsW = curMaxW;
					
					// Resize the col accordingly
					$(this).closest('.ui-finder-col').width(curMaxW);
				}
			});
			
			return this;
		};
		
		this.updateList = function()
		{
			var self 			= this,
				args 			= arguments, 										// Shortcut for arguments
				a 				= args[0],											// Shortcut clicked <A> tag, wrapped by jquery
				o				= args.length > 1 
									? args[1] 
									: {updateGroup:true, fullList:false},			// Shortcut for options
				c 				= self.context,										// Shortcut for the context
				lc 				= self.listContext, 								// Shortcut for the listView Block
				item 			= a.closest('.ui-finder-item'),						// Get the item
				url 			= a.attr('href') || '', 							// Get the url to use for the request
				groupHeader		= item.closest('.ui-finder-group-content').prev(), 	// Get the group header
				by 				= '';
				
			if ( o.fullList )
			{
//Tools.log('case 1');
				var resL = a.siblings('.relatedResourceList').find('a');
				 
				url = resL.attr('href') || ''; 	// Get the url
				by 	= resL.attr('rel') || ''; 	// Get the column name on which to filter filters by
				//self.valueFilter = '';
			}
			else
			{
//Tools.log('case 2');

				// Store the current value Filter (resource id)
				self.valueFilter = Tools.getURLParamValue(url, 'values');
				
				// Update 'current' states
				if 	( item.hasClass('current') ){ item.removeClass('current'); self.valueFilter = ''; }
				else 							{ item.addClass('current').siblings().removeClass('current'); }
					
				var relParams = a.attr('href').replace(/(.*)\?(.*)/,'$2') || '';
					
				if ( o.updateGroup ) { this.updateGroup($('a:first', groupHeader), {updateList:false, relParams:relParams}) }				
			}
			
			// Clean the url, removing the 'values' param
			url = Tools.removeQueryParam(url, 'values');
			
			// Do not continue if the url is empty
			if ( !url ){ return this; }
			
			$.ajax(
			{
				url: url,
				data: 'tplSelf=1' + ( self.valueFilter ? '&values=' + self.valueFilter : '') + ( self.valueFilter && by ? '&by=' + by : ''),
				type: 'GET',
				dataType: 'html',
				success: function(response)
				{
					var r = response;
					
					$(lc).html($('table', r));
					
					if ( !self.listViewInited ){ self.listViewInited = true; adminIndex.init(); }
				}
			});
			
			return this;
		};
		
		this.init();
	}
}


var adminCreate = 
{
	init: function()
	{
		admin
			.handleForeigKeyFields()
			.handleSlugFields()
			.handleDateFields()
			.handleFileFields();
		
		return this;
	}
};


var adminUpdate = 
{
	init: function()
	{		
		admin
			.handleForeigKeyFields()
			.handleSlugFields()
			.handleDateFields()
			.handlePasswordFields()
			.handleFileFields();
		
		return this;
	}
}
