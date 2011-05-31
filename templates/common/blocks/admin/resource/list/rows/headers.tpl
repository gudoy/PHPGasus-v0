<tr>
	<th class="col firstCol colSelectResources" id="toggleAllCel"><input type="checkbox" id="toggleAll" name="toggleAll" /></th>
	{$displayedFieldsNb=0}
	<th class="col actionsCol"><span class="title">{t}Actions{/t}</span></th>
	{foreach $rModel as $fieldName => $field}
	{if ($lightVersion && $field.list == 3) || (!$lightVersion && $field.list >= 1)}
	{$isSorted 				= ($sortBy === $fieldName)}
	{$isDefaultNamefield 	= ($data.meta.defaultNameField === $fieldName)}
	{$orderBy 				= "{if $smarty.get.orderBy === 'asc'}desc{else}asc{/if}"}
	{if $smarty.const._APP_ENABLE_SPLITED_ONE2ONE_COLS && ($field.type === 'onetoone' || $field.fk)}
	<th class="col {$fieldName}Col typeInt fk{if $isSorted} activeSort{/if}{if !$field.list} hidden{/if}" id="{$fieldName}Col" scope="col"><a class="title {if $isSorted}sort {$orderBy}{/if}" href="{$newPageURL}&amp;sortBy={$fieldName}&amp;orderBy={$orderBy}" title="{t}Sort by{/t}{t}:{/t} {$fieldName} {$orderBy}cending">{$data._resources[$field.relResource].singular|default:$field.relResource} {$field.relField}</a></th>
	<th class="col {$field.relGetAs}Col typeVarchar fk{if $isSorted} activeSort{/if}{if !$field.list} hidden{/if}" id="{$field.relGetAs}Col" scope="col"><a class="title {if $isSorted}sort {$orderBy}{/if}" href="{$newPageURL}&amp;sortBy={$field.relGetAs}&amp;orderBy={$orderBy}" title="{t}Sort by{/t}{t}:{/t} {$field.relGetAs} {$orderBy}cending">{$data._resources[$field.relResource].singular|default:$field.relResource} {$field.relGetFields}</a></th>
	{/if}
	{if !$smarty.const._APP_ENABLE_SPLITED_ONE2ONE_COLS || ($smarty.const._APP_ENABLE_SPLITED_ONE2ONE_COLS && $field.type !== 'onetoone' && !$field.fk)}
	<th class="col {$fieldName}Col type{$field.type|ucfirst}{if $isDefaultNamefield} defaultNameField{/if}{if $isSorted} activeSort{/if}{if !$field.list} hidden{/if}" id="{$fieldName}Col" scope="col">
		<a class="title {if $isSorted}sort {$orderBy}{/if}" href="{$newPageURL}&amp;sortBy={$fieldName}&amp;orderBy={$orderBy}" title="{t}Sort by{/t}{t}:{/t} {$fieldName} {$orderBy}cending">{if $field.fk}{$field.displayName|default:$data._resources[$field.relResource].singular}{else}{$field.displayName|default:$fieldName|replace:'_':' '|truncate:'20':'...':true}{/if}{if $field.comment}<span class="comment infos"><span class="detail">{$field.comment}</span></span>{/if}</a>
	</th>
	{/if}
	{$displayedFieldsNb = $displayedFieldsNb+2}
	{/if}
	{/foreach}
    {*<th class="col goToCol"><span class="title">{t}Go{/t}</span></th>*}
	<th class="col colsCol goToCol last">
		<div class="colsManagerBlock" id="colsManagerBlock">
			<a id="colsManagerLink" href="#"><span class="label">{t}show/hide columns{/t}</span></a>
			<ul class="colsBlock" id="colsBlock">
			<li>
				<input type="checkbox" id="actionsColDisplay" checked="checked" />
				<label class="span" for="actionsColDisplay">{t}actions{/t}</label>
			</li>
			{foreach array_keys($rModel) as $column}
				<li>
				{if $smarty.const._APP_ENABLE_SPLITED_ONE2ONE_COLS && ($rModel[$column].type === 'onetoone' || $rModel[$column].fk)}
					<input type="checkbox" id="{$column}ColDisplay" {if $rModel[$column].list}checked="checked"{/if} />
					<label class="span" for="{$column}ColDisplay">{$column|replace:'_':' '}</label>
				</li>
				<li>
					<input type="checkbox" id="{$rModel[$column].relGetAs}ColDisplay" {if $rModel[$column].list}checked="checked"{/if} />
					<label class="span" for="{$rModel[$column].relGetAs}ColDisplay">{$rModel[$column].relGetAs|replace:'_':' '}</label>
				{else}
					<input type="checkbox" id="{$column}ColDisplay" {if $rModel[$column].list}checked="checked"{/if} />
					<label class="span" for="{$column}ColDisplay">{$column|replace:'_':' '}</label>
				{/if}
				</li>
			{/foreach}
	       </ul>
	   </div>
	</th>
</tr>
{include file='common/blocks/admin/resource/list/rows/filters.tpl'}