{extends file='common/layout/page.tpl'}

{block name='languageSelection'}{/block}

{block name='mainNav'}
{$mainNavItems=['admin' => $smarty.const._URL_ADMIN, 'API' => $smarty.const._URL_API]}
{$smarty.block.parent}
{/block}
{block name='poweredBy'}{/block}