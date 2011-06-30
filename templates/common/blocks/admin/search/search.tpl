{$search		= $data.search}
{$current		= $data.current}
{$type 			= $search.type|default:'contextual'}
<section class="searchBlock adminSearchBlock" id="adminSearchBlock">

    <header class="titleBlock">
        <h2 class="title">{t}search{/t}</h2>
    </header>
    
    <div class="contentBlock">
    
        <form action="{if $current.resource}{$smarty.const._URL_ADMIN}{$data.current.resource}{else}{$smarty.const._URL_ADMIN_SEARCH}{/if}" method="get" class="commonForm searchForm adminSearchForm" id="adminSearchForm">
            <fieldset id="simpleSearchFieldset">    
                <legend>
                    <span>{t}simple search{/t}</span>
                </legend>
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