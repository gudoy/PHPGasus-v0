<form action="{$data.current.url}" id="resetPasswordForm" method="post">
	<fieldset>
		<legend><span class="value">{t}Reset your password{/t}</span></legend>
		{include file='common/formFields/user/password.tpl' name='userNewPassword' label="{t}new password{/t}" pattern={$data._columns.users.password.pattern} placeholder="{t}new password{/t}" autocomplete=false hint={$data._columns.users.password.hint}}
		{include file='common/formFields/user/password.tpl' name='userNewPasswordConfirm' label="{t}confirm password{/t}"  placeholder="{t}confirm password{/t}" autocomplete=false}
		<div class="line buttons noLabelBlock buttonsLine">
			<div class="fieldBlock">
				{include file='common/blocks/actionBtn.tpl' mode='button' type='submit' label="{t}validate{/t}"}	
			</div>
		</div>
	</fieldset>
</form>