{$crudability=$resources[$resourceName].crudability|default:'CRUD'}
{$nbOfItemsPerPage=$data.current.limit|default:$smarty.const._ADMIN_RESOURCES_NB_PER_PAGE}
<div class="menu toolbar adminListToolbar" id="adminListToolbar{$position|ucfirst}">
    <div class="group createButtons">
        <span class="buttons">
            {$disabled=(strpos($crudability, 'C')>-1)?0:1}
            <a class="actionBtn addLink {if $disabled}disabled{/if}" href="{if !$disabled}{$smarty.const._URL_ADMIN}{$resourceName}?method=create{else}#{/if}">
                <span class="label value">{'new'|gettext}</span>
            </a>
        </span>
    </div>
    <div class="group actionsButtons">
        <span class="title">{t}selection{/t}{t}:{/t}</span>
        <span class="buttons">
        {strip}
            {$crudability=$data._resources[$resourceName].crudability|default:'CRUD'}
            {$disabled=(strpos($crudability, 'U')>-1)?0:1}
            <a class="actionBtn editLink editAllLink {if $disabled}disabled{/if}" href="{if !$disabled}{$smarty.const._URL_ADMIN}{$resourceName}/{$resource.id}?method=update{else}#{/if}">
                <span class="label value">{'edit'|gettext}</span>
            </a>
            {$disabled=(strpos($crudability, 'C')>-1&&strpos($crudability, 'U')>-1)?0:1}
            <a class="actionBtn duplicateLink duplicateAllLink {if $disabled}disabled{/if}" href="{if !$disabled}{$smarty.const._URL_ADMIN}{$resourceName}/{$resource.id}?method=duplicate{else}#{/if}">
                <span class="label value">{'duplicate'|gettext}</span>
            </a>
            {$disabled=(strpos($crudability, 'D')>-1)?0:1}
            <a class="actionBtn deleteLink deleteAllLink {if $disabled}disabled{/if}" href="{if !$disabled}{$smarty.const._URL_ADMIN}{$resourceName}/{$resource.id}?method=delete{else}#{/if}">
                <span class="label value">{'delete'|gettext}</span>
            </a>
        {/strip}
        </span>
    </div>
    <div class="group filterButtons">
        <span class="buttons">
            <a class="actionBtn filterLink" href="#{$resourceName}FiltersRow">
                <span class="label value">{'filter'|gettext}</span>
            </a>
        </span>
    </div>
    <div class="group itemsCounts">
        <span class="title">{t}items{/t}{t}:{/t}</span>
        {$data.current.urlParams.offset=null}
        {$newPageURL={$curURL|regex_replace:'/(.*)\?(.*)/U':'$1'}|cat:'?'|cat:{http_build_query($data.current.urlParams)}}
        <form action="{$newPageURL}" method="get">
            <fieldset>
                <select id="itemsPerPage{$position|ucfirst}" class="sized itemPerPage" name="limit">
                    {foreach array(25,50,100,200,500) as $nb}
                    <option value="{$nb}" {if $nb === $nbOfItemsPerPage}selected="selected"{/if}>{$nb}</option>
                    {/foreach}
                </select>
                {include file='common/blocks/actionBtn.tpl' mode='button' btnClasses='validateBtn' btnId='validateBtn' btnType='submit' btnLabel='Ok'|gettext}
            </fieldset>
        </form>
        <span class="value">/ {$data.total[$resourceName]}</span>
    </div>
    <div class="group paginationButtons">
        <span class="title">{t}pages{/t}{t}:{/t}</span>
        <span class="buttons">
        {include file='common/blocks/admin/pagination/index_new.tpl' vPosition=$position}
        </span>
    </div>
</div>