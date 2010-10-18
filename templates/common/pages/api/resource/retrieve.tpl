{extends file='specific/layout/page.tpl'}

{block name='pageContent'}

	{$resourceName=$data.view.resourceName}

	<div class="box block grid_10">
		<h2>{t}data{/t}</h2>
		{$items=$items|default:$data[$resourceName]}
		{if count($items)}
		<div class="apiItemBlock">
		{foreach $items as $key => $val}
			<span class="key">{$key}</span>:<span class="value">{$val|regex_replace:'/&([^#]|$)/':'$1&amp;$2'}</span><br/>
		{/foreach}
		</div>
		{else}
		{/if}
	</div>
	
	<div class="grid_6">		
		{include file='common/blocks/api/resource/dataModel.tpl'}
	</div>
	
{/block}


