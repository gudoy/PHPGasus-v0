{$possValues 	= Tools::toArray($field.possibleValues)|default:[]}
{$defValues 	= Tools::toArray($field.default)|default:[]}

{if $field.uiWidget === 'checkboxes'}
{if count($possValues) >= 3}
<a id="toggleAll{$resourceFieldName}{$itemIndex}" class="toggleAll">[{t}all / none{/t}]</a>
{/if}
{foreach $possValues as $item}
<div class="fieldItem">
	<input type="checkbox" class="multi checkbox" name="{$resourceFieldName}{$useArray}[]" id="{$resourceFieldName}{$itemIndex}-{$item}" {if !$editable}disabled="disabled"{/if}{if in_array($item, (array) $smarty.post[$resourceFieldName]) || in_array($item, (array) $resource[$fieldName]) || in_array($item, (array) $defValues)}checked="checked"{/if} value="{$item}" />
	<label class="span multi" for="{$resourceFieldName}{$itemIndex}[]">{$item}</label>
</div>
{/foreach}
{elseif $field.uiWidget === 'multiselect'}
<select name="{$resourceFieldName}{$useArray}[]" id="{$resourceFieldName}{$itemIndex}" {if !$editable}disabled="disabled"{/if}{if $isRequired} required="required"{/if} multiple="multiple">
	{foreach $possValues as $item}
	<option value="{$item}" {if in_array($item, (array) $smarty.post[$resourceFieldName]) || in_array($item, (array) $resource[$fieldName]) || in_array($item, (array) $defValues)}selected="selected"{/if}>{$item}</option>
	{/foreach}
</select>
<sub class="info">{t escape=no}hold <kbd>CTRL</kbd> to select several items{/t}</sub>
{else}
<input type="text" name="{$resourceFieldName}{$useArray}" id="{$resourceFieldName}{$itemIndex}" class="normal {if $field.check}check-{$field.check}{/if}" value="{join(',',(array) $postedVal)|default:{join(',',(array) $resource[$fieldName])}|default:{join(',',(array) $defValues)}}" {if $field.pk || !$editable || ($mode === 'create' && $field.computed && (!isset($field.forceEditable) || $field.forceEditable != true))}disabled="disabled"{/if}{if $isRequired} required="required"{/if} />
{/if}
