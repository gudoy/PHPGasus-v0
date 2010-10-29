<select name="{$resourceFieldName}{$useArray}" id="{$resourceFieldName}{$itemIndex}" {if !$editable}disabled="disabled"{/if}>
	<option>&nbsp;</option>
	{foreach $data[$relResource] as $item}
	{$val=$item[$relField]}
	<option {if $smarty.post[$resourceFieldName] == $val || $resource[$fieldName] == $val}selected="selected"{/if} value="{$val}">{$item[$field.relGetFields]|default:$item[$relField]}</option>
	{/foreach}
</select>
