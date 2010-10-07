{$relResource=$field.relResource|default:$fieldName}
{$pivotResource=$field.pivotResource|default:{$resourceName|cat:$relResource}}
{$pivotTable=$data.resources[$pivotResource]['table']|default:$pivotResource}
{$pivotIdField=$pivotTable|cat:'_id'}
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
		<tr class="odd">
			<td colspan="3">
				<a class="adminLink addLink" href="#">
					<span class="value">{t}add{/t}</span>
				</a>
				<div>
					<form>
						<input type="text" {if $html5}list="suggest"{/if} class="normal search" />
						<{if $html5}datalist{else}{/if}div class="suggest" id="suggest">
							{foreach $data[$relResource] as $option}
							<option value="{$option.admin_title}" />
							{/foreach}
						</{if $html5}datalist{else}{/if}div>
					</from>
				</div>
			</td>
		</tr>
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