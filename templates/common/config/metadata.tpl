<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>{$view.title|default:$smarty.const._APP_TITLE}</title>
<meta name="description" content="{$view.description|default:$smarty.const._APP_META_DECRIPTION|replace:'&':'&amp;'}" />
<meta name="keywords" content="{$view.keywords|default:$smarty.const._APP_META_KEYWORDS}" />
<meta name="robots" content="{if $data.env.type === 'dev' || !$smarty.const._APP_INDEXABLE_BY_ROBOTS}noindex, nofollow{else}index, follow{/if}" />
<meta name="rating" content="General" />
<meta name="distribution" content="Global" />
<meta name="revisit-after" content="7" />
<meta name="author" content="{$smarty.const._APP_AUTHOR_NAME}" />
<meta name="reply-to" content="{$smarty.const._APP_AUTHOR_MAIL}" />
<meta name="owner" content="{$smarty.const._APP_OWNER_MAIL}" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
{if $smarty.const._APP_IPHONE_WEBAPP_CAPABLE}<meta name="apple-mobile-web-app-capable" content="yes">{/if}
{if $smarty.const._APP_USE_CHROME_FRAME}<meta http-equiv="X-UA-Compatible" content="chrome=1" />{/if}

{if $smarty.const._APP_IOS_WEBAPP_CAPABLE}<meta name="apple-mobile-web-app-capable" content="yes">{/if}
<link href="{$smarty.const._URL_PUBLIC}apple-touch-icon.png" rel="apple-touch-icon" />
{if $data.platform.name === 'bada'}
<meta name="viewport" content="user-scalable=yes" />
<style>
@media screen and (orientation:portrait)
{
	html { width:320px; }
}
@media screen and (orientation:landscape)
{
	html { width:480px; }
}
</style>
{else}
<meta name="viewport" content="width=device-width, initial-scale={$smarty.const._APP_IOS_INISCALE|default:'1.0'}, maximum-scale={$smarty.const._APP_IOS_MAXSCALE|default:'10.0'}, user-scalable=yes" />
{/if}