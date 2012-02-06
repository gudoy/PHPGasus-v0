{if $userResPerms.allow_update}
{include file='common/blocks/admin/resource/actions/update.tpl' disabled=(strpos($crudability, 'U')>-1)?0:1}
{/if}
{* if $userResPerms.allow_create && $userResPerms.allow_update}
{include file='common/blocks/admin/resource/actions/duplicate.tpl' disabled=(strpos($crudability, 'C')>-1 && strpos($crudability, 'U')>-1)?0:1}
{/if *}
{if $userResPerms.allow_delete}
{include file='common/blocks/admin/resource/actions/delete.tpl' disabled=(strpos($crudability, 'D')>-1)?0:1}
{/if}