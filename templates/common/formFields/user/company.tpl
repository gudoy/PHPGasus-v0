{if $mode=='api'}{$postValName='company'}{else}{$postValName='userCompany'}{/if}
<div class="line row" id="userCompanyRow">
	<div class="labelBlock">
		<label class="span" for="{$postValName}">{t}company{/t}</label>
	</div>
	<div class="fieldBlock">
		<input type="text" class="normal" name="{$postValName}" id="{$postValName}" value="{$smarty.post[$postValName]}" />
		<span class="optional">{t}(optional){/t}</span>
	</div>
</div>