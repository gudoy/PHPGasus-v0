{if $data._resources[$resourceName].related}
<ul class="related relatedResources" id="{$resourceName}RelatedResources">
	{* sample: 'related' => array({$relatedeEsource} => array('on' => {$relationColumn})) *}
	{foreach $data._resources[$resourceName].related as $relResName => $props}
	{if in_array($relResName, $data.current.user.auths.__can_display)}
	<li class="item {$relResName}">
		<a class="action go" href="{$smarty.const._URL_ADMIN}{$relResName}?conditions={$props.on}|{$data.resourceId}">
			<article>
				<h4 class="name value">{$relResName}</h4>
				{if $data.total[$relResName]}
				<span class="counts">
					<span class="key">total</span>
					<span class="value">{$data.total[$relResName]}</span>
				</span>
				{/if}
			</article>
		</a>
	</li>
	{/if}
	{/foreach}
</ul>
{else}
<p class="nodata">{t}This resource is currently not related with any other resources.{/t}</p>
{/if}
