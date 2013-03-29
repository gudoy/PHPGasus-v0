{if $mode=='api'}{$postValName='lastname'}{else}{$postValName='userLastname'}{/if}
<div class="line">
	<div class="labelBlock">
		<label class="span" for="{$postValName}">{t}last name{/t}{* <span class="required">*</span>*}</label>
	</div>
	<div class="fieldBlock">
		<input type="text" class="normal" name="{$postValName}" id="{$postValName}" value="{$smarty.post[$postValName]}" />
	</div>
</div>