{if $mode=='api'}{$postValName='message'}{else}{$postValName='contactMessage'}{/if}
<div class="line">
	<div class="labelBlock">
		<label class="span" for="{$postValName}">{t}Message{/t}<span class="required">*</span></label>
	</div>
	<div class="fieldBlock">
		<textarea class="normal" cols="40" rows="5" name="{$postValName}" id="{$postValName}" {if $html5}required="required"{/if}>{$smarty.post.$postValName}</textarea>
	</div>
</div>