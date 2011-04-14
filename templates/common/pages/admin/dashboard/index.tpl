{extends file='specific/layout/pageAdmin.tpl'}

{block name='pageContent'}
	
	{*
	<div class="grid_6">
		{include file='common/blocks/admin/dashboard/resourcesTable.tpl'}
	</div>
	
	<div class="grid_10">
		{include file='common/blocks/admin/dashboard/usersStats.tpl'}
	</div>
	*}
	
	{*
	<div class="strate">
	
		<div class="grid_10">
			{include file='common/blocks/admin/dashboard/usersStats.tpl'}
		</div>
	
	</div>
	*}
	
	{$resources	= $data._resources}
	<section class="activity latestActivity" id="latestActivitySection">
		<header class="titleBlock">
			<h3 class="title"><span class="value">{t}latest activity{/t}</span></h3>
		</header>
		{include file='common/blocks/admin/dashboard/latest/actions.tpl'}
		{include file='common/blocks/admin/dashboard/latest/connexions.tpl'}
	</section>
	
{/block}