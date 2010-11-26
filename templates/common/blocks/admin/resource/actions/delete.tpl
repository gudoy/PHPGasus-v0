<a class="adminLink deleteLink {if $disabled}disabled{/if}" href="{if !$disabled}{$smarty.const._URL_ADMIN}{$resourceName}/{$resource.id}?method=delete{else}#{/if}" title="{t}delete this item{/t}">
<span class="key">&nbsp;</span>
	<span class="value">{t}Delete{/t}</span>
</a>