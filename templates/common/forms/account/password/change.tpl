	<form action="{$data.current.url}" id="resetPasswordForm" method="post">
		<fieldset>
			<legend><span class="value">{t}Change your password{/t}</span></legend>
			{if !$data.logged}
			{include file='common/formFields/user/email.tpl' name='userEmail' label='email' placeholder='email@example.com' autocomplete=false}
			{/if}
			{include file='common/formFields/user/password.tpl' name='userOldPassword' label={'current password'|gettext} placeholder={'current password'|gettext} autocomplete=false}
			{include file='common/formFields/user/password.tpl' name='userNewPassword' label={'new password'|gettext} pattern={$data.dataModel.users.password.pattern} placeholder={'new password'|gettext} autocomplete=false hint={$data.dataModel.users.password.hint}}
			{include file='common/formFields/user/password.tpl' name='userNewPasswordConfirm' label={'new password confirmation'|gettext} placeholder={'confirm new password'|gettext} autocomplete=false}
			<div class="line buttons noLabelBlock buttonsLine">
				<div class="fieldBlock">
					{include file='common/blocks/actionBtn.tpl' mode='button' type='submit' label={'validate'|gettext}}	
				</div>
			</div>
		</fieldset>
	</form>