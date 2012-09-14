{extends file='specific/layout/pageAdmin.tpl'}

{block name='mainHeader'}
{$resourceName 	= $view.resourceName}
{$resource 		= $data[$resourceName][0]}
<header class="titleBlock" id="mainHeader">
	{block name='mainHeaderSecondaryActions'}{/block}
	{block name='mainbreadcrumbs'}
	<nav class="breadcrumbs" id="mainBreadcrumbs">{strip}
		<span class="breadcrumb item" itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb">
			<a rel="home up up up" href="{$smarty.const._URL}" itemprop="url"><span class="value" itemprop="title">{t}home{/t}</span></a>
		</span>
		<span class="breadcrumb item" itemprop="child" itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb">
			<a rel="up up" href="{$smarty.const._URL_ADMIN}" itemprop="url"><span class="value" itemprop="title">{t}admin{/t}</span></a>
		</span>
		<span class="breadcrumb item" itemprop="child" itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb">
			<a rel="up" href="{$smarty.const._URL_ADMIN}{$resourceName}" itemprop="url"><span class="value" itemprop="title">{$data._resources[$resourceName].displayName|default:$resourceName}</span></a>
		</span>
		<span class="breadcrumb item" itemprop="child" itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb">
			<a rel="" href="{$smarty.const._URL_ADMIN}{$resourceName}/new" itemprop="url"><span class="value" itemprop="title">{t}new{/t}</span></a>
		</span>
	{/strip}</nav>
	{/block}
	{block name='adminCreateBlockTitle'}
	<h2 class="title">
        <a href="{$smarty.const._URL_ADMIN}{$resourceName}" class="{$resourceName}" id="resourceName" data-singular="{$data._resources[$resourceName].singular}">
			{$resourceName} - {$resource.id}
		</a>
	</h2>
	{/block}
	{block name='mainHeaderPrimaryActions'}
	<nav class="resourceActions" id="mainHeaderPrimaryActions">
		<div class="actions primary">{include file='common/blocks/admin/resource/actions/actions.tpl'}</div>
		<div class="actions secondary">{strip}{block name='secondaryActions'}{/block}{/strip}</div>
	</nav>
	{/block}
</header>
{/block}

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