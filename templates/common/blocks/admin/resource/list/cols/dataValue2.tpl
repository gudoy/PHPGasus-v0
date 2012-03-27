{if is_array($colVal)}
{count($colVal)} {$colName}
{elseif in_array($rType, array('timestamp','datetime','date','time'))}
{include file='common/blocks/humanTime.tpl' class='datetimeField' value=$colName}
{else}
{$colVal}{/if}