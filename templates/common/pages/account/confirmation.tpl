{extends file='specific/layout/page.tpl'}

{block name='mainContent'}

<section class="accountConfirmation" id="accountConfirmation">
	<header>
		<h2>{t}Account Confimed!{/t}</h2>
	</header>
	<div class="content">
		<p>
			{t escape=no}Thanks! Your account is activated. Your are now able to login.{/t}
		</p>
	</div>
	<nav class="actions">
		{include file='common/blocks/actionBtn.tpl' href={$smarty.const._URL_HOME} id='goToHomeBtn' classes='action goToHomeBtn' label='back to home'|gettext}<span class="or">{t}or{/t}</span>
		{include file='common/blocks/actionBtn.tpl' href={$smarty.const._URL_LOGIN} id='goToLoginBtn' classes='action goToLoginBtn' label='login'|gettext}
	</nav>
</section>

{/block}