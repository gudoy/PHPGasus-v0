{$relatedsResources=$data.related}
<div class="ui-finder-col">
	{foreach $relatedsResources as $rel}
	<div class="ui-finder-group">
		<div class="ui-finder-group-header">
			<a class="relatedResourceLink" href="{$smarty.const._URL_ADMIN}related/{$rel.meta.name}?relOn={$rel.relOn}">{$rel.meta.displayName}</a>
			<span class="ninja relatedResourceList">
				<a rel="{$rel.relOn}" class="relatedResourceListLink" href="{$rel.meta.fullAdminPath}">{t}list of the{/t} {$rel.meta.name}</a>	
			</span>
		</div>
		<div class="ui-finder-group-content">
			{foreach $rel.items as $item}
			{if $item.first}
			<ul>
			{/if}
				<li class="ui-finder-item">
					{$paramsQuery=''}
					{foreach $item as $key => $val}{if is_numeric($val)}{$paramsQuery=$paramsQuery|cat:'&amp;'|cat:$key|cat:'='|cat:$val}{/if}{/foreach}
					{*$paramsQuery={$paramsQuery}<br/>*}
					<a class="relatedResourceItemLink" href="{$rel.meta.fullAdminPath}?values={$item.id}{$paramsQuery}">
						{$item.id}&nbsp;-&nbsp;{$item.{$rel.meta['defaultNameField']}|default:'[unknown name]'|regex_replace:"/\s/":"&nbsp;"}
					</a>
				</li>
			{if $item.last}
			</ul>
			{/if}
			{/foreach}
		</div>
	</div>
	{/foreach}
</div>