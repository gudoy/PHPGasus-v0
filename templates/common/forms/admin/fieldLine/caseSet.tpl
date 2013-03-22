{$possValues 	= Tools::toArray($field.possibleValues)|default:[]}
{$defValues 	= Tools::toArray($field.default)|default:[]}

{if $field.uiWidget === 'checkboxes'}
{if count($possValues) >= 3}
<div class="fieldItem">
	<input type="checkbox" class="multi checkbox toggleAll" name="{$resourceFieldName}{$useArray}[]" id="{$resourceFieldName}{$itemIndex}-none" {if !$editable}disabled="disabled"{/if}{if empty($smarty.post[$resourceFieldName]) && empty($resource[$fieldName]) && empty($defValues)}checked="checked"{/if} value="" />
	<label class="span multi" for="{$resourceFieldName}{$itemIndex}-none" data-altvalue="{t}all{/t}"><a class="toggleAll" id="toggleAll{$resourceFieldName}{$itemIndex}">{t}none{/t}</a></label>
</div>
{/if}
{foreach $possValues as $item}
{if $mode === 'create'}			
	{$active=in_array($item, (array) $defValues)}
{else}
	{$active=(in_array($item, (array) $smarty.post[$resourceFieldName]) || in_array($item, (array) $resource[$fieldName]))}
{/if}
<div class="fieldItem">
	<input type="checkbox" class="multi checkbox" name="{$resourceFieldName}{$useArray}[]" id="{$resourceFieldName}{$itemIndex}-{$item}" {if !$editable}disabled="disabled"{/if}{if $active}checked="checked"{/if} value="{$item}" />
	<label class="span multi" for="{$resourceFieldName}{$itemIndex}-{$item}">{$item}</label>
</div>
{/foreach}
{elseif $field.uiWidget === 'multiselect'}
<select name="{$resourceFieldName}{$useArray}[]" id="{$resourceFieldName}{$itemIndex}" {if !$editable}disabled="disabled"{/if}{if $isRequired} required="required"{/if} multiple="multiple">
	<option value="none">[none]</option>
	{foreach $possValues as $item}
	{if $mode === 'create'}			
		{$active=in_array($item, (array) $defValues)}
	{else}
		{$active=(in_array($item, (array) $smarty.post[$resourceFieldName]) || in_array($item, (array) $resource[$fieldName]))}
	{/if}
	<option value="{$item}" {if $active}selected="selected"{/if}>{$item}</option>
	{/foreach}
</select>
<sub class="info">{t escape=no}hold <kbd>CTRL</kbd> to select several items{/t}</sub>
{else}
<input type="text" name="{$resourceFieldName}{$useArray}" id="{$resourceFieldName}{$itemIndex}" class="normal {if $field.check}check-{$field.check}{/if}" value="{join(',',(array) $postedVal)|default:{join(',',(array) $resource[$fieldName])}|default:{join(',',(array) $defValues)}}" {if $field.pk || !$editable || ($mode === 'create' && $field.computed && (!isset($field.forceEditable) || $field.forceEditable != true))}disabled="disabled"{/if}{if $isRequired} required="required"{/if} />
{/if}
