{extends file='specific/layout/pageAdmin.tpl'}

{block name='mainContent'}

{$resourceName 	= $view.resourceName}
{$resourceId 	= $data.resourceId}
{$resource 		= $data[$resourceName]}

<form class="adminForm adminIndexForm" id="frmAdmin{$resourceName|capitalize}" action="{$smarty.const._URL_ADMIN}{$resourceName}?method=index" class="commonForm" method="post" enctype="multipart/form-data">

    {block name='admin{$resourceName|ucfirst}IndexBlock'}
	<section class="adminSection adminIndexSection admin{$resourceName|ucfirst}IndexSection">
	{include file='common/blocks/admin/resource/list.tpl'}
	</section>
	{/block}
	
	{if $resourceName === 'resources'}
	{include file='common/blocks/admin/resources/dataModel/resources.tpl'}
	{include file='common/blocks/admin/resources/dataModel/groups.tpl'}
	{include file='common/blocks/admin/resources/dataModel/columns.tpl'}
	{/if}

</form>
{/block}