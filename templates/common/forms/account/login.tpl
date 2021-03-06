<form action="{$data.current.url}" id="frmLogin" class="commonForm loginForm {if !empty($smarty.post.loginForm)}submited{/if}" method="post" enctype="multipart/form-data">
	{block name='loginFieldset'}
	<fieldset>
		<legend><span class="value">{$legend|default:"{t}login information{/t}"}</span></legend>
		{block name='notifications'}{include file='common/blocks/notifications.tpl'}{/block}
		{if $smarty.const._APP_MAX_LOGIN_ATTEMPTS >= 1 && $data.errors[0].id == 10030}
		{else}
		{$resourceSingular = 'user'}
		{include file='common/formFields/user/socialSignIn.tpl'}
		{include file='common/formFields/user/email.tpl' name='email' label="{t}email{/t}" placeholder="{t}email{/t}" autofocus=true required=true}
		<div class="field line" id="userPasswordLine">
			<div class="label labelBlock">
				<label for="userPassword">{t}password{/t}<span class="required">*</span></label>
			</div>
			<div class="input fieldBlock">
				{include file='common/formFields/user/password.tpl' label="{t}password{/t}" name='password' placeholder="{t}password{/t}" inputOnly=1 required=true autocomplete=true}
				{if $smarty.const._APP_ALLOW_LOST_PASSWORD_RESET}
				{include file='common/blocks/actionBtn.tpl' href=$smarty.const._URL_ACCOUNT_PASSWORD_LOST class='lostPasswordLink' id='lostPasswordLink' label="{t}lost password?{/t}"}
				{/if}
			</div>
		</div>
		{* to be deleted
		<div class="line noLabelBlock buttonsLine">
			<div class="fieldBlock">
				<input type="hidden" name="loginForm" id="loginForm" value="1" />
				<input type="hidden" name="deviceResolution" id="deviceResolution" />
				<input type="hidden" name="deviceOrientation" id="deviceOrientation" />
				{include file='common/blocks/actionBtn.tpl' mode='button' id='validateBtn' type='submit' label="{t}validate{/t}"}
				{if $smarty.const._APP_ALLOW_SIGNUP}
					{include file='common/blocks/actionBtn.tpl' href="{$smarty.const._URL_SIGNUP}{$redir}" label="{t}sign up{/t}"}
				{/if}
			</div>
		</div>
		*}
		{/if}
	</fieldset>
	{/block}
	<fieldset class="actions">
		<input type="hidden" name="loginForm" id="loginForm" value="1" />
		<input type="hidden" name="deviceResolution" id="deviceResolution" />
		<input type="hidden" name="deviceOrientation" id="deviceOrientation" />
		{include file='common/blocks/actionBtn.tpl' mode='button' id='validateBtn' type='submit' label="{t}validate{/t}"}
		{if $smarty.const._APP_ALLOW_SIGNUP}
			{include file='common/blocks/actionBtn.tpl' href="{$smarty.const._URL_SIGNUP}{$redir}" label="{t}sign up{/t}"}
		{/if}		
	</fieldset>
</form>