<input type="datetime" name="{$resourceFieldName}{$useArray}" id="{$resourceFieldName}{$itemIndex}" {if $field.length}maxlength="{$field.length}"{/if} class="normal datetime{if $field.check} check-{$field.check}{/if}" value="{$postedVal|default:$resource[$fieldName]|default:$field.default|default:$smarty.now|date_format:"%Y-%m-%dT%H:%M:%S.0Z"}" placeholder="{$field.placeholder|default:'YYYY-MM-DDThh:mm:ss.0Z'}" {if !$editable}disabled="disabled"{/if}{if $isRequired} required="required"{/if} />