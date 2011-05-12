{extends file='specific/layout/pageAdmin.tpl'}

{block name='mainContent'}

	{$resourceName 	= $view.resourceName}
	{$resourceId 	= $data.resourceId}
	{$resource 		= $data[$resourceName]}

    {block name='admin{$resourceName|ucfirst}CreateBlock'}
    <section class="adminSection adminCreateSection admin{$resourceName|ucfirst}CreateSection">
	{include file='common/blocks/admin/resource/create.tpl'}
	</section>
	{/block}

{/block}

{block name='resourceColumnsFieldset' append}
	{if $resourceName === 'resources'}
	{block name='adminCreateResourceFilesFieldset'}
	{include file='common/forms/admin/resources/create/createResourceFilesFieldset.tpl'}
	{/block}
	{/if}
{/block}