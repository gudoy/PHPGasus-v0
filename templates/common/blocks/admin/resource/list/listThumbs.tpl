{* $rProps 		= $data._resources[$resourceName]}
{$rModel 		= $data.dataModel[$resourceName]}
{$imageField 	= $rProps.imageField}
{$nameField 	= $rProps.nameField|default:$rProps.defaultNameField}
{$descField 	= $rProps.descField|default:null}
{$userResPerms 	= $data.current.user.auths[$resourceName]}
{$crudability 	= $rProps.crudability|default:'CRUD'}
{$isReadable 	= (strpos($crudability, 'R')>-1)?1:0 *}

{foreach array_keys((array) $data[$resourceName]) as $key}
{$resource = $data[$resourceName][$key]}
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
	{if $isReadable}<a class="content goTo" href="{$smarty.const._URL_ADMIN}{$resourceName}/{$resource.id}">{else}<div class="content">{/if}
		{if $resource.update_date}
		{include file='common/blocks/humanTime.tpl' class='datetimeField' value=$resource.update_date}
		{/if}
		<hgroup>
			<h3 class="title"><span class="id">{$resource.id} </span>{if $resource[$nameField]}<span class="nameField">{$resource[$nameField]}</span>{/if}</h3>
		</hgroup>
		{if $descField}
		<p class="summary descField" data-column="{$colName}" data-label="{$colName|replace:'-':' '}" data-type="{$rType}" {if !is_array($colVal)}data-exactvalue="{$colVal}"{/if}>{$resource[$descField]}</p>
		{/if}
		<div class="data">
			{$skipColumns =['id' => 'id', $imageField => $imageField, $nameField => $nameField, $descField => $descField, 'update_date' => 'update_date']}
			{$hasData = false}
			{foreach $resource as $colName => $colVal}
			{$rType 		= $rModel[$colName].type}
			{if !isset($skipColumns[$colName]) && ($rModel[$colName].list && $rModel[$colName].list > 0)}
			<span class="col {$colName}Col" data-column="{$colName}" data-label="{$colName|replace:'-':' '}" data-type="{$rType}" {if !is_array($colVal)}data-exactvalue="{$colVal}"{/if}>{include file='common/blocks/admin/resource/list/cols/dataValue2.tpl'}</span>
			{/if}
			{/foreach}
		</div>
	{if $isReadable}</a>{else}</div>{/if}
	{/if}
	<nav class="actions">
		{if $isReadable}<a class="action primary view goTo" href="{$smarty.const._URL_ADMIN}{$resourceName}/{$resource.id}">{/if}
		{include file='common/blocks/admin/resource/actions/listActions.tpl'}
		{if $isReadable}</a>{/if}
	</nav>
</article>
{foreachelse}
<p class="nodata">{t}There's currently nothing here{/t}</p>
{/foreach}
