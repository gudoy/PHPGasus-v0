{extends file='specific/layout/page.tpl'}

{block name='loggedUserBlock'}
{include file='common/blocks/header/account/myProfile.tpl'}
{/block}

{block name='accountNavLogoutLink'}{/block}

{block name='headerNav'}
{include file='common/blocks/admin/nav/main.tpl'}
{/block}

{block name='breadcrumbs'}
{include file='common/blocks/header/breadcrumbs.tpl'}
{/block}

{block name='asideContent'}

	{block name='adminSearch'}
	{include file='common/blocks/admin/search/search.tpl'}
	{/block}

	{*block name='secondNav'}
	{include file='common/blocks/admin/nav/secondNav.tpl'}
	{/block*}
		
{/block}

{* TODO: create rule via js instead??? *}
{block name='dynamicCss' nocache}
{if $data.search.query}
<style class="dynamicCSS" id="searchDynamicCSS">
.commonTable.adminTable td .dataValue[data-exactValue*='{$data.search.query}'] { background:lightyellow; }
</style>
{/if}
{/block}