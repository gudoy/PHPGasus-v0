<div class="box block adminBlock adminUpdateBlock" id="admin{$resourceName|capitalize}UpdateBlock">
	
	<div class="titleBlock">
		<h2>
			<span class="{$resourceName}" id="resourceName">
				{$data.meta.displayName} - {$resourceId}
			</span>
			<span class="{$data.meta.singular}" id="resourceSingular">&nbsp;</span>
		</h2>
		<span class="actionsBlock">
			{include file='common/blocks/admin/resource/actions/actions.tpl'}
		</span>
	</div>
	
	{include file='common/blocks/admin/pagination/index.tpl' adminView='update'}
	
	{*if $data.errors}
		{include file='config/errors.tpl'}
	{/if*}
	
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
	{include file='common/forms/admin/frmAdminResourceUpdate.tpl' viewMode='admin' resource=$data.$resourceName}
	
</div>