<div class="apiGroupBlock first">
	{$rUsedName='categories'}
	<header class="groupTitle">
		<h3 class="title">
			{$rUsedName}
		</h3>
	</header>
	<ul class="apis">
		<li class="item api">
			<h4>
				<span class="method">GET</span>
				<span class="uri">/ {$rUsedName}</span>
			</h4>
			<div class="props">
				<div class="prop returnedOutput">
					<span class="key">{t}return{/t}{t}:{/t}</span>
					<span class="values">
						list of {$rUsedName} items
					</span>
				</div>
				<div class="prop successOutput">
					<span class="key">success{t}:{/t}</span>
					<dl class="values">
						<dt>200 OK</dt>
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
							<a class="value" href="{$smarty.const._URL_API}{$rUsedName}.json">
								{$smarty.const._URL_API}{$rUsedName}.json
							</a>
						</li>
					</ul>
				</div>
			</div>
		</li>
		<li class="item api">
			<h4>
				<span class="method">GET</span>
				<span class="uri">/ {$rUsedName} / { $id or $name }</span>
			</h4>
			<div class="props">
				<div class="prop returnedOutput">
					<span class="key">{t}return{/t}{t}:{/t}</span>
					<span class="value">
						return data of the specified resource
					</span>
				</div>
				<div class="prop successOutput">
					<span class="key">success{t}:{/t}</span>
					<dl class="values">
						<dt>200 OK</dt>
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
							<a class="value" href="{$smarty.const._URL_API}{$rUsedName}/1.json">
								{$smarty.const._URL_API}{$rUsedName}/1.json
							</a>
						</li>
						<li>
							<a class="value" href="{$smarty.const._URL_API}{$rUsedName}/divers.json">
								{$smarty.const._URL_API}{$rUsedName}/divers.json
							</a>
						</li>
					</ul>
				</div>
			</div>
		</li>
	</ul>
</div>