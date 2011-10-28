{block name='adminIndexBlockHeader'}
<header class="titleBlock">
	{block name='adminIndexBlockTitle'}
	<h2 class="title">
		<a href="{$smarty.const._URL_ADMIN}{$resourceName}" class="{$resourceName}" id="resourceName" data-singular="{$data.meta.singular}">
			{$resourceName}
		</a>
	</h2>
	{/block}
	{if count($data[$resourceName]) || $data.total[$resourceName]}
    <span class="counts countsBlock" id="resourceCountsBlock">
        <span class="key">{t}counts{/t}</span>
        {if count($data[$resourceName])}
        <span class="displayedCount displayedResourcesCount" id="displayedResourcesCount">
            <span class="key">{t}displayed{/t}</span>
            <span class="value">{count($data[$resourceName])}</span>
        </span>
        {/if}
        {if $data.total[$resourceName]}
        <span class="totalCount totalResourcesCount" id="totalResourcesCount">
            <span class="key">{t}total{/t}</span>
            <span class="value">{$data.total[$resourceName]}</span>
        </span>
        {/if}
    </span>
	{/if}
</header>
{/block}

<div class="block adminBlock adminListBlock" id="admin{$resourceName|capitalize}ListBlock">
	{strip}
	
    {$curURL 		= $data.current.url}
    {if strpos($curURL,'?') !== false}{$linker = '&amp;'}{else}{$linker = '?'}{/if}
    {$curURLbase 	= "{$curURL|regex_replace:'/(.*)\\?(.*)$/U':'$1'}"}
    
    {/strip}
    {block name='adminIndexContent'}
	<div class="block adminListingBlock" id="admin{$resourceName|capitalize}Block">
		{include file='common/blocks/admin/resource/listTable.tpl'}
	</div>
	{/block}
</div>