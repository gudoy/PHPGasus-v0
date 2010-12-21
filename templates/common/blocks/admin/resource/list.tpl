{strip}
<div class="block box adminBlock adminListBlock" id="admin{$resourceName|capitalize}ListBlock" title="{$data.meta.displayName} - {$resourceId}">
	<h2>
		<span class="{$resourceName}" id="resourceName">
			{$data.meta.displayName}
		</span>
		<span class="{$data.meta.singular}" id="resourceSingular">&nbsp;</span>
	</h2>
	
    {$curURL=$data.current.url}
    {if strpos($curURL,'?') !== false}{$linker='&amp;'}{else}{$linker='?'}{/if}
    {$curURLbase={$curURL|regex_replace:'/(.*)\?(.*)/U':'$1'}|cat:{http_build_query($data.current.urlParams)}|cat:$linker}
	
    {* Pagination params *}
    {if $smarty.get.offset}{$paginationParams=$paginationParams|cat:'&offset='|cat:$smarty.get.offset}{/if}
    {if $smarty.get.limit}{$paginationParams=$paginationParams|cat:'&limit='|cat:$smarty.get.limit}{/if}
	
	{* Filtering params *}
	{if $smarty.const._APP_USE_ADMIN_LIST_FILTERS_V2}
	{else}
        {if $smarty.get.by}{assign var='filteringParams' value=$filteringParams|cat:'&by='|cat:$smarty.get.by}{/if}
        {if $smarty.get.by && $smarty.get.values}{assign var='filteringParams' value=$filteringParams|cat:'&values='|cat:$smarty.get.values}{/if}
        {if $smarty.get.operation && $smarty.get.values}{assign var='filteringParams' value=$filteringParams|cat:'&operation='|cat:$smarty.get.operation}{/if}
	
    	{if (isset($view.displayFilters) && $view.displayFilters) || !isset($data.view.displayFilters)}
    	{include file='common/blocks/admin/filter.tpl'}
    	{/if}
    {/if}

    {if !defined('_APP_USE_LIST_PAGINATION_V2') || !_APP_USE_LIST_PAGINATION_V2}
	{include file='common/blocks/admin/pagination/index.tpl' vPosition='top'}
	{/if}
	<div class="block adminListingBlock" id="admin{$resourceName|capitalize}Block">
		{include file='common/blocks/admin/resource/listTable.tpl'}
	</div>
	{if !defined('_APP_USE_LIST_PAGINATION_V2') || !_APP_USE_LIST_PAGINATION_V2}
	{include file='common/blocks/admin/pagination/index.tpl' vPosition='bottom'}
	{/if}
	
</div>
{/strip}