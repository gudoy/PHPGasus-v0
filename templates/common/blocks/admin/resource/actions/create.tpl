<a class="adminLink addLink {if $disabled}disabled{/if}" href="{if !$disabled}{$data.metas[$resourceName].fullAdminPath}?method=create{else}#{/if}" title="{t}create a new item{/t}">
<span class="key">&nbsp;</span>
<span class="value">{t}Create{/t}</span>
</a>