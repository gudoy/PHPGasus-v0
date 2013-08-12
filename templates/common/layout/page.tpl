{extends file='common/layout/html.tpl'}

{block name='layout'}
    
    {include file='common/layout/header.tpl'}
    
    <div id="body" class="body">
    	<header id="bodyHeader" class="bodyHeader">{block name='bodyHeader'}{/block}</header>
    	<div id="bodyContent" class="bodyContent">
    	{block name='bodyContent'}
    	
	    	{block name='main'}
	    	<div id="main" class="col main" role="main">
	    		{block name='mainHeader'}{/block}
	    		<div class="colContent mainContent" id="mainContent">
					{block name='mainContent'}{/block}
				</div>
	    		{block name='mainFooter'}{/block}
	    	</div>
	    	{/block}
	    	
	    	{block name='aside'}
	    	<aside id="aside" class="aside col expanded" role="complementary">
	    		{block name='asideHeader'}{/block}
	    		{block name='asideContent'}{/block}
	    		{block name='asideFooter'}{/block}
	    	</aside>
	    	{/block}
    	
    	{/block}
    	</div>
    </div>
    
    {include file='common/layout/footer.tpl'}
	
{/block}