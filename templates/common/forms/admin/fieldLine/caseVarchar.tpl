{if count($field.possibleValues) > 0}
<select name="{$resourceFieldName}{$useArray}" id="{$resourceFieldName}{$itemIndex}" {if !$editable}disabled="disabled"{/if}>
	{foreach name='relOptions' from=$field.possibleValues key='key' item='value'}
	<option value="{$key}" {if (isset($smarty.post.$resourceFieldName) && $smarty.post.$resourceFieldName == $key) || $resource[$fieldName] == $key}selected="selected"{/if}>{$value}</option>
	{/foreach}
</select>
{else}
{if $mode === 'create'}			
	{$value=$postedVal|default:$field.default|escape|stripslashes}
{else}
	{if $field.subtype === 'url'}
	{$value=$postedVal|default:$resource[$fieldName]|replace:'&':'&amp;'|stripslashes}			
	{else}			
	{$value=$postedVal|default:$resource[$fieldName]|stripslashes}
	{/if}
{/if}
<input type="text" name="{$resourceFieldName}{$useArray}" id="{$resourceFieldName}{$itemIndex}" {if $field.length}maxlength="{$field.length}"{/if} class="normal {if $field.check}check-{$field.check}{/if}" value="{$value}" {if $field.pk || !$editable || ($mode === 'create' && $field.computed && (!isset($field.forceEditable) || $field.forceEditable != true))}disabled="disabled"{/if} />
{/if}