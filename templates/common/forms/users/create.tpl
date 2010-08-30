<form action="" id="frmUserCreate" class="commonForm userCreateForm" method="post" enctype="multipart/form-data">
	
	<fieldset>
		<legend><span>{t}personal data{/t}</span></legend>
		
		{include file='common/formFields/user/firstName.tpl'}
		
		{include file='common/formFields/user/lastName.tpl'}
		
		{include file='common/formFields/user/email.tpl'}
		
		{include file='common/formFields/user/password.tpl'}
		
		{if $mode && $mode !== 'api'}
		{include file='common/formFields/user/passwordConfirmation.tpl'}
		{/if}
		
		{include file='common/formFields/user/country.tpl'}
		
		{include file='common/formFields/user/city.tpl'}
		
		{include file='common/formFields/user/zipcode.tpl'}
		
		{include file='common/formFields/user/address.tpl'}
		
		{include file='common/formFields/user/company.tpl'}
		
		{include file='forms/common/fields/legendDetail.tpl'}
		<div class="line noLabelBlock buttonsLine">
			<div class="fieldBlock">
				<input type="hidden" name="userCreate" id="userCreate" value="1" />
				{include file='common/blocks/common/actionBtn.tpl' mode='button' btnId='validateBtn' btnType='submit' btnLabel='create'|gettext}
			</div>
		</div>

	</fieldset>

</form>