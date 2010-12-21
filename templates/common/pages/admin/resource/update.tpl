{extends file='specific/layout/pageAdmin.tpl'}

{block name='pageContent'}

	{$resourceName=$view.resourceName}
	{$resourceId=$data.resourceId}
	{$resource=$data.$resourceName}
	{block name="admin{$resourceName|ucfirst}UpdateBlock"}
	{include file='common/blocks/admin/resource/update.tpl'}
	{/block}

{/block}