{strip}

{$displayAs 			= $field.relGetAs|default:$field.relField}
{$relResource 			= $field.relResource}
{$relResourceSingular 	= $data._resources[$field.relResource].singular}
{$relField 				= $field.relField}
{$relDisplayField 		= $field.relGetFields|default:$data._resources[$relResource].defaultNameField}
{$relGetAs 				= $field.relGetAs|default:$relDisplayField}
{$curVal 				= $postedVal|default:$resource[$fieldName]}

{/strip}
{if $data.total[$relResource] <= 25 || $field.uiWidget === 'select'}
	<select name="{$resourceFieldName}{$useArray}" id="{$resourceFieldName}{$itemIndex}" {if !$editable}disabled="disabled"{/if}{if $isRequired} required="required"{/if}>
		<option>&nbsp;</option>
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
{elseif $data.total[$relResource] < 100 || $field.uiWidget === 'datalist'}
	<input type="search" name="{$resourceFieldName}{$useArray}" id="{$resourceFieldName}{$itemIndex}" {if !$editable}disabled="disabled"{/if}{if $isRequired} required="required"{/if} list="{$resourceFieldName}Options" placeholder="id{if $relDisplayField} or {$relDisplayField}{/if}"{if $curVal}value="{$curVal}"{/if} autocomplete="off" />
	<datalist id="{$resourceFieldName}Options">
	<!--[if !IE]><!-->
	<select name="{$resourceFieldName}{$useArray}" id="{$resourceFieldName}{$itemIndex}" {if !$editable}disabled="disabled"{/if}{if $isRequired} required="required"{/if}>
	<!--<![endif]-->
		{foreach $data[$relResource] as $item}
		{$val 	= $item[$relField]}
		{$label = $item[$relDisplayField]|default:$item[$relField]}
		<option value="{$val}">{$val}&nbsp;-&nbsp;{$label}</option>
		{/foreach}
	<!--[if !IE]><!-->
	</select><!--<![endif]-->
	</datalist>
	<nav class="actions">
		<span class="or">{t}or{/t}</span>
		<a class="action adminLink add addLink addRelatedItemsLink" href="{$smarty.const._URL_ADMIN}{$relResource}?method=create" data-relResource="{$relResource}" data-relGetFields="{$relDisplayField}" title="{t 1=$relResourceSingular}add a new %1{/t}">
			<span class="value">{t}add{/t}</span>
		</a>
	</nav>
{else}
	<div class="current">
		<span class="idValue{if !$curVal} empty{/if}">{$resource[$fieldName]}</span>
		<span class="textValue{if !$curVal} empty{/if}">{if $curVal}{$resource[$relGetAs]|default:"{t}[untitled]{/t}"}{/if}</span>
		<nav class="actions">
			{if $curVal}
			{include file='common/blocks/actionBtn.tpl' id="edit{$fieldName|ucfirst}Btn" class="action edit relItemSearchBtn" label="{t}edit{/t}" title="{t 1=$relResourceSingular|default:$relResource}search %1{/t}"}
			{else}
			{include file='common/blocks/actionBtn.tpl' id="search{$fieldName|ucfirst}Btn" class="action search relItemSearchBtn" label="{t}search{/t}" title="{t 1=$relResourceSingular|default:$relResource}search %1{/t}"}
			{/if}
			{*
			<a class="action adminLink add addLink addRelatedItemsLink" href="{$smarty.const._URL_ADMIN}{$relResource}?method=create" data-relResource="{$relResource}" data-relGetFields="{$relDisplayField}" title="{t 1=$relResourceSingular}add a new %1{/t}">
				<span class="value">{t}add{/t}</span>
			</a>
			*}
		</nav>
	</div>
	<input type="hidden" name="{$resourceFieldName}{$useArray}" id="{$resourceFieldName}{$itemIndex}" {if !$editable}disabled="disabled"{/if}{if $isRequired} required="required"{/if} data-relresource="{$relResource}" {if isset($curVal)}value="{$curVal}{/if}" />
{/if}