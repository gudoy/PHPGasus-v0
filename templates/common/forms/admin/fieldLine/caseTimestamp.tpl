{if $smarty.const._USE_HTML5 && $data.browser.name === 'opera' && $data.browser.version >= 9}
<input type="datetime" name="{$resourceFieldName}{$useArray}" id="{$resourceFieldName}{$itemIndex}" />
{else}
<input 
	type="text" 
	name="{$resourceFieldName}{$useArray}" 
	id="{$resourceFieldName}{$itemIndex}" 
	{if $field.length}maxlength="{$field.length}"{/if} 
	class="normal datetime{if $field.check} check-{$field.check}{/if}"
	value="{$postedVal|default:$resource[$fieldName]|default:$field.default|default:$smarty.now|date_format:"%Y-%m-%d %H:%M:%S"}"
	{*
	{strip}value="
		{if $mode === 'create'}
			{if $smarty.post.$resourceFieldName}
				{$postedVal}
			{else}
				{$field.displayedValue|default:$field.computedValue|default:$smarty.now|date_format:"%Y-%m-%d %H:%M:%S"}
			{/if}
		{else}
			{$postedVal|default:$resource[$fieldName]|date_format:"%Y-%m-%d %H:%M:%S"}
		{/if}"{/strip} 
	*}
	{*{if !$editable || ($mode === 'create' && $field.computed)}disabled="disabled"{/if} />*}
	{if !$editable}disabled="disabled"{/if} />
{/if}