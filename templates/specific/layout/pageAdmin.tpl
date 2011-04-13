{extends file='common/layout/pageAdmin.tpl'}

{block name='aside'}
<aside class="aside col expanded" id="sideCol">

    {block name='adminSearch'}
    {include file='specific/blocks/admin/search/search.tpl'}
    {/block}
    
    {block name='secondNav'}{/block}
        
</aside>
{/block}

{block name='breadcrumbs'}{/block}