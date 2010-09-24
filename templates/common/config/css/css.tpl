{* The following CC prevents IE8 from blocking css & scripts download until the main css is arrived
{* cf: http://www.phpied.com/conditional-comments-block-downloads/ *}
<!--[if IE]><![endif]-->
{$version='v='|cat:{$smarty.const._CSS_VERSION|default:''}}
{strip}{include file='common/config/css/ipad.tpl'}{/strip}
{strip}{include file='common/config/css/iphone.tpl'}{/strip}
{strip}{include file='common/config/css/android.tpl'}{/strip}
{if $smarty.get.minify !== 'none' && ($smarty.const._MINIFY_CSS || in_array($smarty.get.minify, array('css','all')))}
{include file='common/config/css/minification.tpl'}
{else}
{$cssBasePath=$smarty.const._URL_STYLESHEETS_REL}
{foreach $data.css as $item}
{strip}
{* If the file link is asbolute, do not add base path *}
{if strpos($item, 'http://') !== false || strpos($item, 'http://') !== false}{$basePath=''}{else}{$basePath=$cssBasePath}{/if}
<link href="{$basePath}{$item}?{$version}" media="screen" rel="stylesheet" type="text/css" />
{/strip}
{/foreach}
{/if}

{include file='common/config/css/opera.tpl'}
{include file='common/config/css/ie.tpl'}
{include file='common/config/css/ie6.tpl'}
{include file='common/config/css/ie7.tpl'}
{block name='dynamicCss'}{/block}