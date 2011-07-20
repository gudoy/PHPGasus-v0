{$mode='create'}
<form action="{$data.current.url}" id="frmAdminCreate{$resourceName|capitalize}" class="commonForm {$mode}Mode" method="post" enctype="multipart/form-data">
	
	{block name='resourceColumnsFieldset'}
	<fieldset>
		<legend><span class="value">{t 1=$data._resources[$resourceName].singular|default:$resourceName}create new %1{/t}</span></legend>
		
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
			<input type="hidden" name="method" id="method" value="create" />
			{$parentResURI = $smarty.const._URL_ADMIN|cat:$resourceName}
			{$backURI = $smarty.server.HTTP_REFERER|replace:'&':'&amp;'|default:$parentResURI}
			{include file='common/blocks/actionBtn.tpl' href=$backURI class='cancelBtn' id='cancelBtn' label='cancel'|gettext}
			<span class="sep or">{t}or{/t}</span>
			{include file='common/blocks/actionBtn.tpl' mode='button' class='validateBtn' id='validateBtn' type='submit' label='create'|gettext}
			{if $viewMode === 'admin'}
			{include file='common/blocks/actionBtn.tpl' mode='button' type='submit' name='successRedirect' value=$parentResURI class='validateAndBackBtn' id='validateAndBackBtn' label='create & back'|gettext}
			{/if}
			</div>
		</div>
	</fieldset>

</form>