{block name='beforeFooter'}{/block}
{block name='footer'}
{if !isset($view.footer) || (isset($view.footer) && !empty($view.footer) )}
<{if $html5}footer{else}div{/if} id="footer">
	
	{block name='poweredBy'}
	<div class="block poweredByBlock" id="poweredByBlock">
		
		{t}powered by{/t} PHPgasus
	
	</div>
	{/block}
	
	{block name='copyrights'}
	{/block}
	
</{if $html5}footer{else}div{/if}>
{/if}
{/block}
{block name='afterFooter'}{/block}