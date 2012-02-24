{if in_array($adminView, array('create','retrieve','update'))}
<div class="group group paginationButtons">
	<div class="actions page paginationBlock" id="paginationBlock">
		{$prevId = $data.pagination.prev}
		{$nextId = $data.pagination.next}
		<a rel="{$prevId}" id="topPaginationPrevLink" class="action page prev paginationLink prevLink {if empty($prevId)}disabled{/if}" {if !empty($prevId)}href="{$marty.const._URL_ADMIN}{$resourceName}/{$prevId}{if !$adminView == 'retrieve'}?method={$adminView}{/if}"{/if}>
			<span class="value label">{t}prev{/t}</span>
		</a>
		<a rel="{$nextId}" id="topPaginationNextLink" class="action page next paginationLink nextLink {if empty($nextId)}disabled{/if}" {if !empty($nextId)}href="{$marty.const._URL_ADMIN}{$resourceName}/{$nextId}{if !$adminView == 'retrieve'}?method={$adminView}{/if}"{/if}>
			<span class="value label">{t}next{/t}</span>
		</a>
	</div>
</div>
{/if}