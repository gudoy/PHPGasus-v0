{block name='adminDataModelResourcesBlock'}
<section class="block adminBlock adminDataModelBlock adminDataModelResourcesBlock" id="adminDataModelResourcesBlock">
	<header class="groupTitle">
		<h3 class="title">
			<span>dataModel resources</span>	
		</h3>
		<nav class="toolbar actions">
			{include file='common/blocks/actionBtn.tpl' id="dataModelResourcesFileLink" class="action file zip" href="{$smarty.const._URL_ADMIN}resources/file" label="{t}file{/t}"}
			{include file='common/blocks/actionBtn.tpl' id="dataModelResourcesCodeLink" class="action code" href="{$smarty.const._URL_ADMIN}resources/code" label="{t}code{/t}"}
		</nav>
	</header>
	<div class="content"></div>
</section>
{/block}