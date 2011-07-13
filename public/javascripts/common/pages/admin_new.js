


 


var admin =
{
	init: function()
	{
		this.menu.init();
		this.search.init();
		
		return this;
	},
	
	// Dashboard
	index: 
	{
		init: function()
		{
			
		}
	},
	
	// List/table
	'list': 
	{
		init: function($tables)
		{			
			$table.each(function()
			{
				var $table = $(this);
				
				 
			})
		},
		
		toolbars: function()
		{
			this.filters();
			this.cols();
		},
		
		// Handle columns filtering
		filters: function()
		{
			
		},
		
		// Handle Show/Hide of columns
		cols: function()
		{
			
		},
		
	},
	
	create: 
	{
		init: function()
		{
			
		}
	},
	
	retrieve: 
	{
		init: function()
		{
			
		}
	},

	update: 
	{
		init: function()
		{
			
		}
	},
	
	'delete': 
	{
		init: function()
		{
			
		}
	},
	
	duplicate: 
	{
		init: function()
		{
			
		}
	},
	
	search: 
	{
		init: function()
		{
			
		},
		
		listen: function()
		{
			
		},
		
		udpate: function()
		{
			
		},
		
		results: function()
		{
			
		}
	},
};