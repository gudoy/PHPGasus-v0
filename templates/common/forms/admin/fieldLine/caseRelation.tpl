{$displayAs=$field.relGetAs|default:$field.relField}
{$relResource=$field.relResource}
{$relField=$field.relField}
{if $data[$relResource] && count($data[$relResource]) < 100}
{include file='common/forms/admin/fieldLine/caseOneToOne.tpl'}
{else}
<div class="relField disabled">
	{* Handle related resource *}
	{assign var='relDisplayVal' value=''}
	{if !empty($field.relDisplayAs)}
		{assign var='relDisplayVal' value=$field.relDisplayAs|regex_replace:"/\%(.*)\%/Ue":"\\1"}
		{foreach from=$resource key='key' item='val'}
			{assign var='tmp' value=$resource[$key]|default:'&nbsp;'}
			{assign var='tmpDisplayVal' value='<span class="'|cat:$key|cat:'">'|cat:$tmp|cat:'</span>'}
			{assign var='relDisplayVal' value=$relDisplayVal|replace:$key:$tmpDisplayVal}
		{/foreach}
	{/if}
	{assign var='relDisplayVal' value=$relDisplayVal|default:$resource[$field.relGetAs]}
	<span class="relDisplayVal">
		{$relDisplayVal|default:'[untitled]'}
	</span>
	{*  if $data.resources[$field.relResource].childOf}
		{assign var='relResourceParent' value=$data.resources[$field.relResource].childOf[0]}
		{assign var='relResourceParentSingular' value=$data.resources[$relResourceParent].singular}
		{assign var='cleanedRelResource' value=$field.relResource|replace:$relResourceParentSingular:''}
	{/if*}
	<a class="actionBtn changeValBtn" href="{$data.metas[$field.relResource].fullAdminPath}" title="{t}[require javascript]{/t}">
		<span class="ninja fieldCurrentVal">{$resource[$fieldName]|default:'&nbsp;'}</span>
		<span class="ninja formFieldName">{$resourceFieldName}</span>
		<span class="ninja relResource">{$relResource}</span>
		<span class="ninja relField">{$relField}</span>
		{strip}
		{assign var='tmpRelGetFields' value=''}
		{if is_array($field.relGetFields)}
		{foreach name='relGetFields' from=$field.relGetFields key='key' item='val'}
			{if !$smarty.foreach.relGetFields.first}
				{assign var='tmpRelGetFields' value=$tmpRelGetFields|cat:' - '}
			{/if}
			{assign var='tmpRelGetFields' value=$tmpRelGetFields|cat:$key}
		{/foreach}
		{/if}
		{/strip}
		<span class="ninja relGetFields">{$tmpRelGetFields|default:$field.relGetFields}</span>
		<span class="ninja relDisplayAs">{$field.relDisplayAs|default:$resource[$field.relGetAs]|default:'&nbsp;'}</span>
		<span class="label">{t}change{/t}</span>
	</a>
	<a class="hidden searchLink" href="#">
		<span class="label">{t}search{/t}</span>
	</a>
</div>
{/if}