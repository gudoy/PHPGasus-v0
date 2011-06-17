<form action="{$data.current.url}" id="frmLogin" class="commonForm loginForm" method="post" enctype="multipart/form-data">
	
	<fieldset>
		<legend><span class="value">{$legend|default:'login data'|gettext}</span></legend>
		{$resourceSingular=user}
		{include file='common/formFields/user/email.tpl' name='email' label={'email'|gettext} placeholder='email@yourdomain.com' autofocus=true}
		
		<div class="line">
			<div class="labelBlock">
				<label for="userPassword">
					{t}password{/t}
				</label>
			</div>
			<div class="fieldBlock">
				{include file='common/formFields/user/password.tpl' label={'password'|gettext} name='password' placeholder='password' inputOnly=1}
				{if $smarty.const._APP_ALLOW_LOST_PASSWORD_RESET}
				{include file='common/blocks/actionBtn.tpl' href=$smarty.const._URL_ACCOUNT_PASSWORD_LOST classes='lostPasswordLink' id='lostPasswordLink' label='lost password?'|gettext}
				{/if}
			</div>
		</div>
		
		<div class="line noLabelBlock buttonsLine">
			<div class="fieldBlock">
				<input type="hidden" name="loginForm" id="loginForm" value="1" />
				<input type="hidden" name="deviceResolution" id="deviceResolution" />
				<input type="hidden" name="deviceOrientation" id="deviceOrientation" />
				{include file='common/blocks/actionBtn.tpl' mode='button' id='validateBtn' type='submit' label='log in'|gettext}
				{if $smarty.const._APP_ALLOW_SIGNUP}
					{include file='common/blocks/actionBtn.tpl' href="{$smarty.const._URL_SIGNUP}{$redir}" label='register'|gettext}
				{/if}
			</div>
		</div>

	</fieldset>

</form>
