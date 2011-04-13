{nocache}{strip}
{include file='common/config/shortcuts.tpl' scope='root'}
{if !$view.isAjaxRequest && (!$smarty.get.tplSelf || $smarty.get.tplSelf != true)}
{include file='common/config/doctype.tpl'}
{/strip}
<head>
    {include file='common/config/metadata.tpl'}
    {include file='common/config/favicon.tpl'}
    {include file='common/config/prefetching.tpl'}
    {include file='common/config/css/css.tpl'}
    {include file='common/config/operaWidget.tpl'}
    {include file='common/config/js/html5.tpl'}
    {include file='common/config/js/googleAnalytics.tpl'}
</head>
{/nocache}

<body class="{include file='common/config/css/bodyClasses.tpl'}" id="{$view.cssid|default:$view.smartname|default:'noSpecificId'}">    

    {include file='common/config/flush/onBodyStart.tpl'}	
	{include file='common/config/js/detectjs.tpl'}
	{include file='common/config/ienomore.tpl'}
	{include file='common/config/js/googleChromeFrameLoad.tpl'}
	{include file='common/config/js/googleChromeFrameInit.tpl'}

	<div class="container_16" id="layout">
		{block name='layout'}{/block}
	</div>
	
	{include file='common/config/js/js.tpl'}
	
</body>
</html>
{else}
{include file='common/config/shortcuts.tpl' scope='root'}
{block name='pageContent'}{/block}
{/if}