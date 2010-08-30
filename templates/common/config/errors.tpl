{block name='beforeErrorsMain'}{/block}
{block name='errorsMain'}
{if count($data.errors)}
<div id="errorsBlock" class="notificationsBlock errorsBlock">
	<ul class="errors errorsList">
		{foreach $data.errors as $error}
		<li class="notification error" id="error{$error.id}">{$error.message|default:'unknown error'}</li>
		{/foreach}
	</ul>
</div>
{/if}
{/block}
{block name='afterErrorsMain'}{/block}