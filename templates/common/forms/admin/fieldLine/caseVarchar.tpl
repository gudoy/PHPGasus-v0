{if count($field.possibleValues) > 0}
<select name="{$resourceFieldName}{$useArray}" id="{$resourceFieldName}{$itemIndex}" {if !$editable}disabled="disabled"{/if}>
	{foreach name='relOptions' from=$field.possibleValues key='key' item='value'}
	<option 
		value="{$key}"
		{if (isset($smarty.post.$resourceFieldName) && $smarty.post.$resourceFieldName == $key) || $resource[$fieldName] == $key}selected="selected"{/if}
		>
		{$value}
	</option>
	{/foreach}
</select>
{else}
<input {strip}
	type="text"{/strip} {strip} 
	name="{$resourceFieldName}{$useArray}"{/strip} {strip}  
	id="{$resourceFieldName}{$itemIndex}"{/strip} {strip}
	{if $field.length}maxlength="{$field.length}"{/if}{/strip} {strip}
	class="normal {if $field.check}check-{$field.check}{/if}"{/strip} {strip}
	value="{strip}
		{if $mode === 'create'}			
			{$postedVal|default:$field.default|escape|stripslashes}
		{else}
			{if $field.subtype === 'url'}
			{$postedVal|default:$resource[$fieldName]|replace:'&':'&amp;'|stripslashes}			
			{else}			
			{$postedVal|default:$resource[$fieldName]|stripslashes}
			{/if}
		{/if}
	{/strip}"{/strip} {strip}
	{if $field.pk || !$editable || ($mode === 'create' && $field.computed && (!isset($field.forceEditable) || $field.forceEditable != true))}disabled="disabled"{/if}{/strip} />
{/if}