{$useAclV2=$smarty.const._APP_USE_ACL_V2|default:false}
{$resources=$data.resources}
<ul class="nav section main level1" id="adminMainNav">
    <li class="item item-lv1 {if empty($data.current.menu) || $data.current.menu === 'dashboard'}current{/if}">
        <a href="{$smarty.const._URL_ADMIN}">{'admin home'|gettext|ucfirst|escape:'html'}</a>
    </li>
    {foreach $resources as $k => $v}
    {$type=$v.type|default:'native'}
    {if $type === 'filter'}{$usedResource=$v.extends}{else}{$usedResource=$k}{/if}
    {if !$useAclV2 || in_array($usedResource, $data.current.user.auths.__can_display)}
    <li>
        <a href="{$smarty.const._URL_ADMIN}{$k}/">{$v.displayName|default:$k}</a>
    </li>
    {/if}
    {/foreach}
</ul>