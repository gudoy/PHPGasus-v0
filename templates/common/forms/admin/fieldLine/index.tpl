{$capFieldName 			= $fieldName|ucfirst}
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

{* For ADMIN *}
{* We do not want ontomany link to field to be displayed since we can't create them until we create the resource *}
{if $viewMode === 'admin'}
	{if $type === 'onetomany'}{$displayLine = false}{/if}
{/if} 

{if $displayLine}
<div id="{$fieldName}Field" class="line type{$field.type|ucfirst}{if $field.fk} typeOneToOne{/if}{if $field.subtype} subtype{$field.subtype|ucfirst}{/if}{if !$editable} disabled{/if}" data-type="{$field.type}" {if $field.from}data-from="{$field.from}"{/if} {if $field.required}data-required="1"{/if}>
	
	<div class="labelBlock{if $field.comment} hasInfos{/if}">
		{strip}
		{if $field.relResource && $field.type !== 'onetomany'}
			{*<label for="{$resourceFieldName}{$itemIndex}">{$data._resources[$field.relResource].singular|default:$field.displayName|default:$field.relResource|default:$fieldName|capitalize|replace:'_':' '}*}
			<label for="{$resourceFieldName}{$itemIndex}">{$field.displayName|default:$data._resources[$field.relResource].singular|default:$field.relResource|default:$fieldName|capitalize|replace:'_':' '}
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
	{if ( $type === 'onetoone' || $field.fk ) && ( $viewMode === 'admin' || $mode === 'create')}
		{include file='common/forms/admin/fieldLine/caseOneToOne.tpl'}
	{elseif $type === 'int' || $type === 'float'}
		{include file='common/forms/admin/fieldLine/caseInt.tpl'}
	{elseif $type == 'bool'}
		{include file='common/forms/admin/fieldLine/caseBool.tpl'}
	{elseif $type === 'text'}
		{include file='common/forms/admin/fieldLine/caseText.tpl'}
	{elseif $type == 'json'}
		{include file='common/forms/admin/fieldLine/caseJson.tpl'}		
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
	{elseif $type == 'set'}
		{include file='common/forms/admin/fieldLine/caseSet.tpl'}
	{elseif $type === 'varchar' && $field.subtype === 'email'}
		{include file='common/forms/admin/fieldLine/caseEmail.tpl'}
	{elseif $type === 'varchar' && $field.subtype === 'password'}
		{include file='common/forms/admin/fieldLine/casePassword.tpl'}
	{elseif $type === 'varchar' && ($field.subtype === 'file' || $field.subtype === 'fileDuplicate')}
		{include file='common/forms/admin/fieldLine/caseFile.tpl'}
	{elseif $type === 'onetomany'}
		{include file='common/forms/admin/fieldLine/caseOneToMany.tpl'}
	{else}
		{include file='common/forms/admin/fieldLine/caseVarchar.tpl'}
	{/if}
	
		{if $field.hint}
		<small class="hint"><span class="key">{t}hint{/t}{t}:{/t}</span><span class="value">{$field.hint}</span></small>
		{/if}
	</div>
	
</div>
{/if}