{$resources=$data._resources}
{if $resourceName}
<div class="box block resourceDataModelBlock" id="resourceDataModelBlock">
	<h2>{t}dataModel{/t}{t}:{/t} {$resourceName}</h2>
	{if $resources[$resourceName]}
	<ul>
	{foreach $data.dataModel[$resourceName] as $fieldName => $field}
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