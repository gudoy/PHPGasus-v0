var adminHome =
{
	init: function ()
	{
		$('#connectedUsersLink').click(function(e)
		{
			e.preventDefault();
			
			$('#connectedUsersDetailBlock').toggleClass('hidden');
		});
		
		return this;
	}
}
