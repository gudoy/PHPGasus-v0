{$useGroups         = $smarty.const._APP_USE_RESOURCESGROUPS|default:false}
{$resources         = $data._resources}
{$resourcesGroups   = $data._resourcesGroups}
{if $useGroups && $resourcesGroups}
<ul class="nav section main nav-lv1" id="adminMainNav">
    {block name='adminMainNavItems'}
    <li id="dashboardNavItem" class="item item-lv1{if empty($data.current.menu) || $data.current.menu === 'dashboard'} current{/if}">
        <a href="{$smarty.const._URL_ADMIN}"><span class="value">{'dashboard'|gettext}</span></a>
    </li>
    {foreach $resourcesGroups as $gpName => $gpProps}
    {$gpDisplayName     = $gpProps.displayName|default:$gpName}
    {$gpAuthResources   = array_intersect((array) $gpProps.resources, (array) $data.current.user.auths.__can_display)}
    {if $gpProps.resources && !empty($gpAuthResources)}
    <li id="{$gpName}NavItem" class="item item-lv1 resourceGroupItem{if empty($data.current.menu) || $data.current.menu === 'dashboard'} current{/if}">
        <a class="resourcesGroup" id="{$gpName}ResourcesGroupLink"><span class="value">{$gpDisplayName}</span></a>
        {if $gpProps.resources}
        <ul class="nav nav-lv2 resourcesGroupList" id="{$gpName}ResourcesList">
        {foreach $gpProps.resources as $k => $v}
        {strip}
        
            {$rName         = "{if is_numeric($k)}$v{else}$k{/if}"}
            {$rAdminURL     = "{$smarty.const._URL_ADMIN}{$rName}/"}
            {$rProps        = $resources[$rName]}
            {$rType         = $rProps.type|default:'native'}
            {$rDisplayName  = $rProps.displayName|default:$rName}
            {$rUsed         = "{if $rType === 'filter' && $rProps.extends}$rProps.extends{else}$rName{/if}"}
            
            {if in_array($rUsed, $data.current.user.auths.__can_display)}
            <li class="item item-lv2 resourceItem {if $rName === $data.current.resource}current{/if}"><a href="{$rAdminURL}"><span class="value">{$rDisplayName}</span></a></li>
            {/if}
        {/strip}
        {/foreach}
        </ul>
        {/if}
    </li>
    {/if}
    {/foreach}
    {/block}
</ul>
{else}
<ul class="nav section main level1" id="adminMainNav">{strip}
    {block name='adminMainNavItems'}
    <li class="item item-lv1 {if empty($data.current.menu) || $data.current.menu === 'dashboard'}current{/if}">
        <a href="{$smarty.const._URL_ADMIN}">{'admin home'|gettext|ucfirst|escape:'html'}</a>
    </li>
    {foreach $resources as $k => $v}
    {if is_numeric($k) && isset($v['name'])}{$k=$v.name}{/if}
    {$type =$v.type|default:'native'}
    {*if $type === 'filter'}{$usedResource=$v.extends}{else}{$usedResource=$k}{/if*}
    {$usedResource=$k}
    {if in_array($usedResource, $data.current.user.auths.__can_display)}
    <li class="item item-lv1 {if $k === $data.current.resource}current{/if}"><a href="{$smarty.const._URL_ADMIN}{$k}/"><span class="value">{$v.displayName|default:$k}</span></a></li>
    {/if}
    {/foreach}
    {/block}
{/strip}</ul>
{/if}