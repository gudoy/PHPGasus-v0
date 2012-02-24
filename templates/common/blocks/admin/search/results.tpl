{if $data.search.query}
<style class="dynamicCSS" id="searchDynamicCSS">
.commonTable.adminTable td .dataValue[data-exactValue*='{$data.search.query}'] { background:lightyellow; }
</style>
{/if}
<section id="adminSearchResultsBlock" class="section searchResultsBlock adminSearchResultsBlock">
{if $search.totalResults && $search.type === 'global'}
	{*
    <header class="titleBlock">
        <h3 class="title">{t}results{/t}</h3>
    </header>
    *}
    {$currentDefined    = false}
    {foreach $search.groups as $groupName => $group}
    {$groupResource     = $group.resource}
    {$displayField      = $data._resources[$groupResource].defaultNameField}
    {$resultsURL        = "{$smarty.const._URL_ADMIN}{$groupResource}?method=search&amp;searchContext=local&amp;searchQuery={$search.query}"}
    {$displayedCount    = count($group.results)}
    <div class="resultsGroup{if $group@first} first{/if}{if ($group@first && $group.results) || ($group.results && !$currentDefined)} expanded{$currentDefined=true}{/if}{if $group@last} last{/if}">
        <header class="groupTitle">
            <h3 class="title">
                <a href="{$resultsURL}" class="value">{$groupName}</a>
            </h3>
            <nav class="actions">
                {* if $group.results && ($displayedCount != $group.count) *}
                {include file='common/blocks/actionBtn.tpl' class="action go" href=$resultsURL label={'all'}}
                {* /if *}
            </nav>
            <span class="counts countsBlock">                
                <span class="key">{t}counts{/t}</span>
                {if $displayedCount}
                <span class="displayedCount displayedResourcesCount" id="displayedResourcesCount">
                    <span class="key">{t}displayed{/t}</span>
                    <span class="value">{$displayedCount}</span>
                </span>
                {/if}
                <span class="totalCount">
                    <span class="key">{t}total{/t}</span>
                    <span class="value">{$group.count|default:0}</span>
                </span>
            </span>
        </header>
        {if $group.results}
        <div class="adminBlock adminListingBlock resultsTableBlock">
        	{include file='common/blocks/admin/resource/list/table.tpl' rows=$group.results rName=$groupResource rModel=$data.dataModel[$groupResource] options=['addHiddenCols' => false]}
        	<a class="allResultsLink" href="{$resultsURL}">{t}all results{/t} >></a>
        </div>
        {/if}
    </div>
    {/foreach}
{else}
<p class="nodata" id="adminSearchNoResults">
	{t}Sorry, no matching results{/t}
</p>
{/if}
</section>