{$displayAs=$field.relGetAs|default:$field.relField}
{$relResource=$field.relResource}
{$relField=$field.relField}
{if isset($data[$relResource]) && count($data[$relResource]) < 100}
{include file='common/forms/admin/fieldLine/caseOneToOne.tpl'}
{else}
<div class="relField disabled">
	{$relDisplayVal=''}
	{if !empty($field.relDisplayAs)}
		{$relDisplayVal=$field.relDisplayAs|regex_replace:"/\%(.*)\%/Ue":"\\1"}
		{foreach from=$resource key='key' item='val'}
			{$tmp=$resource[$key]|default:'&nbsp;'}
			{$tmpDisplayVal='<span class="'|cat:$key|cat:'">'|cat:$tmp|cat:'</span>'}
			{$relDisplayVal=$relDisplayVal|replace:$key:$tmpDisplayVal}
		{/foreach}
	{/if}
	{$relDisplayVal=$relDisplayVal|default:$resource[$field.relGetAs]}
	<span class="relDisplayVal">
		{if $relDisplayVal}{$relDisplayVal}{else}{/if}
	</span>
    {*if $postedVal || $resource[$fieldName]}
    <input type="hidden" name="{$resourceFieldName}{$useArray}" id="{$resourceFieldName}{$itemIndex}" {if $field.length}maxlength="{$field.length}"{/if} value="{$postedVal|default:$resource[$fieldName]}" />
    {/if*}
	<a class="actionBtn changeValBtn" href="{$smarty.const._URL_ADMIN}{$relResource}" title="{t}[require javascript]{/t}">
		<span class="ninja fieldCurrentVal">{$resource[$fieldName]|default:'&nbsp;'}</span>
		<span class="ninja formFieldName">{$resourceFieldName}</span>
		<span class="ninja relResource">{$relResource}</span>
		<span class="ninja relField">{$relField}</span>
		{strip}
		{$tmpRelGetFields=''}
		{if is_array($field.relGetFields)}
		{foreach name='relGetFields' from=$field.relGetFields key='key' item='val'}
			{if !$smarty.foreach.relGetFields.first}
				{$tmpRelGetFields=$tmpRelGetFields|cat:' - '}
			{/if}
			{$tmpRelGetFields=$tmpRelGetFields|cat:$key}
		{/foreach}
		{/if}
		{/strip}
		<span class="ninja relGetFields">{$tmpRelGetFields|default:$field.relGetFields}</span>
		<span class="ninja relDisplayAs">{$field.relDisplayAs|default:$resource[$field.relGetAs]|default:'&nbsp;'}</span>
		<span class="label">{if $relDisplayVal}{t}change{/t}{else}{t}choose{/t}{/if}</span>
	</a>
</div>
{/if}