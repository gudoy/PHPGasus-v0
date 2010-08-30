<div class="line {if $lastline}lastline{/if}" id="userMobilePhoneNbLine">
	<div class="labelBlock">
		<label for="userMobilePhoneNb">{t escape=no}Mobile Phone #  <small>(with country code. ex: +33 for France)</small>{/t}</label>
	</div>
	<div class="fieldBlock">
		<input type="text" class="normal" id="userMobilePhoneNb" name="userMobilePhoneNb" value="{$smarty.post.userMobilePhoneNb|default:''}" />
	</div>
</div>