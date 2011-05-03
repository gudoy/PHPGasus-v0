{strip}

{$displayAs 			= $field.relGetAs|default:$field.relField}
{$relResource 			= $field.relResource}
{$relResourceSingular 	= $data._resources[$field.relResource].singular}
{$relField 				= $field.relField}
{$relDisplayField 		= $field.relGetFields|default:$data._resources[$relResource].defaultNameField}

{/strip}
{if $data.total[$relResource] <= 12}
<select name="{$resourceFieldName}{$useArray}" id="{$resourceFieldName}{$itemIndex}" {if !$editable}disabled="disabled"{/if}{if $isRequired} required="required"{/if}>
	<option>&nbsp;</option>
	{* $void = sort($data[$relResource]) *}
	{foreach $data[$relResource] as $item}
	{$val 	= $item[$relField]}
	{$label = $item[$relDisplayField]|default:$item[$relField]}
	<option {if $smarty.post[$resourceFieldName] == $val || $resource[$fieldName] == $val}selected="selected"{/if} value="{$val}">{$label}</option>
	{/foreach}
</select>
{elseif $data.total[$relResource] <= 100}
<input type="search" name="{$resourceFieldName}{$useArray}" id="{$resourceFieldName}{$itemIndex}" {if !$editable}disabled="disabled"{/if}{if $isRequired} required="required"{/if} list="{$resourceFieldName}Options" placeholder="id{if $relDisplayField} or {$relDisplayField}{/if}" />
<datalist id="{$resourceFieldName}Options">
	{* $void = sort($data[$relResource]) *}
	{foreach $data[$relResource] as $item}
	{$val 	= $item[$relField]}
	{$label = $item[$relDisplayField]|default:$item[$relField]}
	<option value="{$val}">{$val} - {$label}</option>
	{/foreach}
</datalist>
{else}
<input type="search" name="{$resourceFieldName}{$useArray}" id="{$resourceFieldName}{$itemIndex}" {if !$editable}disabled="disabled"{/if}{if $isRequired} required="required"{/if} data-relresource="{$relResource}" />
{include file='common/blocks/actionBtn.tpl' classes="relItemSearchBtn" mode='button' label='search' title="{t 1=$relResourceSingular|default:$relResource}search %1{/t}"}
{/if}
<span class="or">{t}or{/t}</span>
<a class="action adminLink add addLink addRelatedItemsLink" href="{$smarty.const._URL_ADMIN}{$relResource}?method=create" data-relResource="{$relResource}" data-relGetFields="{$relDisplayField}">
	<span class="value">{t}add{/t}</span>
</a>