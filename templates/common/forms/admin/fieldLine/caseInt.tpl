{if count($field.possibleValues) > 0}
<select name="{$resourceFieldName}{$useArray}" id="{$resourceFieldName}{$itemIndex}" {if !$editable}disabled="disabled"{/if}>
	{foreach name='relOptions' from=$field.possibleValues key='key' item='value'}
	<option value="{$key}" {if (isset($smarty.post.$resourceFieldName) && $smarty.post.$resourceFieldName == $key) || $resource[$fieldName] == $key}selected="selected"{/if}>{$value}</option>
	{/foreach}
</select>
{else}
{if $mode === 'create'}			
	{$value=$postedVal|default:$field.default|escape|stripslashes|replace:',':'.'}
{else}						
	{$value=$postedVal|default:$resource[$fieldName]|stripslashes|replace:',':'.'}
{/if}
<input type="number" pattern="[0-9{if $field.type === 'float'}\.\s\,{/if}]{ldelim}0,{$field.length|default:11}{rdelim}" step="{$field.step|default:'any'}" name="{$resourceFieldName}{$useArray}" id="{$resourceFieldName}{$itemIndex}" {if $field.length}maxlength="{$field.length}"{/if} class="normal {if $field.check}check-{$field.check}{/if}" value="{$value}" {if $field.pk || !$editable || ($mode === 'create' && $field.computed && (!isset($field.forceEditable) || $field.forceEditable != true))}disabled="disabled"{/if} />
{/if}