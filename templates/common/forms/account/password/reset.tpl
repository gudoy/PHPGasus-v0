<form action="{$data.current.url}" id="resetPasswordForm" method="post">
	<fieldset>
		<legend><span class="value">{t}Reset your password{/t}</span></legend>
		{include file='common/formFields/user/password.tpl' name='userNewPassword' label={'new password'|gettext} pattern={$data.dataModel.users.password.pattern} placeholder={'new password'|gettext} autocomplete=false hint={$data.dataModel.users.password.hint}}
		{include file='common/formFields/user/password.tpl' name='userNewPasswordConfirm' label={'confirm password'|gettext} placeholder={'confirm password'|gettext} autocomplete=false}
		<div class="line buttons noLabelBlock buttonsLine">
			<div class="fieldBlock">
				{include file='common/blocks/actionBtn.tpl' mode='button' type='submit' label={'validate'|gettext}}	
			</div>
		</div>
	</fieldset>
</form>