{if !empty($resource[$fieldName])}
<div class="currentItem">
	<span class="hidden exactValue">{$resource[$fieldName]}</span>
	{if strpos($resource[$fieldName], 'http://') === false}{$baseSrc=$field.destBaseURL|default:$smarty.const._URL}{else}{$baseSrc=''}{/if}
	{if $field.storeAs === 'filename'}{$baseSrc=$baseSrc|cat:$field.destFolder}{/if}
	{$fileSrc = $baseSrc|cat:$resource[$fieldName]}
	{$fileExt = $fileSrc|regex_replace:'/(.*)\.(.*)$/':'$2'}
	{if in_array($fileExt, array('png','jpg','gif','bmp'))}{$isImage=true}{else}{$isImage=false}{/if}
	
	{if $field.mediaType && $field.mediaType == 'image' && $field.storeAs !== 'filename' || $isImage}
	<figure class="picsBlock figure">
		<a class="file {if $isImage}image{/if} {$fileExt}" href="{$fileSrc}">
			<img class="icon" src="{if strpos($resource[$fieldName], 'http://') === false}{$field.destBaseURL|default:$smarty.const._URL}{/if}{$resource[$fieldName]}" alt="{$resource[$fieldName]}: {$resource.id}" />
		</a>
		{*if file_exists($fileSrc)}<span class="filesize">[ {filesize($fileSrc)} o]</span>{/if*}
	</figure>
	{/if}
	<div class="dataBlock details">
	<a class="name filename file {if $isImage}image{/if} {$fileExt}" href="{$fileSrc}">	
		<span class="value">
			{$resource[$fieldName]|regex_replace:"/.*\//":""}
		</span>
	</a>
	</div>
	{$updateFieldClass=hidden}
</div>
{/if}
<div class="fieldsAndButtonsBlock {if empty($resource[$fieldName])}emptyValueMode{/if}">
	<input type="file" name="{$resourceFieldName}{$useArray}" id="{$resourceFieldName}{$itemIndex}" class="sized file" {if !$editable || ($mode === 'create' && $field.computed)}disabled="disabled"{/if} />
	<span class="or">{t}or{/t}</span>
	<input type="text" class="normal" {if !$editable || ($mode === 'create' && $field.computed)}disabled="disabled"{/if} value="{$resource[$fieldName]}" />
	<div class="nav actions buttonsBlock">
		{$repBntId='replace'|cat:{$resourceFieldName|ucfirst}|cat:'FileLink'}
		{include file='common/blocks/actionBtn.tpl' mode='button' class='replace replaceFileLink' id=$repBntId label="{t}replace{/t}"}
		<span class="or">{t}or{/t}</span>
		{$editBntId='edit'|cat:{$resourceFieldName|ucfirst}|cat:'FileLink'}
		{include file='common/blocks/actionBtn.tpl' mode='button' class='edit editFileLink' id=$editBntId label="{t}edit URL{/t}"}
		<span class="or">{t}or{/t}</span>
		<a class="action remove deleteFileLink" id="delete{$resourceFieldName|ucfirst}FileLink" href="{$smarty.const._URL_ADMIN}{$resourceName}{$resource.id}/{$fieldName}?method=update&amp;forceFileDeletion=1" title="{t}Delete this file{/t}">
			<span class="label">{t}remove{/t}</span>
		</a>
		<span class="or">{t}or{/t}</span>
		{include file='common/blocks/actionBtn.tpl' mode='button' class='cancel cancelFileActionLink' label="{t}cancel{/t}"}
	</div>
</div>