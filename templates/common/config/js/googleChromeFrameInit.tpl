{if $smarty.const._APP_USE_CHROME_FRAME && $data.browser.alias === 'ie'}
<!--[if lte IE 8]>
<script type="text/javascript">
CFInstall.check({ldelim}mode:'overlay', node:"gglChrFrPlaceholer"{rdelim});
</script>
<![endif]-->
{/if}