{strip}

{$crudability 	= join('',$data._resources[$resourceName].crudability)|default:'CRUD'}
{$userResPerms 	= $data.current.user.auths[$resourceName]}

{if $userResPerms.allow_create && $data.view.method !== 'create'}
	{include file='common/blocks/admin/resource/actions/create.tpl' disabled=(strpos($crudability, 'C')>-1)?0:1}
{/if}

{if $userResPerms.allow_retrieve && $data.view.method !== 'retrieve' && $data.total[$resourceName] === 1}
	{include file='common/blocks/admin/resource/actions/retrieve.tpl' disabled=(strpos($crudability, 'R')>-1)?0:1}
{/if}

{if $userResPerms.allow_update && $data.view.method !== 'update' && $data.total[$resourceName] === 1}
	{include file='common/blocks/admin/resource/actions/update.tpl' disabled=(strpos($crudability, 'U')>-1)?0:1}
{/if}

{if $userResPerms.allow_delete && $data.view.method !== 'delete' && $data.total[$resourceName] === 1}
	{include file='common/blocks/admin/resource/actions/delete.tpl' disabled=(strpos($crudability, 'D')>-1)?0:1}
{/if}

{/strip}