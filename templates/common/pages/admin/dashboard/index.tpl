{extends file='specific/layout/pageAdmin.tpl'}

{block name='mainColHeader'}

{$resourceName 	= $view.resourceName}

<header class="titleBlock" id="mainColHeader">
	{block name='mainColHeaderSecondaryActions'}{/block}
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