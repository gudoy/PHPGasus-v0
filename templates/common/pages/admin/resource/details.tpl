{extends file='specific/layout/pageAdmin.tpl'}

{block name='mainContent'}

	{$resourceName 	= $view.resourceName}
	{$resourceId 	= $data.resourceId}
	{$resource 		= $data[$resourceName]}

    {block name="admin{$resourceName|ucfirst}IndexBlock"}
	{include file='common/blocks/admin/resource/list.tpl'}
	{/block}

{/block}