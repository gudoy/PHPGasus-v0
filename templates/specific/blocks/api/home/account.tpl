<div class="apiGroupBlock">
	{$rUsedName='account'}
	<header class="groupTitle">
		<h3 class="title">
			{$rUsedName}
		</h3>
	</header>
	<ul class="apis">
		<li class="item api">
			<h4 class="title">
				<span class="method">POST</span>
				<span class="uri">/ {$rUsedName} / login</span>
			</h4>
			<div class="props">
				<div class="prop expectedInputData">
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
						authenticated user (with token)
					</span>
				</div>
				<div class="prop successOutput success">
					<span class="key">success{t}:{/t}</span>
					<dl class="values">
						<dt>201 Created</dt>
						<dd>{$rUsedName} successfully authenticated</dd>
					</dl>
				</div>
				<div class="prop possibleErrors errors">
					<span class="key">{t}errors{/t}{t}:{/t}</span>
					<dl class="values">
						<dt>401 Unauthorized</dt>
						<dd>Wrong email and/or password</dd>
						<dt>409 Conflict</dt>
						<dd>user paired with another device</dd>
					</dl>
				</div>
				<div class="prop samplesURI">
					<span class="key">{t}samples{/t}{t}:{/t}</span>
					<ul class="values">
						<li>
							<a class="value" href="{$smarty.const._URL_API}{$rUsedName}?method=create">
								{$smarty.const._URL_API}{$rUsedName}/login.json
							</a>
						</li>
					</ul>
				</div>
			</div>
		</li>
	</ul>
</div>