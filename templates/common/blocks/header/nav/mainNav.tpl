{$current=$view.current.menu|default:'home'}
<ul class="nav main section" id="mainNav">
	{if $mainNavItems}
	{foreach $mainNavItems as $label => $url}{strip}
	<li class="{if $url@first}first{elseif $url@last}last{/if}{if $current === $label}current{/if}">
		<a href="{$url}">{$label|gettext}</a>
	</li>
	{/strip}{/foreach}
	{else}
	<li class="first {if $current === 'home'}current{/if}">
		<a href="{$smarty.const._URL}">{t}Home{/t}</a>
	</li><li class="{if $current === 'about'}current{/if}">
		<a href="{$smarty.const._URL_ABOUT}">{t}About{/t}</a>
	</li><li class="{if $current === 'references'}current{/if}">
		<a href="{$smarty.const._URL_REFERENCES}">{t}References{/t}</a>
	</li><li class="last {if $current === 'contact'}current{/if}">
		<a href="{$smarty.const._URL_ABOUT_CONTACT}">{t}Contact{/t}</a>
	</li>
	{/if}
</ul>