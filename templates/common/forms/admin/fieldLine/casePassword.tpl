{$currentUser=$data.current.user}
{$updatedUser=$resource}
{if !empty($currentUser.group_slugs)}{$curUGroups=explode(',',$currentUser.group_slugs)}{else}{$curUGroups=[]}{/if}
{if !empty($updatedUser.group_slugs)}{$upUGroups=explode(',',$updatedUser.group_slugs)}{else}{$upUGroups=[]}{/if}

{if in_array('gods', $curUGroups) || ( in_array('superadmins', $curUGroups) && count(array_intersect((array) $upUGroups, array('gods','superadmins'))) )}
{$hasHigherAuth=true}
{* Deprecated *}
{else if $resourceName === 'users'}
	{if $currentUser.auth_level_nb > $resource['auth_level_nb' && $currentUser.auth_level_nb >= 500]}{$hasHigherAuth=true}{/if}
{/if}
{if $currentUser.id === $resource['id'] || $hasHigherAuth}
	{$allowEdit=true}
{else}
	{$allowEdit=false}
{/if}
<span class="icon inputIcon"></span><input type="password" name="{$resourceFieldName}{$useArray}" id="{$resourceFieldName}{$itemIndex}" class="sized" value="" {if $mode !== 'create'}disabled="disabled"{/if}{if $isRequired} required="required"{/if} autocomplete="off" />
{if $allowEdit && $mode !== 'create'}
{include file='common/blocks/actionBtn.tpl' mode='button' class="edit changeValBtn changePassBtn" id={'change'|cat:{$resourceFieldName|ucfirst}} label="{t}edit{/t}" type="button" dataAttrs=['altstate-label' => "{t}cancel{/t}", 'defaultstate-label' => "{t}edit{/t}"]}
{/if}