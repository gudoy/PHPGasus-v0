{extends file='specific/layout/pageAdmin.tpl'}

{block name='pageContent'}

	<div class="strate">
		
		<div class="grid_6"	
		{include file='common/blocks/admin/dashboard/resourcesTable.tpl'}
		</div>
		
		<div class="grid_10">
			{include file='common/blocks/admin/dashboard/usersStats.tpl'}
		</div>
	
	</div>
	
	{*
	<div class="strate">
	
		<div class="grid_10">
			{include file='common/blocks/admin/dashboard/usersStats.tpl'}
		</div>
	
	</div>
	*}
	
{/block}