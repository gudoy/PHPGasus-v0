var apiHome =
{
	init: function()
	{
		$('#apisBlock').accordion(
		{
			header:'header.groupTitle',
			autoHeight: false
			//autoHeight: app.isMobile ? false : true
		});
		
		$('#apiParamsSection dl').click(function(e)
		{
			e.preventDefault();
			
			var $this 	= $(this),
				t 		= e.target,
				$t 		= $(t),
				//$dd 	= $t.closest('dd', $this),
				$dt 	= $t.closest('dt', $this);
				
			if ( !$dt.length ) { return; }
			
			//$dt.addClass('expanded').siblings().removeClass('expanded').end().next('dd').addClass('expanded');
			$dt.toggleClass('expanded').next('dd').toggleClass('expanded');
		})
		//.find('dt:first').click();
		;
	
		return this;
	}
};