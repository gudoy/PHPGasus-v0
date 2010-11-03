<div class="box block adminBlock adminDeleteBlock" id="admin{$resourceName|capitalize}DeleteBlock" title="{$data.meta.displayName} - {$resourceId}">
	<h2>
		<span class="{$resourceName}" id="resourceName">
			{$data.meta.displayName} - {$resourceId}
		</span>
		<span class="{$data.meta.singular}" id="resourceSingular">&nbsp;</span>
	</h2>
	
	{*if $data.errors}
		{include file='config/errors.tpl'}
	{/if*}
	
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