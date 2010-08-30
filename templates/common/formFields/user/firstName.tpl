{if $mode=='api'}{$postValName='first_name'}{else}{$postValName='userFirst_name'}{/if}
<div class="line">
	<div class="labelBlock">
		<label class="span" for="{$postValName}">{t}first name{/t}{* <span class="required">*</span>*}</label>
	</div>
	<div class="fieldBlock">
		<input type="text" class="normal" name="{$postValName}" id="{$postValName}" value="{$smarty.post[$postValName]}" />
	</div>
</div>