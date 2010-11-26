{*
{$metas=$data.metas}
{$groupResources=$data.current.groupResources}
<div class="block box section adminSideNavBlock" id="adminSideNavBlock">
	
	<h2>{t}Managable Resources{/t}</h2>
	
	{if count($groupResources)}
	{include file='common/blocks/admin/nav/groupLevel.tpl' level=1 items=$groupResources}
	{else}
		{t}There's no resource here for the moment.{/t}
	{/if}
</div>
*}
<div class="block box section adminSideNavBlock" id="adminSideNavBlock">
    
    <h2>{t}Managable Resources{/t}</h2>
    
    {if count($groupResources)}
    {include file='common/blocks/admin/nav/mainNAv_new.tpl'}
    {else}
        {t}There's no resource here for the moment.{/t}
    {/if}
</div>