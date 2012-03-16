{strip}

{$version 		= 'v='|cat:{$smarty.const._JS_VERSION|default:''}}
{$jsBasePath 	= $smarty.const._URL_JAVASCRIPTS_REL}
{$useDefer 		= (!defined('_APP_USE_DEFERED_JS') || !$smarty.const._APP_USE_DEFERED_JS) ? false : true}

{/strip}
{if !isset($smarty.get.js) || !in_array($smarty.get.js, array('0','0','no','false',false))}
{if $smarty.get.minify !== 'none' && ($smarty.const._MINIFY_JS || in_array($smarty.get.minify, array('js','all')))}
{include file='common/config/js/minification.tpl'}
{else}
{foreach $data.js as $item}
{$isExternal 	= ( strpos($item, 'http://') !== false || strpos($item, 'https://') ) ? true : false}
{$hasQuery 		= ( strpos($item, '?') ) ? true : false}
{$basePath 		= ( $isExternal ) ? '' : $jsBasePath}
{$itemVersion 	= ( $isExternal ) ? '' : "{if $hasQuery}&{else}?{/if}{$version}"}
<script src="{$basePath}{$item}{$querySep}{$itemVersion}"{if !$html5} charset="utf-8"{/if}{if $useDefer} defer="defer"{/if}></script>
{/foreach}
{/if}
{if $view.name}
<script>
$(document).ready(function(){ if ( typeof({$view.name}) !== 'undefined' && {$view.name}.init ) { {$view.name}.init(); } });
</script>
{/if}
{/if}