{extends file='specific/layout/page.tpl'}

{block name='pageContent'}

	{$resourceName=$data.view.resourceName}

	<div class="box block grid_10">
		
		<h2>{t}data{/t}</h2>
		
	{if $data.success}
		<div class="notificationsBlock">
			<p class="notification success">
				{t}The resource has been successfully created!{/t}
			</p>
		</div>
			
		{include file='common/blocks/api/resource/retrieve.tpl' items=$data[$data.view.resourceName]}
	{else}
		{include file='common/forms/admin/frmAdminResourceCreate.tpl' viewMode='api'}
	{/if}
	
	</div>
	
	<div class="grid_6">		
		{include file='common/blocks/api/resource/dataModel.tpl'}
	</div>
	
{/block}