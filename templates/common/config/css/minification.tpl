{strip}

{$chain 		= ''}
{$cssBasePath 	= {$smarty.const._URL_STYLESHEETS_REL|regex_replace:'/^\/(.*)/':'$1'}}

{foreach $data.css as $item}
{* Case where the file is distant, we can't use it with the minify lib *}
{if strpos($item, 'http') !== false}
	{if strpos($item, 'http://') !== false || strpos($item, 'https://') !== false}{$basePath=''}{else}{$basePath=$cssBasePath}{/if}
	{if strpos($item, 'http://') !== false || strpos($item, 'https://') !== false}{$version=''}{else}{$version='?'|cat:$version}{/if}
	<link href="{$item}{$version}" media="screen" rel="stylesheet" />
{else}
	{$chain = $chain|cat:$cssBasePath|cat:$item|cat:','}
{/if}
{/foreach}

{* remove any trailing coma *}
{$chain = rtrim($chain,',')}

{if $chain !== ''}
	<link href="{$smarty.const._URL_PUBLIC}min/?f={$chain}" media="{$mediaTarget|default:'screen'}" rel="stylesheet" />
{/if}

{/strip}