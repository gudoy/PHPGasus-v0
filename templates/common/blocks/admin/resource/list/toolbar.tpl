{$crudability 		= $data._resources[$resourceName].crudability|default:'CRUD'}
{$userResPerms 		= $data.current.user.auths[$resourceName]}
<div class="menu toolbar adminToolbar adminResourcesToolbar adminListToolbar {$position}" id="adminListToolbar{$position|ucfirst}">
{include file='common/blocks/admin/resource/list/toolbar/actions.tpl'}
</div>