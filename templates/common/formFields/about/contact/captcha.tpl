{if $mode=='api'}{$postValName='captcha'}{else}{$postValName='contactCaptchaResult'}{/if}
<div class="line">
	<div class="labelBlock">
		<label class="span" for="{$postValName}">{t}Anti-spam{/t}<span class="required">*</span></label>
	</div>
	<div class="fieldBlock">
		<span class="captchaOperation">{$smarty.session.captchaOperation}</span>
		<input type="text" class="sized" size="2" name="{$postValName}" id="{$postValName}" {*value="{$smarty.post[$postValName]}"*} />
		<small class="infos captchaHint">{t}hint{/t}{t}:{/t} {t}The answer is{/t} {$smarty.session.captchaResult}</small>
	</div>
</div>