{$relDisplayField=$field.relGetFields|default:$data.resources[$relResource].defaultNameField}
<select name="{$resourceFieldName}{$useArray}" id="{$resourceFieldName}{$itemIndex}" {if !$editable}disabled="disabled"{/if}>
	<option>&nbsp;</option>
	{foreach $data[$relResource] as $item}
	{$val=$item[$relField]}
	{$label=$item[$relDisplayField]|default:$item[$relField]}
	<option {if $smarty.post[$resourceFieldName] == $val || $resource[$fieldName] == $val}selected="selected"{/if} value="{$val}">{$label}</option>
	{/foreach}
</select>
<a class="adminLink addLink addRelatedItemsLink" href="{$smarty.const._URL_ADMIN}{$relResource}?method=create" data-relResource="{$relResource}" data-relGetFields="{$relDisplayField}">
	<span class="value">{t}add{/t}</span>
</a>
