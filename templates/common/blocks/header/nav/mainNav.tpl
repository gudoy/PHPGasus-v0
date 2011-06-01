{$current = $view.current.menu|default:'home'}
<ul class="nav main" id="mainNav">
	{if $mainNavItems}
	{foreach $mainNavItems as $label => $url}{strip}
	<li id="{$label}NavItem" class="item item-lv1{if $url@first} first{/if}{if $url@last} last{/if}{if $current === $label} current{/if}">
		<a href="{$url}"><span class="value">{$label|gettext}</span></a>
	</li>
	{/strip}{/foreach}
	{else}
	<li class="item item-lv1 first{if $current === 'home'} current{/if}">
		<a href="{$smarty.const._URL}"><span class="value">{t}Home{/t}</span></a>
	</li><li class="item item-lv1{if $current === 'about'} current{/if}">
		<a href="{$smarty.const._URL_ABOUT}"><span class="value">{t}About{/t}</span></a>
	</li><li class="item last{if $current === 'contact'} current{/if}">
		<a href="{$smarty.const._URL_ABOUT_CONTACT}"><span class="value">{t}Contact{/t}</span></a>
	</li>
	{/if}
</ul>