{foreach array_keys((array) $data[$resourceName]) as $key}
{$resource = $data[$resourceName][$key]}
<tr class="dataRow" id="row{$resource.id}" data-id="{$resource.id}" data-nameField="{$nameField}">
	<td class="col colSelectResources"><input type="checkbox" name="ids[]" value="{$resource.id}"{if $smarty.post.ids && in_array($resource.id, $smarty.post.ids)} checked="checked"{/if} /></td>
	<td class="col actionsCol"><span class="actions">{include file='common/blocks/admin/resource/actions/listActions.tpl'}</span></td>
	{include file='common/blocks/admin/resource/list/cols/dataCols.tpl'}
	<td class="col goToCol">{include file='common/blocks/admin/resource/actions/retrieve.tpl' disabled=(strpos($crudability, 'R')>-1)?0:1}	</td>
</tr>
{foreachelse}
<tr class="noData">
	{* TODO: use proper displayed fields count + 3 (checkbox col, actions co, goto col) *}
	<td colspan="{count($rModel)*2+3}"><p class="nodata">{t}There's currently nothing here{/t}</p></td>
</tr>
{/foreach}