<!--- Start Contact Us --->
<{if $html5}section{else}div{/if} class="section block contactUsBlock" id="contactUsBlock">
	
	<h2 class="blockTitle">
		{t}Say hello?{/t}
	</h2>
	
	{include file='common/config/errors.tpl'}	
	{include file='common/forms/about/contact/contactUs.tpl'}
	
</{if $html5}section{else}div{/if}>
<!--- End Contact Us --->