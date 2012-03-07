{extends file='specific/layout/page.tpl'}

{block name='loggedUserBlock'}
{include file='common/blocks/header/account/myProfile.tpl'}
{/block}

{block name='accountNavLogoutLink'}{/block}

{block name='mainNav'}
{include file='common/blocks/header/nav/adminMainNav.tpl'}
{/block}

{block name='accountNav'}{/block}

{block name='breadcrumbs'}
{include file='common/blocks/header/breadcrumbs.tpl'}
{/block}

{block name='asideContent'}
	
	<div class="colContent asideContent" id="asideContent">
		
		{include file='common/blocks/admin/search/search.tpl'}
		
		<nav class="main mainNav" id="mainNav">
			<h2 class="title" id="mainNavTitle">{t}Menu{/t}</h2>
			{include file='common/blocks/header/nav/adminMainNav.tpl'}
		</nav>
		
	</div>
		
{/block}

{block name='asideFooter'}
	<footer class="menu" id="asideFooter">
		<ul>
			<li class="toggler" id="asideColToggler">
				<a class="toggle plus" id="showAsideCol"><span class="value">{t}show{/t}</span></a>
				<a class="toggle minus" id="hideAsideCol"><span class="value">{t}hide{/t}</span></a>
			</li>
			<li class="more" id="asideMoreOptions">
				<a id="asideMoreOptionsLink"><span class="value">{t}more{/t}</span></a>
			</li>
			<li class="group account accountActions myAccountNav" id="accountActions">
				<span class="title">{t}account{/t}</span>
				<div class="groups">
				{include file='common/blocks/header/account/detail.tpl' user=$data.current.user}
				</div>
			</li>
		</ul>
	</footer>
{/block}

{block name='mainColFooter'}
	<footer class="menu" id="mainColFooter">
		{block name="mainColFooterContent"}{/block}
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