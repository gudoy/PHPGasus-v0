{block name='commonParamsContent'}
<section class="apiParamsSection" id="apiParamsSection">
	<div class="block apiParamsBlock commonApiParamsBlock commonApiHeadersBlock" id="commonApiHeadersBlock">
		<header class="titleBlock">
			<h3 class="title">{t}Accepted request headers{/t}</h3>
		</header>
		<dl class="paramsList">
			<dt class="name">accept</dt>
			<dd class="details">
				<span class="summary">returned data format (overload Accept header)</span>
				<ul class="acceptedValues">
					<li><strong>text/html</strong> <strong>application/xhtml+xml</strong></li>
					<li><strong>text/xml</strong>, <strong>application/xml</strong></li>
					<li><strong>application/json</strong></li>
					<li><strong>text/yaml</strong></li>
					<li><strong>application/plist+xml</strong></li>
				</ul>
			</dd>
		</dl>
	</div>
	
	<div class="block apiParamsBlock commonApiParamsBlock commonApiURIParamsBlock" id="commonApiURIParamsBlock">
		<header class="titleBlock">
			<h3 class="title">{t}Accepted URI params{/t}</h3>
		</header>
		<dl class="paramsList">
			<dt class="name">URI extension (output format)</dt>
			<dd class="details">
				<div>
					<span class="summary">returned data format (overload Accept header)</span>
					<ul>
						<li><strong>.html</strong></li>
						<li><strong>.xhtml</strong> (html served with application/xhtml+xml header)</li>
						<li><strong>.json</strong></li>
						<li><strong>.xml</strong></li>
						<li><strong>.plist</strong></li>
						<li><strong>.yaml</strong></li>
						<li><strong>.jsontxt</strong> (json served with a plain txt header)</li>
						<li><strong>.plistxml</strong> (plist served with a xml header)</li>
						<li><strong>.yamltxt</strong> (plist served with a plain txt header</li>
						<li><strong>.jsonreport</strong></li>
					</ul>
				</div>
				<br/><br/>
				<div class="samples">Samples:</div>
				<ul>
					<li>/users.json</li>
					<li>/product/foobar.plist</li>
					<li>/animals/goldfish.html?foo=bar</li>
				</ul>
			</dd>
			<dt class="name">output (DEPRECATED)</dt>
			<dd class="details">
				<span class="summary">deprecated: use URI extension instead.<br/>returned data format (overload URI extension)</span>
				<span class="acceptedValues"><strong>any of the accepted formats above (without '.')</strong></span>
			</dd>
			<dt class="name">offset</dt>
			<dd class="details">
				<span class="summary">Offset from which you want to get data</span>
				<span class="acceptedValues"><strong>any numeric >= 0</strong></span>
			</dd>
			<dt class="name">limit</dt>
			<dd class="details">
				<span class="summary">Maximum number of resources you want to get</span>
				<span class="acceptedValues"><strong>-1</strong> (no limit) or <strong>any number > 1</strong>. <em>(default = {$smarty.const._ADMIN_RESOURCES_NB_PER_PAGE})</em></span>
			</dd>
			<dt class="name">sortBy</dt>
			<dd class="details">
				<span class="summary">Name of the field to sort resources by</span>
				<span class="acceptedValues"><strong>any existing field name</strong></span>
			</dd>
			<dt class="name">orderBy</dt>
			<dd class="details">
				<span class="summary">Direction of the sorting operation</span>
				<span class="acceptedValues"><strong>ASC</strong> or <strong>DESC</strong></span>
			</dd>
			<dt class="name">conditions</dt>
			<dd class="details">
				<p class="summary">
					Conditions for filtering data<br/><br/>
				</p>
				<p class="acceptedValues">
					<strong>Semicolon separated conditions groups</strong><br/><br/>
					<strong>groups are:</strong> field | [$operator] | $value(s)<br/><br/>
					<strong>values are:</strong> comma separated items
					<br/><br/>
					<strong>operators can be:</strong>
					<ul>
						<li>is or equal or = <em>[default]</em></li>
						<li>not or isnot or notequal or !=</li>
						<li>in (+ several values)</li>
						<li>notin (+ several values)</li>
						<li>contains</li>
						<li>like</li>
						<li>doesnotcontains</li>
						<li>notlike</li>
						<li>startsby</li>
						<li>endsby</li>
						<li>doesnotstartsby</li>
						<li>doesnotendsby</li>
						<li>greater or &gt;</li>
						<li>like</li>
						<li>doesnotcontains</li>
						<li>notlike</li>
						<li>startsby</li>
						<li>endsby</li>
						<li>doesnotstartsby</li>
						<li>doesnotendsby</li>
						<li>lower or &lt;</li>
						<li>greaterorequal or '&gt;='</li>
						<li>lowerorequal or '&lt;='</li>
						<li>between</li>
						<li>notbetween</li>
					</ul>
					<br/><br/>
					<div class="samples">Samples:</div>
					<ul>
						<li>?conditions=name|foo</li>
						<li>?conditions=email|contains|gmail.com</li>
						<li>?conditions=id|notin|3,5</li>
						<li>?conditions=type|bar;email|endsby|yahoo.com;active|true</li>
					</ul>
				</p>
			</dd>
		</dl>
	</div>
</section>
{/block}