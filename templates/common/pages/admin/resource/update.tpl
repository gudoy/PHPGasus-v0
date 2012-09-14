{extends file='specific/layout/pageAdmin.tpl'}

{block name='mainHeader'}
{$resourceName 	= $view.resourceName}
{$resource 		= $data[$resourceName][0]}
<header class="titleBlock" id="mainHeader">
	{block name='mainHeaderSecondaryActions'}{/block}
	{block name='mainbreadcrumbs'}
	<nav class="breadcrumbs" id="mainBreadcrumbs">{strip}
		<span class="breadcrumb item" itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb">
			<a rel="home up up" href="{$smarty.const._URL}" itemprop="url"><span class="value" itemprop="title">{t}home{/t}</span></a>
		</span>
		<span class="breadcrumb item" itemprop="child" itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb">
			<a rel="up" href="{$smarty.const._URL_ADMIN}" itemprop="url"><span class="value" itemprop="title">{t}admin{/t}</span></a>
		</span>
		<span class="breadcrumb item" itemprop="child" itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb">
			<a rel="" href="{$smarty.const._URL_ADMIN}{$resourceName}" itemprop="url"><span class="value" itemprop="title">{$data._resources[$resourceName].displayName|default:$resourceName}</span></a>
		</span>
		{if $data.total[$resourceName] === 1}
		<span class="breadcrumb item" itemprop="child" itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb">
			<a rel="" href="{$smarty.const._URL_ADMIN}{$resourceName}/{$resource.id}" itemprop="url"><span class="value" itemprop="title">{$resource[$data._resources[$resourceName].defaultNameField]|default:$resource.id}</span></a>
		</span>
		{/if}
	</nav>
	{/block}
	{block name='adminUpdateBlockTitle'}
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
	
	{block name='admin{$resourceName|ucfirst}UpdateBlock'}
    {$all = $data[$resourceName]}
	{foreach array_keys((array) $all) as $rKey}
	{$resource = $all[$rKey]}
    <section class="adminSection adminUpdateSection admin{$resourceName|ucfirst}UpdateSection">
	{include file='common/blocks/admin/resource/update.tpl'}
	</section>
	{foreachelse}
	<p class="nodata">{t}This resource(s) doesn't seem to exist.{/t}</p>
	{/foreach}
	{/block}

{/block}


{block name='mainFooterContent'}
{$position 		= 'bottom'}
{$crudability 	= $data._resources[$resourceName].crudability|default:'CRUD'}
{$userResPerms 	= $data.current.user.auths[$resourceName]}
<nav class="actions toolbar adminToolbar adminUpdateToolbar {$position}" id="adminUpdateToolbar{$position|ucfirst}">
{if $data.total[$rName] === 1}
{include file='common/blocks/admin/pagination/nextprev.tpl' adminView='update'}
{/if}
</nav>
{/block}