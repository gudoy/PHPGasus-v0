	<form action="{$data.current.url}" id="resetPasswordForm" method="post">
		<fieldset>
			<legend><span class="value">{t}Change your password{/t}</span></legend>
			{if !$data.logged}
			{include file='common/formFields/user/email.tpl' name='userEmail' label='email' placeholder='email@example.com' autocomplete=false}
			{/if}
			{include file='common/formFields/user/password.tpl' name='userOldPassword' label='current password' placeholder='current password' autocomplete=false}
			{include file='common/formFields/user/password.tpl' name='userNewPassword' label='new password' placeholder='new password' autocomplete=false}
			{include file='common/formFields/user/password.tpl' name='userNewPasswordConfirm' label='confirm password' placeholder='confirm new password' autocomplete=false}
			<div class="line buttons noLabelBlock buttonsLine">
				<div class="fieldBlock">
					{include file='common/blocks/actionBtn.tpl' mode='button' type='submit' label='validate'}	
				</div>
			</div>
		</fieldset>
	</form>