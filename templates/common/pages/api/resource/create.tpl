{extends file='specific/layout/page.tpl'}

{block name='mainColContent'}

	{$resourceName = $data.current.resource}
	{$isAdminView = in_array('admin', explode(' ',$view.smartclasses))}
		
	{if $data.success}
		<div class="notificationsBlock">
			<p class="notification success">
				{t}The resource has been successfully created!{/t}
			</p>
		</div>
		{include file='common/blocks/api/resource/retrieve.tpl' item=$data[$resourceName]}
	{else}
		{include file='common/forms/admin/frmAdminResourceCreate.tpl' viewMode='api' mode='create'}
	{/if}
	
{/block}