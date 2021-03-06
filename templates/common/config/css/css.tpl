{strip}

{* The following CC prevents IE8 from blocking css & scripts download until the main css is arrived
{* cf: http://www.phpied.com/conditional-comments-block-downloads/ *}

{$version 		= 'v='|cat:{$smarty.const._CSS_VERSION|default:''}}
{$cssBasePath 	= $smarty.const._URL_STYLESHEETS_REL}

{/strip}
{if !isset($smarty.get.css) || !in_array($smarty.get.css, array('0','0','no','false',false))}

<!--[if IE]><![endif]-->
{if $smarty.get.minify !== 'none' && ($smarty.const._MINIFY_CSS || in_array($smarty.get.minify, array('css','all')))}
{include file='common/config/css/minification.tpl'}
{else}
{foreach $data.css as $item}
{strip}
{$isExternal 	= ( strpos($item, 'http://') !== false || strpos($item, 'https://') ) ? true : false}
{$hasQuery 		= ( strpos($item, '?') ) ? true : false}
{$basePath 		= ( $isExternal ) ? '' : $cssBasePath}
{$itemVersion 	= ( $isExternal ) ? '' : "{if $hasQuery}&{else}?{/if}{$version}"}
	<link href="{$basePath}{$item}{$itemVersion}" media="screen" rel="stylesheet" />
{/strip}
{/foreach}
{/if}
{include file='common/config/css/ie.tpl'}
{include file='common/config/css/ie6.tpl'}
{include file='common/config/css/ie7.tpl'}
{block name = 'dynamicCss'}{/block}
{/if}