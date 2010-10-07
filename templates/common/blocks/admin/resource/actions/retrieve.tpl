<a class="adminLink viewLink {if $disabled}disabled{/if}" href="{if !$disabled}{$data.metas[$resourceName].fullAdminPath}{$resource.id}{else}#{/if}" title="{t}view the detail of this item{/t}">
<span class="key">&nbsp;</span>
	<span class="value">{t}View{/t}</span>
</a>