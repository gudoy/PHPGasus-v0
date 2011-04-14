{$currentUser=$data.current.user}
{$updatedUser=$resource}
{if !empty($currentUser.group_admin_titles)}{$curUGroups=explode(',',$currentUser.group_admin_titles)}{else}{$curUGroups=[]}{/if}
{if !empty($updatedUser.group_admin_titles)}{$upUGroups=explode(',',$updatedUser.group_admin_titles)}{else}{$upUGroups=[]}{/if}

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
<input type="password" name="{$resourceFieldName}{$useArray}" id="{$resourceFieldName}{$itemIndex}" class="sized" value="" {if $mode !== 'create'}disabled="disabled"{/if}{if $isRequired} required="required"{/if} />
{if $allowEdit && $mode !== 'create'}
{include file='common/blocks/actionBtn.tpl' mode='button' btnClasses="changeValBtn changePassBtn" btnId={'change'|cat:{$resourceFieldName|ucfirst}} btnLabel={'change'|gettext}}
{/if}