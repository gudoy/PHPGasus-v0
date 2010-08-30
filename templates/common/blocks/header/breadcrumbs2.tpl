{nocache}
{if isset($data.view.breadcrumbs)}
<!-- Start Breadcrumbs Block -->
<div class="commonBlock" id="breadcrumbsBlock">
	<a class="itemLink" href="{$smarty.const.URL_HOME}">{t}Home{/t}</a>
	{foreach name='breadcrumbs' from=$data.view.breadcrumbs key='itemName' item='itemPath'}
	&nbsp;&gt;&nbsp;
	<a class="itemLink {if $smarty.foreach.breadcrumbs.last}current{/if}" href="{$itemPath}">{t}{$itemName|capitalize}{/t}</a>
	{/foreach}
</div>
<!-- End Breadcrumbs Block -->
{/if}
{/nocache}