<form id="frmAdmin{$resourceName|capitalize}" action="" class="commonForm" method="post" enctype="multipart/form-data">
	
	{include file='common/blocks/admin/handleMulti.tpl' position='top'}
	
	<div class="tableWrapperBlock" id="{$resourceName}TableWrapperBlock">
		<table class="commonTable adminTable" id="{$resourceName}Table">
			<caption>{$resourceName}</caption>
			<thead class="{*titleBlock*} titleRow sortables">
				<tr>
		      		{if !$data.sortBy}
						{assign var='sortBy' value='id'}
						{assign var='order' value='asc'}
					{/if}
					<th class="col firstCol colSelectResources" id="toggleAllCel">
						<input type="checkbox" id="toggleAll" name="toggleAll" />
					</th>
					{assign var='displayedFieldsNb' value=0}
					<th class="col actionsCol">
			          	<span class="title">{t}Actions{/t}</span>
					</th>
					{foreach name='tableFields' from=$data.dataModel[$resourceName] key='fieldName' item='field'}
					{if $field.list}
					{if $smarty.get.sortBy == $fieldName || (strpos($smarty.get.sortBy,',') !== false && strpos($smarty.get.sortBy,$fieldName) !== false)}
						{assign var='isSorted' value=true}
					{else}
						{assign var='isSorted' value=false}
					{/if}
					{$isDefaultNamefield=($data.metas[$resourceName].defaultNameField===$fieldName)?true:false}
					<th class="col {$fieldName}Col type{$field.type|ucfirst} {if $isDefaultNamefield}defaultNameField{/if} {if $isSorted}ui-state-active{/if} {if !$field.list}hidden{/if}">
						<a class="title {if $isSorted}sort {$smarty.get.orderBy|default:'asc'}{/if}" 
							href="{$data.meta.fullAdminPath}?sortBy={$fieldName}&amp;orderBy={if $smarty.get.orderBy == 'asc'}desc{else}asc{/if}" 
							title="{t}Sort by{/t}{t}:{/t} {$fieldName} {if $smarty.get.orderBy == 'asc'}descending{else}ascending{/if}"
							>{if $field.fk}{$field.relResource}{else}{$field.displayName|default:$fieldName|replace:'_':' '|truncate:'20':'...':true}{/if}{if $field.comment}<span class="comment infos"><span class="detail">{$field.comment}</span></span>{/if}</a>
					</th>
					{math assign='displayedFieldsNb' equation="x+2" x=$displayedFieldsNb}
					{/if}
					{/foreach}
					<th class="col colsHandlerCol last">
						<a class="colsHandlerLink" href="#">
							<span class="label">{t}Manage columns{/t}</span>
						</a>
						<div class="colsHandlerManagerBlock hidden">
							{foreach name='tableFields' from=$data.dataModel[$resourceName] key='fieldName' item='field'}
							<div class="colLine">
								<input type="checkbox" id="{$fieldName}DisplayColChooser" class="multi" {if $field.list}checked="checked"{/if} />
								<label class="span" for="{$fieldName}DisplayColChooser">{$fieldName}</label>
							</div>
							{/foreach}
						</div>
					</th>
				</tr>
			</thead>
			<tbody>
				<tr class="{cycle values='odd'} addRow">
					<td colspan="{$displayedFieldsNb+4}">
						{include file='common/blocks/admin/resource/actions/listAdd.tpl'}
						<div class="resourcesCount">
							<span class="value">{$data[$resourceName]|@count} {t}of{/t} {$data.total[$resourceName]}</span>
						</div>
					</td>
				</tr>
				{foreach name=$resourceName from=$data[$resourceName] item='resource'}
				<tr class="dataRow {cycle values='even,odd'}" id="row{$resource.id}" data-fullAdminPath="{$data.meta.fullAdminPath}{$resource.id}">
					<td class="col firstcol colSelectResources">
						<input type="checkbox" name="ids[]" value="{$resource.id}" {if $smarty.post.ids && in_array($resource.id, $smarty.post.ids)}checked="checked"{/if} />
					</td>
					<td class="col actionsCol">
						<span class="actions">{include file='common/blocks/admin/resource/actions/listActions.tpl'}</span>
					</td>
					{foreach name='tableFields' from=$data.dataModel[$resourceName] key='fieldName' item='field'}
					{$isDefaultNamefield=($data.metas[$resourceName].defaultNameField===$fieldName)?true:false}
					{$value=$resource[$fieldName]}
					{if $field.list}
					<td id="{$fieldName}Col{$resource.id}" class="col dataCol {$fieldName}Col type{$field.type|ucfirst} {if $field.subtype}subtype{$field.subtype|ucfirst}{/if} {if $isDefaultNamefield}defaultNameField{/if} {if $field.relResource}typeRel{/if} {if !$field.list}hidden{/if}">
						<div class="value dataValue" id="{$fieldName}{$resource.id}" {if $field.type === 'timestamp'}title="{$value|date_format:"%Y-%m-%d %H:%M:%S"}"{/if}>{strip}
						{if $field.type === 'timestamp' || $field.type === 'datetime'}
						{$value|date_format:"%d %B %Y, %Hh%M"}
						{$storedValue=$value}{* Remove and directly use $value  *}
						{elseif $field.type === 'bool'}
							{if $value === true || $value === 't' || $value == 1}
								<span class="validity valid">{t}yes{/t}</span>
							{else}
								<span class="validity invalid">{t}no{/t}</span>
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
							{if $field.relResource}
								{* Handle related resource *}
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
								<a class="relResourceLink" href="{$data.metas[$field.relResource].fullAdminPath}{$value}?method=retrieve" title="{t}[require javascript]{/t}">
									{$resource[$fieldName]} - {$relDisplayVal|default:'[untitled]'}
								</a>
							{else}
								{*$value|escape:'html':'utf-8'|truncate:50:"..."*}
								{if !$field.listTruncate}
									{$value|regex_replace:'/&([^#]|$)/':'&amp;$1'|stripslashes|default:'&nbsp;'}
								{else}
									{$value|regex_replace:'/&([^#]|$)/':'&amp;$1'|stripslashes|truncate:50:"..."|default:'&nbsp;'}
								{/if}
							{/if}
						{/if}
						{/strip}</div>
						<span class="hidden exactValue">{$value}</span>
						{if empty($field.pk) && empty($field.editable)}
						<span class="ninja columName">{$data.meta.singular}{$fieldName|ucfirst}</span>
						<span class="ninja fullAdminPath">{$data.meta.fullAdminPath}{$resource.id}</span>
						{/if}
					</td>
					{/if}
					{/foreach}
					<td class="col colsHandlerCol last">&nbsp;</td>	
				</tr>
				{foreachelse}
				<tr>
					<td colspan="{$displayedFieldsNb+5}">
						{t}There's currently no item in the database{/t}
					</td>
				</tr>
				{/foreach}
				<tr class="{cycle values='odd'} addRow">
					<td colspan="{$displayedFieldsNb+4}">
						{include file='common/blocks/admin/resource/actions/listAdd.tpl'}
						<div class="resourcesCount">
							<span class="value">{$data[$resourceName]|@count} {t}of{/t} {$data.total[$resourceName]}</span>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	
	{include file='common/blocks/admin/handleMulti.tpl' position='bottom'}
	
</form>