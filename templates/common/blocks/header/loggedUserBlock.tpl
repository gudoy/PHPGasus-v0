{nocache}
{$loggedUser=$data.current.user}
{$userScreenName=$loggedUser.email}
{if $loggedUser.firstname && $loggedUser.lastname}
{$userScreenName=$loggedUser.firstname|cat:' '|cat:$loggedUser.lastname}
{$editUserLink="{$smarty.const._URL_ADMIN}users/{$loogedUser.id}?method=edit"}
{/if}
{if $loggedUser}
<{if $html5}section{else}div{/if} class="section block loggedUserBlock" id="loggedUserBlock">
	<{if $html5}figure{else}div{/if} class="figure picsBlock hcard">
		<a class="fn n" href="{$editUserLink}">
			<img class="photo" src="{$loggedUser.profile_pics_url|default:{$smarty.const._URL_STYLESHEETS|cat:'images/icons/userProfileDefault.png'}}" alt="{t}Avatar{/t}: {$userScreenName}" />
		</a>
	</{if $html5}figure{else}div{/if}>
	<div class="header dataBlock">
		<a class="fn n" href="{$editUserLink}">
			<span class="value">{$userScreenName}</span>
		</a>
		<a class="actionBtn logoutLink" href="{$smarty.const._URL_LOGOUT}">
			<span class="label value">{t}logout{/t}</span>
		</a>
	</div>
</{if $html5}section{else}div{/if}>
{/if}
{nocache}