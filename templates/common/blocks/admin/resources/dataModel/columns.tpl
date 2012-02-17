{block name='adminDataModelColumnsBlock'}
<section class="block adminBlock adminDataModelBlock adminDataModelColumnsBlock" id="adminDataModelColumnsBlock">
	<header class="titleBlock">
		<h3 class="title">
			<span>dataModel columns</span>	
		</h3>
		<nav class="toolbar actions">
			{include file='common/blocks/actionBtn.tpl' id="dataModelColumnsFileLink" class="action file zip" href="{$smarty.const._URL_ADMIN}resourcescolumns/file" label="{t}file{/t}"}
			{include file='common/blocks/actionBtn.tpl' id="dataModelColumnsCodeLink" class="action code" href="{$smarty.const._URL_ADMIN}resourcescolumns/code" label="{t}code{/t}"}
		</nav>
	</header>
	<div class="content"></div>
</section>
{/block}