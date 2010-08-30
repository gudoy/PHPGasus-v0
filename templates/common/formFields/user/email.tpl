{if $mode=='api'}{$postValName='email'}{else}{$postValName='userEmail'}{/if}
<div class="line">
	<div class="labelBlock">
		<label class="span" for="{$postValName}">{t}E-Mail{/t}{* <span class="required">*</span>*}</label>
	</div>
	<div class="fieldBlock">
		<input type="{if $html5}email{else}text{/if}" class="normal" name="{$postValName}" id="{$postValName}" value="{$smarty.post[$postValName]}" />
	</div>
</div>