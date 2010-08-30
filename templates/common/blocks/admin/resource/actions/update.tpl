<a class="adminLink editLink {if $disabled}disabled{/if}" href="{if !$disabled}{$data.metas[$resourceName].fullAdminPath}{$resource.id}?method=update{else}#{/if}" title="{t}edit this item{/t}">
<span class="key">&nbsp;</span>
	<span class="value">{t}Edit{/t}</span>
</a>