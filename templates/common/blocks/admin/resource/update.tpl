<div class="contentBlock">

	{block name='adminUpdateContent'}
	<div class="block adminBlock adminUpdateBlock admin{$resourceName|capitalize}UpdateBlock" id="admin{$resourceName|capitalize}UpdateBlock">
		
		{if $data.warnings}
			{include file='common/config/warnings.tpl'}
		{/if}
			
		{if $data.success}
		<div class="notificationsBlock">
			<p class="notification success">
				{t}The resource has been successfully updated!{/t}
			</p>
		</div>
		{/if}
		{block name='adminUpdateForm'}
		{if $data[$resourceName]}
		{include file='common/forms/admin/frmAdminResourceUpdate.tpl' viewMode='admin'}
		{else}
		<p class="nodata">{t}No resource selected{/t}</p>
		{/if}
		{/block}
		
	</div>
	{/block}

</div>