{if $mode=='api'}{$postValName='TU_accepted'}{else}{$postValName='userTU_accepted'}{/if}
<div class="line">
	<div class="fieldBlock noLabelBlock">
		<input type="checkbox" class="multi" name="{$postValName}" id="{$postValName}" value="1" {if $smarty.post[$postValName]}checked="checked"{/if} />
		<label class="span multi" for="{$postValName}">{t}I accept Terms of Use{/t}{* <span class="required">*</span>*}</label>
	</div>
</div>