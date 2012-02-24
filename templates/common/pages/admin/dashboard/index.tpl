{extends file='specific/layout/pageAdmin.tpl'}

{block name='mainColHeader'}
<header class="titleBlock" id="mainColHeader">
	{block name='mainColHeaderSecondaryActions'}{/block}
	{block name='mainColbreadcrumbs'}
	<nav class="breadcrumbs">
		<span class="breadcrumb item" itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb">
			<a rel="home up up" href="{$smarty.const._URL}" itemprop="url"><span class="value" itemprop="title">{t}home{/t}</span></a>
		</span>
		<span class="breadcrumb item" itemprop="child" itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb">
			<a rel="up" href="{$smarty.const._URL_ADMIN}" itemprop="url"><span class="value" itemprop="title">{t}admin{/t}</span></a>
		</span>
	</nav>
	{/block}
	{block name='adminIndexBlockTitle'}
	<h2 class="title"><a href="{$smarty.const._URL_ADMIN}dashboard"><span class="value">{t}dashboard{/t}</span></a></h2>
	{/block}
	{block name='mainColHeaderPrimaryActions'}{/block}
</header>
{/block}

{block name='mainContent'}	
{$resources	= $data._resources}
	
	<section class="activity latestActivity" id="latestActivitySection">
		{include file='common/blocks/admin/dashboard/latest/actions.tpl'}
		{include file='common/blocks/admin/dashboard/latest/connexions.tpl'}
	</section>
	
{/block}