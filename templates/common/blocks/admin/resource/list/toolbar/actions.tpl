{block name='adminListToolbarContent'}
<div class="group createButtons">
	{if $userResPerms.allow_create}
    <span class="actions">
        {$disabled = (strpos($crudability, 'C')!== false)?0:1}
        <a class="action actionBtn add addLink {if $disabled}disabled{/if}" href="{if !$disabled}{$smarty.const._URL_ADMIN}{$resourceName}?method=create{else}#{/if}">
            <span class="value">{t}new{/t}</span>
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
            <span class="value">{t}edit{/t}</span>
        </a>
        {/if}
        {if $userResPerms.allow_create && $userResPerms.allow_update}
        {$disabled = (strpos($crudability, 'C')!== false && strpos($crudability, 'U')>-1)?0:1}
        <a class="action actionBtn duplicate duplicateLink duplicateAllLink {if $disabled}disabled{/if}" href="{if !$disabled}{$smarty.const._URL_ADMIN}{$resourceName}/{$resource.id}?method=duplicate{else}#{/if}">
            <span class="value">{t}duplicate{/t}</span>
        </a>
        {/if}
        {if $userResPerms.allow_delete}
        {$disabled = (strpos($crudability, 'D')!== false)?0:1}
        <a class="action actionBtn delete deleteLink deleteAllLink {if $disabled}disabled{/if}" href="{if !$disabled}{$smarty.const._URL_ADMIN}{$resourceName}/{$resource.id}?method=delete{else}#{/if}">
            <span class="value">{t}delete{/t}</span>
        </a>
        {/if}
    {/strip}
    </span>
    {/if}
</div>
<div class="group filterButtons">
    <span class="actions">
        <a class="action actionBtn filter filterLink" href="#{$resourceName}FiltersRow">
            <span class="value">{t}filter{/t}</span>
        </a>
    </span>
</div>
<div class="group settings">
	<span class="title">{t}settings{/t}</span>
	<div class="groups">
        <div class="group displayMode">
        	<span class="title">{t}display mode{/t}</span>
        	<span class="actions">
	            <a class="action actionBtn displayMode tableMode" id="tableMode">
	                <span class="value">{t}table{/t}</span>
	            </a>
	            <a class="action actionBtn displayMode listMode" id="listMode">
	                <span class="value">{t}list{/t}</span>
	            </a>
	            <a class="action actionBtn displayMode thumbsMode" id="thumbsMode">
	                <span class="value">{t}thumbnails{/t}</span>
	            </a>
        	</span>
        </div>
	    <div class="group itemsCounts">
	        <span class="title">{t}Nb of items displayed{/t}</span>
	        {$data.current.urlParams.offset = null}
	        {$newPageURL 		= {$curURL|regex_replace:'/(.*)\?(.*)/U':'$1'}|cat:'?'|cat:{http_build_query($data.current.urlParams)}}
	        {$nbOfItemsPerPage	= $data.current.limit|default:$smarty.const._ADMIN_RESOURCES_NB_PER_PAGE}
	        <fieldset>
	            <select id="itemsPerPage{$position|ucfirst}" class="sized itemPerPage" name="limit" formmethod="get">
	                {foreach array(25,50,100,200,500) as $nb}
	                <option value="{$nb}" {if $nb === $nbOfItemsPerPage}selected="selected"{/if}>{$nb}</option>
	                {/foreach}
	            </select>
	            {* include file='common/blocks/actionBtn.tpl' mode='button' class='action validateBtn' id='validateBtn' type='submit' label="{t}ok{t}" *}
	        </fieldset>
	    </div>
        <div class="group density">
        	<span class="title">{t}density{/t}</span>
        	<span class="actions">
        		{$curDensity = 'high'}
        		{$densities = ['normal' => "{t}normal{/t}", 'average' => "{t}average{/t}", 'high' => "{t}high{/t}"]}
        		{foreach $densities as $item => $translation}
	            <a class="action actionBtn displayDensity {$item}Density {if $item === $curDensity}current{/if}" id="{$item}Density" data-value="{$item}">
	                <span class="value">{$translation}</span>
	            </a>
        		{/foreach}
        	</span>
        </div>
	</div>
</div>
{if $data.total[$resourceName] > $data.current.limit}
<div class="group paginationButtons">
    <span class="title">{t}pages{/t}</span>
    <span class="actions">
    {include file='common/blocks/admin/pagination/pagination.tpl' vPosition='top'}
    </span>
</div>
{/if}
<div class="group itemsCounts resourcesCounts" id="resourcesCounts{$position|ucfirst}">
    <span class="title">{t}items{/t}</span>
    {$data.current.urlParams.offset = null}
    {$newPageURL = {$curURL|regex_replace:'/(.*)\?(.*)/U':'$1'}|cat:'?'|cat:{http_build_query($data.current.urlParams)}}
    <fieldset class="count" id="displayedResourcesCount{$position|ucfirst}">
        <select id="itemsPerPage{$position|ucfirst}" class="sized itemPerPage" name="limit" formmethod="get">
            {foreach array(25,50,100,200,500) as $nb}
            <option value="{$nb}" {if $nb === $nbOfItemsPerPage}selected="selected"{/if}>{$nb}</option>
            {/foreach}
        </select>
        {include file='common/blocks/actionBtn.tpl' mode='button' class='action validateBtn' id='validateBtn' type='submit' label="{t}ok{/t}"}
    </fieldset>
    <span class="totalCount totalResourcesCount" id="totalResourcesCount{$position|ucfirst}">
        <span class="key">{t}of{/t}</span>
        <span class="value">{$data.total[$resourceName]}</span>
    </span>
</div>
{/block}