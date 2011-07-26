{block name='adminDataModelResourcesBlock'}
<section class="block adminBlock adminDataModelBlock adminDataModelResourcesBlock" id="adminDataModelResourcesBlock">
	<header class="titleBlock">
		<h3 class="title">
			<span>dataModel resources</span>	
		</h3>
		<nav class="toolbar actions">
			{include file='common/blocks/actionBtn.tpl' id="dataModelResourcesFileLink" class="action file zip" href="{$smarty.const._URL_ADMIN}resources/file" label='file'|gettext}
			{include file='common/blocks/actionBtn.tpl' id="dataModelResourcesCodeLink" class="action code" href="{$smarty.const._URL_ADMIN}resources/code" label='code'|gettext}
		</nav>
	</header>
	<div class="content"></div>
</section>
{/block}