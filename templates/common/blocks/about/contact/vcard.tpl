<!-- Start VCard -->
<section class="section block contactBlock" id="contactBlock">
	
	<header class="titleBlock">
		<h2 class="title">{t}Get in touch?{/t}</h2>
	</header>
	
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
	
</section>
<!-- End VCard -->