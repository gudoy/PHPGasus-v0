{$version='v='|cat:{$smarty.const._JS_VERSION|default:''}}
{if !defined('_APP_USE_DEFERED_JS') || !$smarty.const._APP_USE_DEFERED_JS}{$useDefer=false}{else}{$useDefer=true}{/if}
{if $smarty.get.minify !== 'none' && ($smarty.const._MINIFY_JS || in_array($smarty.get.minify, array('js','all')))}
{include file='common/config/js/minification.tpl'}
{else}
{$jsBasePath=$smarty.const._URL_JAVASCRIPTS_REL}
{foreach $data.js as $item}
{strip}
{* If the file link is asbolute, do not add base path *}
{if strpos($item, 'http://') !== false || strpos($item, 'http://') !== false}{$basePath=''}{else}{$basePath=$jsBasePath}{/if}
{if strpos($item, '?') !== false}{$querySep='&amp;'}{else}{$querySep='?'}{/if}
<script type="text/javascript" src="{$basePath}{$item}{$querySep}{$version}"{if !$html5} charset="utf-8"{/if}{if $useDefer} defer="defer"{/if}></script>
{/strip}
{/foreach}
{/if}
{if $view.name}
<script type="text/javascript">
$(document).ready(function(){ if ( typeof({$view.name}) !== 'undefined' {if $data.options.outputExtension === 'xhtml'}&amp;&amp;{else}&&{/if} {$view.name}.init ) { {$view.name}.init(); } });
</script>
{/if}