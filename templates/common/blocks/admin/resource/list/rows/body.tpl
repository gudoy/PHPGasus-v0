{foreach $data[$resourceName] as $resource}
<tr class="dataRow {cycle values='odd,even'}{if $resource@first} firstRow{/if}{if $resource@last} lastRow{/if}" id="row{$resource.id}">
	<td class="col firstCol colSelectResources"><input type="checkbox" name="ids[]" value="{$resource.id}"{if $smarty.post.ids && in_array($resource.id, $smarty.post.ids)} checked="checked"{/if} /></td>
	<td class="col actionsCol">
		<span class="actions">{include file='common/blocks/admin/resource/actions/listActions.tpl'}</span>
	</td>
	{include file='common/blocks/admin/resource/list/cols/dataCols.tpl'}
	<td class="col goToCol last lastCol">
	{include file='common/blocks/admin/resource/actions/retrieve.tpl' disabled=(strpos($crudability, 'R')>-1)?0:1}	
	</td>
</tr>
{foreachelse}
<tr class="noData first firstRow last lastRow">
	{* TODO: use proper displayed fields count + 3 (checkbox col, actions co, goto col) *}
	<td class="firstCol lastCol" colspan="{count($rModel)*2+3}">{t}There's currently nothing here{/t}</td>
</tr>
{/foreach}