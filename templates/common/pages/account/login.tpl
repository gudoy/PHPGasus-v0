{extends file='specific/layout/page.tpl'}
{block name='pageContent'}

<div class="strate container_16">
	
{if $data.success}
	<p>{t}You are now logged in!{/t}</p>
{else}
	<div class="loginBlock" id="loginBlock">
		{include file='common/forms/account/login.tpl' legend='log in'|gettext}
	</div>
{/if}

</div>
{/block}