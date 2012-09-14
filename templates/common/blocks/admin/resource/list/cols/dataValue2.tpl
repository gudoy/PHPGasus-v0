{if is_array($colVal)}
{count($colVal)} {$colName}
{elseif $cProps.fk}
{$relResource 	= $cProps.relResource}
{$relField 		= $cProps.relField}
<a class="relResourceLink" href="{$smarty.const._URL_ADMIN}{$relResource}/{$colVal}">{$resource[{$cProps.relGetAs|default:$cProps.relGetFields}]|default:$colVal}</a>
{else if $cType === 'url' || $cProps.subtype === 'url'}
<a class="uri" href="{if strpos($colVal, $smarty.const._APP_PROTOCOL) === false}{$cProps.prefix|default:$smarty.const._URL}{/if}{$colVal}">http../{$colVal|regex_replace:"/.*\//":""}</a>
{else if $cType === 'json'}
{if strlen($colVal) > 40}{$colVal|truncate:40:'...':true:true}{else}{$colVal}{/if}
{else if $cType === 'text'}
{if strlen($colVal) > 40}{$colVal|truncate:40:'...':true:true}{else}{$colVal}{/if}
{elseif in_array($cType, array('timestamp','datetime','date','time'))}
{include file='common/blocks/humanTime.tpl' class='datetimeField' value=$colVal}
{elseif in_array($cType, array('bool','boolean'))}
{$valid = in_array($colVal, array(1,true,'1','true','t'), true)}
<span class="validity {if !$valid}in{/if}valid">{if $valid}{t}yes{/t}{else}{t}no{/t}{/if}</span>
{else}
{if $request.pattern === 'column'}{$colVal|default:"{t}[no value]{/t}"}{else}{$colVal}{/if}
{/if}