{$type=$btnType|default:'button'}
{$label=$btnLabel|escape:'html'}
{if $mode === 'input'}
{elseif $mode === 'button'}
<div class="actionBtn {$btnClasses}" {if $btnId}id="{$btnId}"{/if}>
	<button {if $btnValue}name="{$btnName}"{/if} type="{$type}" {if $btnValue}value="{$btnValue}"{/if}>{$label}</button>
</div>
{else}
<a {if $btnId}id="{$btnId}"{/if} class="actionBtn {$btnClasses}" href="{$btnHref}{if $smarty.get.redirect}?redirect={$smarty.get.redirect}{/if}" {if $btnTitle}title="{$btnTitle}"{/if}>
	<span class="label">{$label}</span>
</a>
{/if}