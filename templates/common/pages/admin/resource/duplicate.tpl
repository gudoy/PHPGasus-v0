{extends file='specific/layout/pageAdmin.tpl'}

{block name='mainContent'}

	{$resourceName 	= $view.resourceName}
	{$resourceId 	= $data.resourceId}
	{$resource 		= $data[$resourceName]}

    {block name="admin{$resourceName|ucfirst}DuplicateBlock"}
    <section class="adminSection adminDuplicateSection admin{$resourceName|ucfirst}DuplicateSection">
	{include file='common/blocks/admin/resource/create.tpl'}
	</section>
	{/block}

{/block}