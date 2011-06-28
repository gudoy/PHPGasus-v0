{$resources 		= $data._resources}
{$relResource 		= $field.relResource|default:$fieldName}
{$relField 			= $field.relField|default:'id'}
{$pivotResource 	= $field.pivotResource|default:{$resourceName|cat:$relResource}}
{$pivotTable 		= $resources[$pivotResource]['table']|default:$pivotResource}
{$pivotIdField 		= $pivotTable|cat:'_id'}
{$pivotLeftField 	= $field.pivotLeftField|default:{$resources[$resourceName]['singular']|cat:'_id'}}
{$pivotRightField 	= $field.pivotRightField|default:{$resources[$relResource]['singular']|cat:'_id'}}
{if !empty($resource[$fieldName])}
<table class="commonTable adminTable relationTable">
	<thead>
		<tr>
			{foreach $resource[$fieldName][0] as $propName => $propVal}
			{if $propName !== $pivotIdField}
			<th>
				{$propName}
			</th>
			{/if}
			{/foreach}
			<th class="actionsCol">{strip}
				<span class="title">&nbsp;</span>
			{/strip}</th>
		</tr>
	</thead>
	<tbody>
{*
		<tr class="odd">
			<td colspan="0">
				<a class="adminLink addLink addOneToManyItemLink" href="#">
					<span class="value label">{t}add{/t}</span>
				</a>
				<div class="suggestBlock">
*}
					{*
					$relResource:{$relResource}<br/>
					$pivotResource:{$pivotResource}<br/>
					$pivotTable:{$pivotTable}<br/>
					$pivotIdField:{$pivotIdField}<br/>
					$pivotLeftField:{$pivotLeftField}<br/>
					$pivotRightField:{$pivotRightField}<br/>
					count:{$data[$relResource]|@count}<br/>
					*}
{*
						{$relPostFieldName=$data.meta.singular|cat:{$pivotRightField|ucfirst}}
						<input type="{if $html5 && $browser.support.datalist}search{else}text{/if}" {if $html5 && $browser.support.datalist}list="suggest{$resourceFieldName}"{/if} class="normal search" />
						<input type="hidden" name="{$relPostFieldName}" id="{$relPostFieldName}" />
						{$labelField=$resources[$relResource].defaultNameField}
						{if $html5 && $browser.support.datalist}datalist
							{foreach $data[$relResource] as $option}
							<datalist class="suggest hidden" id="suggest{$resourceFieldName}">
								<option class="item" value="{$option[$relField]}" label="{$option[$labelField]|default:$option[$relField]}" />
							</datalist>
							{/foreach}
						{else}
							<div class="suggest" id="suggest">
								{foreach $data[$relResource] as $option}
								<span id="{$resourceFieldName}Option{$option@iteration}" class="item">
									<span class="label">{$option[$labelField]|default:$option[$relField]}</span>
									<span class="value hidden">{$option[$relField]}</span>
								</span>
								{/foreach}
							</div>
						{/if}
				</div>
			</td>
		</tr>
*}
		{foreach $resource[$fieldName] as $item}
		<tr class="{cycle values='even,odd'}">
			{foreach $item as $propName => $propVal}
			{if $propName !== $pivotIdField}
			<td class="{$propName}Col">
				{$propVal}
			</td>
			{/if}
			{/foreach}
			<td class="actionsCol">{strip}
				<span class="actions">
					<a class="adminLink deleteLink" href="{$smarty.const._URL_ADMIN}{$pivotResource}/{$item[$pivotIdField]}?method=delete">
						<span class="value">{t}delete{/t}<span>
					</a>
				</span>
			{/strip}</td>
		</tr>
		{/foreach}
	</tbody>	
</table>
{else}
<p>
    {t}none{/t}
</p>
{/if}