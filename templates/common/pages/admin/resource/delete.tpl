{extends file='specific/layout/pageAdmin.tpl'}
{block name='pageContent'}

	{$resourceName=$view.resourceName}
	{$resourceId=$data.resourceId}
	{$resource=$data.$resourceName}

    {block name="admin{$resourceName|ucfirst}DeleteBlock"}
	{include file='common/blocks/admin/resource/delete.tpl'}
	{/block}

{/block}