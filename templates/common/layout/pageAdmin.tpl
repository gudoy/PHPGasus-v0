{extends file='specific/layout/page.tpl'}

{block name='loggedUserBlock'}
{include file='common/blocks/header/loggedUserBlock.tpl'}
{/block}

{block name='accountNavLogoutLink'}{/block}

{block name='headerNav'}
{include file='common/blocks/admin/nav/mainNav_new.tpl'}
{/block}

{*
{block name='adminMainNav'}
{include file='common/blocks/admin/nav/mainNav.tpl'}
{/block}
*}

{block name='breadcrumbs'}
{include file='common/blocks/header/breadcrumbs.tpl'}
{/block}

{block name='aside'}
<{if $html5}aside{else}div{/if} class="col grid_3" id="sideCol">

	{block name='adminSearch'}
	{include file='common/blocks/admin/search.tpl'}
	{/block}

	{block name='secondNav'}
	{include file='common/blocks/admin/nav/secondNav.tpl'}
	{/block}
		
</{if $html5}aside{else}div{/if}>
{/block}

{block name='mainCol'}
<div class="col grid_13" id="mainCol">
{$smarty.block.parent}
{/block}