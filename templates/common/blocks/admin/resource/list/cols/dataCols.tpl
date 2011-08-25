{foreach $rModel as $fieldName => $field}
{$isSorted 				= ($sortBy === $fieldName)}
{$isDefaultNamefield 	= ($data.meta.defaultNameField === $fieldName)}
{$value                 = $resource[$fieldName]}
{if ($lightVersion && $field.list == 3) || (!$lightVersion && $field.list >= 1)}
{if $smarty.const._APP_ENABLE_SPLITED_ONE2ONE_COLS && ($field.type === 'onetoone' || $field.fk)}
{$relResource 	= $field.relResource}
<td id="{$fieldName}Col{$resource.id}" class="col {$fieldName}Col typeInt fk{if $isSorted} activeSort{/if}{if !$field.list} hidden{/if}" headers="row{$resource.id} {$fieldName}Col">
	<div class="value dataValue" id="{$fieldName}{$resource.id}" data-exactValue="{$value}">
		<a class="relResourceLink" href="{$smarty.const._URL_ADMIN}{$relResource}/{$value}">{$value}</a>
	</div>
</td>
<td id="{$field.relGetAs}Col{$resource.id}" class="col {$field.relGetAs}Col typeVarchar fk{if $isSorted} activeSort{/if}{if !$field.list} hidden{/if}" headers="row{$resource.id} {$field.relGetAs}Col">
	<div class="value dataValue" id="{$field.relGetAs}{$resource.id}" data-exactValue="{$resource[$field.relGetAs]}">
		<a class="relResourceLink" href="{$smarty.const._URL_ADMIN}{$relResource}/{$value}">{$resource[$field.relGetAs]}</a>
	</div>
</td>
{/if}
{if !$smarty.const._APP_ENABLE_SPLITED_ONE2ONE_COLS || ($smarty.const._APP_ENABLE_SPLITED_ONE2ONE_COLS && $field.type !== 'onetoone' && !$field.fk)}
<td id="{$fieldName}Col{$resource.id}" class="col dataCol {$fieldName}Col type{$field.type|ucfirst}{if $field.subtype} subtype{$field.subtype|ucfirst}{/if}{if $isDefaultNamefield} defaultNameField{/if}{if $isSorted} activeSort{/if}{if $field.relResource} typeRel{/if}{if !$field.list} hidden{/if}" headers="row{$resource.id} {$fieldName}Col">
	<div class="value dataValue" id="{$fieldName}{$resource.id}" {if $field.type === 'timestamp'}title="{$value|date_format:"%Y-%m-%d %H:%M:%S"}"{/if} data-exactValue="{if $value.type === 'set'}{join(',',$value)|default:''}{else}{$value}{/if}">{include file='common/blocks/admin/resource/list/cols/dataValue.tpl'}</div>
</td>
{/if}
{/if}
{/foreach}