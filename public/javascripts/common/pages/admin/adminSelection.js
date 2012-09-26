var adminSelection =  
{
	//$context: $('#transactionsFilters'),
	$context: $('#resourceFilters'),
	$form: $('#adminSelectionFilterForm'),
	
	init: function()
	{
//console.log('admin selection init')
		
		var self 		= this;
			$actions 	= self.$context.find('a').filter('.action'),
			$addFilter 	= $('#addSelectionFilter');
		
		// Handle add filter button
		$addFilter
			.data('defaultlabel', $addFilter.find('.value').text())
			.on('click', function()
			{
				var $this = $(this);
				
				// If were are in the 'add' mode, swith back to 'default' mode	
				if ( self.$context.hasClass('addMode') )
				{
					$this
						.removeClass('revert')
						.attr('title', ($this.data('titleold') || ''))
						.find('.value').text($this.data('defaultlabel'));
					self.$context.removeClass('addMode')
				}
				// Otherwise, active the 'add' mode
				else
				{
					$this
						.addClass('revert')
						.data('titleold', $this.attr('title'))
						.removeAttr('title')
						.find('.value').text($this.data('revertlabel'));
					self.$context.addClass('addMode');
					$('#filterColumn').focus();
				}
			})
			
		this.form();
		this.filters.init();
		
		// TODO: preload values for column with preload=true
		
		return this;
	},
	
	filters:
	{
		init: function()
		{
			var self 		= this;
			
			self.$filters 	= $('#selectionFilters'); 					// Store a reference to the filters context
			self.$ul 		= self.$filters.find('> ul'); 				// Store a reference to the filters ul
			self.$li 		= self.$ul.children(); 						// Store a reference to the filter items 
			self.$tpl 		= self.$li.filter('.tpl')
								.removeClass('tpl hidden').remove();	// Get the template and remove it from the DOM
			
			this.nav();
			
			return this;
		},
		
		onBeforeChange: function()
		{
			$(document).trigger('onAdminSelectionBeforeChange');
		},
		
		onChange: function()
		{
			$(document).trigger('onAdminSelectionChange');
		},
		
		getAll: function()
		{
			var self 		= this;
				selConds 	= [];
			
			// Loop over filters
			self.$li.each(function()
			{
				/*
				var $this 	= $(this),
					//isMulti = $this.hasClass('multi'),
					col 	= $this.find('.filterColumn').data('column'),
					op 		= $this.find('.filterOperator').data('operator'),
					vals 	= $this.find('.filterValues').data('values'),
					cond 	= [col,op,vals];
				*/
				var $this 	= $(this),
					cond 	= [$this.data('column'), $this.data('operator'), $this.data('values')]
				selConds.push(cond);
			})
			
			return selConds;
		},
		
		nav: function()
		{
			var self = this;
			
			self.$filters
				.attr('tabindex', 0)
				.on('click', '.action.remove', function(e)
				{
//console.log('click on filter remove');
					
					e.preventDefault();
					e.stopPropagation();
					
					var $this 	= $(this),
						$li 	= $this.closest('.selectionFilter', self.$filters);
				
					// Remove the filter and/or the value
					if ( $li.hasClass('multi') )
					{
						var $value = $this.closest('.filterValue', self.$filters);
						
						// Case remove all values
						if 	( !$value.length )	{ self.remove($li) }
						// Case remove on (clicked) value
						else 					{ self.removeValue($value); }
					}
					//else { $li.remove(); }
					else { self.remove($li) }
				})
				.on('click', '.selectionFilter details', function(e)
				{
//console.log('click on multi')
					e.preventDefault();
					e.stopPropagation();
					
					var $this = $(this);
					 
					if 		( $this.attr('open') === 'open' ) 	{ $this.removeAttr('open'); }
					else 										{ $this.attr('open', 'open'); }
					
					e.stopPropagation();
				})
				.on('click', '.selectionFilter', function(e)
				{
					$(this).toggleClass('active').trigger($(this).hasClass('active') ? 'focus' : 'blur');
				})
				.on('keyup', function(e)
						{
//console.log('keyup filter: ' + e.keyCode);
					// Delete (BACKSPACE or DELETE) 
					if ( e.keyCode === 8 || e.keyCode === 46 ){ e.preventDefault(); e.stopPropagation(); self.remove($(this)); }
				})
			
			
			return this;
		},
		
		create: function()
		{
//console.log('create filter');
			var self 			= this,
				args 			= arguments
				o 				= $.extend({
					// Default options
					data:{}
				}, (args[0] || {}), // user options 
				{
					// Forced options
				}),
				isMulti 		= (typeof o.data.values === "object" && o.data.values.length > 1) ? true : false,
				$sameCond 		= self.$li.filter('[data-resource="' + o.data.resource + '"][data-column="' + o.data.column + '"][data-operator="' + o.data.operator + '"]'),
				sameCondExists	= $sameCond.length ? true : false,
				$new 			= self.$tpl.clone(),
				url 			= adminSelection.$form.data('ajaxaction'),
				insertedValues 	= 0;
				
//console.log('sameCondExists: ' + sameCondExists);
//console.log('url: ' + url);
//console.log('isMulti: ' + isMulti);
//console.log('exists selector: ' + '[data-resource="' + o.data.resource + '"][data-column="' + o.data.column + '"][data-operator="' + o.data.operator + '"]');

//console.log('exact sameCond Exists: ' + (sameCondExists && $sameCond.data('values') === o.data.values) );
//console.log('same cond values: ' + ($sameCond.data('values') || 'null') );

			// Force passed values into an array
			o.data.values = typeof o.data.values === 'object' ? o.data.values : [o.data.values];
			
			// If a condition (for the same resource, with the same operator) for this column already exists
			// We just update it
			if ( sameCondExists )
			{
//console.log('case update existing filter');
				// Otherwise, just update the filter with new values
				self.updateValues($sameCond, o.data.values);
			}
			else
			{
//console.log('case create new filter');
//console.log($new);
//console.log($new.html());
				// Otherwise, we have to add a new item
				$new
					.removeClass('multi')
					.attr(
					{
						'data-resource': o.data.resource,
						'data-column': o.data.column,
						'data-operator': o.data.operator,
						//'data-values': o.data.values.join(',')
					})
					.find('.filterColumn')
						//.attr('data-column', o.data.column)
						.html(o.data.columnDisplayName)
					.end()
					.find('.filterValues').filter('[data-exact="false"]').remove()
						.end()
					.find('.filterOperator')
						//.attr('data-operator', o.data.operator)
						.html(o.data.operator)
						
				// Insert the new item into the DOM
				$new.appendTo(self.$ul);
					
//$new.css('border','1px solid red')
						
				self.updateValues($new, o.data.values);
				
				// Insert the new item into the DOM
				$new.appendTo(self.$ul);
			}
			
			// Do not add the condition server side 
			// if the exact same condition (same resource, column, operator & values) already exists
			//if ( !sameCondExists || $sameCond.data('values') !== o.data.values )
			//if ( !insertedValues.length )
			//{
				self.onBeforeChange();
				
				// Create the filter server side
				$.ajax(
				{
					//url: url.replace(/\/$/, '') + '.json',
					url: url.replace(/\/$/, ''),
					type: 'post',
					//dataType: 'html',
					dataType:'json',
					data: adminSelection.$form.serialize(),
					success: function(r)
					{
						self.onChange();
//$('#mainContent').empty().append(r);
					}
				})
			//}
			
			// Since items list has been updated, we have to update the reference
			self.$li = self.$ul.children();
			
			return this;
		},
		
		updateValues: function($filter, values)
		{
//console.log('update values');
			
			// update classes
			var self 		= this,
				inserted 	= 0,
				$count 		= $filter.find('summary').find('.count'),
				//$values 	= $filter.find('.filterValues').filter('[data-exact="' + (curCount ? 'false' : 'true')  + '"]'),
				$values 	= $filter.find('.filterValues'),
				curCount 	= $count.length ? ($count.text() || '').replace(/\D/g,'') : $values.not(':empty').length;
			
			// Loop over passed values
			for (i in values)
			{
				var val 	= values[i], // Current value
					exists 	= ($filter.attr('data-values') || '').indexOf(val) !== -1 || $filter.data('values') == val;
					
//console.log('try adding val:' + val);
//console.log('value exists:' + exists);
//console.log('curCount:' + curCount);
//console.log('.filterValues :' + $filter.find('.filterValues').length);
//console.log($filter.find('.filterValues'));
//console.log('$values sel:' + '[data-exact="' + (curCount ? 'false' : 'true')  + '"]');
//console.log('$values :' + $values.length);
				
				// Skip value if already present
				if ( exists ){ continue; }
				
				// Otherwise
				// If the current filter has no value
				if ( !curCount ){ $values.html(val); curCount++; }
				
				// Or if it only has 1, transform the filter into its 'multiple' form
				else if ( curCount === 1 )
				{
					// Get the 'multiple' form from the template
					var $tplMulti 	= self.$tpl.find('.filterValues').filter('[data-exact="false"]').clone(),
						curVal 		= $values.html();
					
					// Insert the current value
					$tplMulti.find('li:first').find('> span').attr('data-value', curVal).html(curVal);
					$tplMulti.insertAfter($values);
					$values.remove();
					
					$filter.addClass('multi');
					
					// Update count reference & value
					$count 	= $filter.find('summary').find('.count');
//console.log('$count :' + $count.length);
					$count.text('(1 items)');
					//$count.text(function(i,txt){ return txt.replace(/\d/g, 1); });
					
					// Update values reference & insert a new value
					$values = $filter.find('.filterValues');
					var $last = $values.find('li:last');
					$last.clone().attr('data-value', val).find('> span').html(val).end().insertAfter($last);
				}
				// Otherwise
				else
				{					
					var $last 	= $values.find('li:last');
					
//console.log('$last :' + $last.length);
					
					// Insert a new value
					$last.clone().attr('data-value', val).find('> span').html(val).end().insertAfter($last);
				}

//console.log('try updating count. Count length: ' + $count.length);
				
				// Update count (if any)
				$count.text(function(i, txt)
				{
//console.log('count before: ' + curCount);										
					curCount = (( parseInt((txt || '').replace(/\D/g,'')) || 0) + 1 );
//console.log('count after: ' + curCount);
					//return '(' + curCount  + ')';
					return txt.replace(/\d/g, curCount);
				})
				
				// Update data attributes
				$filter.attr('data-values', function(i,txt){ return txt += (txt !== '' ? ',' : '') + val; })
			}
			
			return this;
		},
		
		remove: function($filters)
		{
//console.log('filter remove')

//console.log('filter count: ' + $filters.length)
			
			var self 	= this,
				ids 	= [],
				url 	= adminSelection.$form.data('ajaxaction');
			
			$filters.each(function()
			{
				var $this = $(this),
					index = $this.data('filterindex') != '' ? $this.data('filterindex') : $this.index();
				
				ids.push(index)
				
				//$this.remove();
				$this.find('> .actions .remove').addClass('loading');
			})
			
//console.log('ids')

			self.onBeforeChange();
			
			// Delete filter from selection by updating the selection (for the whole resource)
			$.ajax(
			{
				//url: url.replace(/\/$/, '') + '.json',
				url: url.replace(/\/$/, '') + '/filters/' + ids.join(',') + '.json',
				type: 'delete',
				//type: 'put',
				dataType: 'json',
				//data: adminSelection.$form.serialize(),
				success: function(r)
				{
					// Do not continue any longer if the response success property is not true 
					if ( r.success === undefined || !r.success ){ $filters.find('> .actions .remove').removeClass('loading'); return; }
					
					// Remove the filters
					$filters.remove();
					
					// Since items list has been updated, we have to update the reference
					self.$li = self.$ul.children();
					
					self.onChange();
				}
			})
			
			// Since items list has been updated, we have to update the reference
			//self.$li = self.$ul.children();
			
			return this;	
		},
		
		removeValue: function($val)
		{
			var self 		= this,
				$li 		= $val.closest('.selectionFilter', self.$filters),
				valsCount 	= $val.siblings().andSelf().length,
				url 		= adminSelection.$form.data('ajaxaction');
			
			// Remove the value
			$val.remove();
			
			// If there won't remain any value, remove the whole filter 
			if ( valsCount - 1 <= 0 )	{ self.remove($li) }
			// Otherwise, just update the counter
			else 
			{
				$li.find('.count').text('(' + (valsCount-1) + ')');
				
				self.onBeforeChange();
				
				// Delete values from the filter by simply updating it
				$.ajax(
				{
					url: url.replace(/\/$/, '') + '/filters/' + ($li.index('data-filterindex') || $li.index()) + '.json',
					type: 'put',
					dataType: 'json',
					data: {'column': $li.data('column'), 'operator': $li.data('operator'), 'values': $li.data('values')},
					success: function(r)
					{
						self.onChange();
					}
				})
			}
			
			return this;
		}
	},
	
	form: function()
	{
		var self 			= this
			$fCol 			= $('#filterColumnLine'),
			$fColSel 		= $fCol.find('select'),
			$fColOpts 		= $fColSel.find('option'),
			fColOptsNb 		= $fColOpts.length,
			$fValue 		= $('#filterValueLine'),
			$fValueInput 	= $('#filterValue').attr('data-oldplaceholder', function(){ return $(this).attr('placeholder') || ''; }),
			rName 			= $fColSel.data('resource'),
			$fValSuggest 	= $('#suggestFilterValue'),
			suggestReq 		= {}, // We will store launched request
			selectSuggestItem = function($item)
			{
				// Get the proper value
				var $colValItem = $item.find('[data-column="' + $fColSel.val() + '"]:first'),
					value 		= $colValItem.data('value') || $colValItem.text();
	
				// Update selected
				$item.removeClass('focused').addClass('active').siblings().removeClass('focused active');
				
				// Update the filter input value with the selected one
				$fValueInput.val(value);
			}
		
		// Handle filter column selection
		$fCol
			.on('click', function(e)
			{
				var $html = $('html');
				
				if ( $html.hasClass('webkit') || $html.hasClass('gecko') ){ fColOptsNb = 1; }
				
				var $this = $(this);
				
				$this.toggleClass('active');
				
				// Setting the size of the select opens it
				// For IE, it requires to be delayed 
				if 	( $html.hasClass('ie') ) { window.setTimeout(function()	{ $fColSel.attr('size', fColOptsNb); }, 1); }
				else 														{ $fColSel.attr('size', fColOptsNb); }
				
				e.stopPropagation();
				
				if ( $this.hasClass('active') )
				{
					$('body')
						// On any click outside
						.one('click', function(e){ e.preventDefault(); $this.removeClass('active'); })
						
					$fColSel.attr('size', 1);
				}
			})
			
		$fColSel
			.data('oldvalue', $fColSel.val())
			.on('keyup', function(e)
					{
//console.log('keyup: ' + e.keyCode);
				
				// Cancel (ESC or LEFT ARROW or )						
				if ( e.keyCode === 27 || e.keyCode === 37 )
				{
					$fCol.removeClass('active');
							
//console.log('cancel: should restaure');
					
					// Restaure previous value
					$fColSel.val($fColSel.data('oldvalue'));
				}

				// Validate choice (ENTER or RIGHT ARROW)
				else if ( e.keyCode === 13  || e.keyCode === 39 )
				{
					$fCol.trigger('click');
							
//console.log('selected: ' + $fColOpts.filter(':selected').length);
//console.log('val: ' + $fColOpts.filter(':selected').attr('value'));
					
					// Update old value since new one has been validated by the user, either by a click of by pressing enter
					$fColSel.val($fColOpts.filter(':selected').attr('value'));
					$fColSel.data('oldvalue', $fColSel.val()).change();
				}
				
				// Do not continue if there's less than 2 options
				if ( fColOptsNb.length < 2 ){ return; }
				
				// DOWN ARROW
				if ( e.keyCode === 40 )
				{
					var $next = $fColOpts.filter(':selected').next(); 
					
					$next = $next.length ? $next : $fColOpts.filter(':first');
					$next.siblings().removeAttr('selected');
					$fColSel.val($next.attr('value')).siblings();
					
					if ( !$fCol.hasClass('active') ){ $fColSel.trigger('change'); }
				}
				
				// UP ARROW
				else if ( e.keyCode === 38 )
				{
					var $prev = $fColOpts.filter(':selected').prev();
					
					$prev = $prev.length ? $prev : $fColOpts.filter(':last');
					$prev.siblings().removeAttr('selected');  
					$fColSel.val($prev.attr('value'));
					
					if ( !$fCol.hasClass('active') ){ $fColSel.trigger('change'); }
				}
			})
			.on('focus', function(e)
			{
//console.log('select focused')	
			})
			.on('click', function(e)
			{
//console.log('select clicked')
				e.stopPropagation();
				$fCol.removeClass('active');
				
				// Update old value since new one has been validated by the user, either by a click of by pressing enter
				$fColSel.data('oldvalue', $fColSel.val()).change();
			})
			.on('change', function(e)
			{
//console.log('value changed')
				var $opt 		= $fColSel.find('option:selected'),
					placeholder = $opt.data('placeholder') || $opt.text();

				$fCol.removeClass('active'); 
				$fValueInput
					.attr('placeholder', placeholder)
					.val('')
					
				var suggestList 	= ($fColSel.val() || '') + 'ValuesSuggest',
					$suggestList 	= $('#' + suggestList);
					
				// Handle binding between chosen column & values suggests list (datalist), if any
				if 		( $suggestList.length && $suggestList.find('option').length )	{ $fValueInput.attr('list', suggestList); }
				else 																	{ $fValueInput.removeAttr('list'); }
			})
			
			
		// Handle filter values input
		$fValueInput
			.on('focus', function(e)
			{
				var visibleH 		= null,
					scrollH 		= null,
					$articles 		= null,
					$focused 		= null,
					colName 		= $fColSel.val(), 												// Get the current filter column name
					minForSuggest 	= $fColSel.find('option:selected').data('minforsuggest') || 0, 	//
					dataList 		= $fValueInput.attr('list') || '',
					$dataList 		= $('#' + dataList),
					
					// Use native datalist if any for the selected column
					// TODO: handle support of datalist 
					// (OK: Chrome 20+, FF 12+, IE 10+, Opera 12+)
					// (KO: Safari, Android browser, Blackberry
					useDatalist 	= Modernizr.input.list && $dataList.length && $dataList.find('option').length,
					setReferences 	= function()
					{
						$articles 		= $fValSuggest.find('article'); 					// Store a reference to suggest items
						
						// Do not continue if there no suggests
						if ( $articles !== null && $articles.length ){ return };
						
						visibleH 		= $fValSuggest.innerHeight(); 						// Get suggest box displayed height
						scrollH 		= $fValSuggest.find('> :first').outerHeight(); 		// Get suggest box content height
						$focused 		= $articles.filter('.focused:first'); 				// Get the current focused item (default it to the 1st one)	
					};

				$(document)
					.off('keydown')
					.on('keydown', function(e)
					{
//console.log('input keydown:' + e.keyCode);
						var val 			= $fValueInput.val() || '';
						
						if ( useDatalist ){ return this; }
							
						setReferences();
//console.log('val:' + val);
//console.log('colName:' + colName);

						switch(e.keyCode)
						{
							// Validate (ENTER)
							case 13: 
								e.preventDefault();
								//e.stopPropagation();
							// Cancel (ESC)
							case 27:
								$fValue.removeClass('active'); 
								break;
							
							// Do nothing (SHITF, CTRL, ALT, DELETE, WINDOWS/COMMAND,)
							case 16:
							case 17:
							case 18:
							case 46:
							case 91:
							case 92:
							case 93:
							case 224:
								break;
							
							// (LEFT & RIGHT ARROWS)
							case 37:
							case 39: 
								break;
								
							// Move to Top (PAGE UP ARROW)
							case 36:

								if (e.shiftKey || e.ctrlKey || e.altKey) { break; };
								
								$focused.removeClass('focused');
								$focused = $articles.filter(':first').addClass('focused');
								$fValSuggest.scrollTop(0);
								break;
							
							// Move to Bottom (PAGE DOWN ARROW)
							case 35:
								$focused.removeClass('focused');
								$focused = $articles.filter(':last').addClass('focused');
								$fValSuggest.scrollTop(scrollH - visibleH);
								break;	
							
							// Move Down (DOWN ARROW)
							case 40:
							
								if ( !$fValue.hasClass('active') ){ $fValue.addClass('active'); break; }

								// Get current active item
								var $newFocused 	= $focused && $focused.length & $focused.next().length ? $focused.next() : $articles.filter(':first');
								//var $newFocused 	= $focused.next() || $articles.filter(':first');
								//var $newFocused 	= $('article.focused').length ? $('article.focused') : $articles.filter(':first');
								
/*								
console.log('ARTICLES LENGTH: ' + $articles.length);
//console.log('FOCUSED LENGTH: ' + ($focused.length || 0));
console.log('NEWFOCUSED LENGTH: ' + $newFocused.length);
*/
//console.log($articles.css('border','1px solid green'));
//console.log($newFocused.css('border','1px solid red'));
									
								// Do not continue if there's no article or no next item
								if ( !$articles.length || !$newFocused.length ){ break; }
								
//console.log($newFocused.css('border','1px solid yellow'));
								
								// Update "focused" item
								selectSuggestItem($newFocused);
								$focused = $newFocused;
								
								// Handle Scrolling
								var newFocusedY 	= $newFocused[0].offsetTop || 0,
									newFocusedH 	= $newFocused.outerHeight() || 0;
		
								if ( (newFocusedY + newFocusedH) > visibleH )
								{
									var offset = Math.floor(newFocusedY / visibleH) + Math.abs( visibleH - newFocusedY - newFocusedH);

									$fValSuggest.scrollTop(offset);
								}
								
								e.preventDefault();
								
								break;
							
							// Move UP (UP ARROW)
							case 38:
								// Get current active item
								var $newFocused 	= $focused.length & $focused.prev().length ? $focused.prev() : $articles.filter(':last');
								
								// Do not continue if there's no article or no prev item
								if ( !$articles.length || !$newFocused.length ){ return; }
								
								// Update "focused" item
								selectSuggestItem($newFocused);
								$focused = $newFocused;
								
								// Handle Scrolling
								var newFocusedY 	= $newFocused[0].offsetTop || 0,
									newFocusedH 	= $newFocused.outerHeight() || 0;
		
								if ( (newFocusedY + newFocusedH) > visibleH )
								{
									var offset = Math.floor(newFocusedY / visibleH) + Math.abs( visibleH - newFocusedY - newFocusedH);

									$fValSuggest.scrollTop(offset);
								}
								
								e.preventDefault();
								
								break;
							default:
//console.log('input keydown:' + e.keyCode);

								// Use a timeout to allow input value to be updated with pressed key
								setTimeout(function()
								{
									var newval 	= (e.keyCode === 8) ? val.slice(0,-1) : val + (String.fromCharCode(e.keyCode) || '').toLowerCase();
										//newval 		= $fValueInput.val();
										
//console.log('newval: ' + newval)
									
									// Do not continue if the value is not at least {$minForSuggest} characters longs
									if ( newval.length < minForSuggest ){ $fValue.removeClass('active'); return; }
									
									// Make the suggest block visible (if not already)
									$fValue.addClass('active');
									
									var col 	=
										url 	= '/admin/' + rName 
													+ '?mode=distinct'
													+ '&field=' + colName
													+ '&getFields=id,' + colName
													+ '&displayMode=list' 
													+ '&displayCols=' + colName 
													+ '&sortBy=' + colName
													//+ '&limit=-1'
													+ '&limit=100'
													+ '&conditions=' + colName + '|contains|' + newval,
										urlenc 	= encodeURI(url),
										key 	= rName + '|' + colName + '|' + 'contains' + '|' + $.trim(newval),
										success = function(key,r)
										{
											suggestReq[key].response = r;
											
//console.log('req success: newval=' + newval + ', reqval=' + suggestReq[key].value);
//console.log('Current value: ' + newval);
//console.log('request value: ' + suggestReq[key].value);
											// Do not continue any longer if the current value of the input does not match with the one used for the request
											// This means that the value has changed between the moment where the request has been launched
											// and the one the response has been received
											//if ( $fValueInput.val() !== suggestReq[key].value ){ return; }
											
											$fValSuggest.empty()
											
											if ( newval !== suggestReq[key].value ){ return; }
											
//console.log('values are identical, should reinject response data');
//console.log(r);
//console.log(r);
//console.log(suggestReq[key].response)
											
											// Otherwise, update the suggests with response data
											$fValSuggest
												.html($(suggestReq[key].response).html())
												.removeClass('loading').find('a').on('click', 'a', function(e){ e.preventDefault(); });
											
//console.log('ARTICLES LENGTH BEFORE UPDATE: ' + $articles.length)
											
											// Update jquery reference to suggest values
											$fValSuggest = $('#suggestFilterValue')
											
//console.log('ARTICLES LENGTH AFTER UPDATE: ' + $articles.length)
											
											setReferences();
											
//console.log('ARTICLES LENGTH AFTER UPDATE + RESET REFS: ' + $articles.length)
//console.log('ARTICLES LENGTH AFTER UPDATE + RESET REFS 2: ' + $('article').length)
										};
								
//console.log('stored requests:');
//console.log(suggestReq);	
//console.log('url: ' + url);
//console.log('key:' + key);
//console.log('key length:' + key.length);
//console.log(suggestReq[key]);
									
									// If the request for the current value has already been launched
									if ( suggestReq[key] )
									{
//console.log('request already launched');
//console.log(suggestReq[key]);
										// Response received
										if ( suggestReq[key].xhr.readyState && suggestReq[key].xhr.readyState === 4 )
										{
//console.log('relaunch xhr success');
//console.log('Current value: ' + newval);
//console.log('request value: ' + suggestReq[key].value);
											success(key, suggestReq[key].response);
										}
										
										//break;
										return;
									}
									
//console.log('launch new request');
									
									// Otherwise, launch it
									suggestReq[key] = {value:newval, response:null, xhr:$.ajax(
									{
										url: url,
										type: 'get',
										dataType: 'html',
										cache: true,
										beforeSend: function(){ $fValSuggest.empty().addClass('loading') },
										success: function(r){ success(key,r); }
									})};
							}, 0);
								
								break;
						}
					})
					.off('keyup')
					.on('keyup', function(e)
					{
//console.log('input keyup:' + e.keyCode);
						var val 			= $fValueInput.val() || '';
						
						if ( useDatalist ){ return this; }

						switch(e.keyCode)
						{
							case 37: // LEFT ARROW
							case 38: // UP ARROW
							case 39: // RIGHT ARROW
							case 40: // DOWN ARROW
								e.preventDefault();
								break;
							case 13: // ENTER
								self.$form.submit();
								break;
							default:
								break;
						}	
					})
			})
			
		$fValSuggest
			.on('focus', function()
			{
//console.log('focus on suggests');
			})
			.on('click', 'article', function(e)
			{
//console.log('click on suggest');
				
				// Update $focused ref
				$focused = $(this);
				
				// Update selected
				selectSuggestItem($focused);
				
				// Hide suggests
				$fValue.removeClass('active');
				
				//e.preventDefault();
			})

		
		self.$form.on('submit', function(e)
		{
//console.log('form submited')
			e.preventDefault();
			
			// Do not continue if there's no value 
			if ( $('#filterValue').val() === '' ){ return; }
			
			$fValue.removeClass('active');
			
//console.log('pending requests: ' + curSuggestReq.length)
			
			// Abort any running ajax suggest request
			//for(i in curSuggestReq){ curSuggestReq[i].abort(); }
			
			var url 	= self.$form.data('ajaxaction'),
				data 	= {
					'resource': $('#filterColumn').data('resource'), 
					'column': $('#filterColumn').val(),
					'columnDisplayName': $('#filterColumn').find(':selected').text() || $('#filterColumn').val(), 
					//'operator': 'contains',
					'operator': $('#filterOperator').val(),
					'values': ($('#filterValue').val() || '').split(',')
				};
				
//console.log(data)
			
			$fValue.removeClass('active');
						
			// Create a new selection filter item
			adminSelection.filters.create({data:data});
		});
		
		return this;
	} 
}