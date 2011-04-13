<select name="{$resourceFieldName}{$useArray}" id="{$resourceFieldName}{$itemIndex}" {if !$editable}disabled="disabled"{/if}>
	<option value="">&nbsp;</option>
	{foreach $field.possibleValues as $value}
	<option value="{$value}" {if $smarty.post[$resourceFieldName] === $value || $resource[$fieldName] === $value || $field.default === $value}selected="selected"{/if}>{$value}</option>
	{/foreach}
</select>