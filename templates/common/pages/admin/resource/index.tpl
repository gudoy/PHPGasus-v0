{extends file='specific/layout/pageAdmin.tpl'}

{* block name='asideHeader'}
<header class="titleBlock" id="asideHeader">
	{block name='asideHeaderSecondaryActions'}{/block}
	{block name='asidebreadcrumbs'}
	<nav class="breadcrumbs">{strip}
		<span class="breadcrumb item" itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb">
			<a rel="home up up" href="{$smarty.const._URL}" itemprop="url"><span class="value" itemprop="title">{t}home{/t}</span></a>
		</span>
		<span class="breadcrumb item" itemprop="child" itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb">
			<a rel="up" href="{$smarty.const._URL_ADMIN}" itemprop="url"><span class="value" itemprop="title">{t}admin{/t}</span></a>
		</span>
		<span class="breadcrumb item" itemprop="child" itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb">
			<a rel="" href="{$smarty.const._URL_ADMIN}{$resourceName}" itemprop="url"><span class="value" itemprop="title">{$data._resources[$resourceName].displayName|default:$resourceName}</span></a>
		</span>
	{/strip}</nav>
	{/block}
	{block name='adminIndexBlockTitle'}
	<h2 class="title">
		<a href="{$smarty.const._URL_ADMIN}{$resourceName}" class="{$resourceName}" id="resourceName" data-singular="{$data._resources[$resourceName].singular}">
			<span class="value">{$resourceName}</span>
		</a>
	</h2>
	{/block}
	{block name='asideHeaderPrimaryActions'}
	<nav class="actions resourceActions" id="asideHeaderPrimaryActions">
		<a class="action primary edit" href="#editMode" id="editModeBtn"><span class="value" data-revert-label="{t}fishished{/t}">{t}edit{/t}</span></a>
	</nav>
	{/block}
</header>
{/block}

{block name='asideContent'}
<div id="asideContent">
	{$data._resources[$view.resourceName].displayMode = 'list'}
	{include file='common/blocks/admin/resource/list/list.tpl'}
</div>
{/block}

{block name='asideFooter'}
<footer class="menu" id="asideFooter">
</footer>
{/block}

{block name='aside'}
<aside id="aside">
	{block name='asideHeader'}{/block}
	{block name='asideContent'}{/block}
	{block name='asideFooter'}{/block}
</aside>
{/block *}

{block name='mainHeader'}
{$resourceName 	= $view.resourceName}
<header class="titleBlock" id="mainHeader">
	{block name='mainHeaderSecondaryActions'}{/block}
	{block name='mainbreadcrumbs'}
	<nav class="breadcrumbs">{strip}
		<span class="breadcrumb item" itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb">
			<a rel="home up up" href="{$smarty.const._URL}" itemprop="url"><span class="value" itemprop="title">{t}home{/t}</span></a>
		</span>
		<span class="breadcrumb item" itemprop="child" itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb">
			<a rel="up" href="{$smarty.const._URL_ADMIN}" itemprop="url"><span class="value" itemprop="title">{t}admin{/t}</span></a>
		</span>
		<span class="breadcrumb item" itemprop="child" itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb">
			<a rel="" href="{$smarty.const._URL_ADMIN}{$resourceName}" itemprop="url"><span class="value" itemprop="title">{$data._resources[$resourceName].displayName|default:$resourceName}</span></a>
		</span>
	{/strip}</nav>
	{/block}
	{block name='adminIndexBlockTitle'}
	<h2 class="title">
		<a href="{$smarty.const._URL_ADMIN}{$resourceName}" class="{$resourceName}" id="resourceName" data-singular="{$data._resources[$resourceName].singular}">
			<span class="value">{$resourceName}</span>
		</a>
	</h2>
	{/block}
	{block name='mainHeaderPrimaryActions'}
	<nav class="actions resourceActions" id="mainHeaderPrimaryActions">
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
	{include file='common/blocks/admin/resource/list/list.tpl'}
	<input type="hidden" name="ids[]" id="resourceIds" />
	</form>
	
</section>
{/block}
{if $resourceName === 'resources'}
{include file='common/blocks/admin/resources/dataModel/resources.tpl'}
{include file='common/blocks/admin/resources/dataModel/groups.tpl'}
{include file='common/blocks/admin/resources/dataModel/columns.tpl'}
{/if}
{/block}


{block name='mainFooterContent'}
{$position 		= 'bottom'}
{$crudability 	= $data._resources[$resourceName].crudability|default:'CRUD'}
{$userResPerms 	= $data.current.user.auths[$resourceName]}
<nav class="actions toolbar adminToolbar adminListToolbar {$position}" id="adminListToolbar{$position|ucfirst}">
{include file='common/blocks/admin/resource/list/toolbar/actions.tpl'}
</nav>
{/block}
