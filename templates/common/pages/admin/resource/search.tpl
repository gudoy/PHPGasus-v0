{extends file='specific/layout/pageAdmin.tpl'}

{block name='mainHeader'}
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
			<a rel="" href="{$smarty.const._URL_ADMIN}search" itemprop="url"><span class="value" itemprop="title">{t}search{/t}</span></a>
		</span>
	{strip}</nav>
	{/block}
	{block name='adminIndexBlockTitle'}
	<h2 class="title">
		<span class="value">{t}search results{/t}</span>
	</h2>
	{/block}
	{block name='mainHeaderPrimaryActions'}{/block}
</header>
{/block}

{block name='mainContent'}
{if $data.search.type === 'contextual'}

{$resourceName 	= $view.resourceName}
{$resourceId 	= $data.resourceId}
{$resource 		= $data[$resourceName]}

<form class="adminForm adminIndexForm" id="frmAdmin{$resourceName|capitalize}" action="{$smarty.const._URL_ADMIN}{$resourceName}?method=index" class="commonForm" method="post" enctype="multipart/form-data">

    {block name='admin{$resourceName|ucfirst}IndexBlock'}
	<section class="adminSection adminIndexSection admin{$resourceName|ucfirst}IndexSection">
	{include file='common/blocks/admin/resource/list/list.tpl'}
	</section>
	{/block}
	
	{if $resourceName === 'resources'}
	{include file='common/blocks/admin/resources/dataModel/groups.tpl'}
	{include file='common/blocks/admin/resources/dataModel/resources.tpl'}
	{include file='common/blocks/admin/resources/dataModel/generator.tpl'}
	{/if}

</form>

{else}
{block name='mainHeader'}{/block}
{include file='common/blocks/admin/search/results.tpl' search=$data.search}
{/if}

{/block}