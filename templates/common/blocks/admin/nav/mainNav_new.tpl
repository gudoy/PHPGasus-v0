{nocache}
<ul class="nav section main level1" id="adminMainNav">
	<li class="item item-lv1 {if empty($data.current.menu) || $data.current.menu === 'dashboard'}current{/if}">
		<a href="{$smarty.const._URL_ADMIN}">{'admin home'|gettext|ucfirst|escape:'html'}</a>
	</li>
	{foreach $data.resources as $k => $v}
	<li>
		<a href="{$smarty.const._URL_ADMIN}{$key}/">{$v.displayName|default:$key}</a>
	</li>
	{/foreach}
	{*
	<li class="item item-lv1 {if $data.current.menu === 'setup'}current{/if}">
		<a href="{$smarty.const._URL_ADMIN}">{'setup'|gettext}</a>
	</li>
	*}
</ul>
{/nocache}