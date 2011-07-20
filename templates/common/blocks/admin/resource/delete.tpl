<header class="header titleBlock">
	<h2 class="title">
		<a href="{$smarty.const._URL_ADMIN}{$resourceName}" class="{$resourceName}" id="resourceName" data-singular="{$data.meta.singular}">
			{$resourceName} - {$data[$resourceName].id}
		</a>
	</h2>
	{include file='common/blocks/admin/pagination/index.tpl' adminView='delete'}
</header>

<div class="contentBlock">

	{block name='adminDeleteContent'}
	<div class="block adminBlock adminDeleteBlock" id="admin{$resourceName|capitalize}DeleteBlock">
		
		{if $data.warnings}
			{include file='common/config/warnings.tpl'}	
		{/if}
			
		{if $data.success}
		<div class="notificationsBlock">
			<p class="notification success">
				{t}The resource has been successfully deleted!{/t}
			</p>
			<div class="buttonsLine">
				{include file='common/blocks/actionBtn.tpl' id='continueBtn' href=$smarty.const._URL_ADMIN|cat:$resourceName label='continue'|gettext}	
			</div>
		</div>
		{else}
		<div class="notificationsBlock confirmationBlock" id="confirmationBlock">
			<p class="notification warning">
				{t}Are you sure you want to delete the following resource(s){/t}{t}:{/t} {$data.resourceId}
			</p>
			<div class="buttonsLine">
				{include file='common/blocks/actionBtn.tpl' id='cancelBtn' class='cancelBtn' href=$smarty.const._URL_ADMIN|cat:$resourceName label='Cancel'|gettext}
				<span class="sep or">{t}or{/t}</span>
				{include file='common/blocks/actionBtn.tpl' id='confirmBtn' href=$smarty.const._URL_ADMIN|cat:$resourceName|cat:'/'|cat:$resourceId|cat:'?method=delete&amp;confirm=1' label='delete'|gettext}
			</div>
		</div>
		{/if}
		
	</div>
	{/block}

</div>