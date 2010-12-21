{$useAclV2=$smarty.const._APP_USE_ACL_V2|default:false}
{$resources=$data._resources}
<ul class="nav section main level1" id="adminMainNav">{strip}
    <li class="item item-lv1 {if empty($data.current.menu) || $data.current.menu === 'dashboard'}current{/if}">
        <a href="{$smarty.const._URL_ADMIN}">{'admin home'|gettext|ucfirst|escape:'html'}</a>
    </li>
    {foreach $resources as $k => $v}
    {if is_numeric($k) && isset($v['name'])}{$k=$v.name}{/if}
    {$type=$v.type|default:'native'}
    {*if $type === 'filter'}{$usedResource=$v.extends}{else}{$usedResource=$k}{/if*}
    {$usedResource=$k}
    {if !$useAclV2 || in_array($usedResource, $data.current.user.auths.__can_display)}
    <li class="item item-lv1 {if $k === $data.current.resource}current{/if}"><a href="{$smarty.const._URL_ADMIN}{$k}/">{$v.displayName|default:$k}</a></li>
    {/if}
    {/foreach}
{/strip}</ul>