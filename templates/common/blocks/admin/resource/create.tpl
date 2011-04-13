<header class="header titleBlock">
	<h2 class="title">
        <span class="{$resourceName}" id="resourceName" data-singular="{$data.meta.singular}">
			{$data.meta.displayName} - {t}Create New{/t}
		</span>
	</h2>
	<span class="nav actions actionsBlock">
		{include file='common/blocks/admin/resource/actions/actions.tpl'}
	</span>
	{include file='common/blocks/admin/pagination/index.tpl' adminView='create'}
</header>

<div class="box block adminBlock adminCreateBlock" id="admin{$resourceName|capitalize}CreateBlock" {*title="{$data.meta.displayName} - {t}Create New{/t}"*}>
	
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