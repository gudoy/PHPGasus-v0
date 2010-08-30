<div class="pageContent adminPageContent" id="{$data.view.name}Content">
	
	{if $data.success}
		TODO: login success message/page/redirection
	{else}
		<div class="box loginBlock" id="loginBlock">
			<div class="loginFormBlock" id="loginFormBlock">
				{include file='common/forms/account/login.tpl' mode='api'}
			</div>
		</div>
	{/if}
	

</div>