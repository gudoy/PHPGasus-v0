{extends file='specific/layout/page.tpl'}

{block name='mainCol'}
<div class="col" id="mainCol">
{$smarty.block.parent}
</div>
{/block}

{block name='asideContent'}
{include file='common/blocks/api/commonParams.tpl'}
{/block}

{block name='mainColContent'}
<section class="apiSection" id="apiSection">
		
	<div class="block apisBlock" id="apisBlock">
		
		<header class="titleBlock">
			<h2 class="title">{t}APIs{/t}</h2>
		</header>
		
		{block name='exposedApiContent'}{/block}
		
	</div>
	
</section>
{/block}