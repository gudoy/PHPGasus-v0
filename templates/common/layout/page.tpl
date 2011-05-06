{extends file='common/layout/html.tpl'}

{block name='layout'}

    {include file='common/layout/header.tpl'}
    
    {if !isset($view.errorsBlock) || (isset($view.errorsBlock) && $view.errorsBlock)}
    {include file='common/config/errors.tpl'}
    {/if}
    
    {block name='bodyContent'}
    <div id="body">
    
    	{block name='breadcrumbs'}{/block}
    	
    	{block name='aside'}
    	<aside class="aside col expanded" id="sideCol" role="complementary">
    		{block name='asideContent'}{/block}
    	</aside>
    	{/block}
    	
    	{block name='mainCol'}
    	<div class="col" id="mainCol" role="main">
    		{block name='mainContent'}{/block}
    	</div>
    	{/block}
    	
    </div>
    {/block}
    
    {include file='common/layout/footer.tpl'}
	
{/block}