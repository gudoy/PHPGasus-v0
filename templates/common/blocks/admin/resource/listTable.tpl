{$rModel=$data.dataModel[$resourceName]}
{$rCount=$data[$resourceName]|@count}
<form id="frmAdmin{$resourceName|capitalize}" action="" class="commonForm" method="post" enctype="multipart/form-data">
	
	{if $smarty.const._APP_USE_ADMIN_LIST_TOOLBAR_V2}
	{include file='common/blocks/admin/resource/list/toolbar.tpl' position='top'}
	{else}
	{include file='common/blocks/admin/handleMulti.tpl' position='top'}
	{/if}
	
	<div class="tableWrapperBlock" id="{$resourceName}TableWrapperBlock">
		<table class="commonTable adminTable" id="{$resourceName}Table">
			<caption>{$resourceName}</caption>
			<thead class="{*titleBlock*} titleRow sortables">
				<tr>
		      		{if !$data.sortBy}
						{$sortBy='id'}
						{$order='asc'}
					{/if}
					<th class="col firstCol colSelectResources" id="toggleAllCel">
						<input type="checkbox" id="toggleAll" name="toggleAll" />
					</th>
					{$displayedFieldsNb=0}
					<th class="col actionsCol">
			          	<span class="title">{t}Actions{/t}</span>
					</th>
					{foreach $rModel as $fieldName => $field}
					{if $field.list}
					{if $smarty.get.sortBy == $fieldName || (strpos($smarty.get.sortBy,',') !== false && strpos($smarty.get.sortBy,$fieldName) !== false)}
						{$isSorted=true}
					{else}
						{$isSorted=false}
					{/if}
					{$isDefaultNamefield=($data.meta.defaultNameField===$fieldName)?true:false}
					<th class="col {$fieldName}Col type{$field.type|ucfirst} {if $isDefaultNamefield}defaultNameField{/if} {if $isSorted}ui-state-active{/if} {if !$field.list}hidden{/if}" id="{$fieldName}Col" scope="col">
						<a class="title {if $isSorted}sort {$smarty.get.orderBy|default:'asc'}{/if}" 
							href="{$data.meta.fullAdminPath}?sortBy={$fieldName}&amp;orderBy={if $smarty.get.orderBy == 'asc'}desc{else}asc{/if}" 
							title="{t}Sort by{/t}{t}:{/t} {$fieldName} {if $smarty.get.orderBy == 'asc'}descending{else}ascending{/if}"
							>{if $field.fk}{$field.displayName|default:$data._resources[$field.relResource].singular}{else}{$field.displayName|default:$fieldName|replace:'_':' '|truncate:'20':'...':true}{/if}{if $field.comment}<span class="comment infos"><span class="detail">{$field.comment}</span></span>{/if}</a>
					</th>
					{math assign='displayedFieldsNb' equation="x+2" x=$displayedFieldsNb}
					{/if}
					{/foreach}
					<th class="col colsHandlerCol last">
					   <div class="colsHandlerBlock" id="colsHandlerBlock">
					       <a class="colsHandlerLink" href="#">
					           <span class="label">{t}Manage columns{/t}</span>
					       </a>
					       <div class="colsHandlerManagerBlock hidden" id="colsHandlerManagerBlock" style="height:{$rCount*20}px;">
					       {foreach array_keys($rModel) as $column}
					           <div class="colLine">
					               <input type="checkbox" id="{$column}ColDisplay" class="multi" {if $rModel[$column].list}checked="checked"{/if} />
					               <label class="span" for="{$column}ColDisplay">{$column|replace:'_':' '}</label>
					           </div>
					       {/foreach}
					       </div>
					   </div> 
					</th>
				</tr>
				{if $smarty.const._APP_USE_ADMIN_LIST_FILTERS_V2}
				<tr class="filtersRow" id="{$resourceName}FiltersRow">
                    <td class="col firstcol colSelectResources"></td>
                    <td class="col actionsCol"></td>
				    {foreach $rModel as $colName => $column}
				    {$i=$column@iteration}
				    {$type=$column.type}
				    {$subtype=$column.subtype}
                    <td id="{$colName}FilterCol" class="col {$colName}Col type{$type|ucfirst} {if $subtype}subtype{$subtype|ucfirst}{/if}  {if $column.relResource}typeRel{/if} {if !$column.list}hidden{/if}" scope="col" headers="{$colName}Col">
                        {include file='common/blocks/admin/resource/list/colFilter.tpl'}
				    </td>
				    {/foreach}
				    <td class="col colsHandlerCol last"></td>
				</tr>
				{/if}
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
				{foreach name=$resourceName from=$data[$resourceName] item='resource'}
				<tr class="dataRow {cycle values='even,odd'}" id="row{$resource.id}" {*data-fullAdminPath="{$data.meta.fullAdminPath}{$resource.id}"*} scope="row">
					<td class="col firstcol colSelectResources">
						<input type="checkbox" name="ids[]" value="{$resource.id}" {if $smarty.post.ids && in_array($resource.id, $smarty.post.ids)}checked="checked"{/if} />
					</td>
					<td class="col actionsCol">
						<span class="actions">{include file='common/blocks/admin/resource/actions/listActions.tpl'}</span>
					</td>
					{foreach $rModel as $fieldName => $field}
					{$isDefaultNamefield=($data.metas[$resourceName].defaultNameField===$fieldName)?true:false}
					{$value=$resource[$fieldName]}
					{if $field.list}
					<td id="{$fieldName}Col{$resource.id}" class="col dataCol {$fieldName}Col type{$field.type|ucfirst} {if $field.subtype}subtype{$field.subtype|ucfirst}{/if} {if $isDefaultNamefield}defaultNameField{/if} {if $field.relResource}typeRel{/if} {if !$field.list}hidden{/if}" headers="row{$resource.id} {$fieldName}Col">
						<div class="value dataValue" id="{$fieldName}{$resource.id}" {if $field.type === 'timestamp'}title="{$value|date_format:"%Y-%m-%d %H:%M:%S"}"{/if}>{strip}
						{if $field.type === 'timestamp' || $field.type === 'datetime'}
						{$value|date_format:"%d %B %Y, %Hh%M"}
						{$storedValue=$value}{* Remove and directly use $value  *}
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
							{*
							{if $field.relResource}
								{assign var='relDisplayVal' value=''}
								{if !empty($field.relDisplayAs)}
									{assign var='relDisplayVal' value=$field.relDisplayAs|regex_replace:"/\%(.*)\%/Ue":"\\1"}
									{foreach from=$resource key='key' item='val'}
										{if $resource[$key]}
										{assign var='tmpDisplayVal' value='<span class="'|cat:$key|cat:'">'|cat:$resource[$key]|cat:'</span>'}
										{else}
										{assign var='tmpDisplayVal' value=''}
										{/if}
										{assign var='relDisplayVal' value=$relDisplayVal|replace:$key:$tmpDisplayVal}
									{/foreach}
								{/if}
								{assign var='relDisplayVal' value=$relDisplayVal|default:$resource[$field.relGetAs]}
								<a class="relResourceLink" href="{$smarty.const._URL_ADMIN}{$field.relResource}/{$value}?method=retrieve" title="{t}[require javascript]{/t}">
									{$resource[$fieldName]} - {$relDisplayVal|default:'[untitled]'}
								</a>
								*}
							{if $field.fk}
								{$relResource=$field.relResource}
								{$relField=$field.relField}
								<a class="relResourceLink" href="{$smarty.const._URL_ADMIN}{$relResource}/{$value}">
									{*<span class="relField">{$resource[$fieldName]}</span>*}
									{$resource[{$field.relGetAs|default:$field.relGetFields}]|default:''}
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
						<span class="hidden exactValue">{$value}</span>
						{*
						{if empty($field.pk) && empty($field.editable)}
						<span class="ninja columName">{$data.meta.singular}{$fieldName|ucfirst}</span>
						<span class="ninja fullAdminPath">{$data.meta.fullAdminPath}{$resource.id}</span>
						{/if}
						*}
					</td>
					{/if}
					{/foreach}
					<td class="col colsHandlerCol last">&nbsp;</td>	
				</tr>
				{foreachelse}
				<tr class="noData">
					<td colspan="{$displayedFieldsNb+5}">
						{t}There's currently nothing here{/t}
					</td>
				</tr>
				{/foreach}
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
			</tbody>
		</table>
	</div>
	
    {if $smarty.const._APP_USE_ADMIN_LIST_TOOLBAR_V2}
    {include file='common/blocks/admin/resource/list/toolbar.tpl' position='bottom'}
    {else}
    {include file='common/blocks/admin/handleMulti.tpl' position='bottom'}
    {/if}
	
</form>