{$rProps 		= $data._resources[$resourceName]}
{$imageField 	= $rProps.imageField}
{$nameField 	= $rProps.nameField|default:$rProps.defaultNameField}
{$userResPerms 	= $data.current.user.auths[$resourceName]}
{$crudability 	= $data._resources[$resourceName].crudability|default:'CRUD'}
{$isReadable 	= (strpos($crudability, 'R')>-1)?1:0}

{foreach array_keys((array) $data[$resourceName]) as $key}
{$resource = $data[$resourceName][$key]}
<article class="resource" id="{$resourceName}{$resource.id}" data-id="{$resource.id}">
	<figure>
		{$src = $resource[$imageField]|default:$rProps.icon}
		<img class="cover{if !$src} default{/if}" src="{$src|default:"{$smarty.const._URL_STYLESHEETS}images/pix.png"}" />
		{if $displayMode === 'thumbs'}
		<figcaption>
			{if $isReadable}<a class="action primary goTo" href="{$smarty.const._URL_ADMIN}{$resourceName}/{$resource.id}">{/if}<span class="title">{$resource[$nameField]|default:$resource.id}</span>{if $isReadable}</a>{/if}
		</figcaption>
		{/if}
	</figure>
	{if $displayMode === 'list'}
	<div class="content">
		{if $isReadable}<a class="action primary goTo" href="{$smarty.const._URL_ADMIN}{$resourceName}/{$resource.id}">{/if}
		<hgroup>
			<h3><span class="id">{$resource.id} </span>{if $resource[$nameField]}<span class="nameField">{$resource[$nameField]}</span>{/if}</h3>
		</hgroup>
		{if $isReadable}</a>{/if}
	</div>
	{/if}
	{if $resource.update_date}<time class="lastupdate" datetime="{$resource.update_date|date_format:"%Y-%m-%dT%H:%M:%S:00Z"}">{$resource.update_date|date_format:"%d %B %Y, %Hh%M"}</time>{/if}
	<nav class="actions">
		{include file='common/blocks/admin/resource/actions/listActions.tpl'}
	</nav>
</article>
{foreachelse}
<p class="nodata">{t}There's currently nothing here{/t}</p>
{/foreach}
