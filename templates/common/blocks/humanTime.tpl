{strip}{if $value}
{*
	PARAMS: value, [as], [format], [withseconds], [class]
	- value 
	- as: 'datetime'|'date'|'date' (default=null)],
	- format: TODO  
	- withseconds: true|false (default=null)
	- class: (default ='')
*}

{if !$CURRRENT_DATETIME}
	{$curTimestamp = $data.current.timestamp|default:$smarty.server.REQUEST_TIME|default:$smarty.now}
	{$CURRRENT_DATETIME = [
		'year' 		=> {$curTimestamp|date_format:'%Y'},
		'month' 	=> {$curTimestamp|date_format:'%m'},
		'day' 		=> {$curTimestamp|date_format:'%d'}, 
		'hour' 		=> {$curTimestamp|date_format:'%H'}, 
		'minutes' 	=> {$curTimestamp|date_format:'%M'}, 
		'seconds' 	=> {$curTimestamp|date_format:'%S'}
	] scope=global}
{/if}
{$year 	= $value|date_format:'%Y'}
{$month = $value|date_format:'%m'}
{$day 	= $value|date_format:'%d'}
<time class="{$class}" datetime="{$value|date_format:'%Y-%m-%dT%H:%M:%SZ'}">
{if $as === 'datetime'}
	{$value|date_format:'%d/%m/%y %H:%M:%S'}
{elseif $as === 'date'}
	{$value|date_format:'%d/%m/%y'}
{elseif $as === 'time'}
	{$value|date_format:'%H:%M:%S'}
{elseif $year === $CURRRENT_DATETIME.year && $month === $CURRRENT_DATETIME.month}
	{if $day == $CURRRENT_DATETIME.day}
		{$value|date_format:'%H:%M'}
	{elseif $day == $CURRRENT_DATETIME.day-1}
		{t}yesterday{/t}
	{elseif ($CURRRENT_DATETIME.day - $day) < 7}
		{$value|date_format:'%A'}
	{else}
		{$value|date_format:'%d/%m/%y'}
	{/if}
{else}
	{$value|date_format:'%d/%m/%y'}
{/if}
</time>{/if}{/strip}