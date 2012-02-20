{extends file='specific/layout/pageAdmin.tpl'}

{block name='mainColHeader'}

{$resourceName 	= $view.resourceName}

<header class="titleBlock" id="mainColHeader">
	{block name='mainColHeaderSecondaryActions'}{/block}
	{block name='adminIndexBlockTitle'}
	<h2 class="title">
		<a href="{$smarty.const._URL_ADMIN}{$resourceName}" class="{$resourceName}" id="resourceName" data-singular="{$data._resources[$resourceName].singular}">
			{$resourceName}
		</a>
	</h2>
	{/block}
	{block name='mainColHeaderPrimaryActions'}
	<nav class="actions resourceActions" id="mainColHeaderPrimaryActions">
		<a class="action primary edit" href="#editMode" id="editModeBtn"><span class="value" data-revert-label="{t}fishished{/t}">{t}edit{/t}</span></a>
	</nav>
	{/block}
</header>
{/block}

{block name='mainContent'}

{$resourceName 	= $view.resourceName}
{$resourceId 	= $data.resourceId}
{$resource 		= $data[$resourceName]}

{block name='admin{$resourceName|ucfirst}IndexBlock'}
<section class="adminSection adminIndexSection admin{$resourceName|ucfirst}IndexSection">

	<form class="adminForm adminIndexForm" id="frmAdmin{$resourceName|capitalize}" action="{$smarty.const._URL_ADMIN}{$resourceName}?method=index" class="commonForm" method="post" enctype="multipart/form-data">
	{include file='common/blocks/admin/resource/list.tpl'}
	</form>
	
</section>
{/block}

{if $resourceName === 'resources'}
{include file='common/blocks/admin/resources/dataModel/resources.tpl'}
{include file='common/blocks/admin/resources/dataModel/groups.tpl'}
{include file='common/blocks/admin/resources/dataModel/columns.tpl'}
{/if}

{/block}


{block name='mainColFooterContent'}
{$position = 'bottom'}
<nav class="actions toolbar adminToolbar adminListToolbar {$position}" id="adminListToolbar{$position|ucfirst}">
{include file='common/blocks/admin/resource/list/toolbar/actions.tpl'}
</nav>
{/block}
