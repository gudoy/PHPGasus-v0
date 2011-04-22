{extends file='specific/layout/page.tpl'}

{block name='mainContent'}

	{$resourceName = $data.current.resource}
	{$isAdminView = in_array('admin', explode(' ',$view.smartclasses))}
	
	{if $data.success}
		<div class="notificationsBlock">
			<p class="notification success">
				{t}The resource has been successfully updated!{/t}
			</p>
		</div>
		{include file='common/blocks/api/resource/retrieve.tpl' item=$data[$resourceName]}
	{else}
		{include file='common/forms/admin/frmAdminResourceUpdate.tpl' viewMode='api' resource=$data[$resourceName]}
	{/if}
	
{/block}