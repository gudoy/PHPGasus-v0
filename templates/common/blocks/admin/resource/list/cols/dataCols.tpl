{foreach $rModel as $fieldName => $field}
{$isSorted 				= ($sortBy === $fieldName)}
{$isDefaultNamefield 	= ($data._resources[$resourceName].defaultNameField === $fieldName)}
{$value                 = $resource[$fieldName]}
{$displayed 			= false}
{if ($lightVersion && $field.list == 3) || (!$lightVersion && $field.list >= 1)}{$displayed = true}{/if}
{if $smarty.const._APP_ENABLE_SPLITED_ONE2ONE_COLS && ($field.type === 'onetoone' || $field.fk)}
{$relResource 	= $field.relResource}
<td id="{$fieldName}Col{$resource.id}" class="col {$fieldName}Col typeInt fk{if $isDefaultNamefield} defaultNameField{/if}{if $isSorted} activeSort{/if}{* if !$displayed} hidden{/if *}" headers="row{$resource.id} {$fieldName}Col" data-importance="{$field.list|default:0}">
	<div class="value dataValue" id="{$fieldName}{$resource.id}" data-exactValue="{$value}">
		{if $value}
		<a class="relResourceLink" href="{$smarty.const._URL_ADMIN}{$relResource}/{$value}">{$value}</a>
		{/if}
	</div>
</td>
<td id="{$field.relGetAs}Col{$resource.id}" class="col {$field.relGetAs}Col typeVarchar fk{if $isDefaultNamefield} defaultNameField{/if}{if $isSorted} activeSort{/if}{* if !$displayed} hidden{/if *}" headers="row{$resource.id} {$field.relGetAs}Col" data-importance="{$field.list|default:0}">
	<div class="value dataValue" id="{$field.relGetAs}{$resource.id}" data-exactValue="{$resource[$field.relGetAs]}">
		{if $value}
		<a class="relResourceLink" href="{$smarty.const._URL_ADMIN}{$relResource}/{$value}">{$resource[$field.relGetAs]}</a>
		{/if}
	</div>
</td>
{/if}
{if !$smarty.const._APP_ENABLE_SPLITED_ONE2ONE_COLS || ($smarty.const._APP_ENABLE_SPLITED_ONE2ONE_COLS && $field.type !== 'onetoone' && !$field.fk)}
<td id="{$fieldName}Col{$resource.id}" class="col dataCol {$fieldName}Col type{$field.type|ucfirst}{if $field.subtype} subtype{$field.subtype|ucfirst}{/if}{if $isDefaultNamefield} defaultNameField{/if}{if $isSorted} activeSort{/if}{if $field.relResource} typeRel{/if}{* if !$displayed} hidden{/if *}" headers="row{$resource.id} {$fieldName}Col" data-importance="{$field.list|default:0}">
	<div class="value dataValue" id="{$fieldName}{$resource.id}" {if $field.type === 'timestamp'}title="{$value|date_format:"%Y-%m-%d %H:%M:%S"}"{/if} data-exactValue="{if $field.type === 'set'}{join(',',$value)|default:''}{else}{$value}{/if}">{include file='common/blocks/admin/resource/list/cols/dataValue.tpl'}</div>
</td>
{/if}
{/foreach}