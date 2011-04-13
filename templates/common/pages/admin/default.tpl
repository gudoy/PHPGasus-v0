{extends file='specific/layout/pageAdmin.tpl'}

{block name='pageContent'}

{block name='searchResults'}
{include file='specific/blocks/admin/search/results.tpl' search=$data.search}
{/block}


{/block}