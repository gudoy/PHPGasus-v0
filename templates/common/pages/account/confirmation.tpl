{extends file='specific/layout/page.tpl'}

{block name='mainContent'}
<section class="accountConfirmation" id="accountConfirmation">

	{if !$smarty.const._APP_PASS_FORCE_DEFINE_ON_1ST_LOGIN || $data.success}
	<header>
		<h2>{t}Account Confimed!{/t}</h2>
	</header>
	<div class="content">
		<p class="notification success">
			{t escape=no}Thanks! Your account is activated. Your are now able to login.{/t}
		</p>
		<nav class="actions">
			{include file='common/blocks/actionBtn.tpl' href={$smarty.const._URL_HOME} id='goToHomeBtn' class='action goToHomeBtn' label="{t}back to home{/t}"}<span class="or">{t}or{/t}</span>
			{include file='common/blocks/actionBtn.tpl' href={$smarty.const._URL_LOGIN} id='goToLoginBtn' class='action goToLoginBtn' label="{t}login{/t}"}
		</nav>
	</div>
	{elseif $smarty.const._APP_PASS_FORCE_DEFINE_ON_1ST_LOGIN}
	<header>
		<h2>{t}Account activation{/t}</h2>
	</header>
	<div class="content">
		<p>
		{t escape=no}To finish your account activation and be able to login, please define your password:{/t}
		</p>
		{include file='common/forms/account/password/define.tpl'}
	</div>
	{/if}
</section>
{/block}