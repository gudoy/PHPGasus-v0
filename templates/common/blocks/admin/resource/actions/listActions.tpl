{$crudability=$data.resources[$resourceName].crudability|default:'CRUD'}{strip}
{/strip}{include file='common/blocks/admin/resource/actions/update.tpl' disabled=(strpos($crudability, 'U')>-1)?0:1}{strip}
{/strip}{include file='common/blocks/admin/resource/actions/retrieve.tpl' disabled=(strpos($crudability, 'R')>-1)?0:1}{strip}
{/strip}{include file='common/blocks/admin/resource/actions/duplicate.tpl'}{strip}
{/strip}{include file='common/blocks/admin/resource/actions/delete.tpl' disabled=(strpos($crudability, 'D')>-1)?0:1}