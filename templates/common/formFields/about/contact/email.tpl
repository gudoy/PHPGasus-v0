{if $mode=='api'}{$postValName='email'}{else}{$postValName='contactEmail'}{/if}
<div class="line">
	<div class="labelBlock">
		<label class="span" for="{$postValName}">{t}E-Mail{/t}<span class="required">*</span></label>
	</div>
	<div class="fieldBlock">
		<input type="email" class="normal" name="{$postValName}" id="{$postValName}" required="required" value="{$smarty.post[$postValName]}" />
	</div>
</div>