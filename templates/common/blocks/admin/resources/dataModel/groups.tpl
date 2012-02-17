{block name='adminDataModelGroupsBlock'}
<section class="block adminBlock adminDataModelBlock adminDataModelGroupsBlock" id="adminDataModelGroupsBlock">
	<header class="titleBlock">
		<h3 class="title">
			<span>dataModel groups</span>	
		</h3>
		<nav class="toolbar actions">
			{include file='common/blocks/actionBtn.tpl' id="dataModelGroupsFileLink" class="action file zip" href="{$smarty.const._URL_ADMIN}groups/file" label="{t}file{/t}"}
			{include file='common/blocks/actionBtn.tpl' id="dataModelGroupsCodeLink" class="action code" href="{$smarty.const._URL_ADMIN}groups/code" label="{t}code{/t}"}
		</nav>
	</header>
	<div class="content"></div>
</section>
{/block}