{strip}
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
		{$hasProtocol =  strpos($value, '://')}
	{*<a class="currentItem file" href="{if !$hasProtocol}{rtrim($baseURL,'/')}{if $field.storeAs === 'filename'}{$field.destFolder}{/if}{/if}{$value}">*}
	<a class="currentItem file" href="{if !$hasProtocol}{rtrim($baseURL,'/')}/{if $field.storeAs === 'filename'}{ltrim($field.destFolder, '/')}{/if}{/if}{ltrim($value, '/')}">
		{if $field.mediaType && $field.mediaType == 'image' && $field.storeAs !== 'filename'}
		<img class="value" src="{$smarty.const._URL}{$value}" alt="{$value}: {$resource.id}" />
		{else}
		<span class="value">{$value|regex_replace:"/.*\//":""}</span>
		{/if}
	</a>
	{/if}
{elseif $field.type === 'set'}
	{if strlen(join(',',$value)) > 40}
	<details>
		<summary>({count($value)} {t}items{/t})</summary>
		{foreach $value as $item}
		<span class="item">{$item}<a class="deleteItem deleteSetItem">{t}x{/t}</a></span>
		{/foreach}
	</details>
	{else}
		{foreach $value as $item}
		<span class="item">{$item}<a class="deleteItem deleteSetItem">{t}x{/t}</a></span>
		{/foreach}
	{/if}
{elseif $field.subtype === 'url'}
	{if $value}
	<a class="url" href="{if strpos('/', $smarty.const._APP_PROTOCOL) === 0}{$field.prefix|default:$smarty.const._URL}{/if}{$value}">
		{* <span class="value">../{$value|regex_replace:"/.*\//":""}</span>*}
		<span class="value">{$value|regex_replace:"/.*\//":""}</span>
	</a>
	{else}
		&nbsp;
	{/if}
{else}
	{if $field.fk}
		{$relResource 	= $field.relResource}
		{$relField 		= $field.relField}
		<a class="relResourceLink" href="{$smarty.const._URL_ADMIN}{$relResource}/{$value}">{$resource[{$field.relGetAs|default:$field.relGetFields}]|default:$value}</a>
	{else}
		{if !$field.listTruncate}
			{* $value|regex_replace:'/&([^#]|$)/':'&amp;$1'|stripslashes|default:'&nbsp;' *}
			{$value|regex_replace:'/&([^#]|$)/':'&amp;$1'|stripslashes|escape:'html'|default:'&nbsp;'}
		{else}
			{$value|regex_replace:'/&([^#]|$)/':'&amp;$1'|stripslashes|truncate:50:"..."|default:'&nbsp;'}
		{/if}
		{/if}
{/if}
{/strip}