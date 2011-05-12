{extends file='specific/layout/pageAdmin.tpl'}

{block name='mainContent'}

{$resourceName 	= $view.resourceName}
{$resourceId 	= $data.resourceId}
{$resource 		= $data[$resourceName]}

<form id="frmAdmin{$resourceName|capitalize}" action="{$smarty.const._URL_ADMIN}{$resourceName}?method=index" class="commonForm" method="post" enctype="multipart/form-data">

    {block name='admin{$resourceName|ucfirst}IndexBlock'}
	<section class="adminSection adminRetrieveSection admin{$resourceName|ucfirst}RetrieveSection">
	{include file='common/blocks/admin/resource/list.tpl'}
	</section>
	{/block}
	
	{if $resourceName === 'resources'}
	{include file='common/blocks/admin/resources/dataModel/groups.tpl'}
	{include file='common/blocks/admin/resources/dataModel/resources.tpl'}
	{include file='common/blocks/admin/resources/dataModel/generator.tpl'}
	{/if}

</form>
{/block}