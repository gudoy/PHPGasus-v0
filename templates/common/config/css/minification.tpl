{strip}
{$chain=''}
{$cssBasePath={$smarty.const._URL_STYLESHEETS_REL|regex_replace:'/^\/(.*)/':'$1'}}
{foreach $data.css as $item}
	{if !$item@last}{$sep=','}{else}{$sep=''}{/if}
	{* If the file link is asbolute, do not add base path *}
	{if strpos($item, 'http://') !== false || strpos($item, 'http://') !== false}{$basePath=''}{else}{$basePath=$cssBasePath}{/if}
	{$chain=$chain|cat:$basePath|cat:$item|cat:$sep}
{/foreach}
{if $chain !== ''}
<link href="{$smarty.const._URL_PUBLIC}min/?f={$chain}" media="{$mediaTarget|default:'screen'}" rel="stylesheet" type="text/css" />
{/if}
{/strip}