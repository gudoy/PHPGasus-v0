{$mode='update'}
<form action="{$data.meta.fullAdminPath}{$resource.id}?method=update" id="frmAdminUpdate{$resourceName|capitalize}" class="commonForm {$mode}Mode" method="post" enctype="multipart/form-data">
	
	<fieldset>
		<legend><span>{t}Update data of this resource{/t}</span></legend>
		
		{foreach name='tableFields' from=$data.dataModel[$resourceName] key='fieldName' item='field'}
		{include file='common/forms/admin/fieldLine/index.tpl'}
		{/foreach}
		
		{include file='common/forms/common/fields/legendDetail.tpl'}
		<div class="line noLabelBlock buttonsLine">
			<div class="fieldBlock">
				<input type="hidden" name="update{$resourceName|capitalize}" id="update{$resourceName|capitalize}" value="1" />
				{assign var='parentResURI' value=$smarty.const._URL_ADMIN|cat:$resourceName}
				{assign var='backURI' value=$smarty.server.HTTP_REFERER|replace:'&':'&amp;'|default:$parentResURI}
				{include file='common/blocks/actionBtn.tpl' btnHref=$backURI btnClasses='cancelBtn' btnId='cancelBtn' btnLabel='Cancel'|gettext}
				<span class="sep or">{t}or{/t}</span>
				{include file='common/blocks/actionBtn.tpl' mode='button' btnClasses='validateBtn' btnId='validateBtn' btnType='submit' btnLabel='Update'|gettext}
				{include file='common/blocks/actionBtn.tpl' mode='button' btnType='submit' btnName='successRedirect' btnValue=$data.meta.fullAdminPath btnClasses='validateAndBackBtn' btnId='validateAndBackBtn' btnLabel='Update & Back to list'|gettext}
			</div>
		</div>

	</fieldset>

</form>
