<a class="action adminLink edit editLink {if $disabled}disabled{/if}" href="{if !$disabled}{$smarty.const._URL_ADMIN}{$resourceName}/{$resource.id}?method=update{else}#{/if}" title="{t}edit this item{/t}">
<span class="key">&nbsp;</span>
	<span class="value">{t}edit{/t}</span>
</a>