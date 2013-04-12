{if $resourceName}
<div class="block resourceDataModelBlock" id="resourceDataModelBlock">
	<h2>{t}dataModel{/t}{t}:{/t} {$resourceName}</h2>
	{if $data._resources[$resourceName]}
	<ul>
	{foreach $data._columns[$resourceName] as $fieldName => $field}
		{if !isset($field.exposed) || $field.exposed}
		<li>
			{$fieldName}
		</li>
		{/if}
	{/foreach}
	</ul>
	{/if}
</div>
{/if}