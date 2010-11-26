{$filterName="filterCondition[{$colName}]"}
{$filterId="{$colName}FilterCondition"}
<div class="colFilterBlock">
{if ($column.fk || $type === 'fk') && $data[$column.relResource]}
    {$defNameCol=$resources.defaultNameField}
    <select name="{$filterName}" id="{$filterId}">
        <option></option>
        {foreach $data[$column.relResource] as $item}
        {$dispVal=$item[$defNameCol]|default:$item.name|default:$item.name|default:$item[0]}
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
{elseif $subtype === 'email' || $type === 'email'}
    <input class="sized" type="{if $html5}email{else}text{/if}" name="{$filterName}" id="{$filterId}" />
{elseif $subtype === 'color' || $type === 'color'}
    <input class="sized" type="{if $html5}color{else}text{/if}" name="{$filterName}" id="{$filterId}" />
{elseif in_array($subtype, array('url','file','image')) || in_array($type, array('url','file','image'))}
    <input class="sized" type="{if $html5}url{else}text{/if}" name="{$filterName}" id="{$filterId}" />
{elseif $type === 'int' && !$column.fk}
    <input class="sized number" type="{if $html5}number{else}text{/if}" name="{$filterName}" id="{$filterId}" step="1" value="0" />
{else}
    <input class="sized" type="{if $html5}search{else}text{/if}" name="{$filterName}" id="{$filterId}" />
{/if}
</div>