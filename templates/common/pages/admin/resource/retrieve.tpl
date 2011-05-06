{extends file='specific/layout/pageAdmin.tpl'}

{block name='mainContent'}

	{$resourceName 	= $view.resourceName}
	{$resourceId 	= $data.resourceId}
	{$resource 		= $data[$resourceName]}

    {block name="admin{$resourceName|ucfirst}RetrieveBlock"}
    <section class="adminSection adminRetrieveSection admin{$resourceName|ucfirst}RetrieveSection">
	{include file='common/blocks/admin/resource/retrieve.tpl'}
	</section>
	{/block}

{/block}