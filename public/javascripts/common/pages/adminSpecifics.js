var adminHome =
{
	init: function ()
	{
	    admin.init();
	    
	    //adminSearch.init();
	    
		$('#connectedUsersLink').click(function(e)
		{
			e.preventDefault();
			
			$('#connectedUsersDetailBlock').toggleClass('hidden');
		});
		
		return this;
	}
}

var adminResourcesCreate =
{
	init: function()
	{
		
	}	
};
