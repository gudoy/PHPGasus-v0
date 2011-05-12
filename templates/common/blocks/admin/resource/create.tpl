<header class="header titleBlock">
	<h2 class="title">
		<a href="{$smarty.const._URL_ADMIN}{$resourceName}" class="{$resourceName}" id="resourceName" data-singular="{$data.meta.singular}">
			{$resourceName} - {t}new{/t}
		</a>
	</h2>
	<span class="nav actions actionsBlock">
		{include file='common/blocks/admin/resource/actions/actions.tpl'}
	</span>
	{include file='common/blocks/admin/pagination/index.tpl' adminView='create'}
</header>

<div class="contentBlock">

	{block name='adminCreateContent'}
	<div class="block adminBlock adminCreateBlock" id="admin{$resourceName|capitalize}CreateBlock">
		
		{if $data.warnings}
			{include file='common/config/warnings.tpl'}		
		{/if}
			
		{if $data.success}
		<div class="notificationsBlock">
			<p class="notification success">
				{t}The resource has been successfully created!{/t}
			</p>
		</div>
		{/if}
	
		{include file='common/forms/admin/frmAdminResourceCreate.tpl' viewMode='admin'}
		
	</div>
	{/block}

</div>