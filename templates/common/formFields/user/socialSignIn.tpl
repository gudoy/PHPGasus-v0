{if $smarty.const._APP_USE_FACEBOOK_LOGIN || $smarty.const._APP_USE_TWITTER_LOGIN || $smarty.const._APP_USE_GOOGLE_LOGIN}
<div class="line" id="loginWithLine">
	<div class="actions">
		{if $smarty.const._APP_USE_FACEBOOK_LOGIN}
		{block name='facebookLoginButton'}{include file='common/blocks/actionBtn.tpl' class="signInWithAction" id="signinWithFacebookAction" label="{t}Sign in with Facebook{/t}"}{/block}
		{/if}
		{if $smarty.const._APP_USE_GOOGLE_LOGIN}
		{block name='googleLoginButton'}{include file='common/blocks/actionBtn.tpl' class="signInWithAction" id="signinWithGoogleAction" label="{t}Sign in with Google{/t}"}{/block}
		{/if}
		{if $smarty.const._APP_USE_TWITTER_LOGIN}
		{block name='twitterLoginButton'}{include file='common/blocks/actionBtn.tpl' class="signInWithAction" id="signinWithTwitterAction" label="{t}Sign in with Twitter{/t}"}{/block}
		{/if}
	</div>
	<div class="sep">
		<span class="or">{t}or{/t}</span>	
	</div>
</div>
{/if}
{* if $smarty.const._APP_USE_FACEBOOK_LOGIN || $smarty.const._APP_USE_TWITTER_LOGIN || $smarty.const._APP_USE_GOOGLE_LOGIN}
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
{/if *}