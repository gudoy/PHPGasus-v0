<header class="header titleBlock">
	<h2 class="title">
        <span class="{$resourceName}" id="resourceName" data-singular="{$data.meta.singular}">
			{$data.meta.displayName} - {$resourceId}
		</span>
	</h2>
	{include file='common/blocks/admin/pagination/index.tpl' adminView='delete'}
</header>

<div class="box block adminBlock adminDeleteBlock" id="admin{$resourceName|capitalize}DeleteBlock" {*title="{$data.meta.displayName} - {$resourceId}"*}>
	
	{if $data.warnings}
		{include file='common/config/warnings.tpl'}	
	{/if}
		
	{if $data.success}
	<div class="notificationsBlock">
		<p class="notification success">
			{t}The resource has been successfully deleted!{/t}
		</p>
		<div class="buttonsLine">
			{include file='common/blocks/actionBtn.tpl' btnId='continueBtn' btnHref=$smarty.const._URL_ADMIN|cat:$resourceName btnLabel='Continue'|gettext}	
		</div>
	</div>
	{else}
		<div class="confirmationBlock notificationsBlock" id="confirmationBlock">
			<p class="notification warning">
				{t}Are you sure you want to delete the following resource(s){/t}{t}:{/t} {$data.resourceId}
			</p>
			<div class="buttonsLine">
				{include file='common/blocks/actionBtn.tpl' btnId='cancelBtn' btnClasses='cancelBtn' btnHref=$smarty.const._URL_ADMIN|cat:$resourceName btnLabel='Cancel'|gettext}
				<span class="sep or">{t}or{/t}</span>
				{include file='common/blocks/actionBtn.tpl' btnId='confirmBtn' btnHref=$smarty.const._URL_ADMIN|cat:$resourceName|cat:'/'|cat:$resourceId|cat:'?method=delete&amp;confirm=1' btnLabel='Delete'|gettext}
			</div>
		</div>
	{/if}
	
</div>