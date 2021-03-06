{if $data.warnings}
<div class="notificationsBlock">
	<ul class="notification warning">
		{foreach from=$data.warnings item='warning'}
		<li id="warning{$warning.id}">{$warning.message.front}</li>
		{/foreach}
	</ul>
</div>		
{/if}

{if $data.success}
<div class="notificationsBlock">
	<p class="notification success">
		{t}The resource has been successfully created!{/t}
	</p>
</div>
{/if}

{include file="common/blocks/forms/{$data.view.resourceName}/create.tpl" mode='api'}