{foreach $data[$data.view.resourceName] as $item}
{include file='common/pages/api/resource/retrieve.tpl' items=$item}
{foreachelse}
<p>
{t}Sorry, there's currently no items for this resource{/t}
</p>
{/foreach}