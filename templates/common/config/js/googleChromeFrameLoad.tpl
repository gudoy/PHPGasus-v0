{if $smarty.const._APP_USE_CHROME_FRAME && $browser.alias === 'ie'}
<!--[if lte IE 8]>
<div id="googleChromeFrameTest">
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/chrome-frame/1/CFInstall.min.js"></script>
	<div id="gglChrFrPlaceholer"></div>
</div>
<![endif]-->
{/if}