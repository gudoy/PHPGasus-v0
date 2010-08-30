{if !is_array($data.total[$resourceName]) && !empty($data.total[$resourceName])}
{assign var='nbOfItemsPerPage' value=$data.current.limit|default:$smarty.const._ADMIN_RESOURCES_NB_PER_PAGE}
{math assign='nbOfPages' equation="ceil(x/y)" x=$data.total[$resourceName] y=$nbOfItemsPerPage}
{math assign='currentPage' equation="x/y+1" x=$data.current.offset y=$nbOfItemsPerPage}
{if $nbOfPages > 1}
<div class="paginationBlock" id="{$vPosition}PaginationBlock">
	<a
		id="{$vPosition}PaginationFirstLink"
		class="paginationlink firstLink {if $currentPage <= 1}disabled{/if}"
		{if $currentPage > 1}
		href="{$data.meta.fullAdminPath}?offset=0&amp;limit={$nbOfItemsPerPage}{if $smarty.get.sortBy}&amp;sortBy={$smarty.get.sortBy}{/if}{if $smarty.get.orderBy}&amp;orderBy={$smarty.get.orderBy}{/if}{$filteringParams}"
		title="{t}Go to first page{/t}"
		{/if}
		>
		<span class="label">{t}<< First{/t}</span>
	</a>
	<a
		id="{$vPosition}PaginationPrevLink"
		class="paginationlink prevLink {if $currentPage <= 1}disabled{/if}"
		{if $currentPage > 1}
		href="{$data.meta.fullAdminPath}{$resourceName}?offset={math equation="(x-2)*y" x=$currentPage y=$nbOfItemsPerPage}&amp;limit={$nbOfItemsPerPage}{if $smarty.get.sortBy}&amp;sortBy={$smarty.get.sortBy}{/if}{if $smarty.get.orderBy}&amp;orderBy={$smarty.get.orderBy}{/if}{$filteringParams}"
		title="{t}Go to first previous page (page {$currentPage-1}){/t}"
		{/if}
		>
		<span class="label">{t}< Previous{/t}</span>
	</a>
	<ul class="nav linksList paginationList">
		{assign var='beforeHellipDisplayed' value=false}
		{assign var='middleHellipDisplayed' value=false}
		{assign var='afterHellipDisplayed' value=false}
		
		{section name='pageNb' start=1 loop=$nbOfPages+1}
		{assign var='displayPageLink' value=false}
		{if $nbOfPages < 10
			|| ($smarty.section.pageNb.index <= 3 || $smarty.section.pageNb.index >= $nbOfPages - 2)
			|| ($smarty.section.pageNb.index <= 6 && $currentPage <= 6)
			|| ($smarty.section.pageNb.index >= $nbOfPages - 5 && $currentPage >= $nbOfPages - 5)
			|| ($currentPage > 6 && $currentPage < $nbOfPages - 5 && $smarty.section.pageNb.index > $currentPage - 3 && $smarty.section.pageNb.index < $currentPage + 3)}
			{assign var='displayPageLink' value=true}
		{/if}
		
		{if $displayPageLink === true}
		<li class="item pageNbItem {if $smarty.section.pageNb.index == $currentPage}current{/if}">
			<a href="{$data.meta.fullAdminPath}?offset={math equation="(x-1)*y" x=$smarty.section.pageNb.index y=$nbOfItemsPerPage}&amp;limit={$nbOfItemsPerPage}{if $smarty.get.sortBy}&amp;sortBy={$smarty.get.sortBy}{/if}{if $smarty.get.orderBy}&amp;orderBy={$smarty.get.orderBy}{/if}{$filteringParams}">
				{$smarty.section.pageNb.index}
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
		class="paginationlink nextLink {if $currentPage >= $nbOfPages}disabled{/if}"
		{if $currentPage < $nbOfPages}
		href="{$data.meta.fullAdminPath}?offset={math equation="x*y" x=$currentPage y=$nbOfItemsPerPage}&amp;limit={$nbOfItemsPerPage}{if $smarty.get.sortBy}&amp;sortBy={$smarty.get.sortBy}{/if}{if $smarty.get.orderBy}&amp;orderBy={$smarty.get.orderBy}{/if}{$filteringParams}"
		title="{t}Go to next page (page {$currentPage+1}){/t}"
		{/if}>
		<span class="label">{t}Next >{/t}</span>
	</a>
	<a
		id="{$vPosition}PaginationLastLink"
		class="paginationlink lastLink {if $currentPage >= $nbOfPages}disabled{/if}"
		{if $currentPage < $nbOfPages}
		href="{$data.meta.fullAdminPath}?offset={math equation="(x-1)*y" x=$nbOfPages y=$nbOfItemsPerPage}&amp;limit={$nbOfItemsPerPage}{if $smarty.get.sortBy}&amp;sortBy={$smarty.get.sortBy}{/if}{if $smarty.get.orderBy}&amp;orderBy={$smarty.get.orderBy}{/if}{$filteringParams}"
		title="{t}Go to last page{/t}"
		{/if}>
		<span class="label">{t}Last >>{/t}</span>
	</a>
</div>
{/if}
{/if}
{if $adminView == 'create' || $adminView == 'retrieve' || $adminView == 'update'}
	<div class="paginationBlock" id="paginationBlock">
		{$prevId = $data.pagination.prev}
		{$nextId = $data.pagination.next}
		<a rel="{$prevId}" id="topPaginationPrevLink" class="paginationLink prevLink {if empty($prevId)}disabled{/if}" {if !empty($prevId)}href="{$data.meta.fullAdminPath}{$prevId}?method={$adminView}"{/if}>
			<span class="label">{t}prev{/t}</span>
		</a>
		<a rel="{$nextId}" id="topPaginationNextLink" class="paginationLink nextLink {if empty($nextId)}disabled{/if}" {if !empty($nextId)}href="{$data.meta.fullAdminPath}{$nextId}?method={$adminView}"{/if}>
			<span class="label">{t}next{/t}</span>
		</a>
	</div>
{/if}