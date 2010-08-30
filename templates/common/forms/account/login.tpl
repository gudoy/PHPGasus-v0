<form action="" id="frmLogin" class="commonForm loginForm" method="post" enctype="multipart/form-data">
	
	<fieldset>
		<legend><span>{$legend|default:'login data'|gettext}</span></legend>
		
		{include file='common/formFields/user/email.tpl'}
		
		{include file='common/formFields/user/password.tpl'}
		
		<div class="line noLabelBlock buttonsLine">
			<div class="fieldBlock">
				<input type="hidden" name="loginForm" id="loginForm" value="1" />
				{block name='beforeLoginBtn'}{/block}
				{include file='common/blocks/actionBtn.tpl' mode='button' btnId='validateBtn' btnType='submit' btnLabel='log in'|gettext}
				{block name='afterLoginBtn'}{/block}
				{*include file='common/blocks/actionBtn.tpl' btnHref=$smarty.const._URL_SIGNUP btnLabel='register'|gettext*}
			</div>
		</div>

	</fieldset>

</form>
