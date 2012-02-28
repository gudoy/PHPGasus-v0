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
			{include file='common/forms/admin/frmAdminResourceDelete.tpl' viewMode='admin'}
			{else}
			<p class="nodata">{t}No resource selected{/t}</p>
			{/if}
		{/if}
		
	</div>
	{/block}

</div>