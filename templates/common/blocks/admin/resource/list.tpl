<header class="titleBlock">
	<h2 class="title">
		<span class="{$resourceName}" id="resourceName" data-singular="{$data.meta.singular}">
			{$data.meta.displayName}
		</span>
	</h2>
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

<div class="box block adminBlock adminListBlock" id="admin{$resourceName|capitalize}ListBlock" {*title="{$data.meta.displayName} - {$resourceId}"*}>
	{strip}
	
    {$curURL = $data.current.url}
    {if strpos($curURL,'?') !== false}{$linker='&amp;'}{else}{$linker='?'}{/if}
    {$curURLbase="{$curURL|regex_replace:'/(.*)\\?(.*)$/U':'$1'}"}
	
    {* Pagination params *}
    {if $smarty.get.offset}{$paginationParams=$paginationParams|cat:'&offset='|cat:$smarty.get.offset}{/if}
    {if $smarty.get.limit}{$paginationParams=$paginationParams|cat:'&limit='|cat:$smarty.get.limit}{/if}
    
    {/strip}
	<div class="block adminListingBlock" id="admin{$resourceName|capitalize}Block">
		{include file='common/blocks/admin/resource/listTable.tpl'}
	</div>
	
</div>