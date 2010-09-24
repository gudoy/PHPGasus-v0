{$version='v='|cat:{$smarty.const._JS_VERSION|default:''}}
{if $smarty.get.minify !== 'none' && ($smarty.const._MINIFY_JS || in_array($smarty.get.minify, array('js','all')))}
{include file='common/config/js/minification.tpl'}
{else}
{$jsBasePath=$smarty.const._URL_JAVASCRIPTS_REL}
{foreach $data.js as $item}
{strip}
{* If the file link is asbolute, do not add base path *}
{if strpos($item, 'http://') !== false || strpos($item, 'http://') !== false}{$basePath=''}{else}{$basePath=$jsBasePath}{/if}
<script type="text/javascript" src="{$basePath}{$item}?{$version}" {if !$html5}charset="utf-8"{/if}></script>
{/strip}
{/foreach}
{/if}
{if $view.name}
<script type="text/javascript" {if !$html5}charset="utf-8"{/if}>
//<![CDATA[
$(document).ready(function(){ if ( typeof({$view.name}) !== 'undefined' && {$view.name}.init ) { {$view.name}.init(); } });
//]]>
</script>
{/if}