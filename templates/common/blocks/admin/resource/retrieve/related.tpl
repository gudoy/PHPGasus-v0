{if $data._resources[$resourceName].related}
<ul class="resources related relatedResources" id="{$resourceName}RelatedResources">
	{* sample: 'related' => array({$relatedeEsource} => array('on' => {$relationColumn})) *}
	{foreach $data._resources[$resourceName].related as $relResName => $props}
	{if in_array($relResName, $data.current.user.auths.__can_display)}
	{$count = $data.total[$relResName]}
	{* $relColumn = ($data._resources[$resourceName].type === 'relation')?'id':$props.on *}
	{$relColumn = $props.on}
	{$relValue 	= $resource.id}
	{*
	<li class="item {$relResName}">
		<a class="action go" href="{$smarty.const._URL_ADMIN}{$relResName}?conditions={$relColumn}|{$relValue}">
			<article>
				<h4 class="name value">{$data._resources[$relResName].displayName|default:$relResName}</h4>
				<span class="counts">
					<span class="key">total</span>
					<span class="value">{$data.total[$relResName]|default:'?'}</span>
				</span>
			</article>
		</a>
	</li>
	*}
	<li class="resource {$relResName} {$relResName}NavItem" id="{$relResName}RelatedNavItem">
		<a class="action view" href="{$smarty.const._URL_ADMIN}{$relResName}?conditions={$relColumn}|{$relValue}" {if $count}data-count="{$count}"{/if}>
			<span class="value name">{$data._resources[$relResName].displayName|default:$relResName}</span>
			{if $count}
			<span class="count">{$data.total[$relResName]}</span>
			{/if}
		</a>
	</li>
	{/if}
	{/foreach}
</ul>
{else}
<p class="nodata">{t}There's currently no related resource.{/t}</p>
{/if}
