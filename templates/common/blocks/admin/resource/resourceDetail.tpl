<dl>
	{foreach name='tableFields' from=$data.dataModel[$resourceName] key='fieldName' item='field'}
	<dt class="{cycle values='odd,odd,even,even'} type{$field.type|ucfirst}">
		<span class="key">
			{$fieldName|replace:'_':' '}{*t}:{/t*}
		</span>
		{if $field.comment}
		<small class="comment">
			<span class="detail">{$field.comment}</span>
		</small>
		{/if}
	</dt>
	<dd class="{cycle values='odd,odd,even,even'} type{$field.type|ucfirst}">
		<span class="value">
		{if $field.type === 'bool'}
			{if $resource[$fieldName] === true || $resource[$fieldName] == 1 || $resource[$fieldName] === 't'}
				{t}yes{/t}
			{else}
				{t}no{/t}
			{/if}
		{elseif $field.type === 'int' && $field.subtype === 'fixedValues'}
			{assign var='posValIndex' value=$resource[$fieldName]}
			{$field.possibleValues[$posValIndex]}
		{elseif $field.type === 'onetomany'}
			{if $resource[$fieldName]}
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
				&& ($field.type === 'text' || $field.type === 'varchar')}
				{$resource[$fieldName]|truncate:'30':'...':true|default:'&nbsp;'}					
			{else}
				{$resource[$fieldName]|default:'&nbsp;'}
			{/if}
		{/if}
		</span>
	</dd>
	{/foreach}
</dl>