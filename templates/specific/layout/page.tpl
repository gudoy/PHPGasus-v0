{extends file='common/layout/page.tpl'}

{block name='languageSelection'}{/block}

{block name='loggedUserProfileLink'}
<a href="{$smarty.const._URL_ACCOUNT_PASSWORD_LOST}">{t}change password{/t}</a>
{/block}

{block name='mainNav'}
{$mainNavItems=['admin' => $smarty.const._URL_ADMIN, 'API' => $smarty.const._URL_API]}
{$smarty.block.parent}
{/block}

{block name='footer'}{/block}