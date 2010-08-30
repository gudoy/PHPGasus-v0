{if $mode === 'input'}
{elseif $mode === 'button'}
<div class="actionBtn {$btnClasses}" {if $btnId}id="{$btnId}"{/if}>
	<button {if $btnValue}name="{$btnName}"{/if} type="{$btnType|default:'button'}" {*class="{$btnClasses}"*} {if $btnValue}value="{$btnValue}"{/if}>{$btnLabel|escape:'html'}</button>
</div>
{else}
<a {if $btnId}id="{$btnId}"{/if} class="actionBtn {$btnClasses}" href="{$btnHref}{if $smarty.get.redirect}?redirect={$smarty.get.redirect}{/if}" {if $btnTitle}title="{$btnTitle}"{/if}>
	<span class="label">{$btnLabel}</span>
</a>
{/if}