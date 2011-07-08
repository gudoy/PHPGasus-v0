var adminResources =
{
	init: function()
	{
		var self = this;
		
		if 		( $('#adminResourcesCreateBlock').length ){ this.create(); }
		else if ( $('#adminResourcesUpdateBlock').length ){ this.update(); }  
		
		return this;		
	},
	
	create: function()
	{
		var self = this;

		this.autoFill();
		
		return this;
	},
	
	update: function()
	{
		var self = this;
		
		this.autoFill();
		
		return this;
	},
	
	autoFill: function()
	{
		var self = this;
		
		$('input#resourceTable')
			.bind('keyup', function(e)
			{
				var curVal 	= $(this).val()
					slug 	= Tools.slug(curVal);
				
				$('input#resourceName').val(slug.replace(/\-/g,'').toLowerCase());
				$('input#resourceSingular').val(Tools.singular(slug).replace(/\-/g,'').toLowerCase());
				$('input#resourceDisplayName').val(curVal.replace(/_/g,' '));
				$('input#resourceAlias').val(self.guessAlias(curVal));
			});
			
		return this;
	},
	
	guessAlias: function(resource)
	{
		var parts = resource.split('_');
		
		// ex: 			user_medias => um (if not already in use)
		// otherwise: 	user_medias => usr_md
		
		// 1st possibility: get the first char of every part of the name
		poss1 = '';
		for ( var i in parts ){ poss1 += parts[i][0]; }
		
		// 2nd possibility: get only the vowels of every part of the name
		poss2 = '';
		for ( var i in parts ){ poss2 += Tools.consonants(parts[i]); }
		
		// 3rd possibility: use the full resource name
		poss3 = resource;
		
		// TODO: how to check that alias is not already in use? Make JS DataModel?
		//return !empty(self::$resources['_aliases'][poss1]) ? poss1 : ( !empty(self::$resources['_aliases'][poss2]) ? poss2 : poss3 );
		return poss1;
	}
};

$(document).ready(function(){ adminResources.init(); })
