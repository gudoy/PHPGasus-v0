{if !is_array($data.total[$resourceName]) && !empty($data.total[$resourceName])}
{$nbOfItemsPerPage=$data.current.limit|default:$smarty.const._ADMIN_RESOURCES_NB_PER_PAGE}
{$nbOfPages=ceil($data.total[$resourceName]/$nbOfItemsPerPage)}
{$currentPage=($data.current.offset/$nbOfItemsPerPage)+1}
<a
    id="{$vPosition}PaginationFirstLink"
    class="action actionBtn page first paginationLink firstLink {if $currentPage <= 1}disabled{/if}"
    {if $currentPage > 1}
    {$data.current.urlParams.offset=0}
    {$newPageURL={$curURL|regex_replace:'/(.*)\?(.*)$/U':'$1'}|cat:'?'|cat:{http_build_query($data.current.urlParams)}}
    href="{$newPageURL}"
    title="{t}Go to first page{/t}"
    {/if}
    >
    <span class="label value">{t}<< First{/t}</span>
</a>
<a
    id="{$vPosition}PaginationLastLink"
    class="action actionBtn page last paginationLink lastLink {if $currentPage >= $nbOfPages}disabled{/if}"
    {if $currentPage < $nbOfPages}
    {$newOffset=($nbOfPages-1)*$nbOfItemsPerPage}
    {$data.current.urlParams.offset=$newOffset}
    {$newPageURL={$curURL|regex_replace:'/(.*)\?(.*)$/U':'$1'}|cat:'?'|cat:{http_build_query($data.current.urlParams)}}
    href="{$newPageURL}"
    title="{t}Go to last page{/t}"
    {/if}>
    <span class="label value">{t}Last >>{/t}</span>
</a>
{/if}