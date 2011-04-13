<a class="action adminLink view viewLink {if $disabled}disabled{/if}" href="{if !$disabled}{$smarty.const._URL_ADMIN}{$resourceName}/{$resource.id}{else}#{/if}" title="{t}view the detail of this item{/t}">
<span class="key">&nbsp;</span>
	<span class="value">{t}view{/t}</span>
</a>