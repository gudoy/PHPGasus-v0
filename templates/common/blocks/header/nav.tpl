<nav class="navBlock mainNavBlock" id="mainNavBlock">
	
	<h2 class="title" id="mainNavTitle">{t}Menu{/t}</h2>
	
	{include file='common/blocks/header/nav/main.tpl'}
	
	{block name='accountNav'}
	{if $smarty.const._APP_USE_ACCOUNTS && _APP_ALLOW_SIGNUP}
	<ul class="nav accountNavList" id="accountNav">
	{if $data.logged}
		{block name='accountNavLogoutLink'}
		<li class="item item-lv1 first last">
			<a class="logoutLink" href="{$smarty.const._URL_LOGOUT}"><span class="value">{t}logout{/t}</span></a>
		</li>
		{/block}
		{else}
		<li class="item item-lv1{if !$smarty.const._APP_ALLOW_SIGNUP} last{/if}">
			<a class="loginLink" href="{$smarty.const._URL_LOGIN}"><span class="value">{t}login{/t}</span></a>
		</li>
		{if $smarty.const._APP_ALLOW_SIGNUP}
		<li class="item item-lv1 last">
			<a class="signupLink" href="{$smarty.const._URL_SIGNUP}"><span class="value">{t}sign up{/t}</span></a>
		</li>
		{/if}
	{/if}
	</ul>
	{/if}
	{/block}
	
</nav>