<a class="action adminLink add addLink {if $disabled}disabled{/if}" href="{if !$disabled}{$smarty.const._URL_ADMIN}{$resourceName}?method=create{else}#{/if}" title="{t}create a new item{/t}">
<span class="key">&nbsp;</span>
<span class="value">{t}create{/t}</span>
</a>