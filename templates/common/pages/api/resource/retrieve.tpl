{extends file='specific/layout/page.tpl'}

{block name='pageContent'}

	{$resourceName=$data.view.resourceName}

	<div class="box block grid_10">
		<h2>{t}data{/t}</h2>
		{$items=$items|default:$data[$resourceName]}
		<div class="apiItemBlock">
			{include file='common/blocks/api/resource/retrieve.tpl'}
		</div>
	</div>
	
	<div class="grid_6">		
		{include file='common/blocks/api/resource/dataModel.tpl'}
	</div>
	
{/block}


