{$mode='update'}
<form action="{$data.meta.fullAdminPath}{$resource.id}?method=update" id="frmAdminUpdate{$resourceName|capitalize}" class="commonForm {$mode}Mode" method="post" enctype="multipart/form-data">
	
	<fieldset>
		<legend><span class="value">{t}Edit resource data{/t}</span></legend>
		
		{block name='resourceFieldsRows'}
		{foreach name='tableFields' from=$data.dataModel[$resourceName] key='fieldName' item='field'}
		{include file='common/forms/admin/fieldLine/index.tpl'}
		{/foreach}
		{/block}
		
		{include file='common/forms/common/fields/legendDetail.tpl'}

	</fieldset>
	
	<fieldset class="buttonsFieldset">
		<div class="line noLabelBlock buttonsLine">
			<div class="fieldBlock">
				<input type="hidden" name="update{$resourceName|capitalize}" id="update{$resourceName|capitalize}" value="1" />
				{assign var='parentResURI' value=$smarty.const._URL_ADMIN|cat:$resourceName}
				{assign var='backURI' value=$smarty.server.HTTP_REFERER|replace:'&':'&amp;'|default:$parentResURI}
				{include file='common/blocks/actionBtn.tpl' btnHref=$backURI btnClasses='cancelBtn' btnId='cancelBtn' btnLabel='Cancel'|gettext}
				<span class="sep or">{t}or{/t}</span>
				{include file='common/blocks/actionBtn.tpl' mode='button' btnClasses='validateBtn' btnId='validateBtn' btnType='submit' btnLabel='Update'|gettext}
				{if $viewMode === 'admin'}
				{include file='common/blocks/actionBtn.tpl' mode='button' btnType='submit' btnName='successRedirect' btnValue=$data.meta.fullAdminPath btnClasses='validateAndBackBtn' btnId='validateAndBackBtn' btnLabel='Update & Back to list'|gettext}
				{/if}
			</div>
		</div>
	</fieldset>

</form>
