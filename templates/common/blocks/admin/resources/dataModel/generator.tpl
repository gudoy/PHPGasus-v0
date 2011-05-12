{block name='adminDataModelCodeBlock'}
<div class="block adminBlock adminDataModelCodeBlock" id="adminDataModelCodeBlock">
	<header class="titleBlock">
		<h3 class="title">
			<span>datamodel generated code</span>	
		</h3>
	</header>
	<div class="content">
		<div class="item">
			<label for="datamodelResourceCode">resources</label>
			<a href="#datamodelResourceCode"><span class="value">{t}show{/t}</span></a>
			<a href="{$smarty.const._URL_ADMIN}resources/dataModelGenerator/resources"><span class="value">{t}generate{/t}</span></a>
			<textarea id="datamodelResourceCode" rows="5" cols="100">{$data._extras.dataModel.resources.code}</textarea>
		</div>
		<div class="item">
			<label for="datamodelGroupsCode">groups</label>
			<a href="#datamodelGroupsCode"><span class="value">{t}show{/t}</span></a>
			<a href="{$smarty.const._URL_ADMIN}resources/dataModelGenerator/groups"><span class="value">{t}generate{/t}</span></a>
			<textarea id="datamodelGroupsCode" rows="5" cols="100">{$data._extras.dataModel.groups.code}</textarea>
		</div>
		<div class="item">
			<label for="datamodelColumnsCode">columns</label>
			<a href="#datamodelColumnsCode"><span class="value">{t}show{/t}</span></a>
			<a href="{$smarty.const._URL_ADMIN}resources/dataModelGenerator/columns"><span class="value">{t}generate{/t}</span></a>
			<textarea id="datamodelColumnsCode" rows="5" cols="100">{$data._extras.dataModel.columns.code}</textarea>
		</div>
	</div>
</div>
{/block}