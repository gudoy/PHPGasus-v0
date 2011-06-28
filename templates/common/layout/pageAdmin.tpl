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
		{block name='adminSearch'}
		{include file='common/blocks/admin/search/search.tpl'}
		{/block}
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
			<li class="resize"  id="asideResizer">
				<a id="asideResizeLink"><span class="value">{t}resize{/t}</span></a>
			</li>
		</ul>
	</footer>
{/block}

{block name='mainColFooter'}
	<footer class="menu" id="mainColFooter">
		{block name='poweredBy'}
		<div class="block poweredByBlock" id="poweredByBlock">{t escape=no}powered by <a href="http://www.phpgasus.com">PHPGasus</a>{/t}</div>
		{/block}
		<ul>
			<li class="toggler" id="mainColColToggler">
				<a class="toggle plus" id="showMainCol"><span class="value">{t}show{/t}</span></a>
				<a class="toggle minus" id="hideMainCol"><span class="value">{t}hide{/t}</span></a>
			</li>
			<li class="more" id="mainColMoreOptions">
				<a id="mainColMoreOptionsLink"><span class="value">{t}more{/t}</span></a>
			</li>
			<li class="resize" id="mainColResizer">
				<a id="mainColResizeLink"><span class="value">{t}resize{/t}</span></a>
			</li>
		</ul>
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