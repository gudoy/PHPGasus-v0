{extends file='specific/layout/page.tpl'}

{block name='aside'}{/block}

{block name='mainContent'}
<div class="pageFormBlock resetPasswordBlock" id="resetPasswordBlock">
{if $data.success}
	<div class="notification success">
		<p>{t}Password reset! You should now be able to use it to login.{/t}</p>	
	</div>
{else}
	<form action="{$data.current.url}" id="resetPasswordForm" method="post">
		<fieldset>
			<legend><span class="value">{t}Reset your password{/t}</span></legend>
			{include file='common/formFields/user/password.tpl' name='userNewPassword' label='new password' placeholder='enter new password' autocomplete=false}
			{include file='common/formFields/user/password.tpl' name='userNewPasswordConfirm' label='confirm password' placeholder='confirm new password' autocomplete=false}
			<div class="line buttons noLabelBlock buttonsLine">
				<div class="fieldBlock">
					{include file='common/blocks/actionBtn.tpl' mode='button' type='submit' label='validate'}	
				</div>
			</div>
		</fieldset>
	</form>
{/if}
</div>
{/block}
