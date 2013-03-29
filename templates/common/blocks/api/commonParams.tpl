{block name='commonParamsContent'}
<section class="apiParamsSection" id="apiParamsSection">
	<div class="block apiParamsBlock commonApiParamsBlock commonApiHeadersBlock" id="commonApiHeadersBlock">
		<header class="titleBlock">
			<h3 class="title">{t}Accepted request headers{/t}</h3>
		</header>
		<dl class="paramsList">
			<dt class="name">accept</dt>
			<dd class="details">
				<span class="summary">returned data format</span>
				<ul class="acceptedValues">
					<li><strong>text/html</strong>, <strong>application/xhtml+xml</strong></li>
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
			<dt class="name">.{ldelim}$extension{rdelim}</dt>
			<dd class="details">
				<div>
					<span class="summary">returned data format (overload Accept header)</span>
					<ul class="acceptedValues">
						<li><strong>.html</strong></li>
						<li><strong>.xhtml</strong> (html served as application/xhtml+xml)</li>
						<li><strong>.json</strong></li>
						<li><strong>.xml</strong></li>
						<li><strong>.plist</strong></li>
						<li><strong>.yaml</strong></li>
						<li><strong>.jsontxt</strong> (json served as text/plain)</li>
						<li><strong>.plistxml</strong> (plist served as application/xml)</li>
						<li><strong>.yamltxt</strong> (plist served as text/plain)</li>
					</ul>
				</div>
				<div class="samples">Samples:</div>
				<ul>
					<li>/users.json</li>
					<li>/product/foobar.plist</li>
					<li>/animals/goldfish.html?foo=bar</li>
				</ul>
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
				<span class="summary">Name of the column to sort resources by</span>
				<span class="acceptedValues"><strong>any existing column name</strong></span>
			</dd>
			<dt class="name">orderBy</dt>
			<dd class="details">
				<span class="summary">Direction of the sorting operation</span>
				<span class="acceptedValues"><strong>ASC</strong> or <strong>DESC</strong></span>
			</dd>
			<dt class="name">indexByUnique</dt>
			<dd class="details">
				<span class="summary">Return data arrays/hashmaps/dictionaries indexed with the passed column</span>
				<span class="acceptedValues"><strong>any existing column name</strong></span>
			</dd>
			<dt class="name">indexBy</dt>
			<dd class="details">
				<span class="summary">Return data arrays/hashmaps/dictionaries indexed with the passed column with values as an arrays/hashmaps/dictionaries</span>
				<span class="acceptedValues"><strong>any existing column name</strong></span>
			</dd>
			<dt class="name">conditions</dt>
			<dd class="details">
				<p class="summary">
					Conditions for filtering data<br/><br/>
				</p>
				<p class="acceptedValues">
					<strong>1 or more conditions</strong> (semicolon separated)
				</p>
				<p>
					<strong>$condition:</strong> $column | [$operator] | $values<br/>
					<strong>$column:</strong> any existing column<br/>
					<strong>$values:</strong> 1 or more values (comma separated)<br/>
					<strong>$operator [optional]:</strong> any of<br/>
				</p>
				<ul class="operators">
					<li>is <span class="or">or</span> equal <span class="or">or</span> = <em>[default]</em></li>
					<li>not <span class="or">or</span> isnot <span class="or">or</span> notequal <span class="or">or</span> !=</li>
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
					<li>greater <span class="or">or</span> &gt;</li>
					<li>like</li>
					<li>doesnotcontains</li>
					<li>notlike</li>
					<li>startsby</li>
					<li>endsby</li>
					<li>doesnotstartsby</li>
					<li>doesnotendsby</li>
					<li>lower <span class="or">or</span> &lt;</li>
					<li>greaterorequal <span class="or">or</span> '&gt;='</li>
					<li>lowerorequal <span class="or">or</span> '&lt;='</li>
					<li>between</li>
					<li>notbetween</li>
				</ul>
				<br/><br/>
				<div class="samples">Samples:</div>
				<ul>
					<li>?conditions=name|foo</li>
					<li>?conditions=email|contains|@gmail</li>
					<li>?conditions=id|notin|3,5</li>
					<li>?conditions=type|bar;email|endsby|.org</li>
				</ul>
			</dd>
		</dl>
	</div>
</section>
{/block}