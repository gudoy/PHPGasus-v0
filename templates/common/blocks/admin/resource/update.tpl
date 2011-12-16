{block name='adminUpdateBlockHeader'}
<header class="titleBlock">
	{block name='adminUpdateBlockTitle'}
	<h2 class="title">
        <a href="{$smarty.const._URL_ADMIN}{$resourceName}" class="{$resourceName}" id="resourceName" data-singular="{$data.meta.singular}">
			{$resourceName} - {$data[$resourceName].id}
		</a>
	</h2>
	{/block}
	<span class="actions actionsBlock">
		{include file='common/blocks/admin/resource/actions/actions.tpl'}
	</span>
	{include file='common/blocks/admin/pagination/index.tpl' adminView='update'}
</header>
{/block}

<div class="contentBlock">

	{block name='adminUpdateContent'}
	<div class="block adminBlock adminUpdateBlock" id="admin{$resourceName|capitalize}UpdateBlock">
		
		{if $data.warnings}
			{include file='common/config/warnings.tpl'}
		{/if}
			
		{if $data.success}
		<div class="notifierBlock">
			<p class="notification success">
				{t}The resource has been successfully updated!{/t}
			</p>
		</div>
		{/if}
		{if $data[$resourceName]}
		{include file='common/forms/admin/frmAdminResourceUpdate.tpl' viewMode='admin' resource=$data[$resourceName]}
		{else}
		<p class="nodata">{t}No resource selected{/t}</p>
		{/if}
		
	</div>
	{/block}

</div>