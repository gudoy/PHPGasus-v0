<a class="action adminLink delete deleteLink {if $disabled}disabled{/if}" href="{if !$disabled}{$smarty.const._URL_ADMIN}{$resourceName}/{$resource.id}?method=delete{else}#{/if}">
<span class="key">&nbsp;</span>
	<span class="value">{t}delete{/t}</span>
</a>