{extends file='specific/layout/page.tpl'}

{block name='aside'}{/block}

{block name='mainContent'}
<div class="pageFormBlock resetPasswordBlock" id="resetPasswordBlock">
{if $data.success}
	<div class="notification success">
		<p>{t}Password changed! You should now be able to use it to login.{/t}</p>	
	</div>
{else}
	{include file='common/forms/account/password/change.tpl'}
{/if}
</div>
{/block}
