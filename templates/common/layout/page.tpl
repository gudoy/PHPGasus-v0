{extends file='common/layout/html.tpl'}

{block name='layout'}

    {include file='common/layout/header.tpl'}
    
    {if !isset($view.errorsBlock) || (isset($view.errorsBlock) && $view.errorsBlock)}
    {include file='common/config/errors.tpl'}
    {/if}
    
    {block name='beforeBodyContent'}{/block}
    {block name='bodyContent'}
    <div id="body">
    	{block name='bodyContentStart'}{/block}
    
    	{block name='breadcrumbs'}{/block}
    	
    	{block name='aside'}{/block}
    	
    	{block name='mainCol'}
    		{block name='pageContent'}{/block}
    	{/block}
    	
    	{block name='bodyContentEnd'}{/block}
    </div>
    {/block}
    {block name='afterBodyContent'}{/block}
    
    {include file='common/layout/footer.tpl'}
	
{/block}