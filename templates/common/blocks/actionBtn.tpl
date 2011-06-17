{$type		= $type|default:'button'}
{if $mode === 'input'}
{elseif $mode === 'button'}
<div class="action actionBtn {$classes}"{if $id} id="{$id}"{/if}{if $title} title="{$title}"{/if}>
	<button type="{$type}" {if $value}name="{$name}"{/if} {if $value}value="{$value}"{/if}>{$label|escape:'html'}</button>
</div>
{else}
<a {if $id}id="{$id}"{/if} class="actionBtn {$classes}" {if $href}href="{$href}{if $smarty.get.redirect}?redirect={$smarty.get.redirect}{/if}"{/if}{if $title} title="{$title}"{/if}><span class="value">{$label|escape:'html'}</span></a>
{/if}