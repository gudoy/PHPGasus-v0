{$crudability=$data._resources[$resourceName].crudability|default:'CRUD'}
{include file='common/blocks/admin/resource/actions/create.tpl' disabled=(strpos($crudability, 'C')>-1)?0:1}