{foreach $rModel as $fieldName => $field}
{$isSorted 				= ($sortBy === $fieldName)}
{$isDefaultNamefield 	= ($data.meta.defaultNameField === $fieldName)}
{$value                 = $resource[$fieldName]}
{if ($lightVersion && $field.list == 3) || (!$lightVersion && $field.list >= 1)}
<td id="{$fieldName}Col{$resource.id}" class="col dataCol {$fieldName}Col type{$field.type|ucfirst}{if $field.subtype} subtype{$field.subtype|ucfirst}{/if}{if $isDefaultNamefield} defaultNameField{/if}{if $isSorted} activeSort{/if}{if $field.relResource} typeRel{/if}{if !$field.list} hidden{/if}{if $isSorted} activeSort{/if}" headers="row{$resource.id} {$fieldName}Col">
	<div class="value dataValue" id="{$fieldName}{$resource.id}" {if $field.type === 'timestamp'}title="{$value|date_format:"%Y-%m-%d %H:%M:%S"}"{/if} data-exactValue="{$value}">
	{if $field.type === 'timestamp' || $field.type === 'datetime'}
	{$value|date_format:"%d %B %Y, %Hh%M"}
	{elseif $field.type === 'bool'}
	 	{$valid = in_array($value, array(1,true,'1','true','t'), true)}
		<span class="validity {if !$valid}in{/if}valid"><span class="label">{if $valid}{t}yes{/t}{else}{t}no{/t}{/if}</span></span>
	{elseif $field.type === 'int' && $field.subtype === 'fixedValues'}
		{$field.possibleValues[$value]}
	{elseif $field.subtype === 'file' || $field.subtype == 'fileDuplicate'}
		{if $value}
			{$baseURL = $field.destBaseURL|default:$smarty.const._URL}
		<a class="currentItem file" href="{if strpos($value, 'http://') === false}{rtrim($baseURL,'/')}{if $field.storeAs === 'filename'}{$field.destFolder}{/if}{/if}{$value}">
			{if $field.mediaType && $field.mediaType == 'image' && $field.storeAs !== 'filename'}
			<img class="value" src="{$smarty.const._URL}{$value}" alt="{$value}: {$resource.id}" />
			{else}
			<span class="value">{$value|regex_replace:"/.*\//":""}</span>
			{/if}
		</a>
		{/if}
	{elseif $field.subtype === 'url'}
		{if $value}
		<a class="url" href="{if strpos($value, 'http://') === false}{$field.prefix|default:$smarty.const._URL}{/if}{$value}">
			<span class="value">../{$value|regex_replace:"/.*\//":""}</span>
		</a>
		{else}
			&nbsp;
		{/if}
	{else}
		{* Handle foreign keys/onetoone relations *}
		{if $field.fk}
			{$relResource 	= $field.relResource}
			{$relField 		= $field.relField}
			<a class="relResourceLink" href="{$smarty.const._URL_ADMIN}{$relResource}/{$value}">
				{$resource[{$field.relGetAs|default:$field.relGetFields}]|default:$value}
			</a>
		{else}
			{if !$field.listTruncate}
				{$value|regex_replace:'/&([^#]|$)/':'&amp;$1'|stripslashes|default:'&nbsp;'}
			{else}
				{$value|regex_replace:'/&([^#]|$)/':'&amp;$1'|stripslashes|truncate:50:"..."|default:'&nbsp;'}
			{/if}
		{/if}
	{/if}
	{/strip}</div>
</td>
{/if}
{/foreach}