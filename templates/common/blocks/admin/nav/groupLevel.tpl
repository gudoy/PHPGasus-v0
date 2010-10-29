<ul class="{if $level === 1}menu{else}submenu{/if} level{$level}">
	{foreach $items as $key => $val}
	{if is_numeric($key)}{$name=$val}{else}{$name=$key}{/if}
	{if in_array($name, $data.current.user.auths.__can_display)}
	{$resource=$val}
	{if $level !== 1 || !$metas[$name].hasAncestors}
	<li class="item item-lv2 {if $data.current.resource === $name}ui-state-current{/if}">
		<a href="{$metas[$name].fullAdminPath}"><span class="value">{$metas[$name].displayName|default:$metas[$name].shortname|default:'Unknown name'}</span></a>
		{if $metas[$name].hasChildren}
		{include file='common/blocks/admin/nav/groupLevel.tpl' level=$level+1 items=$metas[$name].children}
		{/if}
	</li>
	{/if}
	{/if}
	{/foreach}
</ul>