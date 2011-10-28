{block name='adminRetrieveBlockHeader'}
<header class="header titleBlock">
	{block name='adminRetrieveBlockTitle'}
	<h2 class="title">
        <a href="{$smarty.const._URL_ADMIN}{$resourceName}" class="{$resourceName}" id="resourceName" data-singular="{$data.meta.singular}">
			{$resourceName} - {$data[$resourceName].id}
		</a>
	</h2>
	{/block}
	<span class="nav actions actionsBlock">
		{include file='common/blocks/admin/resource/actions/actions.tpl'}
	</span>
	{include file='common/blocks/admin/pagination/index.tpl' adminView='retrieve'}
</header>
{/block}

<div class="contentBlock">

	{block name='adminRetrieveContent'}
	<div class="block adminBlock adminRetrieveBlock" id="admin{$resourceName|capitalize}RetrieveBlock"> 
		
		{block name='adminResourceDetail'}
		<div class="resourceDetailBlock adminResourceDetailBlock" id="admin{$resourceName|capitalize}DetailBlock">		
			{include file='common/blocks/admin/resource/resourceDetail.tpl'}
		</div>
		{/block}
		
	</div>
	{/block}

</div>