{strip}

{$chain 		= ''}
{$cssBasePath 	= {$smarty.const._URL_STYLESHEETS_REL|regex_replace:'/^\/(.*)/':'$1'}}

{foreach $data.css as $item}
	{if !$item@last}{$sep=','}{else}{$sep=''}{/if}
	{if strpos($item, $smarty.const._APP_PROTOCOL) !== false || strpos($item, $smarty.const._APP_PROTOCOL) !== false}{$basePath=''}{else}{$basePath=$cssBasePath}{/if}
	{$chain = $chain|cat:$basePath|cat:$item|cat:$sep}
{/foreach}

{if $chain !== ''}
	<link href="{$smarty.const._URL_PUBLIC}min/?f={$chain}&{$version}" media="{$mediaTarget|default:'screen'}" rel="stylesheet" type="text/css" />
{/if}

{/strip}