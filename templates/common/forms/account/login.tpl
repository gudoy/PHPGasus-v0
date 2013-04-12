<form action="{$data.current.url}" id="frmLogin" class="commonForm loginForm" method="post" enctype="multipart/form-data">
	{block name='loginFieldset'}
	<fieldset>
		<legend><span class="value">{$legend|default:"{t}login information{/t}"}</span></legend>
		{block name='notifications'}{include file='common/blocks/notifications.tpl'}{/block}
		{if $smarty.const._APP_MAX_LOGIN_ATTEMPTS >= 1 && $data.errors[0].id == 10030}
		{else}
		{$resourceSingular = 'user'}
		{if $smarty.const._APP_USE_FACEBOOK_LOGIN || $smarty.const._APP_USE_TWITTER_LOGIN || $smarty.const._APP_USE_GOOGLE_LOGIN}
		<div class="line noLabelBlock" id="loginWithLine">
			<div class="fieldBlock">
				<div class="actions">
					{if $smarty.const._APP_USE_FACEBOOK_LOGIN}
					{block name='facebookLoginButton'}
					<div class="signInWithAction fb-login-button" data-show-faces="false" data-width="200" data-size="xlarge" data-max-rows="1" data-scope="email,user_birthday"></div>
					{/block}
					{/if}
					{if $smarty.const._APP_USE_GOOGLE_LOGIN}
					{block name='googleLoginButton'}
					<span class="signInWithAction" id="signinButton">
					  <span
					    class="g-signin"
					    data-height="tall"
					    data-callback="signinCallback"
					    data-clientid="{$smarty.const._GOOGLE_CLIENT_ID}"
					    data-cookiepolicy="single_host_origin"
					    data-requestvisibleactions="http://schemas.google.com/AddActivity"
					    data-scope="https://www.googleapis.com/auth/plus.login">
					  </span>
					</span>
					{/block}
					{/if}
					{if $smarty.const._APP_USE_TWITTER_LOGIN}
					{block name='twitterLoginButton'}
					{include file='common/blocks/actionBtn.tpl' href="{$smarty.const._URL}account/login/with/twitter" class='signInWithAction' id='signInWithTwitterAction' label="{t}sign in with twitter{/t}"}
					{/block}
					{/if}
				</div>
			</div>
			<div class="sep">
				<span class="or">{t}or{/t}</span>	
			</div>
		</div>
		{/if}
		{include file='common/formFields/user/email.tpl' name='email' label="{t}email{/t}" placeholder="{t}email{/t}" autofocus=true required=true}
		<div class="line" id="userPasswordLine">
			<div class="labelBlock">
				<label for="userPassword">{t}password{/t}<span class="required">*</span></label>
			</div>
			<div class="fieldBlock">
				{include file='common/formFields/user/password.tpl' label="{t}password{/t}" name='password' placeholder="{t}password{/t}" inputOnly=1 required=true autocomplete=true}
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
		{/if}
	</fieldset>
	{/block}
</form>