{extends file='specific/layout/page.tpl'}

{block name='aside'}{/block}

{block name='mainContent'}
<div class="pageFormBlock lostPasswordBlock" id="lostPasswordBlock">
{if $data.success}
	{block name='lostPasswordSuccess'}
	<div class="notification success">
		<p>{t}Email sent! You should receive it within a few seconds.{/t}</p>	
	</div>
	{/block}
{else}
	<form action="{$data.current.url}" id="lostPasswordForm" method="post">
		<fieldset>
			<legend><span class="value">{t}lost password?{/t}</span></legend>
			<div class="line">
				<p>{t}We'll send you an email with a link to reset your password.{/t}</p>
			</div>
			{include file='common/formFields/user/email.tpl' name='userEmail' label={'email'|gettext} placeholder={'enter your email'|gettext}}
			<div class="line buttons noLabelBlock buttonsLine">
				<div class="fieldBlock">
					{include file='common/blocks/actionBtn.tpl' mode='button' type='submit' label={'ok'|gettext}}	
				</div>
			</div>
		</fieldset>
	</form>
{/if}
</div>
{/block}
