{$sessions 			= $data.activeUsers}
<div class="block adminBlock activityBlock usersBlock adminActiveUsersBlock" id="adminActiveUsersBlock">
	<h3 class="title titleBlock"><span class="value">{t}Active users{/t}</span></h3>
	<div class="content">
		<ol class="logs items activeUsers" id="activeUsers">
			{foreach $sessions as $session}
			{strip}
			{$days = floor(($smarty.now - $session.expiration_time) / (24*3600))}
			{if $smarty.now < $session.expiration_time}
			{$status='active'}
			{else}
				{if $smarty.server.REQUEST_TIME - $session.update_date < 3200}
				{$status='inactive short'}
				{else}
				{$status='inactive long'}
				{/if}
			{/if}			
			{/strip}
			<li class="item user {$status}">
				<article class="article vcard"> 
					<a class="fn n{if $session.user_mail} email{/if}" href="{$smarty.const._URL_ADMIN}users/{$session.user_id}"><span class="value">{$session.user_email|default:$session.user_id}</span></a>
					{if $status === 'active'}
					<span class="status connected">{t}connected{/t}</span>
					{else}
					<span class="status {$status}">{if $days == 0}{t}today{/t}{elseif $days == 1}{t}yesterday{/t}{else}{t days=$days}%1 days ago{/t}{/if}</span>
					{/if}
				</article> 
			</li>
			{/foreach}
		</ol>
	</div>
</div>
