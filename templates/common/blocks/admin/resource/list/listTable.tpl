{strip}

{$rModel 		= $data.dataModel[$resourceName]}
{$rCount 		= $data[$resourceName]|@count}
{$lowCapDevice 	= $data.device.hasLowCapacity|default:false}
{$crudability 	= $data._resources[$resourceName].crudability|default:'CRUD'}
{$newPageURL 	= "{$curURLbase}?{http_build_query($data.current.urlParams)}"}
{$userResPerms 	= $data.current.user.auths[$resourceName]}

{/strip}
{include file='common/blocks/admin/resource/list/toolbar.tpl' position='top'}
<div class="tableWrapperBlock" id="{$resourceName}TableWrapperBlock">
	<table class="commonTable adminTable {$resourceName}Table {if $lowCapDevice}lowCap{/if}" id="{$resourceName}Table" data-resource="{$resourceName}">
		<caption>{$resourceName}</caption>
		<thead class="titleRow sortables">
			{include file='common/blocks/admin/resource/list/rows/headers.tpl'}
		</thead>
		<tbody>
			{include file='common/blocks/admin/resource/list/rows/body.tpl'}
		</tbody>
	</table>
</div>