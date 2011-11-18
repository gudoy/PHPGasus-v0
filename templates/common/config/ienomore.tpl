{*<!--[if lte IE 7]>*}
{assign var='lteVersion' value=$view.ieNoMoreLTEVersion|default:7}
{if $browser.alias === 'ie' && $browser.version <= $lteVersion && $data.ieNoMoreMsg !== false}
<div style='border: 1px solid #F7941D; background: #FEEFDA; text-align: center; clear: both; height: 75px; position: relative;'>
	<div style="position:absolute; right:3px; top:3px; font-family:courier new; font-weight:bold;">
		<a href="#" onclick="javascript:this.parentNode.parentNode.style.display='none'; return false;">
			<img src="//www.ie6nomore.com/files/theme/ie6nomore-cornerx.jpg" style="border:none;" alt="{t}Close this notice{/t}" />
		</a>
	</div>
	<div style="width:640px; margin:0 auto; text-align:left; padding:0; overflow:hidden; color:black;">
		<div style="width:75px; float:left;">
			<img src="//www.ie6nomore.com/files/theme/ie6nomore-warning.jpg" alt="{t}Warning!{/t}"/>
		</div>
		<div style="width:275px; float:left; font-family:Arial, sans-serif;">
			<div style="font-size:14px; font-weight:bold; margin-top:12px;">
				{t}You are using an outdated browser{/t}
			</div>
			<div style="font-size:12px; margin-top:6px; line-height:12px;">
				{t}For a better experience using this site, please upgrade to a modern web browser.{/t}
			</div>
		</div>
		<div style="width:70px; float:left;">
			<a href="http://www.google.com/chrome" target="_blank">
				<img src="//www.ie6nomore.com/files/theme/ie6nomore-chrome.jpg" style="border:none;" alt="{t}Get Google Chrome{/t}" />
			</a>
		</div>
		<div style="width:70px; float:left;">
			<a href="http://www.firefox.com" target="_blank">
				<img src="//www.ie6nomore.com/files/theme/ie6nomore-firefox.jpg" style="border:none;" alt="{t}Get Firefox{/t}" />
			</a>
		</div>
		<div style="width:70px; float:left;">
			<a href="http://www.apple.com/safari/download/" target="_blank">
				<img src="//www.ie6nomore.com/files/theme/ie6nomore-safari.jpg" style="border:none;" alt="{t}Get Safari{/t}" />
			</a>
		</div>
		<div style="width:70px; float:left;">
			<a href="http://www.browserforthebetter.com/download.html" target="_blank">
				<img src="//www.ie6nomore.com/files/theme/ie6nomore-ie8.jpg" style="border:none;" alt="{t}Get Internet Explorer{/t}" />
			</a>
		</div>
	</div>
</div>
{/if}
{*<![endif]-->*}