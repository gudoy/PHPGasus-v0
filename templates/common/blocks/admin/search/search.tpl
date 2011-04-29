{$search		= $data.search}
{$current		= $data.current}
{$type 			= $search.type|default:'contextual'}
<a id="searchToggler" href="#adminSearchBlock"><span class="label">{t}toggle search{/t}</span></a>
<section class="searchBlock adminSearchBlock" id="adminSearchBlock">

    <header class="titleBlock">
        <h2 class="title">{t}search{/t}</h2>
    </header>
    
    <div class="contentBlock">
    
        <form action="{if $current.resource}{$smarty.const._URL_ADMIN}{$data.current.resource}{else}{$smarty.const._URL_ADMIN_SEARCH}{/if}" method="get" class="commonForm searchForm adminSearchForm" id="adminSearchForm">
           {*
            <fieldset id="advancedSearchFieldset">
                <legend>
                    <span>{t}advanced search{/t}</span>
                </legend>
				<a class="modeLink" id="simpleSearchLink" href="#simpleSearchFieldset">{t}simple{/t}</a>
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
                <legend>
                    <span>{t}simple search{/t}</span>
                </legend>
                <a class="modeLink" id="advancedSearchLink" href="#advancedSearchFieldset">{t}avanced{/t}</a>
                <div class="line">
                    <div class="labelBlock">
                        <label class="span">{'search'|gettext}</label>
                    </div>
                    <div class="fieldBlock" id="searchQueryFieldBlock">
                    	{if $type === 'contextual'}
                    		{$searchableRes=$data._resources[$current.resource].displayName|default:$current.resource}
                        {else}
                        	{$canSearch 	= $data.current.user.auths.__can_search|default:[]}
                        	{$searchableRes = join(', ', $canSearch)}
                        {/if}
                        {include file='common/formFields/common/search.tpl' name='searchQuery' label={'search for'|gettext} placeholder={$searchableRes} value={$search.query|default:''} inputOnly=true}
                        <input type="submit" id="validateSearchBtn" value="{t}go{/t}" />
                    </div>
                </div>
                <input type="hidden" name="method" value="search" />
            </fieldset>
        </form>
        
        {*if $search.type === 'contextual'}
        {block name='contextualSearchResults'}
        {include file='specific/blocks/admin/search/results.tpl'}
        {/block}
        {/if*}
    
    </div>
    
</section>