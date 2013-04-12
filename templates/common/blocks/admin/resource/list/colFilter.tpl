<span class="filterCtnr">
{$filterName 	= "filterCondition[{$colName}]"}
{$filterId 		= "{$colName}FilterCondition"}
{if ($type === 'onetonone' || $column.fk) && $data[$column.relResource]}
    {$defNameCol=$resources.defaultNameField}
    {$relNameField=$column.relGetFields|default:$data._resources[$column.relResource]['defaultNameField']}
    <select name="{$filterName}" id="{$filterId}">
        <option></option>
        {foreach $data[$column.relResource] as $item}
        {$dispVal=$item[$relNameField]|default:$item[$defNameCol]|default:$item.name|default:$item[0]}
        <option value="{$item.id|default:$dispVal}">{$dispVal}</option>
        {/foreach}
    </select>                         
{elseif $type === 'enum'}
    <select name="{$filterName}" id="{$filterId}">
        <option></option>
       {foreach $column.possibleValues as $value}
       <option value="{$value}">{$value}</option>
       {/foreach}
    </select>
{elseif $type === 'set'}
    <select name="{$filterName}" id="{$filterId}">
        <option></option>
       {foreach $column.possibleValues as $value}
       <option value="{$value}">{$value}</option>
       {/foreach}
    </select>
{elseif $type === 'bool'}
    <select name="{$filterName}" id="{$filterId}">
        <option></option>
        <option value="0">{t}no{/t}</option>
        <option value="1">{t}yes{/t}</option>
    </select>
{*
{elseif $type === 'email' || $subtype === 'email'}
    <input class="filter" size="{strlen($colName)}" type="email" name="{$filterName}" id="{$filterId}" autocapitalize="off" />
*}
{elseif $type === 'color' || $subtype === 'color'}
    <input class="filter" type="color" name="{$filterName}" id="{$filterId}" />
{elseif in_array($subtype, array('url','file','image')) || in_array($type, array('url','file','image'))}
    <input class="filter" type="url" name="{$filterName}" id="{$filterId}" autocapitalize="off" />
{elseif $type === 'int' && !$column.fk}
    <input class="number filter" size="2" type="number" name="{$filterName}" id="{$filterId}" step="1" />
{else}
    <input class="filter" size="{strlen($colName)}" type="search" name="{$filterName}" id="{$filterId}" autocapitalize="off" placeholder="{$colName}" />
{/if}
</span>