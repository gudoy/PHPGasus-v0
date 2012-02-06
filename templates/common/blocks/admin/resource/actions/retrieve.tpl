<a class="action adminLink view viewLink {if $disabled}disabled{/if}" href="{if !$disabled}{$smarty.const._URL_ADMIN}{$resourceName}/{$resource.id}{else}#{/if}">
<span class="key">&nbsp;</span>
	<span class="value">{t}view{/t}</span>
</a>