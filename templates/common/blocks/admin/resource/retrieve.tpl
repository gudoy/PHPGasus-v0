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

<div class="contentBlock">

	{block name="adminRetrieveContent"}
	<div class="block adminBlock adminRetrieveBlock" id="admin{$resourceName|capitalize}RetrieveBlock"> 
		
		{block name='adminResourceDetail'}
		<div class="resourceDetailBlock adminResourceDetailBlock" id="admin{$resourceName|capitalize}DetailBlock">		
			{include file='common/blocks/admin/resource/resourceDetail.tpl'}
		</div>
		{/block}
		
	</div>
	{/block}

</div>