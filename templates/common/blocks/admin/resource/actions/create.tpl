<a class="action adminLink add addLink {if $disabled}disabled{/if}" href="{if !$disabled}{$smarty.const._URL_ADMIN}{$resourceName}?method=create{else}#{/if}">
<span class="key">&nbsp;</span>
<span class="value">{t}create{/t}</span>
</a>