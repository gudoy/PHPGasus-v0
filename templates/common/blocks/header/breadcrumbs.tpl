{nocache}
<div class="block breadcrumbsBlock" id="breadcrumbsBlock">
	<a class="home" rel="home" href="{$smarty.const._URL}">
		<span class="value">{t}home{/t}</span>
	</a>
	<a rel="admin" href="{$smarty.const._URL_ADMIN}">
		<span class="value">admin</span>
	</a>
	{foreach $data.meta.breadcrumbs as $item}
	<a {if $item@last}class="current"{/if} href="{$smarty.const._URL_ADMIN}{$item}">
		<span class="value">{$item}</span>
	</a>
	{/foreach}
</div>
{/nocache}