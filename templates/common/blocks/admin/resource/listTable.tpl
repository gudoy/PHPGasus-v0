{strip}
{$rModel 		= $data.dataModel[$resourceName]}
{$rCount 		= $data[$resourceName]|@count}
{$lightVersion 	= $data.device.isMobile|default:false}
{$sortBy 		= $data.current.sortBy}
{$orderBy 		= $data.current.orderBy|default:'asc'}
{$data.current.urlParams.sortBy=null}
{$data.current.urlParams.orderBy=null}
{$newPageURL="{$curURLbase}?{http_build_query($data.current.urlParams)}"}
{/strip}
{include file='common/blocks/admin/resource/list/toolbar.tpl' position='top'}
<div class="tableWrapperBlock" id="{$resourceName}TableWrapperBlock">
	<table class="commonTable adminTable {$resourceName}Table" id="{$resourceName}Table">
		<caption>{$resourceName}</caption>
		<thead class="{*titleBlock*} titleRow sortables">
			<tr>
				<th class="col firstCol colSelectResources" id="toggleAllCel"><input type="checkbox" id="toggleAll" name="toggleAll" /></th>
				{$displayedFieldsNb=0}
				<th class="col actionsCol"><span class="title">{t}Actions{/t}</span></th>
				{foreach $rModel as $fieldName => $field}
				{if ($lightVersion && $field.list == 3) || (!$lightVersion && $field.list >= 1)}
				{$isSorted 				= ($sortBy === $fieldName)}
				{$isDefaultNamefield 	= ($data.meta.defaultNameField === $fieldName)}
				<th class="col {$fieldName}Col type{$field.type|ucfirst}{if $isDefaultNamefield} defaultNameField{/if}{if $isSorted} ui-state-active{/if}{if !$field.list} hidden{/if}" id="{$fieldName}Col" scope="col">
					<a class="title {if $isSorted}sort {$orderBy}{/if}" href="{$newPageURL}sortBy={$fieldName}&amp;orderBy={$orderBy}" title="{t}Sort by{/t}{t}:{/t} {$fieldName} {$orderBy}cending">{if $field.fk}{$field.displayName|default:$data._resources[$field.relResource].singular}{else}{$field.displayName|default:$fieldName|replace:'_':' '|truncate:'20':'...':true}{/if}{if $field.comment}<span class="comment infos"><span class="detail">{$field.comment}</span></span>{/if}</a>
				</th>
				{$displayedFieldsNb=$displayedFieldsNb+2}
				{/if}
				{/foreach}
				<th class="col colsHandlerCol">
					<div class="colsHandlerBlock" id="colsHandlerBlock">
						<a class="colsHandlerLink" href="#"><span class="label">{t}Manage columns{/t}</span></a>
						<div class="colsHandlerManagerBlock" id="colsHandlerManagerBlock" style="height:{$rCount*20}px;">
						{foreach array_keys($rModel) as $column}
							<div class="colLine">
								<input type="checkbox" id="{$column}ColDisplay" class="multi" {if $rModel[$column].list}checked="checked"{/if} />
								<label class="span" for="{$column}ColDisplay">{$column|replace:'_':' '}</label>
							</div>
						{/foreach}
				       </div>
				   </div> 
				</th>
	            <th class="col goToCol last"><span class="title">{t}Go{/t}</span></th>
			</tr>
			<tr class="filtersRow" id="{$resourceName}FiltersRow">
                <td class="col firstcol colSelectResources">&nbsp;</td>
                <td class="col actionsCol">&nbsp;</td>
			    {foreach $rModel as $colName => $column}
			    {if ($lightVersion && $column.list == 3) || (!$lightVersion && $column.list >= 1)}
			    {$i                  	= $column@iteration}
			    {$type               	= $column.type}
			    {$subtype            	= $column.subtype}
				{$isSorted 				= ($sortBy === $fieldName)}
				{$isDefaultNamefield 	= ($data.meta.defaultNameField === $colName)}
                <td class="col {$colName}Col type{$type|ucfirst}{if $subtype} subtype{$subtype|ucfirst}{/if}{if $isDefaultNamefield} defaultNameField{/if}{if $isSorted} ui-state-active{/if} {if $column.relResource} typeRel{/if}{if !$column.list} hidden{/if}" id="{$colName}FilterCol" scope="col" headers="{$colName}Col">{strip}
                    {include file='common/blocks/admin/resource/list/colFilter.tpl'}
			    {/strip}</td>
			    {/if}
			    {/foreach}
			    <td class="col colsHandlerCol last">&nbsp;</td>
			    <td class="col goToCol last">&nbsp;</td>
			</tr>
		</thead>
		<tbody>
			{if !$smarty.const._APP_USE_ADMIN_LIST_TOOLBAR_V2}
			<tr class="{cycle values='odd'} addRow">
				<td colspan="{$displayedFieldsNb+4}">
					{include file='common/blocks/admin/resource/actions/listAdd.tpl'}
					<div class="resourcesCount">
						<span class="value">{$rCount} {t}of{/t} {$data.total[$resourceName]}</span>
					</div>
				</td>
			</tr>
			{/if}
			{foreach $data[$resourceName] as $resource}
			<tr class="dataRow {cycle values='even,odd'}{if $resource@first} firstRow{/if}{if $resource@last} lastRow{/if}" id="row{$resource.id}" scope="row">
				<td class="col firstCol colSelectResources">
					<input type="checkbox" name="ids[]" value="{$resource.id}" {if $smarty.post.ids && in_array($resource.id, $smarty.post.ids)}checked="checked"{/if} />
				</td>
				<td class="col actionsCol">
					<span class="actions">{include file='common/blocks/admin/resource/actions/listActions.tpl'}</span>
				</td>
				{foreach $rModel as $fieldName => $field}
				{$isSorted 				= ($sortBy === $fieldName)}
				{$isDefaultNamefield 	= ($data.meta.defaultNameField === $fieldName)}
				{$value                 = $resource[$fieldName]}
                {if ($lightVersion && $field.list == 3) || (!$lightVersion && $field.list >= 1)}
				<td id="{$fieldName}Col{$resource.id}" class="col dataCol {$fieldName}Col type{$field.type|ucfirst}{if $field.subtype} subtype{$field.subtype|ucfirst}{/if}{if $isDefaultNamefield} defaultNameField{/if}{if $isSorted} ui-state-active{/if}{if $field.relResource} typeRel{/if}{if !$field.list} hidden{/if}" headers="row{$resource.id} {$fieldName}Col">
					<div class="value dataValue" id="{$fieldName}{$resource.id}" {if $field.type === 'timestamp'}title="{$value|date_format:"%Y-%m-%d %H:%M:%S"}"{/if} data-exactValue="{$value}">{strip}
					{if $field.type === 'timestamp' || $field.type === 'datetime'}
					{$value|date_format:"%d %B %Y, %Hh%M"}
					{$storedValue=$value}{* Remove and directly use $value *}
					{elseif $field.type === 'bool'}
						{if $value === true || $value === 't' || $value == 1}
							<span class="validity valid"><span class="label">{t}yes{/t}</span></span>
						{else}
							<span class="validity invalid"><span class="label">{t}no{/t}</span></span>
						{/if}
					{elseif $field.type === 'int' && $field.subtype === 'fixedValues'}
						{* assign var='posValIndex' value=$value*}
						{$field.possibleValues[$value]}
					{elseif $field.subtype === 'file' || $field.subtype == 'fileDuplicate'}
						{if $value}
							{$baseURL=$field.destBaseURL|default:$smarty.const._URL}
						<a class="currentItem file" href="{if strpos($value, 'http://') === false}{rtrim($baseURL,'/')}{if $field.storeAs === 'filename'}{$field.destFolder}{/if}{/if}{$value}">
							{if $field.mediaType && $field.mediaType == 'image' && $field.storeAs !== 'filename'}
							<img class="value" src="{$smarty.const._URL}{$value}" alt="{$value}: {$resource.id}" />
							{else}
							<span class="value">{$value|regex_replace:"/.*\//":""}</span>
							{/if}
						</a>
						{/if}
					{elseif $field.subtype === 'url'}
						{if $value}
						<a class="url" href="{if strpos($value, 'http://') === false}{$field.prefix|default:$smarty.const._URL}{/if}{$value}">
							<span class="value">../{$value|regex_replace:"/.*\//":""}</span>
						</a>
						{else}
							&nbsp;
						{/if}
					{else}
						{* Handle foreign keys/onetoone relations *}
						{if $field.fk}
							{$relResource=$field.relResource}
							{$relField=$field.relField}
							<a class="relResourceLink" href="{$smarty.const._URL_ADMIN}{$relResource}/{$value}">
								{*<span class="relField">{$resource[$fieldName]}</span>*}
								{$resource[{$field.relGetAs|default:$field.relGetFields}]|default:$value}
							</a>
						{else}
							{if !$field.listTruncate}
								{$value|regex_replace:'/&([^#]|$)/':'&amp;$1'|stripslashes|default:'&nbsp;'}
							{else}
								{$value|regex_replace:'/&([^#]|$)/':'&amp;$1'|stripslashes|truncate:50:"..."|default:'&nbsp;'}
							{/if}
						{/if}
					{/if}
					{/strip}</div>
					{*<span class="hidden exactValue">{$value}</span>*}
				</td>
				{/if}
				{/foreach}
				<td class="col colsHandlerCol">&nbsp;</td>
				<td class="col goToCol lastCol">
				{strip}
					{$crudability=$data._resources[$resourceName].crudability|default:'CRUD'}
					{include file='common/blocks/admin/resource/actions/retrieve.tpl' disabled=(strpos($crudability, 'R')>-1)?0:1}
				{/strip}	
				</td>
			</tr>
			{foreachelse}
			<tr class="noData">
				<td class="firstCol lastCol" colspan="{$displayedFieldsNb+5}">
					{t}There's currently nothing here{/t}
				</td>
			</tr>
			{/foreach}
		</tbody>
	</table>
</div>
{include file='common/blocks/admin/resource/list/toolbar.tpl' position='bottom'}