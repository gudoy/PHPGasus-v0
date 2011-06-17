<div class="apiGroupBlock first">
	{$rUsedName='users'}
	<header class="groupTitle">
		<h3 class="title">
			{$rUsedName}
		</h3>
	</header>
	<ul class="apis">
		<li class="item api">
			<h4 class="title">
				<span class="method">POST</span>
				<span class="uri">/ {$rUsedName}</span>
			</h4>
			<div class="props">
				<div class="prop prop expectedInputData">
					<span class="key">{t}expected{/t}{t}:{/t}</span>
					<ul class="values">
						<li>email</li>
						<li>password</li>
						<li>device_id</li>
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
						<dt>417 Expectation Failed</dt>
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
		<li class="item api">
			<h4 class="title secured">
				<span class="method">PUT</span>
				<span class="uri">/ {$rUsedName} / { $id }</span>
			</h4>
			<div class="props">
				<div class="prop prop expectedInputData">
					<span class="key">{t}expected{/t}{t}:{/t}</span>
					<ul class="values">
						<li>password or token</li>
						<li>device_id</li>
					</ul>
				</div>
				<div class="prop successOutput success">
					<span class="key">success{t}:{/t}</span>
					<dl class="values">
						<dt>200 Ok</dt>
						<dd>{$rUsedName} successfully updated</dd>
					</dl>
				</div>
				<div class="prop possibleErrors errors">
					<span class="key">{t}errors{/t}{t}:{/t}</span>
					<dl class="values">
						<dt>400 Bad Request</dt>
						<dd>+ missing request parameter detail</dd>
						<dt>401 Unauthorized</dt>
						<dd>authentication failed</dd>
						<dt>417 Expectation Failed</dt>
						<dd>+ missing data detail</dd>
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