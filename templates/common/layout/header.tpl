{block name='beforeHeader'}{/block}
{block name='header'}
{if $view.header !== false}
<{if $html5}header{else}div{/if} class="header" id="header">
	{block name='headerContentStart'}{/block}

	{block name='languageSelection'}
	{include file='common/blocks/header/languages.tpl'}
	{/block}
	
	{block name='loggedUserBlock'}
	{*include file='common/blocks/header/loggedUserBlock.tpl'*}
	{/block}
	
	{if $data.platform.name === 'tabbee' || $smarty.get.tabbee == 1}
	<a id="closeBtn"><span class="value">{t}Close{/t}</span></a>
	{/if}
	
	{block name='logoAndBaseline'}
	{block name='logo'}
	<h1 id="branding" class="logoBlock lv1Title vcard">
		<a class="logo" rel="home" id="logo" href="{$smarty.const._URL}">
			<span class="fn n">{$smarty.const._APP_TITLE}</span>
		</a>
	</h1>
	{/block}
	{block name='baseline'}{/block}
	{/block}
	
	{block name='headerNav'}
	{include file='common/blocks/header/nav.tpl'}
	{/block}
	
	{block name='headerContentEnd'}{/block}
</{if $html5}header{else}div{/if}>
{/if}
{/block}
{block name='afterHeader'}{/block}