{extends file='specific/layout/page.tpl'}

{block name='mainHeader'}
<header class="titleBlock" id="mainHeader">
	{block name='mainHeaderSecondaryActions'}{/block}
	{block name='mainbreadcrumbs'}
	<nav class="breadcrumbs" id="mainBreadcrumbs">{strip}
		<span class="breadcrumb item" itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb"><a rel="home up up" href="{$smarty.const._URL}" itemprop="url"><span class="value" itemprop="title">{t}home{/t}</span></a>
		</span>
		<span class="breadcrumb item" itemprop="child" itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb"><a rel="up" href="{$smarty.const._URL_API}" itemprop="url"><span class="value" itemprop="title">{t}API{/t}</span></a>
		</span>
	{/strip}</nav>
	{/block}
	{block name='adminIndexBlockTitle'}
	<h2 class="title"><a href="{$smarty.const._URL_API}"><span class="value">{t}APIs{/t}</span></a></h2>
	{/block}
	{block name='mainHeaderPrimaryActions'}{/block}
</header>
{/block}

{block name='asideContent'}
{include file='common/blocks/api/commonParams.tpl'}
{/block}

{block name='mainContent'}
<section class="apiSection" id="apiSection">
		
	<div class="block apisBlock" id="apisBlock">
		
		<header class="titleBlock">
			<h2 class="title">{t}APIs{/t}</h2>
		</header>
		
		{block name='exposedApiContent'}{/block}
		
	</div>
	
</section>
{/block}