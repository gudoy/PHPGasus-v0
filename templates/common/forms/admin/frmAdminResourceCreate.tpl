{$mode='create'}
<form action="{$data.meta.fullAdminPath}?method=create" id="frmAdminCreate{$resourceName|capitalize}" class="commonForm {$mode}Mode" method="post" enctype="multipart/form-data">
	
	{block name='resourceColumnsFieldset'}
	<fieldset>
		<legend><span class="value">{t}Set resource data{/t}</span></legend>
		
		{block name='resourceFieldsRows'}
		{foreach $data.dataModel[$resourceName] as $fieldName => $field}
		{include file='common/forms/admin/fieldLine/index.tpl'}
		{/foreach}
		{/block}
		
		{include file='common/forms/common/fields/legendDetail.tpl'}

	</fieldset>
	{/block}
	
	<fieldset class="buttonsFieldset">
		<div class="line noLabelBlock buttonsLine">
			<div class="fieldBlock">
			<input type="hidden" name="create{$resourceName|capitalize}" id="create{$resourceName|capitalize}" value="1" />
			{$parentResURI = $smarty.const._URL_ADMIN|cat:$resourceName}
			{$backURI = $smarty.server.HTTP_REFERER|replace:'&':'&amp;'|default:$parentResURI}
			{include file='common/blocks/actionBtn.tpl' btnHref=$backURI btnClasses='cancelBtn' btnId='cancelBtn' btnLabel='Cancel'|gettext}
			<span class="sep or">{t}or{/t}</span>
			{include file='common/blocks/actionBtn.tpl' mode='button' btnClasses='validateBtn' btnId='validateBtn' btnType='submit' btnLabel='Create'|gettext}
			{if $viewMode === 'admin'}
			{include file='common/blocks/actionBtn.tpl' mode='button' btnType='submit' btnName='successRedirect' btnValue=$data.meta.fullAdminPath btnClasses='validateAndBackBtn' btnId='validateAndBackBtn' btnLabel='Create & Back'|gettext}
			{/if}
			</div>
		</div>
	</fieldset>

</form>