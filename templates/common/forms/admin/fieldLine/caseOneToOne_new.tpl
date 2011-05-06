{strip}

{$displayAs 			= $field.relGetAs|default:$field.relField}
{$relResource 			= $field.relResource}
{$relResourceSingular 	= $data._resources[$field.relResource].singular}
{$relField 				= $field.relField}
{$relDisplayField 		= $field.relGetFields|default:$data._resources[$relResource].defaultNameField}
{$relGetAs 				= $field.relGetAs|default:$relDisplayField}
{$curVal 				= $postedVal|default:$data[$resourceName][$fieldName]}

{/strip}
{if $data.total[$relResource] <= 25}
	<select name="{$resourceFieldName}{$useArray}" id="{$resourceFieldName}{$itemIndex}" {if !$editable}disabled="disabled"{/if}{if $isRequired} required="required"{/if}>
		<option>&nbsp;</option>
		{* $void = sort($data[$relResource]) *}
		{foreach $data[$relResource] as $item}
		{$val 	= $item[$relField]}
		{$label = $item[$relDisplayField]|default:$item[$relField]}
		<option {if $smarty.post[$resourceFieldName] == $val || $curVal === $val}selected="selected"{/if} value="{$val}">{$label}</option>
		{/foreach}
	</select>
{elseif $data.total[$relResource] <= 100}
	<input type="search" name="{$resourceFieldName}{$useArray}" id="{$resourceFieldName}{$itemIndex}" {if !$editable}disabled="disabled"{/if}{if $isRequired} required="required"{/if} list="{$resourceFieldName}Options" placeholder="id{if $relDisplayField} or {$relDisplayField}{/if}"{if $curVal}value="{$curVal}"{/if} />
	<datalist id="{$resourceFieldName}Options">
		{foreach $data[$relResource] as $item}
		{$val 	= $item[$relField]}
		{$label = $item[$relDisplayField]|default:$item[$relField]}
		<option value="{$val}">{$val} - {$label}</option>
		{/foreach}
	</datalist>
{else}
	<div class="current">
		{if $curVal}
		<span class="idValue">{$data[$resourceName][$fieldName]}</span>
		<span class="textValue">{$data[$resourceName][$relGetAs]|default:"{'[untitled]'|gettext}"}</span>
		{/if}
		<nav class="actions">
			{if $curVal}
			{include file='common/blocks/actionBtn.tpl' id="edit{$fieldName|ucfirst}Btn" classes="action edit relItemSearchBtn" label={'edit'|gettext} title="{t 1=$relResourceSingular|default:$relResource}search %1{/t}"}
			{/if}
			<a class="action adminLink add addLink addRelatedItemsLink" href="{$smarty.const._URL_ADMIN}{$relResource}?method=create" data-relResource="{$relResource}" data-relGetFields="{$relDisplayField}">
				<span class="value">{t}add{/t}</span>
			</a>
		</nav>		
	</div>
	<input type="hidden" name="{$resourceFieldName}{$useArray}" id="{$resourceFieldName}{$itemIndex}" {if !$editable}disabled="disabled"{/if}{if $isRequired} required="required"{/if} data-relresource="{$relResource}" />
{/if}