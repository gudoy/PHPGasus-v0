{extends file='specific/layout/pageAdmin.tpl'}

{block name='mainColHeader'}
{$resourceName 	= $view.resourceName}
{$resource 		= $data[$resourceName][0]}
<header class="titleBlock" id="mainColHeader">
	{block name='mainColHeaderSecondaryActions'}{/block}
	{block name='mainColbreadcrumbs'}
	<nav class="breadcrumbs">
		<span class="breadcrumb item" itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb">
			<a rel="home up up" href="{$smarty.const._URL}" itemprop="url"><span class="value" itemprop="title">{t}home{/t}</span></a>
		</span><span class="breadcrumb item" itemprop="child" itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb">
			<a rel="up" href="{$smarty.const._URL_ADMIN}" itemprop="url"><span class="value" itemprop="title">{t}admin{/t}</span></a>
		</span><span class="breadcrumb item" itemprop="child" itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb">
			<a rel="" href="{$smarty.const._URL_ADMIN}{$resourceName}" itemprop="url"><span class="value" itemprop="title">{$data._resources[$resourceName].displayName|default:$resourceName}</span></a>
		</span>{if $data.total[$resourceName] === 1}<span class="breadcrumb item" itemprop="child" itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb">
			<a rel="" href="{$smarty.const._URL_ADMIN}{$resourceName}/{$resource.id}" itemprop="url"><span class="value" itemprop="title">{$resource[$data._resources[$resourceName].defaultNameField]|default:$resource.id}</span></a>
		</span>{/if}
	</nav>
	{/block}
	{block name='adminRetrieveBlockTitle'}
	<h2 class="title">
        <a href="{$smarty.const._URL_ADMIN}{$resourceName}" class="{$resourceName}" id="resourceName" data-singular="{$data._resources[$resourceName].singular}">
			{$resourceName} - {$resource.id}
		</a>
	</h2>
	{/block}
	{block name='mainColHeaderPrimaryActions'}
	<nav class="actions resourceActions" id="mainColHeaderPrimaryActions">
		{include file='common/blocks/admin/resource/actions/actions.tpl'}
	</nav>
	{/block}
</header>
{/block}

{block name='mainContent'}

	{$resourceName 	= $view.resourceName}
	{$resourceId 	= $data.resourceId}
	{$resource 		= $data[$resourceName]}

    {block name='adminRetrieveSection'}
    {$all = $data[$resourceName]}
	{foreach array_keys((array) $all) as $rKey}
	{$resource = $all[$rKey]}
    <section class="adminSection adminRetrieveSection admin{$resourceName|ucfirst}RetrieveSection">
	{include file='common/blocks/admin/resource/retrieve.tpl'}
	</section>
	{/foreach}
	{/block}

{/block}



{block name='mainColFooterContent'}
{$position 		= 'bottom'}
{$crudability 	= $data._resources[$resourceName].crudability|default:'CRUD'}
{$userResPerms 	= $data.current.user.auths[$resourceName]}
<nav class="actions toolbar adminToolbar adminRetrieveToolbar {$position}" id="adminRetrieveToolbar{$position|ucfirst}">
{if $data.total[$rName] === 1}
{include file='common/blocks/admin/pagination/nextprev.tpl' adminView='retrieve'}
{/if}
</nav>
{/block}