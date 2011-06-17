<form method="post" action="{$smarty.const._URL}{$smarty.server.REQUEST_URI|regex_replace:"/^\//":""|replace:'&':'&amp;'}" id="frmSubscribeNewsletter">
	<fieldset>
		<legend>{t}Personal data{/t}</legend>
		
		{include file='common/forms/common/fields/user/firstname.tpl'}
		
		{include file='common/forms/common/fields/user/lastname.tpl'}
		
		{include file='common/forms/common/fields/user/mail.tpl'}
		
		{include file='common/forms/common/fields/user/hasiPhone.tpl'}
		
		<div class="line ninja" id="userMobilePhoneNbLine">
			<div class="labelBlock">
				<label for="userMobilePhoneNb">{t}Tel (mobile){/t}</label>
			</div>
			<div class="fieldBlock">
				<input type="text" class="normal" id="userMobilePhoneNb" name="userMobilePhoneNb" value="{$smarty.post.userMobilePhoneNb|default:''}" />
			</div>
		</div>
		
		{include file='common/forms/common/fields/user/newsletter.tpl' lastline=true}
		
	</fieldset>
	
	{include file='common/forms/common/fields/legendDetail.tpl'}
	
	<div class="line noLabelBlock buttonsLine">
		<div class="fieldBlock">
			<input type="hidden" name="subscribeNewsletterForm" id="subscribeNewsletterForm" value="1" />
			{include file='common/blocks/common/actionBtnInput.tpl' id='validateBtn' label='validate'|gettext}
		</div>
	</div>
	
</form>