{$crudability=$resources[$resourceName].crudability|default:'CRUD'}
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
    {*
    <div class="group paginationButtons">
        <span class="title">{t}pagination{/t}{t}:{/t}</span>
        <span class="buttons">
        TODO
        </span>
    </div>
    *}
</div>