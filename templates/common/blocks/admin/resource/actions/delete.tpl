<a class="adminLink deleteLink {if $disabled}disabled{/if}" href="{if !$disabled}{$data.metas[$resourceName].fullAdminPath}{$resource.id}?method=delete{else}#{/if}" title="{t}delete this item{/t}">
<span class="key">&nbsp;</span>
	<span class="value">{t}Delete{/t}</span>
</a>