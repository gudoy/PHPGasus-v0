<div class="box relatedResourcesBlock" id="relatedResourcesBlock">
	<h2>
		{t}Related Resources{/t} [beta]
	</h2>
	<div class="ui-finder" id="relatedResourcesFinder">
		
			<div class="ui-finder-col">
				<div class="ui-finder-group">
					<div class="ui-finder-group-header">
						<a class="relatedResourceLink" href="{$smarty.const._URL_ADMIN}related/{$resourceName}">{$data.meta.displayName}</a>
						<span class="ninja relatedResourceList">
							<a class="relatedResourceListLink" href="{$data.meta.fullAdminPath}">{t}list of the{/t} {$data.meta.name}</a>	
						</span>
					</div>
					<div class="ui-finder-group-content">
						<ul>
							<li class="ui-finder-item">
								<a class="relatedResourceItemLink" href="{$data.metas[$resourceName].fullAdminPath}{if $resourceId}?values={$resourceId}{/if}">
									{$resourceId}&nbsp;-&nbsp;{$data[$resourceName][$data.meta['defaultNameField']]|default:'[unknown name]'|regex_replace:"/\s/":"&nbsp;"}
								</a>
							</li>
						</ul>
					</div>
				</div>

		</div
	></div>
	<div class="adminListingBlock" id="adminRelatedListingBlock">
		{*include file='common/blocks/admin/common/resources/listTable.tpl'*}
	</div>
</div>