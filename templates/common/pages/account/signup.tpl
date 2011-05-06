{extends file='specific/layout/page.tpl'}

{block name='mainContent'}

{if $data.success}
	<p>{t}Thanks for your registration!{/t}</p>
	
	{include file='common/blocks/common/actionBtn.tpl' btnHref={$smarty.get.successRedirect|default:{$smarty.const._URL_HOME}} btnId='continueBtn' btnLabel='continue'|gettext}
{else}		
	<div class="box signupBlock" id="signupBlock">
		{include file='common/forms/account/signup.tpl' legend='Register'|gettext}
	</div>
{/if}

{/block}