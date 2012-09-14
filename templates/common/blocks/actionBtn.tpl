{strip}
{$type			= $type|default:'button'}
{$class 		= $class|default:$classes}
{$dataAttrs 	= $dataAttrs|default:[]}
{$dataOutput 	= ''}
{foreach $dataAttrs as $key => $val}
{if !is_numeric($key) && is_string($val)}
{$dataOutput = $dataOutput|cat:' data-'|cat:$key|cat:'="'|cat:$val|cat:'"'}
{/if}
{/foreach}
{if $mode === 'input'}
{elseif $mode === 'button'}
{/strip}
<div class="action actionBtn {$class}"{if $id} id="{$id}"{/if}{if $title} title="{$title}"{/if}{$dataOutput}><button type="{$type}"{if $name} name="{$name}"{/if}{if $value} value="{$value}"{/if}>{$label|escape:'html'}</button></div>
{else}
<a {if $id}id="{$id}"{/if} class="actionBtn {$class}" {if $href}href="{$href}{if $smarty.get.redirect}?redirect={$smarty.get.redirect}{/if}"{/if}{if $title} title="{$title}"{/if} {$dataOutput}><span class="value">{$label|escape:'html'}</span></a>
{/if}