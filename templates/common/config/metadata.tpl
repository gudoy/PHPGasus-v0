<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>{$view.title|default:$smarty.const._APP_TITLE}</title>
<meta name="description" content="{$view.description|default:$smarty.const._APP_META_DECRIPTION|replace:'&':'&amp;'}" />
<meta name="keywords" content="{$view.keywords|default:$smarty.const._APP_META_KEYWORDS}" />
<meta name="robots" content="{if $data.env.type === 'dev' || !$smarty.const._APP_META_ROBOTS_INDEXABLE}noindex,nofollow,{else}index,follow,{/if}{if !$smarty.const._APP_META_ROBOTS_ARCHIVABLE}noarchive,{/if}{if !$smarty.const._APP_META_ROBOTS_IMAGES_INDEXABLE}noimageindex,{/if}" />
{if !$smarty.const._APP_META_GOOGLE_TRANSLATABLE}<meta name="google" content="notranslate" />{/if}
<meta name="rating" content="General" />
<meta name="distribution" content="Global" />
<meta name="revisit-after" content="7" />
<meta name="author" content="{$smarty.const._APP_AUTHOR_NAME}" />
<meta name="reply-to" content="{$smarty.const._APP_AUTHOR_MAIL}" />
<meta name="owner" content="{$smarty.const._APP_OWNER_MAIL}" />
{*
http://www.google.com/support/news_pub/bin/answer.py?answer=191283
<meta name="original-source" content="{$smarty.const._URL}{* current url *}{*">
<meta name="syndication-source" content="http://www.example.com/wire_story_1.html">
*}
{*<meta http-equiv="X-UA-Compatible" content="IE=edge{if $smarty.const._APP_USE_CHROME_FRAME},chrome=1{/if}" />*}
{if $smarty.const._APP_IOS_WEBAPP_CAPABLE}<meta name="apple-mobile-web-app-capable" content="yes" />{/if}
{if $data.platform.name === 'bada'}
{else}
<meta name="viewport" content="width=device-width, initial-scale={$smarty.const._APP_IOS_INISCALE|default:'1.0'}, maximum-scale={$smarty.const._APP_IOS_MAXSCALE|default:'10.0'}, user-scalable=yes" />
{/if}