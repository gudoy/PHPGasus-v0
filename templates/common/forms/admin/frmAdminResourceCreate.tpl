{$mode='create'}
<form action="{$data.current.url}" id="frmAdminCreate{$resourceName|capitalize}" class="commonForm adminForm {$mode}Mode {if !empty($smarty.post)}submited{/if}" method="post" enctype="multipart/form-data">
	
	{block name='resourceColumnsFieldset'}
	<fieldset class="body">
		<legend><span class="value">{t 1=$data._resources[$resourceName].singular|default:$resourceName}create new %1{/t}</span></legend>
		
		{block name='resourceFieldsRows'}
		{foreach $data._columns[$resourceName] as $fieldName => $field}
		{include file='common/forms/admin/fieldLine/index.tpl'}
		{/foreach}
		{/block}
		
		{* include file='common/forms/common/fields/legendDetail.tpl' *}

	</fieldset>
	{/block}
	
	<fieldset class="hidden">
		<input type="hidden" name="create{$resourceName|capitalize}" id="create{$resourceName|capitalize}" value="1" />
		<input type="hidden" name="method" id="method" value="create" />
		<input type="hidden" name="csrftoken" id="csrftoken" value="{$smarty.session.csrftoken}" />
	</fieldset>
	
	{*
	<fieldset class="buttonsFieldset">
		<div class="line noLabelBlock buttonsLine">
			<div class="fieldBlock">
			{include file='common/blocks/actionBtn.tpl' href="{$smarty.const._URL_ADMIN}{$resourceName}" class='cancelBtn' id='cancelBtn' label="{t}cancel{/t}"}
			<span class="sep or">{t}or{/t}</span>
			{include file='common/blocks/actionBtn.tpl' mode='button' class='validateBtn' id='validateBtn' type='submit' label="{t}create{/t}"}
			{if $viewMode === 'admin'}
			{include file='common/blocks/actionBtn.tpl' mode='button' type='submit' name='successRedirect' value="{$smarty.const._URL_ADMIN}{$resourceName}" class='validateAndBackBtn' id='validateAndBackBtn' label="{t escape=no}create & back{/t}"}
			{/if}
			</div>
		</div>
	</fieldset>*}
	
	<fieldset class="actions formActions">
	{if $viewMode === 'api'}
		{include file='common/blocks/actionBtn.tpl' mode='button' class='validateBtn' id='validateBtn' type='submit' label="{t}create{/t}"}
	{else}
		{include file='common/blocks/actionBtn.tpl' mode='button' class='action closeBtn' id="closeAdminFormBtn{$resourceId}" label="{t}close{/t}"}
		{include file='common/blocks/actionBtn.tpl' href="{$smarty.const._URL_ADMIN}{$view.resourceName}" class='cancelBtn' id='cancelBtn' label="{t}cancel{/t}"}
		<span class="sep or">{t}or{/t}</span>
		{include file='common/blocks/actionBtn.tpl' mode='button' class='validateBtn' id='validateBtn' type='submit' label="{t}create{/t}"}
		{include file='common/blocks/actionBtn.tpl' mode='button' type='submit' name='successRedirect' value="{$smarty.const._URL_ADMIN}{$view.resourceName}" class='validateAndBackBtn' id='validateAndBackBtn' label="{t escape=no}update & back to list{/t}"}
	{/if}
	</fieldset>

</form>