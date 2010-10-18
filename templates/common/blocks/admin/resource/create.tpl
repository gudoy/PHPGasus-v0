<div class="box adminBlock adminCreateBlock" id="admin{$resourceName|capitalize}CreateBlock">
	
	<div class="titleBlock">
		<h2>
			<span class="{$resourceName}" id="resourceName">
				{$data.meta.displayName} - {t}New{/t}
			</span>
			<span class="{$data.meta.singular}" id="resourceSingular">&nbsp;</span>
		</h2>
		<span class="actionsBlock">
			{include file='common/blocks/admin/resource/actions/actions.tpl'}
		</span>
	</div>
	
	{include file='common/blocks/admin/pagination/index.tpl' adminView='create'}
	
	{*if $data.errors}
		{include file='config/errors.tpl'}
	{/if*}
	
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