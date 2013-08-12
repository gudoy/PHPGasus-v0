{if count($items)}
{foreach $items as $item}
	{include file='common/blocks/api/resource/retrieve.tpl' items=$item}
{/foreach}
{else}
{/if}