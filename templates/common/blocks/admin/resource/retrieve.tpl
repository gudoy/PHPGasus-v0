<header class="header titleBlock">
	<h2 class="title">
        <span class="{$resourceName}" id="resourceName" data-singular="{$data.meta.singular}">
			{$data.meta.displayName} - {$resourceId}
		</span>
	</h2>
	<span class="nav actions actionsBlock">
		{include file='common/blocks/admin/resource/actions/actions.tpl'}
	</span>
	{include file='common/blocks/admin/pagination/index.tpl' adminView='retrieve'}
</header>

<div class="box block adminBlock adminRetrieveBlock" id="admin{$resourceName|capitalize}RetrieveBlock" {*title="{$data.meta.displayName} - {$resourceId}"*}> 
	
	{block name='adminResourceDetail'}
	<div class="resourceDetailBlock adminResourceDetailBlock" id="admin{$resourceName|capitalize}DetailBlock">		
		{include file='common/blocks/admin/resource/resourceDetail.tpl'}
	</div>
	{/block}
	
</div>

{*
{if !$data.options.viewType || $data.options.viewType !== 'bubble'} 
{include file='common/blocks/admin/resource/retrieve/related.tpl'}
{/if}
*}