{nocache}{strip}{include file='common/config/shortcuts.tpl' scope='root'}
{if !$view.isAjaxRequest && (!$smarty.get.tplSelf || $smarty.get.tplSelf != true)}
{block name='documentStart'}{/block}{include file='common/config/doctype.tpl'}
{/strip}<head>
{strip}{block name='headStart'}{/block}{/strip}
{include file='common/config/metadata.tpl'}
{include file='common/config/favicon.tpl'}
{include file='common/config/prefetching.tpl'}
{include file='common/config/css/css.tpl'}
{include file='common/config/operaWidget.tpl'}
{include file='common/config/js/html5.tpl'}
{include file='common/config/js/googleAnalytics.tpl'}
{strip}{block name='headEnd'}{/block}{/strip}
</head>

<body class="{include file='common/config/css/bodyClasses.tpl'}" id="{$view.cssid|default:$view.smartname|default:'noSpecificId'}">
{if $smarty.const._FLUSH_BUFFER_EARLY}
{php}
	//str_pad('',20000);
	ob_flush(); 
	flush();
{/php}
{/if}
{block name='bodyStart'}{/block}
	
	{include file='common/config/js/detectjs.tpl'}
	{include file='common/config/ienomore.tpl'}
	{include file='common/config/js/googleChromeFrameLoad.tpl'}
	{include file='common/config/js/googleChromeFrameInit.tpl'}

	{block name='beforeLayout'}{/block}
	<div class="container_16" id="layout">
		{/nocache}
		{block name='layout'}{/block}
		{nocache}
	</div>
	{block name='afterLayout'}{/block}
	
	{block name='beforeJS'}{/block}
	{include file='common/config/js/js.tpl'}
	{block name='afterJS'}{/block}
	
{block name='bodyEnd'}{/block}
</body>
{block name='documentEnd'}{/block}
</html>
{else}
{include file='common/config/shortcuts.tpl' scope='root'}
{block name='pageContent'}{/block}
{/if}
{/nocache}