<tr class="filtersRow" id="{$resourceName}FiltersRow">
    <td class="col firstcol colSelectResources">&nbsp;</td>
    <td class="col actionsCol">&nbsp;</td>
    {foreach $rModel as $colName => $column}
    {$i                  	= $column@iteration}
    {$type               	= $column.type}
    {$subtype            	= $column.subtype}
	{$isSorted 				= ($sortBy === $fieldName)}
	{$isDefaultNamefield 	= ($data._resources[$resourceName].defaultNameField === $colName)}
	{$displayed 			= false}
	{if ($lightVersion && $column.list == 3) || (!$lightVersion && $column.list >= 1)}{$displayed = true}{/if}
	{if !empty($data.options.displayCols)}
	{$column.list 			= (isset($data.options.displayCols[$colName]))?3:0}
	{$displayed 			= true}
	{/if}
	{if $type === 'onetoone' || $column.fk}
	{$column.fk = false}
	<td class="col {$colName}Col typeInt fk{if $isSorted} activeSort{/if}{* if !$displayed} hidden{/if *}" id="{$colName}FilterCol" scope="col" headers="{$colName}Col"  data-importance="{$column.list|default:0}">{include file='common/blocks/admin/resource/list/colFilter.tpl' type='int'}</td>
	<td class="col {$column.relGetAs}Col typeVarchar fk{if $isSorted} activeSort{/if}{* if !$displayed} hidden{/if *}" id="{$column.relGetAs}FilterCol" scope="col" headers="{$column.relGetAs}Col"  data-importance="{$column.list|default:0}">{include file='common/blocks/admin/resource/list/colFilter.tpl' type='varchar' colName=$column.relGetAs}</td>
	{$column.fk = true}
	{else}
    <td class="col {$colName}Col type{$type|ucfirst}{if $subtype} subtype{$subtype|ucfirst}{/if}{if $isDefaultNamefield} defaultNameField{/if}{if $isSorted} activeSort{/if} {if $column.relResource} typeRel{/if}{* if !$displayed} hidden{/if *}" id="{$colName}FilterCol" scope="col" headers="{$colName}Col" data-importance="{$column.list|default:0}">{include file='common/blocks/admin/resource/list/colFilter.tpl'}</td>
    {/if}
    {/foreach}
    <td class="col goToCol last lastCol"></td>
</tr>