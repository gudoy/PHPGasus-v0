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