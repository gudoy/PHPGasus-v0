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