{$capFieldName 			= $fieldName|ucfirst}
{*$resourceFieldName 	= $data.meta.singular|cat:$capFieldName*}
{$resourceFieldName 	= $data._resources[$resourceName].singular|cat:$capFieldName}
{$isRequired 			= ($field.required || $field.pk)}
{$editable 				= $field.editable|default:true}
{$type 					= $field.type}
{$displayLine 			= true}

{* For API *}
{* we do not want onetomany fields to be displayed *}
{if $viewMode === 'api'}
	{$resourceFieldName = $fieldName}
	{if $type === 'onetomany'}{$displayLine = false}{/if}
{/if} 

{if $multipleItems}
	{$useArray 	= '[]'}
	{$itemIndex = $itemIndex}
	{$postedVal = $smarty.post[$resourceFieldName][$itemIndex]|default:null}
{else}
	{$useArray 	= ''}
	{$itemIndex = ''}
	{$postedVal = $smarty.post[$resourceFieldName]|default:null}
{/if}


{if $displayLine}
<div id="{$fieldName}Field" class="line type{$field.type|ucfirst}{if $field.fk} typeOneToOne{/if}{if $field.subtype} subtype{$field.subtype|ucfirst}{/if}{if !$editable} disabled{/if}"{if $field.from} data-from="{$field.from}"{/if}>
	
	<div class="labelBlock{if $field.comment} hasInfos{/if}">
		{strip}
		{if $field.relResource}
		<label for="{$resourceFieldName}{$itemIndex}">{$data._resources[$field.relResource].singular|default:$field.displayName|default:$field.relResource|default:$fieldName|capitalize|replace:'_':' '}
			{if $isRequired}<span class="required">*</span>{/if}
		</label>
		{elseif $field.type == 'bool' || $field.subtype === 'fakebool'}
		<span class="label">{$fieldName|capitalize|replace:'_':' '}{if $field.required}<span class="required">*</span>{/if}</span>
		{else}
		<label for="{$resourceFieldName}{$itemIndex}">{$fieldName|capitalize|replace:'_':' '}
			{if $field.required}<span class="required">*</span>{/if}
		</label>
		{/if}
		{if $field.comment}
		<small class="comment infos">
			<span class="detail">({$field.comment|default:'Sorry, no data explanation'})</span>
		</small>
		{/if}
		{if $field.subtype === 'file'}
		<span class="additional">{t}accept{/t}{t}:{/t} {$field.allowedTypes}</span>
		{/if}
		{/strip}
	</div>
	
	<div class="fieldBlock">
	{if $field.relResource && ( $viewMode === 'admin' || $mode === 'create')}
		{* include file='common/forms/admin/fieldLine/caseRelation.tpl' *}
		{include file='common/forms/admin/fieldLine/caseOneToOne_new.tpl'}
	{elseif $type === 'int' || $type === 'float'}
		{include file='common/forms/admin/fieldLine/caseInt.tpl'}
	{elseif $type == 'bool'}
		{include file='common/forms/admin/fieldLine/caseBool.tpl'}
	{elseif $type === 'text'}
		{include file='common/forms/admin/fieldLine/caseText.tpl'}
	{elseif $type == 'tel'}
		{include file='common/forms/admin/fieldLine/caseTel.tpl'}
	{elseif $type === 'timestamp'}
		{include file='common/forms/admin/fieldLine/caseTimestamp.tpl'}
	{elseif $type === 'date'}
		{include file='common/forms/admin/fieldLine/caseDate.tpl'}
	{elseif $type === 'datetime'}
		{include file='common/forms/admin/fieldLine/caseDatetime.tpl'}
	{elseif $type == 'enum'}
		{include file='common/forms/admin/fieldLine/caseEnum.tpl'}
	{elseif $type === 'varchar' && $field.subtype === 'password'}
		{include file='common/forms/admin/fieldLine/casePassword.tpl'}
	{elseif $type === 'varchar' && ($field.subtype === 'file' || $field.subtype === 'fileDuplicate')}
		{include file='common/forms/admin/fieldLine/caseFile.tpl'}
	{elseif $type === 'onetomany'}
		{include file='common/forms/admin/fieldLine/caseOneToMany.tpl'}
	{else}
		{include file='common/forms/admin/fieldLine/caseVarchar.tpl'}
	{/if}
	
	</div>
	
</div>
{/if}