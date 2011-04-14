<select name="{$resourceFieldName}{$useArray}" id="{$resourceFieldName}{$itemIndex}" {if !$editable}disabled="disabled"{/if}{if $isRequired} required="required"{/if}>
	<option value="">&nbsp;</option>
	{foreach $field.possibleValues as $item}
	<option value="{$item}" {if $smarty.post[$resourceFieldName] === $item || $resource[$fieldName] === $item || $field.default === $item}selected="selected"{/if}>{$item}</option>
	{/foreach}
</select>