{nocache}
<{if $html5}nav{else}div{/if} class="navBlock mainNavBlock" id="mainNavBlock">

	{block name='adminMainNav'}{/block}
	
	{block name='beforeMainNav'}{/block}
	{block name='mainNav'}
	{include file='common/blocks/header/nav/mainNav.tpl'}
	{/block}
	{block name='afterMainNav'}{/block}
	
	{block name='beforeAccountNav'}{/block}
	{block name='accountNav'}
	{if $smarty.const._APP_USE_ACCOUNTS}
	<ul class="nav section accountNavList" id="accountNav">
		{if $data.logged}
		{block name='accountNavLogoutLink'}
		<li class="item first last">
			<a class="logoutLink" href="{$smarty.const._URL_LOGOUT}"><span class="value">{t}logout{/t}</span></a>
		</li>
		{/block}
		{else}
		<li class="item {if !$smarty.const._APP_ALLOW_SIGNUP}last{/if}">
			<a class="loginLink" href="{$smarty.const._URL_LOGIN}"><span class="value">{t}login{/t}</span></a>
		</li>
		{if $smarty.const._APP_ALLOW_SIGNUP}
		<li class="item last">
			<a class="signupLink" href="{$smarty.const._URL_SIGNUP}"><span class="value">{t}register{/t}</span></a>
		</li>
		{/if}
		{/if}
	</ul>
	{/if}
	{/block}
	{block name='afterAccountNav'}{/block}
	
</{if $html5}nav{else}div{/if}>
{/nocache}