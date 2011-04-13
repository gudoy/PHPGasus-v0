{if !is_array($data.total[$resourceName]) && !empty($data.total[$resourceName])}
{$nbOfItemsPerPage=$data.current.limit|default:$smarty.const._ADMIN_RESOURCES_NB_PER_PAGE}
{math assign='nbOfPages' equation="ceil(x/y)" x=$data.total[$resourceName] y=$nbOfItemsPerPage}
{math assign='currentPage' equation="x/y+1" x=$data.current.offset y=$nbOfItemsPerPage}
{if $nbOfPages > 1}
<div class="actions paginationBlock" id="{$vPosition}PaginationBlock">
	<a
		id="{$vPosition}PaginationFirstLink"
		class="action page first paginationLink firstLink {if $currentPage <= 1}disabled{/if}"
		{if $currentPage > 1}
        {$data.current.urlParams.offset=0}
        {$newPageURL={$curURL|regex_replace:'/(.*)\?(.*)/U':'$1'}|cat:'?'|cat:{http_build_query($data.current.urlParams)}}
		href="{$newPageURL}"
		title="{t}Go to first page{/t}"
		{/if}
		>
		<span class="value label">{t}<< First{/t}</span>
	</a>
	<a
		id="{$vPosition}PaginationPrevLink"
		class="action page prev paginationLink prevLink {if $currentPage <= 1}disabled{/if}"
		{if $currentPage > 1}
		href="{$curURLbase}?offset={math equation="(x-2)*y" x=$currentPage y=$nbOfItemsPerPage}&amp;limit={$nbOfItemsPerPage}{if $smarty.get.sortBy}&amp;sortBy={$smarty.get.sortBy}{/if}{if $smarty.get.orderBy}&amp;orderBy={$smarty.get.orderBy}{/if}{$filteringParams}"
		title="{t}Go to first previous page (page {$currentPage-1}){/t}"
		{/if}
		>
		<span class="value label">{t}< Previous{/t}</span>
	</a>
	<ul class="nav linksList paginationList">
		{$beforeHellipDisplayed=false}
		{$middleHellipDisplayed=false}
		{$afterHellipDisplayed=false}
		
		{section name='pageNb' start=1 loop=$nbOfPages+1}
		{$displayPageLink=false}
		{if $nbOfPages < 10
			|| ($smarty.section.pageNb.index <= 3 || $smarty.section.pageNb.index >= $nbOfPages - 2)
			|| ($smarty.section.pageNb.index <= 6 && $currentPage <= 6)
			|| ($smarty.section.pageNb.index >= $nbOfPages - 5 && $currentPage >= $nbOfPages - 5)
			|| ($currentPage > 6 && $currentPage < $nbOfPages - 5 && $smarty.section.pageNb.index > $currentPage - 3 && $smarty.section.pageNb.index < $currentPage + 3)}
			{assign var='displayPageLink' value=true}
		{/if}
		
		{if $displayPageLink === true}
        {if strpos($curURL,'?') !== false}{$linker='&amp;'}{else}{$linker='?'}{/if}
        {$newOffset=($smarty.section.pageNb.index-1)*$nbOfItemsPerPage}
        {$data.current.urlParams.offset=$newOffset}
        {$newPageURL={$curURL|regex_replace:'/(.*)\?(.*)/U':'$1'}|cat:'?'|cat:{http_build_query($data.current.urlParams)}}
		<li class="item pageNbItem {if $smarty.section.pageNb.index == $currentPage}current{/if}">
			<a class="action" href="{$newPageURL}">
				<span class="value">{$smarty.section.pageNb.index}</span>
			</a>
		</li>
		{else}
			{if $currentPage > 6 && $currentPage < $nbOfPages - 5}
				{if $smarty.section.pageNb.index < $currentPage}
					{if !$beforeHellipDisplayed}<li class="hellip">...</li>{assign var='beforeHellipDisplayed' value=true}{/if}
				{elseif $smarty.section.pageNb.index > $currentPage}
					{if !$afterHellipDisplayed}<li class="hellip">...</li>{assign var='afterHellipDisplayed' value=true}{/if}
				{/if}
			{else}
				{if !$middleHellipDisplayed}<li class="hellip">...</li>{assign var='middleHellipDisplayed' value=true}{/if}
			{/if}
		{/if}
		{/section}
	</ul>
	<a
		id="{$vPosition}PaginationNextLink"
		class="action page next paginationLink nextLink {if $currentPage >= $nbOfPages}disabled{/if}"
		{if $currentPage < $nbOfPages}
		href="{$curURLbase}offset={math equation="x*y" x=$currentPage y=$nbOfItemsPerPage}&amp;limit={$nbOfItemsPerPage}{if $smarty.get.sortBy}&amp;sortBy={$smarty.get.sortBy}{/if}{if $smarty.get.orderBy}&amp;orderBy={$smarty.get.orderBy}{/if}{$filteringParams}"
		title="{t}Go to next page (page {$currentPage+1}){/t}"
		{/if}>
		<span class="value label">{t}Next >{/t}</span>
	</a>
	<a
		id="{$vPosition}PaginationLastLink"
		class="action last paginationLink lastLink {if $currentPage >= $nbOfPages}disabled{/if}"
		{if $currentPage < $nbOfPages}
		href="{$curURLbase}offset={math equation="(x-1)*y" x=$nbOfPages y=$nbOfItemsPerPage}&amp;limit={$nbOfItemsPerPage}{if $smarty.get.sortBy}&amp;sortBy={$smarty.get.sortBy}{/if}{if $smarty.get.orderBy}&amp;orderBy={$smarty.get.orderBy}{/if}{$filteringParams}"
		title="{t}Go to last page{/t}"
		{/if}>
		<span class="value label">{t}Last >>{/t}</span>
	</a>
</div>
{/if}
{/if}
{if in_array($adminView, array('create','retrieve','update'))}
	<div class="actions page paginationBlock" id="paginationBlock">
		{$prevId = $data.pagination.prev}
		{$nextId = $data.pagination.next}
		<a rel="{$prevId}" id="topPaginationPrevLink" class="action page prev paginationLink prevLink {if empty($prevId)}disabled{/if}" {if !empty($prevId)}href="{$data.meta.fullAdminPath}{$prevId}{if $adminView == 'retrieve'}{else}?method={$adminView}{/if}"{/if}>
			<span class="value label">{t}prev{/t}</span>
		</a>
		<a rel="{$nextId}" id="topPaginationNextLink" class="action page next paginationLink nextLink {if empty($nextId)}disabled{/if}" {if !empty($nextId)}href="{$data.meta.fullAdminPath}{$nextId}{if $adminView == 'retrieve'}{else}?method={$adminView}{/if}"{/if}>
			<span class="value label">{t}next{/t}</span>
		</a>
	</div>
{/if}