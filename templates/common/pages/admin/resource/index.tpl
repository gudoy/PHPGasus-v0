{extends file='specific/layout/pageAdmin.tpl'}

{block name='pageContent'}
<form id="frmAdmin{$resourceName|capitalize}" action="{$smarty.const._URL_ADMIN}{$resourceName}?method=index" class="commonForm" method="post" enctype="multipart/form-data">

	{$resourceName=$view.resourceName}
	{$resourceId=$data.resourceId}
	{$resource=$data.$resourceName}

    {block name="admin{$resourceName|ucfirst}IndexBlock"}
	{include file='common/blocks/admin/resource/list.tpl'}
	{/block}

</form>
{/block}