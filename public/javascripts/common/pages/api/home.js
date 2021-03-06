var apiHome =
{
	init: function()
	{
		this.apis 			= '#apisBlock';
		this.commonParams 	= '#apiParamsSection';
		
		this.nav();
	
		return this;
	},
	
	nav: function()
	{
		$(document)
			.on('click', this.commonParams, function(e)
			{
				e.preventDefault();
				
				var $t 			= $(e.target),
					$dt 		= $t.is('dt') ? $t : $t.prev('dt'),
					$dd 		= $dt.next('dd'),
					isActive 	= $dt.hasClass('expanded');
				
				$dt.toggleClass('expanded');
				$dd.toggleClass('expanded');
			})
			.on('click', 'header.groupTitle', function(e)
			{
				e.preventDefault();

				var $group 		= $(e.target).closest('.apiGroupBlock', this.apis);

				//$group.not('.active').addClass('active').siblings('.active').removeClass('active');
				$group.toggleClass('active');
			})
		
		return this;	
	}
};