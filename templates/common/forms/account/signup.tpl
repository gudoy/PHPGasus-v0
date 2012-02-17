<form action="{$data.current.url}" id="frmSignup" class="commonForm signupForm" method="post" enctype="multipart/form-data">
	
	<fieldset>
		<legend><span class="value">{$legend|default:"{t}your information{/t}"</span></legend>
		
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
				{include file='common/blocks/actionBtn.tpl' mode='button' id='validateBtn' type='submit' label="{t}sign up{/t}"}
			</div>
		</div>

	</fieldset>

</form>