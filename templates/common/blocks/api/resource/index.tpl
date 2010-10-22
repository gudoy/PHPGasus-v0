{if count($items)}
{foreach $items as $key => $val}
	<dt class="key">{$key}</dt>
	<dd class="value">
	{if is_array($val)}
		{include file='common/blocks/api/resource/retrieve.tpl' items=$val}
	{else}
		{$val|regex_replace:'/&([^#]|$)/':'$1&amp;$2'}
	{/if}
	</dd>
{/foreach}
</dl>
{else}
{/if}