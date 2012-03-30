{extends file='common/layout/html.tpl'}

{block name='layout'}
    
    {include file='common/layout/header.tpl'}
    
    {block name='bodyContent'}
    <div id="body">
    	
    	{block name='main'}
    	<div class="col" id="main" role="main">
    		{block name='mainHeader'}{/block}
    		<div class="colContent mainContent" id="mainContent">
				{block name='mainContent'}{/block}
			</div>
    		{block name='mainFooter'}{/block}
    	</div>
    	{/block}
    	
    	{block name='aside'}
    	<aside class="aside col expanded" id="aside" role="complementary">
    		{block name='asideHeader'}{/block}
    		{block name='asideContent'}{/block}
    		{block name='asideFooter'}{/block}
    	</aside>
    	{/block}
    	
    </div>
    {/block}
    
    {include file='common/layout/footer.tpl'}
	
{/block}