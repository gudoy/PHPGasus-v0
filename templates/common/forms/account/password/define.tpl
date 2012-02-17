<form action="{$data.current.url}" id="definePasswordForm" method="post">
	<fieldset>
		<legend><span class="value">{t}Define your password{/t}</span></legend>
		{include file='common/formFields/user/password.tpl' name='userNewPassword' label="{t}password{/t}" pattern={$data.dataModel.users.password.pattern} placeholder="{t}your password{/t}" autocomplete=false hint={$data.dataModel.users.password.hint}}
		{include file='common/formFields/user/password.tpl' name='userNewPasswordConfirm' label="{t}confirmation{/t}" placeholder="{t}confirm password{/t}" autocomplete=false}
		<div class="line buttons noLabelBlock buttonsLine">
			<div class="fieldBlock">
				{include file='common/blocks/actionBtn.tpl' mode='button' type='submit' label="{t}validate{/t}"}	
			</div>
		</div>
	</fieldset>
</form>