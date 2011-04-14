{block name='footer'}
{strip}
{if !isset($view.footer) || (isset($view.footer) && !empty($view.footer) )}
<footer class="footer" id="footer" role="contentinfo">
	
	{block name='poweredBy'}
	<div class="block poweredByBlock" id="poweredByBlock">
		
		{t}powered by{/t} PHPgasus
	
	</div>
	{/block}
	
	{block name='copyrights'}
	{/block}
	
</footer>
{/if}
{/strip}
{/block}