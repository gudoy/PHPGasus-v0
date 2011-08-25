{extends file='specific/layout/page.tpl'}

{block name='mainContent'}

	{$resourceName = $data.current.resource}
	{$isAdminView = in_array('admin', explode(' ',$view.smartclasses))}

	{if $data.env.type === 'dev'}
	<section class="apiDataSection" id="apiDataSection">
		<header class="titleBlock">
			<h2 class="title">{t}data{/t}</h2>
		</header>
		<div class="content">
			{$items=$items|default:$data[$resourceName]}
			<div class="apiItemBlock">
				{include file='common/blocks/api/resource/retrieve.tpl'}
			</div>
		</div>
	</section>
	{/if}
	
{/block}


