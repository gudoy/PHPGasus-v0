{extends file='specific/layout/pageAdmin.tpl'}

{block name='mainContent'}

	{$resourceName 	= $view.resourceName}
	{$resourceId 	= $data.resourceId}
	{$resource 		= $data[$resourceName]}

    {block name='adminRetrieveSection'}
    {$all = $data[$resourceName]}
	{foreach array_keys((array) $all) as $rKey}
	{$resource = $all[$rKey]}
    <section class="adminSection adminRetrieveSection admin{$resourceName|ucfirst}RetrieveSection">
	{include file='common/blocks/admin/resource/retrieve.tpl'}
	</section>
	{/foreach}
	{/block}

{/block}



{block name='mainColFooterContent'}
{$position 		= 'bottom'}
{$crudability 	= $data._resources[$resourceName].crudability|default:'CRUD'}
{$userResPerms 	= $data.current.user.auths[$resourceName]}
<nav class="actions toolbar adminToolbar adminRetrieveToolbar {$position}" id="adminRetrieveToolbar{$position|ucfirst}">
{if $data.total[$rName] === 1}
{include file='common/blocks/admin/pagination/nextprev.tpl' adminView='retrieve'}
{/if}
</nav>
{/block}