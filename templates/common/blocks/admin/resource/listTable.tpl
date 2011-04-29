{strip}

{$rModel 		= $data.dataModel[$resourceName]}
{$rCount 		= $data[$resourceName]|@count}
{$lightVersion 	= $data.device.isMobile|default:false}
{$sortBy 		= $data.current.sortBy}
{$orderBy 		= $data.current.orderBy|default:'asc'}
{$crudability 	= $data._resources[$resourceName].crudability|default:'CRUD'}
{$data.current.urlParams.sortBy=null}
{$data.current.urlParams.orderBy=null}
{$newPageURL 	= "{$curURLbase}?{http_build_query($data.current.urlParams)}"}
{$userResPerms 	= $data.current.user.auths[$resourceName]}

{/strip}
{include file='common/blocks/admin/resource/list/toolbar.tpl' position='top'}
<div class="tableWrapperBlock" id="{$resourceName}TableWrapperBlock">
	<table class="commonTable adminTable {$resourceName}Table" id="{$resourceName}Table">
		<caption>{$resourceName}</caption>
		<thead class="titleRow sortables">
			{include file='common/blocks/admin/resource/list/rows/headers.tpl'}
		</thead>
		<tbody>
			{include file='common/blocks/admin/resource/list/rows/body.tpl'}
		</tbody>
	</table>
</div>
{include file='common/blocks/admin/resource/list/toolbar.tpl' position='bottom'}