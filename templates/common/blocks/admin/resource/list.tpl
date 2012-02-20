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