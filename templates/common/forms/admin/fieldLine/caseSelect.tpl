<select name="{$resourceFieldName}{$useArray}" id="{$resourceFieldName}{$itemIndex}" {if !$editable}disabled="disabled"{/if}>
{if count($field.possibleValues) > 0}
	{foreach name='relOptions' from=$field.possibleValues key='key' item='value'}
	<option 
		value="{$key}"
		{if (isset($smarty.post.$resourceFieldName) && $smarty.post.$resourceFieldName == $key) || $resource[$fieldName] == $key}selected="selected"{/if}
		>
		{$value}
	</option>
	{/foreach}
{else}
	<option value="0">{t}None{/t}</option>
	{foreach name='relOptions' from=$data[$field.relResource] item='item'}
	<option 
		value="{$item[$field.relField]}"
		{if (isset($smarty.post.$resourceFieldName) && $smarty.post.$resourceFieldName === $item[$field.relField]) || $resource[$fieldName] === $item[$field.relField]}selected="selected"{/if}
		>{$item.id} - {$item.title}
	</option>
	{/foreach}
{/if}
</select>