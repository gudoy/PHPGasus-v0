{block name='adminUsersStatsBlock'}
<div class="box adminBlock statsBlock adminStatsBlock adminUsersStatsBlock" id="adminUsersStatsBlock">
	
	<h2>{t}Users stats{/t}</h2>

	{$connectedUsers=$data.usersStats.connected}
	<dl>
		<dt>
			<span class="key stat">{t}Connected users number{/t}</span>
		</dt>
		<dd>
			<span class="value">{count($connectedUsers)}</span>
		</dd>
		{if $connectedUsers >= 1}
		<dt>
			<span class="key stat">{t}Connected users{/t}</span>
		</dt>
		<dd>
			{foreach $connectedUsers as $session}
			<ul class="value">
				<li>{strip}
					{$user=$data.connectedUsers[$session.users_id]}
					
					{if $user.first_name && $user.last_name}
						{$user.first_name|ucfirst} {$user.last_name|ucfirst}
					{else}
						{$user.email}
					{/if}
					
				{/strip}</li>
			</ul>
			{/foreach}
			<a class="more detail" id="connectedUsersLink" href="#">[{t}detail{/t}]</a>
			<div id="connectedUsersDetailBlock" class="hidden">
				<table class="commonTable adminTable">
					<thead>
						<tr>
							<th class="firstCol">{t}user{/t}</th>
							<th>{t}ip{/t}</th>
							<th class="lastCol">{t}last url{/t}</th>
						</tr>
					</thead>
					<tbody>
						{foreach $connectedUsers as $session}
						<tr>
							<td class="firstCol">{$session.user_email}</td>
							<td>{$session.ip}</td>
							<td class="lastCol">{$session.last_url}</td>
						</tr>
						{/foreach}
					</tbody>
				</table>
			</div>
		</dd>
		{/if}
	</dl>
	
</div>
{/block}