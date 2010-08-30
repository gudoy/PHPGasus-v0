<dl>
	{foreach name='tableFields' from=$data.dataModel[$resourceName] key='fieldName' item='field'}
	<dt class="{cycle values='odd,odd,even,even'} type{$field.type|ucfirst}">
		<span class="key">
			{$fieldName|replace:'_':' '}{*t}:{/t*}
		</span>
		<small class="comment">
			<span class="detail">{$field.comment|default:'Sorry, no data explanation'}</span>
		</small>
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