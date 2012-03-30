{$rProps 		= $data._resources[$resourceName]}
{$rModel 		= $data.dataModel[$resourceName]}
{$imageField 	= $rProps.imageField}
{$nameField 	= $rProps.nameField|default:$rProps.defaultNameField}
{$descField 	= $rProps.descField|default:null}
{$userResPerms 	= $data.current.user.auths[$resourceName]}
{$crudability 	= $rProps.crudability|default:'CRUD'}
{$isReadable 	= (strpos($crudability, 'R')>-1)?1:0}
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
		{elseif $displayMode === 'list'}
		{include file='common/blocks/admin/resource/list/listThumbs.tpl'}
		{elseif $displayMode === 'table' || $displayMode === 'grid'}
		{include file='common/blocks/admin/resource/list/listTable.tpl'}
		{else}
		{include file='common/blocks/admin/resource/list/listTable.tpl'}
		{/if}
	</div>
	{/block}
</div>