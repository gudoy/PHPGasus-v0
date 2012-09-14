{$rProps 		= $data._resources[$resourceName]}
{$rModel 		= $data.dataModel[$resourceName]}
{$imageField 	= $rProps.imageField}
{$nameField 	= $rProps.nameField|default:$rProps.defaultNameField}
{$descField 	= $rProps.descField|default:null}
{$userResPerms 	= $data.current.user.auths[$resourceName]}
{$crudability 	= $rProps.crudability|default:'CRUD'}
{$isCreatable 	= (strpos($crudability, 'C')>-1)?1:0}
{$isReadable 	= (strpos($crudability, 'R')>-1)?1:0}
{$isUpdatable 	= (strpos($crudability, 'U')>-1)?1:0}
{$isDelatable 	= (strpos($crudability, 'D')>-1)?1:0}
{$isDuplicable 	= ($isCreatable && $isUpdatable)?1:0}
<div class="block adminBlock adminListBlock" id="admin{$resourceName|capitalize}ListBlock">
	{strip}
	
    {$curURL 		= $data.current.url}
    {if strpos($curURL,'?') !== false}{$linker = '&amp;'}{else}{$linker = '?'}{/if}
    {$curURLbase 	= "{$curURL|regex_replace:'/(.*)\\?(.*)$/U':'$1'}"}
    {$displayMode 	= $smarty.get.displayMode|default:$data._resources[$resourceName].displayMode|default:'grid'}
    
    {$getFields = Tools::toArray($data.options.getFields)}
    {$data.options.displayCols = array_merge(array_combine((array) $getFields, (array) $getFields), $data.options.displayCols)}
    
    {/strip}
    {block name='adminIndexContent'}
	<div class="block adminListingBlock {$displayMode}Mode" id="admin{$resourceName|capitalize}Block">
		{if $displayMode === 'thumbs' || $smarty.const._APP_ADMIN_LIST_DEFAULT_DISPLAY_MODE === 'thumbs'}
		{include file='common/blocks/admin/resource/list/listThumbs.tpl'}
		{elseif $displayMode === 'list' || $smarty.const._APP_ADMIN_LIST_DEFAULT_DISPLAY_MODE === 'list'}
		{include file='common/blocks/admin/resource/list/listThumbs.tpl'}
		{elseif $displayMode === 'table' || $displayMode === 'grid' || $smarty.const._APP_ADMIN_LIST_DEFAULT_DISPLAY_MODE === 'table'}
		{include file='common/blocks/admin/resource/list/listTable.tpl'}
		{else}
		{include file='common/blocks/admin/resource/list/listTable.tpl'}
		{/if}
	</div>
	{/block}
</div>