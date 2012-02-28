<div class="block adminBlock adminListBlock" id="admin{$resourceName|capitalize}ListBlock">
	{strip}
	
    {$curURL 		= $data.current.url}
    {if strpos($curURL,'?') !== false}{$linker = '&amp;'}{else}{$linker = '?'}{/if}
    {$curURLbase 	= "{$curURL|regex_replace:'/(.*)\\?(.*)$/U':'$1'}"}
    {$displayMode 	= $smarty.get.displayMode|default:$data._resources[$resourceName].displayMode|default:'grid'}
    
    {/strip}
    {block name='adminIndexContent'}
	<div class="block adminListingBlock {$displayMode}Mode" id="admin{$resourceName|capitalize}Block">
		{if $displayMode === 'thumbs'}
		{include file='common/blocks/admin/resource/list/listThumbs.tpl'}
		{else}
		{include file='common/blocks/admin/resource/list/listTable.tpl'}
		{/if}
	</div>
	{/block}
</div>