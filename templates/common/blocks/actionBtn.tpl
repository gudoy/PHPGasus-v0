{$type		= $btnType|default:$type|default:'button'}
{$href		= $btnHref|default:$href}
{$label		= $btnLabel|default:$label|escape:'html'}
{$id		= $btnId|default:$id}
{$value		= $btnValue|default:$value}
{$classes	= $btnClasses|default:$classes}
{$title		= $btnTitle|default:$title}
{if $mode === 'input'}
{elseif $mode === 'button'}
<div class="action actionBtn {$classes}" {if $id}id="{$id}"{/if}>
	<button type="{$type}" {if $btnValue}name="{$btnName}"{/if} {if $value}value="{$value}"{/if}>{$label}</button>
</div>
{else}
<a {if $id}id="{$id}"{/if} class="actionBtn {$classes}" {if $href}href="{$href}{if $smarty.get.redirect}?redirect={$smarty.get.redirect}{/if}"{/if} {if $title}title="{$title}"{/if}>
	<span class="value">{$label}</span>
</a>
{/if}