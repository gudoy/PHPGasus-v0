<a class="action adminLink edit editLink {if $disabled}disabled{/if}" href="{if !$disabled}{$smarty.const._URL_ADMIN}{$resourceName}/{$resource.id}?method=update{else}#{/if}">
<span class="key">&nbsp;</span>
	<span class="value">{t}edit{/t}</span>
</a>