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
	<nav class="actions">
		<span class="or">{t}or{/t}</span>
		<a class="action adminLink add addLink addRelatedItemsLink" href="{$smarty.const._URL_ADMIN}{$relResource}?method=create" data-relResource="{$relResource}" data-relGetFields="{$relDisplayField}" title="{t 1=$relResourceSingular}add a new %1{/t}">
			<span class="value">{t}add{/t}</span>
		</a>
	</nav>
{elseif $data.total[$relResource] <= 100}
	<input type="search" name="{$resourceFieldName}{$useArray}" id="{$resourceFieldName}{$itemIndex}" {if !$editable}disabled="disabled"{/if}{if $isRequired} required="required"{/if} list="{$resourceFieldName}Options" placeholder="id{if $relDisplayField} or {$relDisplayField}{/if}"{if $curVal}value="{$curVal}"{/if} />
	<datalist id="{$resourceFieldName}Options">
		{foreach $data[$relResource] as $item}
		{$val 	= $item[$relField]}
		{$label = $item[$relDisplayField]|default:$item[$relField]}
		<option value="{$val}">{$val} - {$label}</option>
		{/foreach}
	</datalist>
	<nav class="actions">
		<span class="or">{t}or{/t}</span>
		<a class="action adminLink add addLink addRelatedItemsLink" href="{$smarty.const._URL_ADMIN}{$relResource}?method=create" data-relResource="{$relResource}" data-relGetFields="{$relDisplayField}" title="{t 1=$relResourceSingular}add a new %1{/t}">
			<span class="value">{t}add{/t}</span>
		</a>
	</nav>
{else}
	<div class="current">
		<span class="idValue{if !$curVal} empty{/if}">{$data[$resourceName][$fieldName]}</span>
		<span class="textValue{if !$curVal} empty{/if}">{if $curVal}{$data[$resourceName][$relGetAs]|default:"{'[untitled]'|gettext}"}{/if}</span>
		<nav class="actions">
			{if $curVal}
			{include file='common/blocks/actionBtn.tpl' id="edit{$fieldName|ucfirst}Btn" classes="action edit relItemSearchBtn" label={'edit'|gettext} title="{t 1=$relResourceSingular|default:$relResource}search %1{/t}"}
			{else}
			{include file='common/blocks/actionBtn.tpl' id="search{$fieldName|ucfirst}Btn" classes="action search relItemSearchBtn" label={'search'|gettext} title="{t 1=$relResourceSingular|default:$relResource}search %1{/t}"}
			{/if}
			{*
			<a class="action adminLink add addLink addRelatedItemsLink" href="{$smarty.const._URL_ADMIN}{$relResource}?method=create" data-relResource="{$relResource}" data-relGetFields="{$relDisplayField}" title="{t 1=$relResourceSingular}add a new %1{/t}">
				<span class="value">{t}add{/t}</span>
			</a>
			*}
		</nav>
	</div>
	<input type="hidden" name="{$resourceFieldName}{$useArray}" id="{$resourceFieldName}{$itemIndex}" {if !$editable}disabled="disabled"{/if}{if $isRequired} required="required"{/if} data-relresource="{$relResource}" />
{/if}