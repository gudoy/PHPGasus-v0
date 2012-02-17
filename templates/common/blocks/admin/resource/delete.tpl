{block name='adminDeleteBlockHeader'}
<header class="header titleBlock">
	{block name='adminDeleteBlockTitle'}
	<h2 class="title">
		<a href="{$smarty.const._URL_ADMIN}{$resourceName}" class="{$resourceName}" id="resourceName" data-singular="{$data.meta.singular}">
			{$resourceName} - {$data[$resourceName].id}
		</a>
	</h2>
	{/block}
	<span class="actions actionsBlock">
		{include file='common/blocks/admin/resource/actions/actions.tpl'}
	</span>
	{include file='common/blocks/admin/pagination/index.tpl' adminView='delete'}
</header>
{/block}

<div class="contentBlock">

	{block name='adminDeleteContent'}
	<div class="block adminBlock adminDeleteBlock" id="admin{$resourceName|capitalize}DeleteBlock">
		
		{if $data.warnings}
			{include file='common/config/warnings.tpl'}	
		{/if}
			
		{if $data.success}
		<div class="notificationsBlock">
			<p class="notification success">
				{t}The resource has been successfully deleted!{/t}
			</p>
			<div class="buttonsLine">
				{include file='common/blocks/actionBtn.tpl' id='continueBtn' href=$smarty.const._URL_ADMIN|cat:$resourceName label="{t}continue{/t}"}	
			</div>
		</div>
		{else}
			{if $data[$resourceName]}
			{include file='common/forms/admin/frmAdminResourceDelete.tpl' viewMode='admin' resource=$data[$resourceName]}
			{else}
			<p class="nodata">{t}No resource selected{/t}</p>
			{/if}
		{/if}
		
	</div>
	{/block}

</div>