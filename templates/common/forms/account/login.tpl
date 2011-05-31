<form action="{$data.current.url}" id="frmLogin" class="commonForm loginForm" method="post" enctype="multipart/form-data">
	
	<fieldset>
		<legend><span class="value">{$legend|default:'login data'|gettext}</span></legend>
		{$resourceSingular=user}
		{include file='common/formFields/user/email.tpl' name='email' label={'email'|gettext} placeholder='email@yourdomain.com' autofocus=true}
		
		{include file='common/formFields/user/password.tpl' label={'password'|gettext} name='password' placeholder='password'}
		
		<div class="line noLabelBlock buttonsLine">
			<div class="fieldBlock">
				<input type="hidden" name="loginForm" id="loginForm" value="1" />
				<input type="hidden" name="deviceResolution" id="deviceResolution" />
				<input type="hidden" name="deviceOrientation" id="deviceOrientation" />
				{include file='common/blocks/actionBtn.tpl' mode='button' btnId='validateBtn' btnType='submit' btnLabel='log in'|gettext}
				{if $smarty.const._APP_ALLOW_SIGNUP}
					{include file='common/blocks/actionBtn.tpl' btnHref="{$smarty.const._URL_SIGNUP}{$redir}" btnLabel='register'|gettext}
				{/if}
			</div>
		</div>

	</fieldset>

</form>
