{$mode='update'}
<form action="{$data.current.url}" id="frmAdminUpdate{$resourceName|capitalize}" class="commonForm {$mode}Mode" method="post" enctype="multipart/form-data">
	
	<fieldset>
		<legend><span class="value">{t}Edit resource data{/t}</span></legend>
		
		{block name='resourceFieldsRows'}
		{foreach $data.dataModel[$resourceName] as $fieldName => $field}
		{include file='common/forms/admin/fieldLine/index.tpl'}
		{/foreach}
		{/block}
		
		{include file='common/forms/common/fields/legendDetail.tpl'}

	</fieldset>
	
	<fieldset class="buttonsFieldset">
		<div class="line noLabelBlock buttonsLine">
			<div class="fieldBlock">
				<input type="hidden" name="update{$resourceName|capitalize}" id="update{$resourceName|capitalize}" value="1" />
				<input type="hidden" name="method" id="method" value="update" />
				<input type="hidden" name="csrftoken" id="csrftoken" value="{$smarty.session.csrftoken}" />
				{$parentResURI = $smarty.const._URL_ADMIN|cat:$resourceName}
				{$backURI = $smarty.server.HTTP_REFERER|replace:'&':'&amp;'|default:$parentResURI}
				{include file='common/blocks/actionBtn.tpl' href=$parentResURI class='cancelBtn' id='cancelBtn' label='cancel'|gettext}
				<span class="sep or">{t}or{/t}</span>
				{include file='common/blocks/actionBtn.tpl' mode='button' class='validateBtn' id='validateBtn' type='submit' label='update'|gettext}
				{if $viewMode === 'admin'}
				{include file='common/blocks/actionBtn.tpl' mode='button' type='submit' name='successRedirect' value=$parentResURI class='validateAndBackBtn' id='validateAndBackBtn' label='update & back to list'|gettext}
				{/if}
			</div>
		</div>
	</fieldset>

</form>