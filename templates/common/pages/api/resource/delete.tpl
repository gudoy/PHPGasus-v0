{extends file='specific/layout/page.tpl'}

{block name='mainContent'}

	{$resourceName=$data.view.resourceName}

	{if $data.env.type === 'dev'}
	<section class="apiDataSection" id="apiDataSection">
		<header class="titleBlock">
			<h2 class="title">{t}data{/t}</h2>
		</header>
		<div class="content">
			{if $data.success}
				<div class="notificationsBlock">
					<p class="notification success">
						{t}The resource has been successfully deleted!{/t}
					</p>
				</div>
			{/if}
		</div>
	</section>
	{/if}
	
{/block}