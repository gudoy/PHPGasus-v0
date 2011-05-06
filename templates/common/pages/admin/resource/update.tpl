{extends file='specific/layout/pageAdmin.tpl'}

{block name='mainContent'}

	{$resourceName 	= $view.resourceName}
	{$resourceId 	= $data.resourceId}
	{$resource 		= $data[$resourceName]}
	
	{block name="admin{$resourceName|ucfirst}UpdateBlock"}
    <section class="adminSection adminUpdateSection admin{$resourceName|ucfirst}UpdateSection">
	{include file='common/blocks/admin/resource/update.tpl'}
	</section>
	{/block}

{/block}