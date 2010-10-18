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
	type="{if $html5}number{else}text{/if}"{/strip} {strip}
	{if $html5}pattern="[0-9{if $field.type === 'float'}\.\s\,{/if}]{ldelim}0,{$field.length|default:11}{rdelim}"{/if}
	{*if $html5}step="{if $field.type === 'float'}0.01{else}1{/if}"{/if*}
	{if $html5}step="{$field.step|default:'1'}"{/if}
	name="{$resourceFieldName}{$useArray}"{/strip} {strip}  
	id="{$resourceFieldName}{$itemIndex}"{/strip} {strip}
	{if $field.length}maxlength="{$field.length}"{/if}{/strip} {strip}
	class="normal {if $field.check}check-{$field.check}{/if}"{/strip} {strip}
	value="{strip}
		{if $mode === 'create'}			
			{$postedVal|default:$field.default|escape|stripslashes|replace:',':'.'}
		{else}						
			{$postedVal|default:$resource[$fieldName]|stripslashes|replace:',':'.'}
		{/if}
	{/strip}"{/strip} {strip}
	{if $field.pk || !$editable || ($mode === 'create' && $field.computed && (!isset($field.forceEditable) || $field.forceEditable != true))}disabled="disabled"{/if}{/strip} />
{/if}