{extends file='specific/layout/pageAdmin.tpl'}

{block name='mainContent'}

	{$resourceName 	= $view.resourceName}
	{$resourceId 	= $data.resourceId}
	{$resource 		= $data[$resourceName]}

    {block name='adminRetrieveSection'}
    <section class="adminSection adminRetrieveSection admin{$resourceName|ucfirst}RetrieveSection">
	{include file='common/blocks/admin/resource/retrieve.tpl'}
	</section>
	{/block}

{/block}