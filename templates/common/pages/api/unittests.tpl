{$unittests=$data.unittests}
<div class="box unittestsResulsBlock" id="unittestsResulsBlock">
	<div class="titleBlock">
		<h2>
			{t}Unit Tests{/t}
		</h2>
	</div>
	
	{$currentTestGroup=''}
	<table class="commonTable unittestsResultsTable">
		<thead>
			<tr>
				<th class="col firstCol colSelectResources" id="toggleAllCel" scope="col">
					<input type="checkbox" id="toggleAll" name="toggleAll" />
				</th>
				<th class="col colName" scope="col">
					<span class="title">{t}Name{/t}</span>
				</th>
				{$formatsCount=count($data.testsParams.outputFormats)}
				{foreach $data.testsParams.outputFormats as $format}
				<th class="col outputFormatCol" scope="col">
					<span class="title">{t}{$format|upper}{/t}</span>
				</th>
				{/foreach}
			</tr>
		</thead>
		<tbody>
			{foreach $unittests as $test}
			{if empty($currentTestGroup) || $test.group !== $currentTestGroup}
			<tr>
				<th class="rowGroup" colspan="{2+$formatsCount}" scope="rowgroup">
					{$test.group}
				</th>
			</tr>
			{/if}
			<tr class="dataRow {cycle values='even,odd'}" id="row{$test.id}">
				<td class="col firstcol colSelectResources">
					<input type="checkbox" name="ids[]" value="{$test.id}" {if $smarty.post.ids && in_array($test.id, $smarty.post.ids)}checked="checked"{/if} />
				</td>
				<th class="col colName" scope="row">
					<div class="data">
						<span class="name">{$test.name}</span>
						<div class="details hidden">
							<span class="uri">{$test.params.uri}</span>
							<span class="method">{$test.method}</span>
						</div>
					</div>
				</th>
				{foreach $data.testsParams.outputFormats as $format}
				<td class="col outputFormatCol {$format|lower}Col">
					<span class="result">
						{if is_bool($test.result.success)}
							<span class="typeBool">{$test.result.success+''}</span>
						{else}
							?
						{/if}
					</span>
				</td>
				{/foreach}
			</tr>
			{$currentTestGroup=$test.group}
			{/foreach}
			
		</tbody>
	</table>
		
	</div>
</div>