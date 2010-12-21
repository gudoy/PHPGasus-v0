{$resources=$data._resources}
{$search=$data.search}
{$current=$data.current}
{if $search.type === 'contextual' && !$search.allowed}
<p>
    {t}Sorry but the search is not activated for the current resource{/t}
</p>
{else}
<a id="searchToggler" href="#adminSearchBlock"><span class="label">{t}toggle search{/t}</span></a>
{html5 tag='section' class='searchBlock adminSearchBlock' id='adminSearchBlock'}

	{html5 tag='header' class='titleBlock'}
		<h2>{t}search{/t}</h2>
	{/html5}
	
	<div class="contentBlock">
	
    	<form action="{if $current.resource}{else}{$smarty.const._URL_ADMIN_SEARCH}{/if}" method="get" class="commonForm searchForm adminSearchForm" id="adminSearchForm">
    	   {*
            <fieldset id="advancedSearchFieldset">
                <a class="modeLink" id="simpleSearchLink" href="#simpleSearchFieldset">{t}simple{/t}</a>
                <legend>
                    <span>{t}advanced search{/t}</span>
                </legend>
                <div class="line">
                    <div class="labelBlock">
                        <label for="criteriaResource" class="span">{t}resource{/t}</label>
                    </div>
                    <div class="fieldBlock">
                        <select name="criteriaResource[]" id="criteriaResource">
                            <option>all</option>
                            {foreach $data.current.groupResources as $name => $resource}
                            <option value="{$name}">{$name}</option>
                            {/foreach}
                        </select>
                    </div>
                </div>
                <div class="line">
                    <div class="labelBlock">
                        <label for="criteriaColumns" class="span">{t}fields{/t}</label>
                    </div>
                    <div class="fieldBlock">
                        <select name="criteriaColumns[]" id="criteriaColumns" multiple="multiple">
                            <option></option>
                        </select>
                    </div>
                </div>
                <div class="line">
                    <div class="labelBlock">
                        <label for="criteriaOperator" class="span">{t}operator{/t}</label>
                    </div>
                    <div class="fieldBlock">
                        <select name="criteriaOperator[]" id="criteriaOperator">
                            <option value="=">=</option>
                            <option value="!=">!=</option>
                            <option value=">">&gt;</option>
                            <option value=">=">&gt;=</option>
                            <option value="<">&lt;</option>
                            <option value="<=">&lt;=</option>
                            <option value="contains">contains</option>
                            <option value="startsby">start by</option>
                            <option value="endsby">ends by</option>
                            <option value="in">one of</option>
                            <option value="notin">not one of</option>
                        </select>
                    </div>
                </div>
                <div class="line">
                    {include file='common/formFields/common/search.tpl' name='criteriaValues[]' id='criteriaValues' label={'values'|gettext} placeholder={'Ex: value1,value2,...'|gettext}}
                </div>
                <div class="line buttonsLine">
                    {include file='common/blocks/actionBtn.tpl' mode='button' btnClasses='adminLink addLink addCriteriaBtn' btnLabel={'add'} btnId='searchAddCriteriaBtn'}
                    {include file='common/blocks/actionBtn.tpl' mode='button' btnClasses='validateBtn' btnLabel={'search'} btnId='validateSearchBtn'}
                </div>
            </fieldset>
            *}
            <fieldset id="simpleSearchFieldset">
                {*include file='common/blocks/actionBtn.tpl' btnClasses='modeLink' btnHref='#advancedSearchFieldset' btnLabel={'advanced'|gettext} btnId='advancedSearchLink'*}
                <a class="modeLink" id="advancedSearchLink" href="#advancedSearchFieldset">{t}avanced{/t}</a>    
                <legend>
                    <span>{t}simple search{/t}</span>
                </legend>
                {include file='common/formFields/common/search.tpl' name='searchQuery' label={'search for'|gettext} placeholder={'Ex: Sector code, Client name, Technician name, Crash kind ...'|gettext} value={$search.query|default:''}}
                {*<input type="hidden" name="searchResources[]" value="{if $current.resource}{$current.resource}{/if}" />*}
                <input type="hidden" name="method" value="search" />
                {*<input type="submit" value="validate (TODO remove)" />*}
            </fieldset>
    	</form>
	
        {if $search.totalResults && $search.type === 'global'}
        {html5 tag='section' class='searchResultsBlock adminSearchResultsBlock' id='adminSearchResultsBlock'}
            {html5 tag='header' class='titleBlock'}
                <h3>{t}results{/t}</h3>
            {/html5}
            {foreach $search.groups as $groupName => $group}
            {$groupResource=$group.resource}
            {$displayField=$resources[$groupResource].defaultNameField}
            <div class="resultsGroup">
                <h4 class="title"><a href="{$smarty.const._URL_ADMIN}{$groupResource}?method=search&amp;searchQuery={$search.query}">{$groupName}</a></h4>
                <span class="count">{$group.count|default:0}</span>
                {if $group.results}
                <ul class="results">
                    {foreach $group.results as $result}
                    <li>
                        <a href="{$smarty.const._URL_ADMIN}{$groupResource}/{$result.id}">{$result[$displayField]|default:$result.admin_title|default:$result.id}</a>
                    </li>
                    {/foreach}
                </ul>
                {/if}
            </div>
            {/foreach}
        {/html5}
        {/if}
	
	</div>
	
{/html5}
{/if}