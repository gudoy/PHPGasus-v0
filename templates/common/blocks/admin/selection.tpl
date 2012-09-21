{$title = $title|default:"{t}filters{/t}"}
<div class="resourceFilters transactionsFilters" id="transactionsFilters">
	<header>
		<span class="title">{$title}</span>
		<div class="selectionFilters" id="selectionFilters">
			<ul>
				{* Empty filter templates used for new item insertion in js (start) *}
				<li class="item selectionFilter multi tpl hidden" data-resource="{$filtersResName}" data-filterindex="{$fIndex}" data-column="" data-operator="" data-values="">
					<span class="filterResource"></span>
					<span class="filterColumn"></span>
					<span class="filterOperator hidden"></span>
					<span class="filterValues" data-exact="true"></span>
					<span class="filterValues" data-exact="false">
						<details>
							<summary><span class="count"></span></summary>
							<ul>
								<li class="filterValue" data-value="">
									<span></span>
									<nav class="actions">
										<a class="action remove" href="#" title="{t}remove this value from the filter{/t}"><span class="value">{t}remove{/t}</span></a>
									</nav>
								</li>
							</ul>
						</details>
					</span>
					<nav class="actions">
						<a class="action remove" href="#" title="{t}remove this filter{/t}"><span class="value">{t}remove{/t}</span></a>
					</nav>
				</li>
				{* Empty filter templates used for new item insertion in js (end) *}
			{foreach $data.selection.machines.filters as $fIndex => $filter}
				{$valCount 	= count($filter[2])}
				{$isMulti 	= ($valCount >= 2)}
				{$hasDot 	= (strpos($filter[0], '.') !== false)?true:false}
				{$parts 	= ($hasDot)?explode('.',$filter[0]):null}
				{$res 		= ($hasDot)?$parts[0]:$filtersResName}
				{$col 		= ($hasDot)?$parts[1]:$filter[0]}
				<li class="item selectionFilter {if $isMulti}multi{/if}" data-resource="{$res}" data-filterindex="{$fIndex}" data-column="{$col}" data-operator="{$filter[1]}" data-values="{join(',',(array)$filter[2])}">
					<span class="filterResource">{$data._resources[$res].displayName|default:$res}</span>
					<span class="filterColumn">{$filters[$col].displayName|default:$col}</span>
					<span class="filterOperator hidden">{$col}</span>
					{if $isMulti} 
					<span class="filterValues" data-exact="false">
						<details>
							<summary><span class="count">({if $valCount}{t 1=$valCount}%1 items{/t}{else}{t}several items{/t}{/if})</span></summary>
							<ul>
								{foreach $filter[2] as $val}
								<li class="filterValue" data-value="{$val}">
									<span>{$val}</span>
									<nav class="actions">
										<a class="action remove" href="#" title="{t}remove this value from the filter{/t}"><span class="value">{t}remove{/t}</span></a>
									</nav>
								</li>
								{/foreach}
							</ul>
						</details>
					</span>
					{else}
					<span class="filterValues" data-exact="true">{$filter[2]}</span>
					{/if}
					<nav class="actions">
						<a class="action remove" href="{$smarty.const._URL_ADMIN}selection/{$res}/filters/{$fIndex}?method=delete" title="{t}remove this filter{/t}"><span class="value">{t}remove{/t}</span></a>
					</nav>
				</li>
			{/foreach}
			</ul>
		</div>
		<nav class="actions">
			<a id="addSelectionFilter" class="action add" data-revertlabel="{t}cancel{/t}" title="{t}add new filter(s){/t}"><span class="value">add</span></a>
		</nav>
		<form class="adminSelectionFilterForm" id="adminSelectionFilterForm" action="{$data.current.url}" data-ajaxaction="{$smarty.const._URL_ADMIN}selection/{$filtersResName}" method="post">
			<div class="fields fieldset body">
				<legend><span class="value">{t}Add a filter{/t}</span></legend>
				<div class="line" id="filterColumnLine">
					<div class="labelBlock">
						<label class="span" for="filterColumn">{t}column{/t}</label>
					</div>
					<div class="fieldBlock">
						<input type="hidden" name="selection[{$filtersResName}][filters][0][resource]" value="{$filtersResName}" />
						<select name="selection[{$filtersResName}][filters][0][column]" id="filterColumn" data-resource="{$filtersResName}">
							{foreach $filters as $colName => $fProps}
							<option value="{$colName}" data-preload="{$fProps.preload|default:"false"}" data-minforsuggest="{$fProps.minforsuggest|default:0}" {if $colName@first}selected="selected"{/if}>{$fProps.displayName}</option>
							{$placeholderAll[] = $fProps.displayName}
							{/foreach}
						</select>
					</div>
				</div><div class="line" id="filterValueLine">
					<div class="labelBlock">
						<label class="span" for="filterValue">Filter</label>
					</div>
					<div class="fieldBlock">
						<input type="hidden" id="filterOperator" name="selection[{$filtersResName}][filters][0][operator]" value="is" />
						{*<input name="selection[{$filtersResName}][filters][0][values]" type="{if $html5 && $browser.support.datalist}search{else}text{/if}" {if $html5 && $browser.support.datalist}list="suggestFilterValue"{/if} class="normal search" id="filterValue" placeholder="{join(', ',(array)$placeholderAll)}" />*}
						<input name="selection[{$filtersResName}][filters][0][values]" type="{if $html5 && $browser.support.datalist}search{else}text{/if}" {if $html5 && $browser.support.datalist}list="suggestFilterValue"{/if} class="normal search" id="filterValue" placeholder="" />
						<div class="suggestFilterValue" id="suggestFilterValue">
							<datalist class="suggest hidden" ></datalist>
							{foreach $filters as $colName => $fProps}
							{if $fProps.preload}
							<datalist class="suggest" id="{$colName}ValuesSuggest">
								{foreach $fProps.values as $value}
								<option value="{$value}">{$value}</option>
								{/foreach}
							</datalist>
							{/if}
							{/foreach}
						</div>
					</div>
				</div> 
			</div>
			<div class="buttons actions">
				{include file='common/blocks/actionBtn.tpl' mode='button' label="{t}add{/t}"}
			</div>
		</form>
	</header>
</div>