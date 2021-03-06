{extends file='specific/layout/page.tpl'}

{block name='notifications'}{/block}

{block name='aside'}{/block}

{block name='mainContent'}
	
<div class="pageFormBlock loginBlock" id="loginBlock">
{if $data.success}
	<p class="notification success">{t}You are now logged in!{/t}</p>
{else}
    {block name='loginForm'}
	{include file='common/forms/account/login.tpl' legend="{t}log in{/t}"}
	{/block}
{/if}
</div>

{/block}