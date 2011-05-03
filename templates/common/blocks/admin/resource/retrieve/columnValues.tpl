{strip}
{*
	$colProps 	= datamodel of the current column (column properties)
	$value 		= value of the current column
	$row 		= current row/item in the data	
*}
{/strip}
{if $colProps.type === 'bool'}
	{if in_array($value, array(1,true,'1','true','t'), true)}{t}yes{/t}{else}{t}no{/t}{/if}
{elseif $colProps.type === 'onetoone' || $colProps.fk}
	{$relResource = $colProps.relResource}
	{$relField = $colProps.relField}
	{if $isAdminView}
	<a class="relResourceLink" href="{$smarty.const._URL_ADMIN}{$relResource}/{$value}" data-exactValue="{$value}">
		{$value} - {$row[{$colProps.relGetAs|default:$colProps.relGetFields}]}
	</a>
	{else}
	{$row[{$colProps.relGetAs|default:$colProps.relGetFields}]}
	{/if}
{elseif $colProps.type === 'timestamp'}
	{$value|date_format:"%d %B %Y, %Hh%M"}
{elseif $colProps.type === 'onetomany'}
	{if $value}
	<ul>
		{foreach $resource[$fieldName] as $relData}
		{$displayed=''}
		<li>
			{foreach $relData as $dataName => $dataValue}
				{if !empty($displayed)}{$displayed=$displayed|cat:' - '|cat:$dataValue}{else}{$displayed=$dataValue}{/if}
			{/foreach}
			{$displayed}
		</li>
		{/foreach}
	</ul>
	{/if}
{else}
	{$value|default:'&nbsp;'}
{/if}