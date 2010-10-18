{extends file='specific/layout/page.tpl'}

{block name='pageContent'}

	{$resourceName=$data.view.resourceName}

	<div class="box block grid_10">
		<h2>{t}data{/t}</h2>
		{foreach $data[$data.view.resourceName] as $item}
			{include file='common/pages/api/resource/retrieve.tpl' items=$item}
		{foreachelse}
			<p>
			{t}Sorry, there's currently no items for this resource{/t}
			</p>
		{/foreach}
	</div>
	
	<div class="grid_6">		
		{include file='common/blocks/api/resource/dataModel.tpl'}
	</div>
	
{/block}