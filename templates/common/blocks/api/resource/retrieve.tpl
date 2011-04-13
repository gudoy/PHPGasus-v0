<div class="resourceDetailBlock apiResourceDetailBlock">
	<dl>
	{foreach $item as $colName => $value}
		{$colProps = $data.dataModel[$resourceName][$colName]}
		{* Do not display fields that are not defined in the dataModel *}
		{* except for admin views *}
		{if $isAdminView || !empty($colProps)}
		<dt class="key {$colName}">{include file='common/blocks/admin/resource/retrieve/columnName.tpl'}</dt>
		<dd class="value {$colName}">{include file='common/blocks/admin/resource/retrieve/columnValues.tpl'}</dd>
		{/if}
	{/foreach}
	</dl>
</div>