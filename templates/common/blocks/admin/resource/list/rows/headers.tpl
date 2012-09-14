{strip}
{$queryParams 			= array_merge($data.current.urlParams, ['sortBy' => null, 'orderBy' => null])}
{$orderBy 				= "{if $data.current.urlParams.orderBy === 'asc'}desc{else}asc{/if}"}
{$newPageURL 			= {$curURL|regex_replace:'/(.*)\?(.*)$/U':'$1'}|cat:'?'|cat:{http_build_query($queryParams)}}
<tr>
	<th class="col firstCol colSelectResources" id="toggleAllCel"><input type="checkbox" id="toggleAll" name="toggleAll" /></th>
	<th class="col actionsCol"><span class="title">{t}Actions{/t}</span></th>
	{foreach $rModel as $fieldName => $field}
	{$isSorted 				= ($data.current.urlParams.sortBy === $fieldName)}
	{$isDefaultNamefield 	= ($data._resources[$resourceName].defaultNameField === $fieldName)}
	{$displayed 			= ($lowCapDevice)?false:true}
	{if !empty($data.options.displayCols)}
	{$field.list 			= (isset($data.options.displayCols[$fieldName]))?3:0}
	{$displayed 			= true}
	{/if}
	{if $displayed}
	{if $field.type === 'onetoone' || $field.fk}
	<th class="col {$fieldName}Col typeInt fk{if $isDefaultNamefield} defaultNameField{/if}{if $isSorted} activeSort{/if}{* if !$displayed} hidden{/if *}" id="{$fieldName}Col" scope="col" data-importance="{$field.list|default:0}">
		<a class="title {if $isSorted}sort {$orderBy}{/if}" href="{$newPageURL}&amp;sortBy={$fieldName}&amp;orderBy={$orderBy}" title="{t}Sort by{/t}{t}:{/t} {$fieldName} {$orderBy}cending">{$data._resources[$field.relResource].singular|default:$field.relResource} {$field.relField}</a>
		{include file='common/blocks/actionBtn.tpl' class='action filter' id="{$fieldName}ToggleFilter" label="{t}filter{/t}" href="#{$resourceName}FiltersRow"}
	</th>
	<th class="col {$field.relGetAs}Col typeVarchar fk{if $isSorted}{if $isDefaultNamefield} defaultNameField{/if}  activeSort{/if}{* if !$displayed} hidden{/if *}" id="{$field.relGetAs}Col" scope="col" data-importance="{$field.list|default:0}">
		<a class="title {if $isSorted}sort {$orderBy}{/if}" href="{$newPageURL}&amp;sortBy={$field.relGetAs}&amp;orderBy={$orderBy}" title="{t}Sort by{/t}{t}:{/t} {$field.relGetAs} {$orderBy}cending">{$field.displayName|default:$data._resources[$field.relResource].singular|default:$field.relResource} {$field.relGetFields}</a>
		{include file='common/blocks/actionBtn.tpl' class='action filter' id="{$fieldName}ToggleFilter" label="{t}filter{/t}" href="#{$resourceName}FiltersRow"}
	</th>
	{else}
	<th class="col {$fieldName}Col type{$field.type|ucfirst}{if $isDefaultNamefield} defaultNameField{/if}{if $isSorted} activeSort{/if}{* if !$displayed} hidden{/if *}" id="{$fieldName}Col" scope="col" data-importance="{$field.list|default:0}">
		<a class="title {if $isSorted}sort {$orderBy}{/if}" href="{$newPageURL}&amp;sortBy={$fieldName}&amp;orderBy={$orderBy}" title="{t}Sort by{/t}{t}:{/t} {$fieldName} {$orderBy}cending">{if $field.fk}{$field.displayName|default:$data._resources[$field.relResource].singular}{else}{$field.displayName|default:$fieldName|replace:'_':' '|truncate:'20':'...':true}{/if}{if $field.comment}<span class="comment infos"><span class="detail value">{$field.comment}</span></span>{/if}</a>
		{include file='common/blocks/actionBtn.tpl' class='action filter' id="{$fieldName}ToggleFilter" label="{t}filter{/t}" href="#{$resourceName}FiltersRow"}
	</th>
	{/if}
	{/if}
	{/foreach}
	<th class="col colsCol goToCol last lastCol">
		<div class="colsManagerBlock" id="colsManagerBlock">
			<a id="colsManagerLink" href="#"><span class="label">{t}show/hide columns{/t}</span></a>
			<ul class="colsBlock" id="colsBlock">
			{foreach array_keys($rModel) as $column}
				{$colProps = $rModel[$column]}
				<li data-importance="{$rModel[$column].list|default:0}">
				{if $colProps.type === 'onetoone' || $colProps.fk}
					<input type="checkbox" id="{$column}ColDisplay" {* if $displayed}checked="checked"{/if *} />
					<label class="span" for="{$column}ColDisplay">{$column|replace:'_':' '}</label>
				</li>
				<li data-importance="{$colProps.list|default:0}">
					<input type="checkbox" id="{$colProps.relGetAs}ColDisplay" {* if $displayed}checked="checked"{/if *} />
					<label class="span" for="{$colProps.relGetAs}ColDisplay">{$colProps.relGetAs|replace:'_':' '}</label>
				{else}
					<input type="checkbox" id="{$column}ColDisplay" {* if $displayed}checked="checked"{/if *} />
					<label class="span" for="{$column}ColDisplay">{$column|replace:'_':' '}</label>
				{/if}
				</li>
			{/foreach}
	       </ul>
	   </div>
	</th>
</tr>
{include file='common/blocks/admin/resource/list/rows/filters.tpl'}
{/strip}