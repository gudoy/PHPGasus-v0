{strip}{include file='common/config/shortcuts.tpl' scope='root'}{/strip}{if !$view.isAjaxRequest && (!$smarty.get.tplSelf || $smarty.get.tplSelf != true)}{include file='common/config/doctype.tpl'}
<head>
{include file='common/config/metadata.tpl'}

{include file='common/config/favicon.tpl'}

{include file='common/config/css/css.tpl'}

{include file='common/config/js/html5.tpl'}
{include file='common/config/js/googleAnalytics.tpl'}
</head>

{block name='body'}
<body>
{block name='bodyContent'}
	{include file='common/config/flush/onBodyStart.tpl'}
	{include file='common/config/js/detectjs.tpl'}
	{include file='common/config/ienomore.tpl'}
	{include file='common/config/js/googleChromeFrameLoad.tpl'}
	{include file='common/config/js/googleChromeFrameInit.tpl'}
	{block name='notifications'}
    {if !isset($view.errorsBlock) || (isset($view.errorsBlock) && $view.errorsBlock)}{include file='common/config/errors.tpl'}{/if}
	{if !isset($view.warningsBlock) || (isset($view.warningsBlock) && $view.warningsBlock)}{include file='common/config/warnings.tpl'}{/if}
	{/block}
	<div id="layout">{block name='layout'}{/block}</div>
	{include file='common/config/js/js.tpl'}
{/block}
</body>
{/block}
</html>
{else}
{block name='mainContent'}{/block}
{/if}