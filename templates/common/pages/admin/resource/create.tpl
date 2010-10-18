{extends file='specific/layout/pageAdmin.tpl'}

{block name='pageContent'}

	{$resourceName=$view.resourceName}
	{$resourceId=$data.resourceId}
	{$resource=$data.$resourceName}

	{include file='common/blocks/admin/resource/create.tpl'}

{/block}