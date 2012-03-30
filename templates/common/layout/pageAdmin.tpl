{extends file='specific/layout/page.tpl'}

{block name='headerHeaderContent'}{include file='common/blocks/admin/search/search.tpl'}{/block}

{block name='headerNav'}
<nav class="main mainNav" id="mainNav">
	<h2 class="title" id="mainNavTitle"><span class="value">{t}Menu{/t}</span></h2>
	{include file='common/blocks/header/nav/adminMainNav.tpl'}
</nav>
{/block}

{block name='loggedUserBlock'}
{include file='common/blocks/header/account/myProfile.tpl'}
{/block}

{block name='accountNav'}{/block}

{block name='accountNavLogoutLink'}{/block}

{block name='headerFooter'}
<footer class="menu" id="headerFooter">
	<ul>
		<li class="toggler colToggler" id="asideToggler">
			{*<a class="toggle plus" id="showAside"><span class="value">{t}show{/t}</span></a>
			<a class="toggle minus" id="hideAside"><span class="value">{t}hide{/t}</span></a>*}
			<a class="toggle" id="asideToggleBtn"><span class="value">{t}show/hide{/t}</span></a>
		</li>
		{*
		<li class="more" id="asideMoreOptions">
			<a id="asideMoreOptionsLink"><span class="value">{t}more{/t}</span></a>
		</li>*}
		<li class="group account accountActions myAccountNav" id="accountActions">
			<span class="title">{t}account{/t}</span>
			<div class="groups">
			{include file='common/blocks/header/account/detail.tpl' user=$data.current.user}
			</div>
		</li>
	</ul>
</footer>
{/block}


{block name='breadcrumbs'}{/block}

{* block name='asideContent'}
	
	<div class="colContent asideContent" id="asideContent">
		
		{include file='common/blocks/admin/search/search.tpl'}
		
		{block name='headerNav'}{/block}
		
	</div>
		
{/block}

{block name='asideFooter'}
<footer class="menu" id="asideFooter">
	<ul>
		<li class="toggler colToggler" id="asideToggler">
			{*<a class="toggle plus" id="showAside"><span class="value">{t}show{/t}</span></a>
			<a class="toggle minus" id="hideAside"><span class="value">{t}hide{/t}</span></a>*}
			<a class="toggle" id="asideToggleBtn"><span class="value">{t}show/hide{/t}</span></a>
		</li>
		{*
		<li class="more" id="asideMoreOptions">
			<a id="asideMoreOptionsLink"><span class="value">{t}more{/t}</span></a>
		</li>*}
		<li class="group account accountActions myAccountNav" id="accountActions">
			<span class="title">{t}account{/t}</span>
			<div class="groups">
			{include file='common/blocks/header/account/detail.tpl' user=$data.current.user}
			</div>
		</li>
	</ul>
</footer>
{/block *}

{block name='aside'}{/block}

{block name='mainFooter'}
	<footer class="menu" id="mainFooter">
		{block name="mainFooterContent"}{/block}
	</footer>
{/block}


{* TODO: create rule via js instead??? *}
{block name='dynamicCss' nocache}
{if $data.search.query}
<style class="dynamicCSS" id="searchDynamicCSS">
.commonTable.adminTable td .dataValue[data-exactValue*='{$data.search.query}'] { background:lightyellow; }
</style>
{/if}
{/block}