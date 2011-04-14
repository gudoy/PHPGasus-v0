{extends file='specific/layout/page.tpl'}

{block name='asideContent'}
{include file='common/blocks/api/resource/dataModel.tpl'}
{/block}

{block name='mainColContent'}

	{$resourceName = $data.current.resource}
	{$isAdminView = in_array('admin', explode(' ',$view.smartclasses))}

	<section class="apiDataSection" id="apiDataSection">
		<header class="titleBlock">
			<h2 class="title">{t}data{/t}</h2>
		</header>
		<div class="content">
		{foreach $data[$resourceName] as $row}
			{include file='common/blocks/api/resource/retrieve.tpl' item=$row}
		{foreachelse}
			<p>
			{t}Sorry, there's currently no items for this resource{/t}
			</p>
		{/foreach}
		</div>
	</section>
	
{/block}