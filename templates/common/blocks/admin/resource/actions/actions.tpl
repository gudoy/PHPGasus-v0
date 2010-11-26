{$crudability=$data._resources[$resourceName].crudability|default:'CRUD'}
{include file='common/blocks/admin/resource/actions/list.tpl' disabled=(strpos($crudability, 'R')>-1)?0:1}
{include file='common/blocks/admin/resource/actions/create.tpl' disabled=(strpos($crudability, 'C')>-1)?0:1}
{include file='common/blocks/admin/resource/actions/retrieve.tpl' disabled=(strpos($crudability, 'R')>-1)?0:1}
{include file='common/blocks/admin/resource/actions/update.tpl' disabled=(strpos($crudability, 'U')>-1)?0:1}
{include file='common/blocks/admin/resource/actions/delete.tpl' disabled=(strpos($crudability, 'D')>-1)?0:1}