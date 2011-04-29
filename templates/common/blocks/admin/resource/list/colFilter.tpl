<span>
{$filterName="filterCondition[{$colName}]"}
{$filterId="{$colName}FilterCondition"}
{if ($column.fk || $type === 'fk') && $data[$column.relResource]}
    {$defNameCol=$resources.defaultNameField}
    {$relNameField=$column.relGetFields|default:$resources[$column.relResource]['defaultNameField']}
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
{elseif $type === 'bool'}
    <select name="{$filterName}" id="{$filterId}">
        <option></option>
        <option value="0">{t}no{/t}</option>
        <option value="1">{t}yes{/t}</option>
    </select>
{*
{elseif $subtype === 'email' || $type === 'email'}
    <input class="sized search" size="{strlen($colName)}" type="email" name="{$filterName}" id="{$filterId}" autocapitalize="off" />
*}
{elseif $subtype === 'color' || $type === 'color'}
    <input class="sized search" type="color" name="{$filterName}" id="{$filterId}" />
{elseif in_array($subtype, array('url','file','image')) || in_array($type, array('url','file','image'))}
    <input class="sized search" type="url" name="{$filterName}" id="{$filterId}" autocapitalize="off" />
{elseif $type === 'int' && !$column.fk}
    <input class="sized number search" size="2" type="number" name="{$filterName}" id="{$filterId}" step="1" />
{else}
    <input class="sized search" size="{strlen($colName)}" type="search" name="{$filterName}" id="{$filterId}" autocapitalize="off" />
{/if}
</span>