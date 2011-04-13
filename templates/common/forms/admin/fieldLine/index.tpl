{if $viewMode === 'api'}
{$resourceFieldName=$fieldName}
{else}
{$capFieldName=$fieldName|ucfirst}
{$resourceFieldName=$data.meta.singular|cat:$capFieldName}
{/if}

{if $multipleItems}
	{$useArray='[]'}
	{$itemIndex=$itemIndex}
	{$postedVal=$smarty.post[$resourceFieldName][$itemIndex]|default:null}
{else}
	{$useArray=''}
	{$itemIndex=''}
	{$postedVal=$smarty.post[$resourceFieldName]|default:null}
{/if}

<div class="line type{$field.type} {if $field.subtype}subtype{$field.subtype|ucfirst}{/if}" {if $field.from}data-from="{$field.from}"{/if}>
	
	<div class="labelBlock {if $field.comment}hasInfos{/if}">
		{strip}
		{if $field.relResource}
		<label for="{$resourceFieldName}{$itemIndex}">{$field.displayName|default:$field.relResource|default:$fieldName|capitalize|replace:'_':' '}
			{if isset($field.required) && $field.required || $field.pk}<span class="required">*</span>{/if}
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
	{$editable=$field.editable|default:true}
	{$type=$field.type}
	{if $field.relResource && $viewMode === 'admin'}
		{include file='common/forms/admin/fieldLine/caseRelation.tpl'}
	{elseif $type === 'int' || $type === 'float'}
		{include file='common/forms/admin/fieldLine/caseInt.tpl'}
	{elseif $type == 'bool'}
		{include file='common/forms/admin/fieldLine/caseBool.tpl'}
	{elseif $type === 'text'}
		{include file='common/forms/admin/fieldLine/caseText.tpl'}
	{elseif $type === 'timestamp'}
		{include file='common/forms/admin/fieldLine/caseTimestamp.tpl'}
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