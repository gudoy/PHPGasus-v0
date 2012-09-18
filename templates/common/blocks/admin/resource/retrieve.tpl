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
		<nav class="resources resourcesNav" role="navigation">
		{include file='common/blocks/admin/resource/retrieve/related.tpl'}
		</nav>
	</div>
	{/block}

</div>