{strip}

{* Accepted params
    rows: array of data rows
    rName: resource name 
    rModel (resource model): associative array (field name => field properties, [...]) (field properties should at list contains a 'type' property)
    showAllCols: force display of all columns
    idCol: column used as the id for the current row (used to create actions URL)
    showActions: array of displayed actions buttons (default = all = create,retrieve,update,delete,duplicate
	goToResource: name of the resource toward which redirect on "go to" action (default = current) 
*}

{$options 		= $options|default:[]}
{$o 			= array_merge(['showAllCols' => false, 'addHiddenCols' => true, 'idCol' => 'id', 'showActions' => ['create','retrieve','update','delete','duplicate']],$options)}

{$curURL 		= $data.current.url}
{if strpos($curURL,'?') !== false}{$linker='&amp;'}{else}{$linker='?'}{/if}

{$rName 		= $rName|default:$data.current.resource}
{$rModel 		= $rModel|default:$data.dataModel[$rName]}
{$crudability 	= $data._resources[$resourceName].crudability|default:'CRUD'}
{$userResPerms 	= $data.current.user.auths[$resourceName]}

{/strip}
<table id="{$rName}Table" class="commonTable adminTable {$rName}Table">
    <thead>
        <tr>
            {if ($userResPerms.allow_create && in_array('create',$o.showActions)) || ($userResPerms.allow_update && in_array('update',$o.showActions)) || ($userResPerms.allow_delete && in_array('delete',$o.showActions))}
            <th id="toggleAllCel" class="col firstCol colSelectResources"><input type="checkbox" name="toggleAll" id="toggleAll" /></th>
            <th class="col actionsCol">
                <span class="title">{t}actions{/t}</span>
            </th>
            {/if}
            {foreach $rModel as $colName => $colProps}
            {$type                  = $colProps.type}
            {if $colProps.list || $o.showAllCols || $o.addHiddenCols}
            {$isDefaultNameField = ($colName === $data._resources[$rName].defaultNameField)?true:false}
			{if $colProps.type === 'int' && $colProps.fk}{$type = 'onetoone'}{/if}
            <th id="{$colName}Col" class="col {$colName}Col type{$type|ucfirst}{if $isDefaultNameField} defaultNameField{/if}{if $colName@last} lastCol{/if}{if !$o.showAllCols && !$colProps.list} hidden{/if}{if $isSorted} activeSort{/if}" scope="col">
                {$data.current.urlParams.sortBy     = null}
                {$data.current.urlParams.orderBy    = null}
                {$newPageURL 						= "{$curURLbase}?{http_build_query($data.current.urlParams)}"}
                <a class="title" title="{$colProps.displayName|default:$colName}" href="{$newPageURL|replace:'&amp':'&'|replace:'&':'&amp;'}&amp;sortBy={$colName}&amp;orderBy={if $smarty.get.orderBy === 'asc'}desc{else}asc{/if}">{$colProps.displayName|default:$colName|replace:'_':' '|truncate:'20':'...':true}</a>
            </th>
            {/if}
            {/foreach}
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
    </thead>
    <tbody>
        {foreach $rows as $row}
        {$rowNum = $row.id|default:$row@iteration}
        <tr id="row{$rowNum}" class="dataRow {cycle values='even,odd'} {if $row@first} firstRow{/if}{if $row@last} lastRow{/if}">
            {if ($userResPerms.allow_create && in_array('create',$o.showActions)) || ($userResPerms.allow_update && in_array('update',$o.showActions)) || ($userResPerms.allow_delete && in_array('delete',$o.showActions))}
            <td class="col selecRowCol firstCol colSelectResources">
                <input type="checkbox" name="ids[]" value="{$rowNum}" {if $smarty.post.ids && in_array($rowNum, $smarty.post.ids)}checked="checked"{/if} />
            </td>
            <td class="col actionsCol">{strip}
            	<span class="actions">
				{if $userResPerms.allow_update && in_array('update',$o.showActions)}
					<a class="action edit actionBtn adminLink editLink" href="{$smarty.const._URL_ADMIN}{$rName}/{$row[$o.idCol]}?method=update"><span class="value">{t}edit{/t}</span></a>
				{/if}
                
                {if $userResPerms.allow_create && $userResPerms.allow_update && in_array('create',$o.showActions) && in_array('update',$o.showActions)}	
                <a class="action duplicate actionBtn adminLink duplicateLink" href="{$smarty.const._URL_ADMIN}{$rName}/{$row[$o.idCol]}?method=duplicate"><span class="value">{t}duplicate{/t}</span></a>
                {/if}
                
                {if $userResPerms.allow_delete && in_array('delete',$o.showActions)}
                <a class="action delete actionBtn adminLink deleteLink" href="{$smarty.const._URL_ADMIN}{$rName}/{$row[$o.idCol]}?method=delete"><span class="value">{t}delete{/t}</span></a>
                {/if}
               </span>
            {/strip}</td>
            {/if}
            {foreach $rModel as $colName => $colProps}
            {$fieldName             = $colProps.dataName|default:$colName}
            {$type                  = $colProps.type}
            {$value                 = $row[$fieldName]}
            {$isDefaultNameField    = ($colName === $data._resources[$rName].defaultNameField)?true:false}
            {if $type === 'int' && $colProps.fk}{$type = 'onetoone'}{/if}
            {if $colProps.list || $o.showAllCols || $o.addHiddenCols}
            <td class="col dataCol {$colName}Col type{$type|ucfirst}{if $isDefaultNameField} defaultNameField{/if}{if $colName@last} lastCol{/if}{if !$o.showAllCols && !$colProps.list} hidden{/if}" headers="row{$rowNum} {$colName}Col">{strip}
                <div class="value dataValue" data-exactValue="{$value}">{strip}
                {if $type === 'timestamp' || $type === 'datetime'}
                    <time class="date">{$value|date_format:"%d %b %Y"}</time><span class="sep"> </span><time class="time">{$value|date_format:"%Hh%M</time>"}
                {elseif $type === 'onetoone' || $colProps.fk}
                    {$relResource       = $colProps.relResource}
                    {$relField          = $colProps.relField|default:'id'}
                    {$relResourceURL    = $colProps.relResourceURL|default:"{$smarty.const._URL_ADMIN}{$relResource}"}
                    {$relGetFields      = explode(',',{$colProps.relGetFields|default:$relField})}
                    {$relGetAs          = explode(',',{$colProps.relGetAs|default:$colProps.relGetFields})}
                    {$relNameField      = $colProps.relGetFields|default:{$data._resources[$relResource].defaultNameField}}
                    <a href="{$relResourceURL}/{$value}">{$row[$relGetAs[0]]|default:$value}</a>
                {elseif $type === 'bool' || $type === 'boolean'}
                    {$valid = in_array($value, array(1,true,'1','true','t'), true)}
                    <span class="label validity {if !$valid}in{/if}valid">{if $valid}{t}yes{/t}{else}{t}no{/t}{/if}</span>
                {else}
                    {$value|default:''}
                {/if}
                {/strip}</div>
            {strip}</td>
            {/if}
            {/foreach}
            <td class="col goToCol lastCol">
                {if in_array('retrieve',$o.showActions)}<a class="action view actionBtn adminLink viewLink" href="{$smarty.const._URL_ADMIN}{$o.goToResource|default:$rName}/{$row[$o.idCol]}"><span class="value">{t}view{/t}</span></a>{/if}
            </td>
        </tr>
        {/foreach}
    </tbody>
</table>