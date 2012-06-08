<select name="{$resourceFieldName}{$useArray}" id="{$resourceFieldName}{$itemIndex}" {if !$editable}disabled="disabled"{/if}{if $isRequired} required="required"{/if}>
	{$value = $postedVal|default:$resource[$fieldName]|default:$field.default}
	{if !$value}
	<option>&nbsp;</option>
	{/if}
	{foreach $field.possibleValues as $item}
	<option value="{$item}" {if $value === $item}selected="selected"{/if}>{$item}</option>
	{/foreach}
</select>