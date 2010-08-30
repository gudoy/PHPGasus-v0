{if count($items)}
{foreach $items as $key => $val}
	<span class="key">{$key}</span>:<span class="value">{$val|regex_replace:'/&([^#]|$)/':'$1&amp;$2'}</span><br/>
{/foreach}
{else}
{/if}