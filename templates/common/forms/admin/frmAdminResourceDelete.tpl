{$mode='delete'}
<form action="{$data.current.url}" id="frmAdminDelete{$resourceName|capitalize}" class="commonForm {$mode}Mode" method="post" enctype="multipart/form-data">

	<fieldset>
		
		<legend><span class="value">{t}Delete resource{/t}</span></legend>
		
		<p class="notification warning">
			{t}Are you sure you want to delete the following resource(s){/t}{t}:{/t} {$data.resourceId}
		</p>
	</fieldset>
	
	<fieldset class="buttonsFieldset">
		<div class="line noLabelBlock buttonsLine">
			<div class="fieldBlock">
				<input type="hidden" name="update{$resourceName|capitalize}" id="update{$resourceName|capitalize}" value="1" />
				<input type="hidden" name="method" id="method" value="delete" />
				<input type="hidden" name="csrftoken" id="csrftoken" value="{$smarty.session.csrftoken}" />
				{$parentResURI = $smarty.const._URL_ADMIN|cat:$resourceName}
				{include file='common/blocks/actionBtn.tpl' href=$parentResURI class='cancelBtn' id='cancelBtn' label="{t}cancel{/t}"}
				<span class="sep or">{t}or{/t}</span>
				{include file='common/blocks/actionBtn.tpl' mode='button' class='validateBtn' name='confirm' id='validateBtn' type='submit' value='1' label="{t}delete{/t}"}
			</div>
		</div>
	</fieldset>

</form>