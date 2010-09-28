{*include file='common/block/forms/users/create.tpl'*}

<form action="" id="frmSignup" class="commonForm signupForm" method="post" enctype="multipart/form-data">
	
	<fieldset>
		<legend class="hidden"><span>{$legend|default:{'personal data'|gettext}}</span></legend>
		
		{include file='common/formFields/user/email.tpl'}
		
		{include file='common/formFields/user/password.tpl'}
		
		{include file='common/formFields/user/passwordConfirmation.tpl'}
		
		{include file='common/formFields/user/firstName.tpl'}
		
		{include file='common/formFields/user/lastName.tpl'}
		
		{include file='common/formFields/user/address.tpl'}

		{include file='common/formFields/user/city.tpl'}
				
		{include file='common/formFields/user/zipcode.tpl'}
		
		{include file='common/formFields/user/country.tpl'}
		
		{include file='common/formFields/user/company.tpl'}

		{include file='common/formFields/user/TCSaccepted.tpl'}
		
		{include file='common/formFields/user/TUaccepted.tpl'}
		
		<div class="line noLabelBlock buttonsLine">
			<div class="fieldBlock">
				<input type="hidden" name="signupForm" id="signupForm" value="1" />
				{include file='common/blocks/actionBtn.tpl' mode='button' btnId='validateBtn' btnType='submit' btnLabel='sign up'|gettext}
			</div>
		</div>

	</fieldset>

</form>