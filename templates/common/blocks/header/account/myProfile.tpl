{strip}

{$user 					= $data.current.user}
{$user.profile_url 		= "{$smarty.const._URL_ADMIN}users/{$user.id}"}
{$user.edit_profile_url = "{$user.profile_url}?method=update"}
{$user.screen_name 		= $user.email}
{if $user.firstname && $user.lastname}{$user.screen_name="{$user.firstname} {$user.lastname}"}{/if}

{/strip}
{if $user}
<div id="myAccountNavBlock" class="navBlock accountBlock myAccountNavBlock hcard">
    <div class="header titleBlock item-lv1"><a id="goToMyAccountDetailsLink" href="#myAccountDetailsBlock"><h2 class="title"><span class="value">{t}account{/t}</span></h2></a></div>
    <div id="myAccountDetails" class="details">
        <figure class="figure picsBlock">
            <a rel="me" href="{$user.profile_url}">
                <img class="photo" src="{$user.profile_pics_url|default:"{$smarty.const._URL_STYLESHEETS}images/icons/userProfileDefault.png"}" alt="{t}Avatar{/t}: {$user.screen_name}" />
            </a>
            <figcaption class="figcaption">
                {if $user.first_name || $user.lastname}
                <span class="login fn n">{if $user.firstname}<span class="firstname">{$user.firstname}</span>{/if} {if $user.lastname}<span class="lastname">{$user.lastname}</span>{/if}</span>
                {/if}
                {if $user.email}
                <a href="#"><span class="email">{$user.email}</span></a>
                {/if}
            </figcaption>
        </figure>
        <nav class="nav actions actionsBlock">
        	{block name='loggedUserProfileLink'}
            <a class="action edit password" href="{$smarty.const._URL_ACCOUNT_PASSWORD_CHANGE}">{t}change password{/t}</a>
            {/block}
            <a rel="noindex" class="action" href="{$smarty.const._URL_LOGOUT}">{t}logout{/t}</a>
        </nav>
    </div>  
</div>
{/if}