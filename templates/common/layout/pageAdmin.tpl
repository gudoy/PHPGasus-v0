{extends file='specific/layout/page.tpl'}

{block name='adminMainNav'}
{include file='common/blocks/admin/nav/mainNav.tpl'}
{/block}

{block name='breadcrumbs'}
{include file='common/blocks/header/breadcrumbs.tpl'}
{/block}

{block name='aside'}
<{if $html5}aside{else}div{/if} class="col grid_3" id="sideCol">
	{include file='common/blocks/admin/nav/secondNav.tpl'}
</{if $html5}aside{else}div{/if}
{/block}

{block name='mainCol'}
<div class="col grid_13" id="mainCol">
	{block name='pageContent'}
	{/block}
</div>
{/block}