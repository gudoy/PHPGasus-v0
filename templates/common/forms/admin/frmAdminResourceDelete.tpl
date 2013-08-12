{$mode='delete'}
<form action="{$data.current.url}" id="frmAdminDelete{$resourceName|capitalize}" class="commonForm adminForm {$mode}Mode" method="post" enctype="multipart/form-data">

	<fieldset class="body">
		
		<legend><span class="value">{t}Delete resource{/t}</span></legend>
		
		<p class="notification warning">
			{t}Are you sure you want to delete the following resource(s){/t}{t}:{/t} {join(',', (array)$data.resourceId)}
		</p>
	</fieldset>
	
	<fieldset class="hidden">
		<input type="hidden" name="delete{$resourceName|capitalize}" id="delete{$resourceName|capitalize}" value="1" />
		<input type="hidden" name="method" id="method" value="delete" />
		<input type="hidden" name="csrftoken" id="csrftoken" value="{$smarty.session.csrftoken}" />
	</fieldset>
	
	{*
	<fieldset class="buttonsFieldset">
		<div class="line noLabelBlock buttonsLine">
			<div class="fieldBlock">
				{include file='common/blocks/actionBtn.tpl' href="{$smarty.const._URL_ADMIN}{$resourceName}" class='cancelBtn' id='cancelBtn' label="{t}cancel{/t}"}
				<span class="sep or">{t}or{/t}</span>
				{include file='common/blocks/actionBtn.tpl' mode='button' class='validateBtn' name='confirm' id='validateBtn' type='submit' value='1' label="{t}delete{/t}"}
			</div>
		</div>
	</fieldset>
	*}

	<fieldset class="actions formActions">
	{if $viewMode === 'api'}
		<div class="actions formActions">
		{include file='common/blocks/actionBtn.tpl' mode='button' class='validateBtn' id='validateBtn' type='submit' label="{t}create{/t}"}
		</div>
	{else}
		{include file='common/blocks/actionBtn.tpl' href="{$smarty.const._URL_ADMIN}{$view.resourceName}" class='cancelBtn' id='cancelBtn' label="{t}cancel{/t}"}
		<span class="sep or">{t}or{/t}</span>
		{include file='common/blocks/actionBtn.tpl' mode='button' class='validateBtn' name='confirm' id='validateBtn' type='submit' value='1' label="{t}delete{/t}"}
	{/if}
	</fieldset>

</form>