{$resources		= $data._resources}
{$logs 			= $data.adminlogs}
{$today = $smarty.now|date_format:'%B %d'}
{$yesterday = {$smarty.now-3600*24}|date_format:'%B %d'}
<div class="block adminBlock activityBlock logsBlock adminLogsBlock" id="adminLogsBlock">
	<h3 class="title titleBlock">
		<span class="value">{t}latest actions{/t}</span>
	</h3>
	<div class="content">
		{if $logs}
		<ol class="logs items adminLogs" id="adminLogs">
			{$curDay = ''}
			{foreach $logs as $log}
			{if in_array($log.action[-1], array('k','t','d'))}{$conj = 'ed'}{else}{$conj = 'd'}
			{/if}
			{$logDay = $log.update_date|date_format:'%B %d'}
			{if $logDay !== $curDay}
			{if !empty($curDay)}
			</li>
			{/if}
			<li class="group">
				<h4>
				<time class="title date">
					<span class="day">{if $logDay === $today}{t}today{/t}{elseif $logDay === $yesterday}{t}yesterday{/t}{else}{$logDay}{/if}</span>
				</time>
				</h4>
				{$curDay = $logDay}
			{/if}
			<article class="item log {$log.action}">
				<span class="resource">{$data._resources[$log.resource_name].singular|default:$log.resource_name}</span> 
				<span class="logAction">{$log.action}{$conj}</span> 
				<a class="resourceLink" href="{$smarty.const._URL_ADMIN}{$log.resource_name}/{$log.resource_id}">{$log.resource_title|default:$log.resource_id}</a> 
				<span class="by">{t}by{/t}</span> 
				<a class="author" href="{$smarty.const._URL_ADMIN}users/{$log.user_id}"><span class="value">{{$log.user_email|regex_replace:"/@.*$/":""}|default:$log.user_id}</span></a>
				<span class="time">
					<span class="on">{t}on{/t}</span> 
					<time>{$log.update_date|date_format:'%H:%M'}</time>
				</span>
			</article>
			{if $log@last}
			</li>
			{/if}
			{/foreach}
		</ol>
		{else}
		<p class="nodata">
			{t}There's nothing here for the moment{/t}
		</p>
		{/if}
	</div>
</div>
