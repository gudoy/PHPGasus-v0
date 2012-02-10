<tr>
	<th class="col firstCol colSelectResources" id="toggleAllCel"><input type="checkbox" id="toggleAll" name="toggleAll" /></th>
	<th class="col actionsCol"><span class="title">{t}Actions{/t}</span></th>
	{foreach $rModel as $fieldName => $field}
	{$isSorted 				= ($data.current.urlParams.sortBy === $fieldName)}
	{$isDefaultNamefield 	= ($data.meta.defaultNameField === $fieldName)}
	{$orderBy 				= "{if $data.current.urlParams.orderBy === 'asc'}desc{else}asc{/if}"}
	{$queryParams 			= array_merge($data.current.urlParams, ['sortBy' => null, 'orderBy' => null])}
    {$newPageURL 			= {$curURL|regex_replace:'/(.*)\?(.*)$/U':'$1'}|cat:'?'|cat:{http_build_query($queryParams)}}
	{$displayed 			= false}
	{if ($lightVersion && $field.list == 3) || (!$lightVersion && $field.list >= 1)}{$displayed = true}{/if}
	{if $smarty.const._APP_ENABLE_SPLITED_ONE2ONE_COLS && ($field.type === 'onetoone' || $field.fk)}
	<th class="col {$fieldName}Col typeInt fk{if $isSorted} activeSort{/if}{if !$displayed} hidden{/if}" id="{$fieldName}Col" scope="col"><a class="title {if $isSorted}sort {$orderBy}{/if}" href="{$newPageURL}&amp;sortBy={$fieldName}&amp;orderBy={$orderBy}" title="{t}Sort by{/t}{t}:{/t} {$fieldName} {$orderBy}cending">{$data._resources[$field.relResource].singular|default:$field.relResource} {$field.relField}</a></th>
	<th class="col {$field.relGetAs}Col typeVarchar fk{if $isSorted} activeSort{/if}{if !$displayed} hidden{/if}" id="{$field.relGetAs}Col" scope="col"><a class="title {if $isSorted}sort {$orderBy}{/if}" href="{$newPageURL}&amp;sortBy={$field.relGetAs}&amp;orderBy={$orderBy}" title="{t}Sort by{/t}{t}:{/t} {$field.relGetAs} {$orderBy}cending">{$data._resources[$field.relResource].singular|default:$field.relResource} {$field.relGetFields}</a></th>
	{$displayedFieldsNb = $displayedFieldsNb+1}
	{/if}
	{if !$smarty.const._APP_ENABLE_SPLITED_ONE2ONE_COLS || ($smarty.const._APP_ENABLE_SPLITED_ONE2ONE_COLS && $field.type !== 'onetoone' && !$field.fk)}
	<th class="col {$fieldName}Col type{$field.type|ucfirst}{if $isDefaultNamefield} defaultNameField{/if}{if $isSorted} activeSort{/if}{if !$displayed} hidden{/if}" id="{$fieldName}Col" scope="col">
		<a class="title {if $isSorted}sort {$orderBy}{/if}" href="{$newPageURL}&amp;sortBy={$fieldName}&amp;orderBy={$orderBy}" title="{t}Sort by{/t}{t}:{/t} {$fieldName} {$orderBy}cending">{if $field.fk}{$field.displayName|default:$data._resources[$field.relResource].singular}{else}{$field.displayName|default:$fieldName|replace:'_':' '|truncate:'20':'...':true}{/if}{if $field.comment}<span class="comment infos"><span class="detail value">{$field.comment}</span></span>{/if}</a>
	</th>
	{/if}
	{if $displayed}{$displayedFieldsNb = $displayedFieldsNb+1}{/if}
	{/foreach}
	<th class="col colsCol goToCol last">
		<div class="colsManagerBlock" id="colsManagerBlock">
			<a id="colsManagerLink" href="#"><span class="label">{t}show/hide columns{/t}</span></a>
			<ul class="colsBlock" id="colsBlock">
			{*
			<li>
				<input type="checkbox" id="actionsColDisplay" checked="checked" />
				<label class="span" for="actionsColDisplay">{t}actions{/t}</label>
			</li>
			*}
			{foreach array_keys($rModel) as $column}
			{$displayed 			= false}
			{if ($lightVersion && $rModel[$column].list == 3) || (!$lightVersion && $rModel[$column].list >= 1)}{$displayed = true}{/if}
				<li>
				{if $smarty.const._APP_ENABLE_SPLITED_ONE2ONE_COLS && ($rModel[$column].type === 'onetoone' || $rModel[$column].fk)}
					<input type="checkbox" id="{$column}ColDisplay" {if $displayed}checked="checked"{/if} />
					<label class="span" for="{$column}ColDisplay">{$column|replace:'_':' '}</label>
				</li>
				<li>
					<input type="checkbox" id="{$rModel[$column].relGetAs}ColDisplay" {if $displayed}checked="checked"{/if} />
					<label class="span" for="{$rModel[$column].relGetAs}ColDisplay">{$rModel[$column].relGetAs|replace:'_':' '}</label>
				{else}
					<input type="checkbox" id="{$column}ColDisplay" {if $displayed}checked="checked"{/if} />
					<label class="span" for="{$column}ColDisplay">{$column|replace:'_':' '}</label>
				{/if}
				</li>
			{/foreach}
	       </ul>
	   </div>
	</th>
</tr>
{include file='common/blocks/admin/resource/list/rows/filters.tpl'}