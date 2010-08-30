<select name="{$resourceFieldName}{$useArray}" id="{$resourceFieldName}{$itemIndex}" {if !$editable}disabled="disabled"{/if}>
	<option value="">&nbsp;</option>
	{foreach name='relOptions' from=$field.possibleValues item='value'}
	<option value="{$value}" {if (isset($smarty.post.$resourceFieldName) && $smarty.post.$resourceFieldName === $value) || $resource[$fieldName] === $value || (!empty($field.default) && $field.default === $value)}selected="selected"{/if}>{$value}</option>
	{/foreach}
</select>