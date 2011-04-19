<div class="apiGroupBlock first">
	{$rUsedName='users'}
	<header class="groupTitle">
		<h3 class="title">
			{$rUsedName}
		</h3>
	</header>
	<ul class="apis">
		<li class="item api">
			<h4>
				<span class="method">POST</span>
				<span class="uri">/ {$rUsedName}</span>
			</h4>
			<div class="props">
				<div class="prop returnedOutput">
					<span class="key">{t}return{/t}{t}:{/t}</span>
					<span class="values">
						created {$rUsedName}
					</span>
				</div>
				<div class="prop successOutput">
					<span class="key">success{t}:{/t}</span>
					<dl class="values">
						<dt>201 Created</dt>
						<dd>{$rUsedName} successfully created</dd>
					</dl>
				</div>
				<div class="prop possibleErrors">
					<span class="key">{t}errors{/t}{t}:{/t}</span>
					<dl class="values">
						<dt>204 No Content</dt>
						<dd>{$rUsedName} not found</dd>
					</dl>
				</div>
				<div class="prop samplesURI">
					<span class="key">{t}samples{/t}{t}:{/t}</span>
					<ul class="values">
						<li>
							<a class="value" href="{$smarty.const._URL_API}{$rUsedName}?method=create">
								{$smarty.const._URL_API}{$rUsedName}
							</a>
						</li>
					</ul>
				</div>
			</div>
		</li>
	</ul>
</div>