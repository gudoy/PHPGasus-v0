{extends file='specific/layout/page.tpl'}

{block name='pageContent'}

	{$resourceName=$data.view.resourceName}
	{$resource=$data[$resourceName]}

	<div class="box block grid_10">
		<h2>{t}data{/t}</h2>
		{if $data.success}
			{include file='common/blocks/api/resource/retrieve.tpl' items=$data[$data.view.resourceName]}
		{else}
			{include file='common/forms/admin/frmAdminResourceUpdate.tpl' viewMode='api'}
		{/if}
	</div>
	
	<div class="grid_6">		
		{include file='common/blocks/api/resource/dataModel.tpl'}
	</div>
	
{/block}