{$crudability 		= $data._resources[$resourceName].crudability|default:'CRUD'}
{$nbOfItemsPerPage	= $data.current.limit|default:$smarty.const._ADMIN_RESOURCES_NB_PER_PAGE}
{$userResPerms 		= $data.current.user.auths[$resourceName]}
<div class="menu toolbar adminToolbar adminListToolbar {$position}" id="adminListToolbar{$position|ucfirst}">
	{block name='adminListToolbarContent'}
    <div class="group createButtons">
    	{if $userResPerms.allow_create}
        <span class="actions">
            {$disabled = (strpos($crudability, 'C')!== false)?0:1}
            <a class="action actionBtn add addLink {if $disabled}disabled{/if}" href="{if !$disabled}{$smarty.const._URL_ADMIN}{$resourceName}?method=create{else}#{/if}">
                <span class="value">{'new'|gettext}</span>
            </a>
        </span>
        {/if}
    </div>
    <div class="group actionsButtons">
        <span class="title">{t}selection{/t}</span>
        {if $userResPerms.allow_update || $userResPerms.allow_delete}
        <span class="actions">
        {strip}
        	{if $userResPerms.allow_update}
            {$crudability = $data._resources[$resourceName].crudability|default:'CRUD'}
            {$disabled=(strpos($crudability, 'U')!== false)?0:1}
            <a class="action actionBtn edit editLink editAllLink {if $disabled}disabled{/if}" href="{if !$disabled}{$smarty.const._URL_ADMIN}{$resourceName}/{$resource.id}?method=update{else}#{/if}">
                <span class="value">{'edit'|gettext}</span>
            </a>
            {/if}
            {if $userResPerms.allow_create && $userResPerms.allow_update}
            {$disabled = (strpos($crudability, 'C')!== false && strpos($crudability, 'U')>-1)?0:1}
            <a class="action actionBtn duplicate duplicateLink duplicateAllLink {if $disabled}disabled{/if}" href="{if !$disabled}{$smarty.const._URL_ADMIN}{$resourceName}/{$resource.id}?method=duplicate{else}#{/if}">
                <span class="value">{'duplicate'|gettext}</span>
            </a>
            {/if}
            {if $userResPerms.allow_delete}
            {$disabled = (strpos($crudability, 'D')!== false)?0:1}
            <a class="action actionBtn delete deleteLink deleteAllLink {if $disabled}disabled{/if}" href="{if !$disabled}{$smarty.const._URL_ADMIN}{$resourceName}/{$resource.id}?method=delete{else}#{/if}">
                <span class="value">{'delete'|gettext}</span>
            </a>
            {/if}
        {/strip}
        </span>
        {/if}
    </div>
    <div class="group filterButtons">
        <span class="actions">
            <a class="action actionBtn filter filterLink" href="#{$resourceName}FiltersRow">
                <span class="value">{'filter'|gettext}</span>
            </a>
        </span>
    </div>
    <div class="group itemsCounts">
        <span class="title">{t}items{/t}</span>
        {$data.current.urlParams.offset = null}
        {$newPageURL = {$curURL|regex_replace:'/(.*)\?(.*)/U':'$1'}|cat:'?'|cat:{http_build_query($data.current.urlParams)}}
        <fieldset>
            <select id="itemsPerPage{$position|ucfirst}" class="sized itemPerPage" name="limit" formmethod="get">
                {foreach array(25,50,100,200,500) as $nb}
                <option value="{$nb}" {if $nb === $nbOfItemsPerPage}selected="selected"{/if}>{$nb}</option>
                {/foreach}
            </select>
            {include file='common/blocks/actionBtn.tpl' mode='button' class='action validateBtn' id='validateBtn' type='submit' label='Ok'|gettext}
        </fieldset>
    </div>
    {if $data.total[$resourceName] > $data.current.limit}
    <div class="group paginationButtons">
        <span class="title">{t}pages{/t}</span>
        <span class="actions">
        {include file='common/blocks/admin/pagination/index_new.tpl' vPosition=$position}
        </span>
    </div>
    {/if}
    {/block}
</div>