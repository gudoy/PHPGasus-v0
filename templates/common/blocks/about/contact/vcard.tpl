<!-- Start VCard -->
<{if $html5}section{else}div{/if} class="section block contactBlock" id="contactBlock">

	<h2 class="blockTitle">{t}Get in touch?{/t}</h2>
	
	<div class="vcard">
		<a class="fn n org row include" href="#branding">Clicmobile</a>
		<address class="adr">
			<span class="street-address row">3 Rue Primo Levi</span>
			<span class="row">
				<span class="postal-code">75013</span>
				<span class="locality">Paris</span>	
			</span>
			<span class="country-name row">France</span>
		</address>
		<span class="tel row">
			+33 1 43 46 15 15
		</span>
		<a href="mailto:info@clicmobile.com" class="email row">
			info@clicmobile.com
		</a>
		{if $smarty.const._APP_OWNER_MAP_URL}
		<a rel="map" href="{$smarty.const._APP_OWNER_MAP_URL}">{t}Access map{/t}</a>
		{/if}
	</div>
	
</{if $html5}section{else}div{/if}>
<!-- End VCard -->