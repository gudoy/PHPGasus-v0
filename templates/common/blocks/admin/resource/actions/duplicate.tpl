<a class="action adminLink duplicate duplicateLink {if $disabled}disabled{/if}" href="{if !$disabled}{$smarty.const._URL_ADMIN}{$resourceName}/{$resource.id}?method=duplicate{else}#{/if}" title="{t}duplicate this item{/t}">
<span class="key">&nbsp;</span>
	<span class="value">{t}duplicate{/t}</span>
</a>