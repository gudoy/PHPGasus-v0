{extends file='specific/layout/page.tpl'}

{block name='mainContent'}

{if $data.success}
	<p>{t}Thanks for your registration!{/t}</p>
	
	{include file='common/blocks/common/actionBtn.tpl' href={$smarty.get.successRedirect|default:{$smarty.const._URL_HOME}} id='continueBtn' label='continue'|gettext}
{else}		
	<div class="box signupBlock" id="signupBlock">
		{include file='common/forms/account/signup.tpl' legend='Register'|gettext}
	</div>
{/if}

{/block}