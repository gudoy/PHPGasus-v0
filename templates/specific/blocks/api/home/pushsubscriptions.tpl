<div class="apiGroupBlock first">
	{$rUsedName='pushsubscriptions'}
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
				<div class="prop prop expectedInputData">
					<span class="key">{t}expected{/t}{t}:{/t}</span>
					<dl class="values">
						<dt>device_id</dt>
						<dt>token_id</dt>
						<dt>[language]</dt>
					</dl>
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
						<dt>400 Bad request</dt>
						<dd>+ errors detail</dd>
						<dt>409 Conflict</dt>
						<dd>already existing {$rUsedName}</dd>
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