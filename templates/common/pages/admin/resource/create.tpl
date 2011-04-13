{extends file='specific/layout/pageAdmin.tpl'}

{block name='pageContent'}

	{$resourceName=$view.resourceName}
	{$resourceId=$data.resourceId}
	{$resource=$data.$resourceName}

    {block name="admin{$resourceName|ucfirst}CreateBlock"}
	{include file='common/blocks/admin/resource/create.tpl'}
	{/block}

{/block}

{block name='resourceColumnsFieldset' append}
	{if $resourceName === 'resources'}
	{block name="adminCreateResourceFilesFieldset"}
	{include file='common/forms/admin/resources/create/createResourceFilesFieldset.tpl'}
	{/block}
	{/if}
{/block}
