<a class="adminLink duplicateLink {if $disabled}disabled{/if}" href="{if !$disabled}{$data.metas[$resourceName].fullAdminPath}{$resource.id}?method=duplicate{else}#{/if}" title="{t}duplicate this item{/t}">
<span class="key">&nbsp;</span>
	<span class="value">{t}Duplicate{/t}</span>
</a>