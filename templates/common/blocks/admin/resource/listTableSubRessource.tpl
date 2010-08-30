<form id="frmAdmin{$ressourceName|capitalize}" action="{$smarty.const._URL_ADMIN}{$ressourceName}" class="commonForm" method="post" enctype="multipart/form-data">
{include file='common/blocks/admin/handleMulti.tpl'}
<table class="commonTable adminTable" id="{$ressourceName}Table">
	<caption>{$ressourceName}</caption>
	<thead class="titleBlock sortables">
		<tr>
      		{if !$data.sortBy}
				{assign var=sortBy value='id'}
				{assign var=order value='asc'}
			{/if}
			<th class="col firstCol" colspan="2">
				{* TODO: make this work without javascript (using page refresh) *}
				{*  
				<a href="#" title="{t}Expand or Collapse related sub-ressources{/t}">
					{t}toggle subressource{/t}
				</a>
				*}
				<span>&nbsp;</span>
			</th>
			<th class="col actionsCol">
	          	<span class="title">{t}Actions{/t}</span>
			</th>
			{assign var='displayedFieldsNb' value=0}
			{foreach name='tableFields' from=$data.dataModel[$ressourceName] key='fieldName' item='field'}
			{*if empty($smarty.get.simpleAdminList) || ($field.visibility == 'simpleAdminList' && $smarty.get.simpleAdminList != 1) *}
			{if $field.visibility == 'simpleAdminList' || $field.visibility == 'mediumAdminList' || $field.visibility == 'largeAdminList'}
			<th class="col {$fieldName}Col">
	          	<span class="title">
	          		{$fieldName|replace:'_':' '}
					{*if $field.relGetAs}==> {$field.relGetAs}{/if*}
					{*if $field.relRessource}<span class="subTitle">({$field.relRessource})</span>{/if*}
				</span>
	          	<span class="colFilters inlineBlock">
					{if $sortBy === $fieldName && $order === 'asc'}
						{assign var='currentAsc' value='current'}
						{assign var='currentDesc' value=''}
					{elseif $sortBy === $fieldName && $order === 'desc'}
						{assign var='currentAsc' value=''}
						{assign var='currentDesc' value='current'}
					{else}
						{assign var='currentAsc' value=''}
						{assign var='currentDesc' value=''}
					{/if}
					<a class="sort asc {$currentAsc}" href="{$adminResBaseURI}?sortBy={$fieldName}&amp;orderBy=asc">
						<span class="key">&nbsp;</span>
						<span class="value">{t}Sort by{/t} {$fieldName} {t}ascending{/t}</span>
					</a>
                	<a class="sort desc {$currentDesc}" href="{$adminResBaseURI}?sortBy={$fieldName}&amp;orderBy=desc">
						<span class="key">&nbsp;</span>
                		<span class="value">{t}Sort by{/t} {$fieldName} {t}descending{/t}</span>
					</a>
			  	</span>
			</th>
			{math assign='displayedFieldsNb' equation="x+1" x=$displayedFieldsNb}
			{/if}
			{/foreach}
		</tr>
	</thead>
	<tbody>
		<tr class="{cycle values='odd'} addRow">
			<td colspan="{$displayedFieldsNb+5}">
				{if !isset($crudability) || strpos($crudability, 'C') > -1}
					{assign var='disabled' value=false}
				{else}
					{assign var='disabled' value=true}
				{/if}
	          	<a class="adminLink addLink {if $disabled}disabled{/if}" href="{$adminResBaseURI}?method=create">
	          		<span class="key">&nbsp;</span>
	          		<span class="value">{t}Add{/t}</span>
	          	</a>
			</td>
		</tr>
		{foreach name=$ressourceName from=$data[$ressourceName] item='ressource'}
		<tr class="{cycle values='even,odd'}">
			<td class="col firstcol colToggleSubRessources">
				<a id="subRessourcesToggler{$ressource.id}" class="subRessourcesToggler" href="{$adminResBaseURI}?displaySubRessources={$ressource.id}" title="{t}Expand or Collapse related sub-ressources{/t}">
					<span class="key">&nbsp;</span>
					<span class="value">{t}toggle subressource{/t}</span>
				</a>
			</td>
			<td class="col colSelectRessources">
				<input type="checkbox" name="ids[]" value="{$ressource.id}" {if $smarty.post.ids && in_array($ressource.id, $smarty.post.ids)}checked="checked"{/if} />
			</td>
			<td class="actionsCol">
				
				{if !isset($crudability) || strpos($crudability, 'R') > -1}
					{assign var='disabled' value=false}
				{else}
					{assign var='disabled' value=true}
				{/if}
	          	<a class="adminLink viewLink {if $disabled}disabled{/if}" href="{if !$disabled}{$adminResBaseURI}/{$ressource.id}?method=retrieve{else}#{/if}" title="{t}view the detail of this item{/t}">
					<span class="key">&nbsp;</span>
	          		<span class="value">{t}View{/t}</span>
	          	</a>
				
				{if !isset($crudability) || strpos($crudability, 'U') > -1}
					{assign var='disabled' value=false}
				{else}
					{assign var='disabled' value=true}
				{/if}
	          	<a class="adminLink editLink {if $disabled}disabled{/if}" href="{if !$disabled}{$adminResBaseURI}/{$ressource.id}?method=update{else}#{/if}" title="{t}edit this item{/t}">
					<span class="key">&nbsp;</span>
	          		<span class="value">{t}Edit{/t}</span>
	          	</a>
				
				{if !isset($crudability) || strpos($crudability, 'D') > -1}
					{assign var='disabled' value=false}
				{else}
					{assign var='disabled' value=true}
				{/if}
	          	<a class="adminLink deleteLink {if $disabled}disabled{/if}" href="{if !$disabled}{$adminResBaseURI}/{$ressource.id}?method=delete{else}#{/if}" title="{t}delete this item{/t}">
					<span class="key">&nbsp;</span>
	          		<span class="value">{t}Delete{/t}</span>
	          	</a>
				
				{*include file="pages/admin/$ressourceName/specificActions.tpl"*}
				
			</td>
			{foreach name='tableFields' from=$data.dataModel[$ressourceName] key='fieldName' item='field'}
			{*if empty($smarty.get.simpleAdminList) || ($field.visibility == 'simpleAdminList' && $smarty.get.simpleAdminList != 1) *}
			{if $field.visibility == 'simpleAdminList' || $field.visibility == 'mediumAdminList' || $field.visibility == 'largeAdminList'}
			<td class="col {$fieldName}Col">
				<div class="value">
				{if $field.type === 'timestamp'}
				{$ressource[$fieldName]|date_format:"%Y-%m-%d %H:%M:%S"}	
				{elseif $field.type === 'bool'}
					{if $ressource[$fieldName] === true || $ressource[$fieldName] === 't' || $ressource[$fieldName] == 1}
						{t}yes{/t}
					{else}
						{t}no{/t}
					{/if}
				{elseif $field.type === 'int' && $field.subtype === 'fixedValues'}
					{assign var='posValIndex' value=$ressource[$fieldName]}
					{$field.possibleValues[$posValIndex]}
				{else}
					{if $field.relRessource} 
						<a class="relRessourceLink" href="{$smarty.const._URL_ADMIN}{$field.relRessource|lower}/{$ressource[$fieldName]}?method=retrieve&amp;by={$fieldName}">
						{$ressource[$fieldName]}
						</a>
						{*
						<span class="hidden relRessource">{$field.relRessource}</span>
						{if $field.relField}<span class="hidden relRessource">{$field.relRessource}</span>{/if}
						*}
					{else}
						{$ressource[$fieldName]}
					{/if}
				{/if}
				</div>
			</td>
			{/if}
			{/foreach}			
			{*
			<td class="actionsCol displayCell lastcol">
	          	<a class="adminLink goToLink" href="{$smarty.const._URL}{$ressourceName|capitalize}/{$ressource.id}">
	          		<span>{t}Display{/t}</span>
	          	</a>
			</td>
			*}
		</tr>
		{foreachelse}
		<tr>
			<td colspan="{$displayedFieldsNb+5}">
				{t}There's currently no item in the database{/t}
			</td>
		</tr>
		{/foreach}
	</tbody>
</table>
{include file='common/blocks/admin/common/handleMulti.tpl'}
</form>