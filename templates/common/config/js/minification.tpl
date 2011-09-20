{strip}

{$chain 		= ''}
{$version 		= 'v='|cat:{$smarty.const._JS_VERSION|default:''}}
{$jsBasePath 	= {$smarty.const._URL_JAVASCRIPTS_REL|regex_replace:'/^\/(.*)/':'$1'}}

{foreach $data.js as $item}
{* Case where the file is distant, we can't use it with the minify lib *}
{if strpos($item, 'http') !== false}
	{if !defined('_APP_USE_DEFERED_JS') || !$smarty.const._APP_USE_DEFERED_JS}{$useDefer=false}{else}{$useDefer=true}{/if}
	<script src="{$item}"{if !$html5} charset="utf-8"{/if}{if $useDefer} defer="defer"{/if}></script>
{else}
	{$chain = $chain|cat:$jsBasePath|cat:$item|cat:','}
{/if}
{/foreach}

{* remove any trailing coma *}
{$chain = rtrim($chain,',')}

{if $chain !== ''}
<script src="{$smarty.const._URL_PUBLIC}min/?f={$chain}"></script>
{/if}

{/strip}