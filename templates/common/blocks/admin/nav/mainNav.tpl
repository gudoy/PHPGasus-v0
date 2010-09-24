{if count($data.resourceGroups)}
<ul class="nav section main level1" id="adminMainNav">
	<li class="item item-lv1 {if empty($data.current.resourceGroup)}current{/if}">
		<a href="{$smarty.const._URL_ADMIN}">{'Admin dashboard'|gettext|ucfirst|escape:'html'}</a>
	</li>
	{foreach $data.resourceGroups as $key => $val}
	<li class="item item-lv1 {if $key === $data.current.resourceGroup}current{/if}">
		<a href="{$smarty.const._URL_ADMIN}{$key}/">{$val.displayName|default:$key|default:'unknow groupname'|ucfirst|escape:'html'}</a>
	</li>
	{/foreach}
</ul>
{/if}