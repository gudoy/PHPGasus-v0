{foreach array_keys((array) $data[$resourceName]) as $key}
{$resource = $data[$resourceName][$key]}
{* Case 1 column only *}
{if !is_array($resource)}
{$request.pattern = 'column'}
<article class="resource" id="{$resourceName}{$key@index}" data-nameField="{$nameField}">
	<div class="status"><input type="checkbox" name="selectedItems[]" value="{$key@index}" /></div>
	<div class="content oneColOnly">
		<hgroup>
			<h3 class="title">
			{strip}
			{$colVal 	= $resource}
			{if isset($getFields) && count($getFields) === 1}
				{$pattern 	= 'get1Col'}
				{$colName 	= $getFields[0]}
			{elseif $data.options.mode === 'distinct' && isset($data.options.field)}
				{$pattern 	= 'distinct1Col'}
				{$colName 	= $data.options.field}
			{/if}
			{/strip}
			{if $pattern === 'get1Col' || $pattern === 'distinct1Col'}
			{$cProps 		= $rModel[$colName]}
			{$cType 		= $cProps.type}
			<span class="col {$colName}Col {if is_null($colVal) || $colVal == ''} noValue{/if}" data-column="{$colName}" data-label="{$cProps.displayName|default:"{$colName|replace:'-':' '|replace:'_':' '}"}" data-type="{$cType}" data-subtype="{$cProps.subtype}" {if !is_array($colVal)}data-value="{$colVal}"{/if}>{include file='common/blocks/admin/resource/list/cols/dataValue2.tpl'}</span>
			{else}
			<span class="col {if is_null($colVal) || $colVal == ''}noValue{/if}">{$colVal|default:"{t}[no value]{/t}"}</span>
			{/if}
			</h3>
		</hgroup>
	</div>
	<nav class="actions">
		{if $isReadable}
		{if isset($resource.id)}
		<a class="action primary view goTo" href="{$smarty.const._URL_ADMIN}{$resourceName}/{$resource.id}"><span class="value">view</span></a>
		{elseif $colName}
		<a class="action primary view viewAll goTo" href="{$smarty.const._URL_ADMIN}{$resourceName}/?conditions={$colName}|{$colVal}&displayCols={$colName}" title="{t 1=$resourceName 2=$colName 3="{$colVal|default:"{t}this value{/t}"}"}view all %1 whose %2 match %3{/t}"><span class="value">view</span></a>
		{/if}
		{/if}
	</nav>
</article>
{* Case several columns *}
{else}
<article class="resource" id="{$resourceName}{$resource.id}" data-id="{$resource.id}" data-nameField="{$nameField}">
	<div class="status"><input type="checkbox" name="{$resourceName}Ids[]" value="{$resource.id}" /></div>
	<figure>
		{$src = $resource[$imageField]|default:$rProps.icon}
		<img class="cover{if !$src} default{/if}" src="{$src|default:"{$smarty.const._URL_STYLESHEETS}images/pix.png"}" />
		{if $displayMode === 'thumbs'}
		<figcaption>
			{if $isReadable}<a class="goTo" href="{$smarty.const._URL_ADMIN}{$resourceName}/{$resource.id}">{/if}<span class="title nameField">{$resource[$nameField]|default:$resource.id}</span>{if $isReadable}</a>{/if}
		</figcaption>
		{/if}
	</figure>
	{if $displayMode === 'list'}
	{* if $isReadable}<a class="content goTo" href="{$smarty.const._URL_ADMIN}{$resourceName}/{$resource.id}">{else}<div class="content">{/if *}
	<div class="content">
		{* var_dump($data.options) *}
		{if $resource.update_date}
		{include file='common/blocks/humanTime.tpl' class='datetimeField' value=$resource.update_date}
		{/if}
		<hgroup>
			<h3 class="title">
				{if $resource.id}<span class="col id idCol" data-column="id" data-value="{$colVal}">{$resource.id}</span>{/if}
				{if $resource[$nameField]}
				<span class="col nameField nameFieldCol {$nameField}Col" data-column="{$nameField}" data-value="{$resource[$nameField]}">{$resource[$nameField]}</span>
				{/if}
			</h3>
		</hgroup>
		{if $descField}
		<p class="summary descField descFieldCol {$descField}Col" data-column="{$descField}" data-label="{$descField|replace:'-':' '}" data-type="{$rModel[$descField].type}" {if !is_array($resource[$descField])}data-value="{$colVal}"{/if}>{$resource[$descField]}</p>
		{/if}
		<div class="data">{strip}
			{$skipColumns =['id' => 'id', $imageField => $imageField, $nameField => $nameField, $descField => $descField, 'update_date' => 'update_date']}
			{$hasData = false}
			{foreach $resource as $colName => $colVal}
			{$cProps 		= $rModel[$colName]}
			{$cType 		= $cProps.type}
			{if (!isset($skipColumns[$colName]) && (isset($cProps[$colName].list) && $cProps[$colName].list > 0)) || isset($data.options.displayCols[$colName])}
			{* TODO: if col does not exists in dataModel *}
			{if !isset($rModel[$colName])}{$skipColumns[$colName] = $colName}{/if}
			{if !isset($skipColumns[$colName])}
			{* TODO: display human readable values instead of exact ones *}
			<span class="col {$colName}Col {if is_null($colVal) || $colVal == ''} noValue{/if}" data-column="{$colName}" data-label="{$cProps.displayName|default:"{$colName|replace:'-':' '|replace:'_':' '}"}" data-type="{$cType}" data-subtype="{$cProps.subtype}" {if !is_array($colVal)}data-value="{$colVal}"{/if}>{include file='common/blocks/admin/resource/list/cols/dataValue2.tpl'}</span>
			{/if}
			{/if}
			{/foreach}
		{/strip}</div>
	{* if $isReadable}</a>{else}</div>{/if *}
	</div>
	{/if}
	<nav class="actions">
		{if $isReadable}<a class="action primary view goTo" href="{$smarty.const._URL_ADMIN}{$resourceName}/{$resource.id}"><span class="value">view</span></a>{/if}
		{include file='common/blocks/admin/resource/actions/listActions.tpl'}
	</nav>
</article>
{/if}
{foreachelse}
<p class="nodata">{t}There's currently nothing here{/t}</p>
{/foreach}
