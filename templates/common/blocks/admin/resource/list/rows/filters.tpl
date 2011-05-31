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
	{if $smarty.const._APP_ENABLE_SPLITED_ONE2ONE_COLS && ($type === 'onetoone' || $column.fk)}
	{$column.fk = false}
	<td class="col {$colName}Col typeInt fk{if $isSorted} activeSort{/if}{if !$column.list} hidden{/if}" id="{$colName}FilterCol" scope="col" headers="{$colName}Col">{include file='common/blocks/admin/resource/list/colFilter.tpl' type='int'}</td>
	<td class="col {$column.relGetAs}Col typeVarchar fk{if $isSorted} activeSort{/if}{if !$column.list} hidden{/if}" id="{$column.relGetAs}FilterCol" scope="col" headers="{$column.relGetAs}Col">{include file='common/blocks/admin/resource/list/colFilter.tpl' type='varchar' colName=$column.relGetAs}</td>
	{$column.fk = true}
	{/if}
	{if !$smarty.const._APP_ENABLE_SPLITED_ONE2ONE_COLS || ($smarty.const._APP_ENABLE_SPLITED_ONE2ONE_COLS && $type !== 'onetoone' && !$column.fk)}
    <td class="col {$colName}Col type{$type|ucfirst}{if $subtype} subtype{$subtype|ucfirst}{/if}{if $isDefaultNamefield} defaultNameField{/if}{if $isSorted} activeSort{/if} {if $column.relResource} typeRel{/if}{if !$column.list} hidden{/if}" id="{$colName}FilterCol" scope="col" headers="{$colName}Col">{include file='common/blocks/admin/resource/list/colFilter.tpl'}</td>
    {/if}
    {/if}
    {/foreach}
    <td class="col goToCol last">&nbsp;</td>
    {*<td class="col colsHandlerCol last">&nbsp;</td>*}
</tr>