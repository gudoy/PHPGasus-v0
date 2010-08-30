{if (isset($postedVal) && ($postedVal == 1 || $postedVal === true || $postedVal == 't')) 
		|| ($resource[$fieldName] == 1 || $resource[$fieldName] === true || $resource[$fieldName] == 't')}
{assign var='checked' value=true}
{elseif (isset($postedVal) && ($postedVal == 0 || $postedVal === false || $postedVal == 'f')) 
		|| ($resource[$fieldName] == 0 || $resource[$fieldName] === false || $resource[$fieldName] == 'f')}
{assign var='checked' value=false}
{elseif (isset($field.default) && $field.default === true)}
{assign var='checked' value=true}
{/if}
<label class="span multi" for="{$resourceFieldName}{$itemIndex}Y">{t}Yes{/t}</label>
<input type="radio" name="{$resourceFieldName}{$useArray}" id="{$resourceFieldName}{$itemIndex}Y" class="check multi" {if $checked}checked="checked"{/if} {if !$editable}disabled="disabled"{/if} value="1" />
<label class="span multi" for="{$resourceFieldName}{$itemIndex}N">{t}No{/t}</label>
<input type="radio" name="{$resourceFieldName}{$useArray}" id="{$resourceFieldName}{$itemIndex}N" class="check multi" {if !$checked}checked="checked"{/if} {if !$editable}disabled="disabled"{/if}  value="0" />