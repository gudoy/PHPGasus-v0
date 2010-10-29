{block name='beforeResourcesTableBlock'}{/block}
{block name='resourcesTableBlock'}
<div class="box adminBlock adminListBlock" id="adminResourcesListBlock">
	
	<h2>{t}Resources{/t}</h2>

	{block name='adminResourcesTable'}
	<div class="adminListingBlock" id="adminResourcesBlock">
		{if $data.resources|count > 0}
		<table class="commonTable adminTable" id="resourcesTable">
			<caption>{t}Resource management table{/t}</caption>
			<thead class="titleBlock sortables">
				<tr>
		      		{if !$data.sortBy}
						{assign var='sortBy' value='id'}
						{assign var='order' value='asc'}
					{/if}
					<th class="col firstCol colSelectResources" id="toggleAllCel">
						<input type="checkbox" id="toggleAll" name="toggleAll" />
					</th>
					<th class="col typeVarchar nameCol">
						<span class="title">{t}name{/t}</span>
					</th>
					<th class="col typeInt recordsCountCol">
						<span class="title">{t}Records count{/t}</span>
					</th>
				</tr>
			</thead>
			<tbody>
				{foreach $data.current.groupResources as $name => $resource}
				{if in_array($name, $data.current.user.auths.__can_display)}
				<tr class="dataRow {cycle values='even,odd'}" id="row{$resource@iteration}">
					<td class="col firstcol colSelectResources">
						<input type="checkbox" name="ids[]" value="{$resource@iteration}" {if $smarty.post.ids && in_array($resource@iteration, $smarty.post.ids)}checked="checked"{/if} />
					</td>
					<td class="col dataCol nameCol typeVarchar">
						<div class="value dataValue" id="name{$resource@iteration}">{$name}</div>
					</td>
					<td class="col dataCol recordsCountCol typeInt">
						<div class="value dataValue" id="recordsCount{$resource@iteration}">{$data.total[$name]|default:'???'}</div>
					</td>
				</tr>
				{/if}
				{foreachelse}
				<tr>
					<td colspan="3">
						{t}There's currently no resources defined in the datamodel{/t}
					</td>
				</tr>
				{/foreach}
			</tbody>
		</table>
		{/if}
	</div>
	{/block}
	
</div>
{/block}
{block name='afterResourcesTableBlock'}{/block}