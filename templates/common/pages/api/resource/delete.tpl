{extends file='specific/layout/page.tpl'}

{block name='mainContent'}

	{$resourceName=$data.view.resourceName}

	<div class="box block grid_10">
		
		<h2>{t}data{/t}</h2>
		
	{if $data.success}
		<div class="notifierBlock">
			<p class="notification success">
				{t}The resource has been successfully delete!{/t}
			</p>
		</div>
	{/if}
	
	</div>
	
	<div class="grid_6">		
		{include file='common/blocks/api/resource/dataModel.tpl'}
	</div>
	
{/block}