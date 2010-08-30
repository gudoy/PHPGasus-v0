{extends file='specific/layout/page.tpl'}

{block name='adminMainNav'}
{include file='common/blocks/admin/nav/mainNav.tpl'}
{/block}

{block name='breadcrumbs'}
{include file='common/blocks/header/breadcrumbs.tpl'}
{/block}

{block name='aside'}
<div class="col grid_3" id="sideCol">
	{include file='common/blocks/admin/nav/secondNav.tpl'}
</div>
{/block}

{block name='mainCol'}
<div class="col grid_13" id="mainCol">
	{block name='pageContent'}
	{/block}
</div>
{/block}