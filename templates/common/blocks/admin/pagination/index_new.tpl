{if !is_array($data.total[$resourceName]) && !empty($data.total[$resourceName])}
{$nbOfItemsPerPage=$data.current.limit|default:$smarty.const._ADMIN_RESOURCES_NB_PER_PAGE}
{$nbOfPages=ceil($data.total[$resourceName]/$nbOfItemsPerPage)}
{$currentPage=($data.current.offset/$nbOfItemsPerPage)+1}
<a
    id="{$vPosition}PaginationFirstLink"
    class="paginationLink firstLink {if $currentPage <= 1}disabled{/if}"
    {if $currentPage > 1}
    {$data.current.urlParams.offset=0}
    {$newPageURL={$curURL|regex_replace:'/(.*)\?(.*)$/U':'$1'}|cat:'?'|cat:{http_build_query($data.current.urlParams)}}
    href="{$newPageURL}"
    title="{t}Go to first page{/t}"
    {/if}
    >
    <span class="label">{t}<< First{/t}</span>
</a>
<a
    id="{$vPosition}PaginationPrevLink"
    class="paginationLink prevLink {if $currentPage <= 1}disabled{/if}"
    {if $currentPage > 1}
    {$newOffset=($currentPage-2)*$nbOfItemsPerPage}
    {$data.current.urlParams.offset=$newOffset}
    {$newPageURL={$curURL|regex_replace:'/(.*)\?(.*)$/U':'$1'}|cat:'?'|cat:{http_build_query($data.current.urlParams)}}
    href="{$newPageURL}"
    title="{t}Go to first previous page (page {$currentPage-1}){/t}"
    {/if}
    >
    <span class="label">{t}< Previous{/t}</span>
</a>
{$data.current.urlParams.offset=null}
{$newPageURL={$curURL|regex_replace:'/(.*)\?(.*)$/U':'$1'}|cat:'?'|cat:{http_build_query($data.current.urlParams)}}
<form action="{$newPageURL}" method="get">
    <fieldset>
        <label class="accessib" for="pageOffset{$position|ucfirst}">{t}page #{/t}</label>
        <input id="pageNumber{$position|ucfirst}" name="page" type="number" class="sized" size="4" min="1" step="1" value="{$currentPage|default:1}" />
        <span class="value">/ {$nbOfPages}</span>
    </fieldset>
</form>
<a
    id="{$vPosition}PaginationNextLink"
    class="paginationLink nextLink {if $currentPage >= $nbOfPages}disabled{/if}"
    {if $currentPage < $nbOfPages}
    {$newOffset=$currentPage*$nbOfItemsPerPage}
    {$data.current.urlParams.offset=$newOffset}
    {$newPageURL={$curURL|regex_replace:'/(.*)\?(.*)$/U':'$1'}|cat:'?'|cat:{http_build_query($data.current.urlParams)}}
    href="{$newPageURL}"
    title="{t}Go to next page (page {$currentPage+1}){/t}"
    {/if}>
    <span class="label">{t}Next >{/t}</span>
</a>
<a
    id="{$vPosition}PaginationLastLink"
    class="paginationLink lastLink {if $currentPage >= $nbOfPages}disabled{/if}"
    {if $currentPage < $nbOfPages}
    {$newOffset=($nbOfPages-1)*$nbOfItemsPerPage}
    {$data.current.urlParams.offset=$newOffset}
    {$newPageURL={$curURL|regex_replace:'/(.*)\?(.*)$/U':'$1'}|cat:'?'|cat:{http_build_query($data.current.urlParams)}}
    href="{$newPageURL}"
    title="{t}Go to last page{/t}"
    {/if}>
    <span class="label">{t}Last >>{/t}</span>
</a>
{/if}