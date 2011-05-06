{$nbOfItemsPerPage 	= $data.current.limit|default:$smarty.const._ADMIN_RESOURCES_NB_PER_PAGE}
{$nbOfPages 		= ceil($data.total[$resourceName]/$nbOfItemsPerPage)}
{$currentPage 		= ($data.current.offset/$nbOfItemsPerPage)+1}
<a
    id="{$vPosition}PaginationFirstLink"
    class="action page first paginationLink firstLink {if $currentPage <= 1}disabled{/if}"
    {if $currentPage > 1}
    {$data.current.urlParams.offset=0}
    {$newPageURL = {$curURL|regex_replace:'/(.*)\?(.*)$/U':'$1'}|cat:'?'|cat:{http_build_query($data.current.urlParams)}}
    href="{$newPageURL}"
    title="{t}Go to first page{/t}"
    {/if}
    >
    <span class="value">&lt;&lt; {t}first{/t}</span>
</a>
<a
    id="{$vPosition}PaginationPrevLink"
    class="action page prev paginationLink prevLink {if $currentPage <= 1}disabled{/if}"
    {if $currentPage > 1}
    {$newOffset=($currentPage-2)*$nbOfItemsPerPage}
    {$data.current.urlParams.offset=$newOffset}
    {$newPageURL={$curURL|regex_replace:'/(.*)\?(.*)$/U':'$1'}|cat:'?'|cat:{http_build_query($data.current.urlParams)}}
    href="{$newPageURL}"
    title="{t}Go to first previous page (page {$currentPage-1}){/t}"
    {/if}
    >
    <span class="value">&lt; {t}previous{/t}</span>
</a>
{$data.current.urlParams.offset=null}
{$newPageURL={$curURL|regex_replace:'/(.*)\?(.*)$/U':'$1'}|cat:'?'|cat:{http_build_query($data.current.urlParams)}}
    <fieldset>
        <label class="accessib" for="pageOffset{$position|ucfirst}">{t}page #{/t}</label>
        <input id="pageNumber{$position|ucfirst}" name="page" type="number" class="sized pageNb" size="4" min="1" step="1" value="{$currentPage|default:1}" formmethod="get" />
        <span class="value">/ {$nbOfPages}</span>
        {* include file='common/blocks/actionBtn.tpl' mode='button' classes='action validateBtn goToPageBtn' id='goToPageBtn' type='submit' label='Ok'|gettext *}
    </fieldset>
<a
    id="{$vPosition}PaginationNextLink"
    class="action page next paginationLink nextLink {if $currentPage >= $nbOfPages}disabled{/if}"
    {if $currentPage < $nbOfPages}
    {$newOffset=$currentPage*$nbOfItemsPerPage}
    {$data.current.urlParams.offset=$newOffset}
    {$newPageURL={$curURL|regex_replace:'/(.*)\?(.*)$/U':'$1'}|cat:'?'|cat:{http_build_query($data.current.urlParams)}}
    href="{$newPageURL}"
    title="{t}Go to next page (page {$currentPage+1}){/t}"
    {/if}>
    <span class="value">{t}next{/t} &gt;</span>
</a>
<a
    id="{$vPosition}PaginationLastLink"
    class="action page last paginationLink lastLink {if $currentPage >= $nbOfPages}disabled{/if}"
    {if $currentPage < $nbOfPages}
    {$newOffset=($nbOfPages-1)*$nbOfItemsPerPage}
    {$data.current.urlParams.offset=$newOffset}
    {$newPageURL={$curURL|regex_replace:'/(.*)\?(.*)$/U':'$1'}|cat:'?'|cat:{http_build_query($data.current.urlParams)}}
    href="{$newPageURL}"
    title="{t}Go to last page{/t}"
    {/if}>
    <span class="value">{t}last{/t} &gt;&gt;</span>
</a>