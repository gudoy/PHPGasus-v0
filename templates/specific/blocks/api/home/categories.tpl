<div class="apiGroupBlock">
	{$rUsedName='categories'}
	<header class="groupTitle">
		<h3 class="title">
			{$rUsedName}
		</h3>
	</header>
	<ul class="apis">
		<li class="item api">
			<h4 class="title">
				<span class="method">GET</span>
				<span class="uri">/ {$rUsedName}</span>
			</h4>
			<div class="props">
				<div class="prop returnedOutput infos">
					<span class="key">{t}return{/t}{t}:{/t}</span>
					<span class="values">
						list of {$rUsedName} items
					</span>
				</div>
				<div class="prop successOutput success">
					<span class="key">success{t}:{/t}</span>
					<ul class="values">
						<li>200 OK</li>
					</ul>
				</div>
				<div class="prop possibleErrors errors">
					<span class="key">{t}errors{/t}{t}:{/t}</span>
					<dl class="values">
						<dt>204 No Content</dt>
						<dd>{$rUsedName} not found</dd>
					</dl>
				</div>
				<div class="prop samplesURI samples">
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
			<h4 class="title">
				<span class="method">GET</span>
				<span class="uri">/ {$rUsedName} / { $id or $name }</span>
			</h4>
			<div class="props">
				<div class="prop returnedOutput infos">
					<span class="key">{t}return{/t}{t}:{/t}</span>
					<span class="value">
						return data of the specified resource
					</span>
				</div>
				<div class="prop successOutput success">
					<span class="key">success{t}:{/t}</span>
					<ul class="values">
						<li>200 OK</li>
					</ul>
				</div>
				<div class="prop possibleErrors errors">
					<span class="key">{t}errors{/t}{t}:{/t}</span>
					<dl class="values">
						<dt>204 No Content</dt>
						<dd>{$rUsedName} not found</dd>
					</dl>
				</div>
				<div class="prop samplesURI samples">
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
		<li class="item api">
			<h4 class="title secured">
				<span class="method">POST</span>
				<span class="uri">/ {$rUsedName}</span>
			</h4>
			<div class="props">
				<div class="prop prop expectedInputData">
					<span class="key">{t}expected{/t}{t}:{/t}</span>
					<ul class="values">
						<li>name</li>
					</ul>
				</div>
				<div class="prop returnedOutput infos">
					<span class="key">{t}return{/t}{t}:{/t}</span>
					<span class="values">
						created {$rUsedName}
					</span>
				</div>
				<div class="prop successOutput success">
					<span class="key">success{t}:{/t}</span>
					<dl class="values">
						<dt>201 Created</dt>
						<dd>{$rUsedName} successfully created</dd>
					</dl>
				</div>
				<div class="prop possibleErrors errors">
					<span class="key">{t}errors{/t}{t}:{/t}</span>
					<dl class="values">
						<dt>401 Unauthorized</dt>
						<dd>authentication failed</dd>
						<dt>417 Expectation Failed</dt>
						<dd>+ missing data detail</dd>
					</dl>
				</div>
				<div class="prop samplesURI samples">
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
	</ul>
</div>