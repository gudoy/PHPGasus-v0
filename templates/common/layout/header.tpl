{block name='header'}
{if $view.header !== false}
<header class="header" id="header">

	{block name='languageSelection'}
	{include file='common/blocks/header/languages.tpl'}
	{/block}
	
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
	
    {block name='loggedUserBlock'}
    {*if !defined(_APP_USE_MY_ACCOUNT_BLOCK_V2) || !$smarty.const._APP_USE_MY_ACCOUNT_BLOCK_V2}
    {include file='common/blocks/header/loggedUserBlock.tpl'}
    {else}
    {include file='common/blocks/header/account/myProfile.tpl'}
    {/if*}
    {/block}
	
	{block name='headerNav'}
	{include file='common/blocks/header/nav.tpl'}
	{/block}
	
</header>
{/if}
{/block}