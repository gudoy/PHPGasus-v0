{block name='adminRetrieveBlockHeader'}
<header class="header titleBlock">
	{block name='adminRetrieveBlockTitle'}
	<h2 class="title">
        <a href="{$smarty.const._URL_ADMIN}{$resourceName}" class="{$resourceName}" id="resourceName" data-singular="{$data._resources[$resourceName].singular}">
			{$resourceName} - {$resource.id}
		</a>
	</h2>
	{/block}
	<span class="nav actions actionsBlock">
		{include file='common/blocks/admin/resource/actions/actions.tpl'}
	</span>
</header>
{/block}

<div class="contentBlock">

	{block name='adminRetrieveContent'}
	<div class="block adminBlock adminRetrieveBlock" id="admin{$resourceName|capitalize}RetrieveBlock"> 
		
		{block name='adminResourceDetail'}
		<div class="resourceDetailBlock adminResourceDetailBlock" id="admin{$resourceName|capitalize}DetailBlock">		
			{include file='common/blocks/admin/resource/retrieve/retrieve.tpl'}
		</div>
		{/block}
		
	</div>
	{/block}
	
	{block name='adminRelatedItems'}
	<div class="resourcesBlock relatedResourcesBlock {$resourceName}RelatedResourcesBlock" id="{$resourceName}RelatedResourcesBlock">
		<header class="titleBlock">
			<h3 class="title">{t}Related items{/t}</h3>	
		</header>
		<div class="content">
			{include file='common/blocks/admin/resource/retrieve/related.tpl'}
		</div>
	</div>
	{/block}

</div>