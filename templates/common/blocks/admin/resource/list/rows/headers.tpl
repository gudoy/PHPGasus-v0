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
{include file='common/blocks/admin/resource/list/rows/filters.tpl'}