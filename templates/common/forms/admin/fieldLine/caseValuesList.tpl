{* TODO: decprecated, use enum instead *}
<select name="{$resourceFieldName}{$useArray}" id="{$resourceFieldName}{$itemIndex}" {if !$editable}disabled="disabled"{/if}>
{if count($field.possibleValues) > 0}
	{foreach name='relOptions' from=$field.possibleValues item='value'}
	<option 
		value="{$value}"
		{if (isset($smarty.post.$resourceFieldName) && $smarty.post.$resourceFieldName == $value) || $resource[$fieldName] == $value}selected="selected"{/if}
		>
		{$value}
	</option>
	{/foreach}
{/if}
</select>