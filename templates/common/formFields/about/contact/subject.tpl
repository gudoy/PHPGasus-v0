{if $mode=='api'}{$postValName='subject'}{else}{$postValName='contactSubject'}{/if}
<div class="line">
	<div class="labelBlock">
		<label class="span" for="{$postValName}">{t}Subject{/t}<span class="required">*</span></label>
	</div>
	<div class="fieldBlock">
		<select name="{$postValName}" id="{$postValName}" {if $html5}required="required"{/if}>
			<option value="webClicInfo" {if $postValName === 'webClicInfo'}selected="selected"{/if}>{t}Ask for information{/t}</option>
			<option value="webClicInfo" {if $postValName === 'webClicInfo'}selected="selected"{/if}>{t}Initial contact{/t}</option>
			<option value="webClicProjet" {if $postValName === 'webClicProjet'}selected="selected"{/if}>{t}Talk about a project{/t}</option>
			<option value="webClicJob" {if $postValName === 'webClicJob'}selected="selected"{/if}>{t}Work at{/t} {$smarty.const._APP_OWNER_NAME}</option>
			<option value="WebClicInvestisseurs" {if $postValName === 'WebClicInvestisseurs'}selected="selected"{/if}>{t}Investor contact{/t}</option>
			<option value="WebClicPresse" {if $postValName === 'WebClicPresse'}selected="selected"{/if}>{t}Press contact{/t}</option>
		</select>
	</div>
</div>