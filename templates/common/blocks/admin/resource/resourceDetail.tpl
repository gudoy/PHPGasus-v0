{$resourceSingular = $data._resources[$resourceName].singular|default:$resourceName}
<dl>
	{foreach name='tableFields' from=$data.dataModel[$resourceName] key='fieldName' item='field'}
	{$type 	= $field.type}
	{$value = $resource[$fieldName]}
	<dt class="{cycle values='odd,odd,even,even'} type{$field.type|ucfirst} {$resourceSingular}{$fieldName|ucfirst}" id="{$resourceSingular}{$fieldName|ucfirst}Label">
		<span class="key">
			{$fieldName|replace:'_':' '}{*t}:{/t*}
		</span>
		{if $field.comment}
		<small class="comment">
			<span class="detail">{$field.comment}</span>
		</small>
		{/if}
	</dt>
	<dd class="{cycle values='odd,odd,even,even'} type{$field.type|ucfirst} {$resourceSingular}{$fieldName|ucfirst}" id="{$resourceSingular}{$fieldName|ucfirst}">
		<span class="value">{strip}
		{if $type === 'bool'}
			{if in_array($value, array(1,true,'1','true','t'), true)}
				{t}yes{/t}
			{else}
				{t}no{/t}
			{/if}
		{elseif $type === 'onetoone' || $field.fk}
			{$relResource=$field.relResource}
			{$relField=$field.relField}
			<a class="relResourceLink" href="{$smarty.const._URL_ADMIN}{$relResource}/{$value}" data-exactValue="{$value}">
				{$value} - {$resource[{$field.relGetAs|default:$field.relGetFields}]}
			</a>
		{elseif $type === 'int' && $field.subtype === 'fixedValues'}
			{$field.possibleValues[$value]}
		{elseif $type === 'timestamp'}
			{$resource[$fieldName]|date_format:"%d %B %Y, %Hh%M"}
		{elseif $type === 'onetomany'}
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
			{if $data.options.viewType && $data.options.viewType === 'bubble' 
				&& ($type === 'text' || $type === 'varchar')}
				{$value|truncate:'30':'...':true|default:'&nbsp;'}					
			{else}
				{$value|default:'&nbsp;'}
			{/if}
		{/if}
		{/strip}</span>
	</dd>
	{/foreach}
</dl>