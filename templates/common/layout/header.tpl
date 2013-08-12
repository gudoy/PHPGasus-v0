{block name='header'}
{if $view.header !== false}
<header id="header" class="col header" role="banner">
	
	{block name='headerHeader'}
	<header class="headerHeader" id="headerHeader">
		{block name='headerHeaderContent'}
		{block name='logoAndBaseline'}
		{block name='logo'}
		<h1 id="branding" class="logoBlock lv1Title vcard">
			<a class="logo" rel="home" id="logo" href="{$smarty.const._URL}">
				<span class="fn n">{$smarty.const._APP_TITLE}</span>
			</a>
		</h1>
		{/block}
		{block name='baseline'}{/block}
		{/block}
		{/block}
	</header>
	{/block}
	
	{block name='headerContent'}
	<div id="headerContent" class="headerContent">
		{block name='headerContentContent'}
		{block name='headerNav'}{include file='common/blocks/header/nav.tpl'}{/block}
		{/block}
	</div>
	{/block}
	
	{block name='headerFooter'}
	<footer id="headerFooter" class="headerFooter">
		{block name='headerFooterContent'}
		{block name='languageSelection'}{include file='common/blocks/header/languages.tpl'}{/block}
    	{block name='loggedUserBlock'}{include file='common/blocks/header/account/myProfile.tpl'}{/block}
    	{/block}
    </footer>
	{/block}
	
</header>
{/if}
{/block}