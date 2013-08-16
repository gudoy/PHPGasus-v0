	<form action="{$data.current.url}" id="resetPasswordForm" method="post">
		<fieldset>
			<legend><span class="value">{t}Change your password{/t}</span></legend>
			{if !$data.logged}
			{include file='common/formFields/user/email.tpl' name='userEmail' label='email' placeholder='email@example.com' autocomplete=false}
			{/if}
			{include file='common/formFields/user/password.tpl' name='userOldPassword' label="{t}current password{/t}" placeholder="{t}current password{/t}" autocomplete=false}
			{include file='common/formFields/user/password.tpl' name='userNewPassword' label="{t}new password{/t}" pattern={$data._columns.users.password.pattern} placeholder="{t}new password{/t}" autocomplete=false hint={$data._columns.users.password.hint}}
			{include file='common/formFields/user/password.tpl' name='userNewPasswordConfirm' label="{t}new password confirmation{/t}" placeholder="{t}confirm new password{/t}" autocomplete=false}
			<div class="line buttons noLabelBlock buttonsLine">
				<div class="fieldBlock">
					{include file='common/blocks/actionBtn.tpl' mode='button' type='submit' label="{t}validate{/t}"}	
				</div>
			</div>
		</fieldset>
	</form>