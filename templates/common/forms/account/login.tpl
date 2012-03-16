{if $smarty.const._APP_MAX_LOGIN_ATTEMPTS >= 1 && $data.errors[0].id == 10030}
{else}
<form action="{$data.current.url}" id="frmLogin" class="commonForm loginForm" method="post" enctype="multipart/form-data">
	{block name='loginFieldset'}
	<fieldset>
		<legend><span class="value">{$legend|default:"{t}login information{/t}"}</span></legend>
		{$resourceSingular = 'user'}
		{include file='common/formFields/user/email.tpl' name='email' label="{t}email{/t}" placeholder="{t}email{/t}" autofocus=true required=true}
		
		<div class="line" id="userPasswordLine">
			<div class="labelBlock">
				<label for="userPassword">
					{t}password{/t}<span class="required">*</span>
				</label>
			</div>
			<div class="fieldBlock">
				{include file='common/formFields/user/password.tpl' label="{t}password{/t}" name='password' placeholder="{t}password{/t}" inputOnly=1 required=true}
				{if $smarty.const._APP_ALLOW_LOST_PASSWORD_RESET}
				{include file='common/blocks/actionBtn.tpl' href=$smarty.const._URL_ACCOUNT_PASSWORD_LOST class='lostPasswordLink' id='lostPasswordLink' label="{t}lost password?{/t}"}
				{/if}
			</div>
		</div>
		
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

	</fieldset>
	{/block}
</form>
{/if}
