<div class="box adminBlock adminRetrieveBlock" id="admin{$resourceName|capitalize}RetrieveBlock"> 
	
	<div class="titleBlock">
		<h2>
			<span class="{$resourceName}" id="resourceName">
				{$data.meta.displayName} - {$resourceId}
			</span>
			<span class="{$data.meta.singular}" id="resourceSingular">&nbsp;</span>
		</h2>
		<span class="actionsBlock">
			{include file='common/blocks/admin/resource/actions/actions.tpl'}
		</span>
	</div>
	
	{include file='common/blocks/admin/pagination/index.tpl' adminView='retrieve'}
	
	<div class="adminResourceDetailBlock" id="admin{$resourceName|capitalize}DetailBlock">		
		{include file='common/blocks/admin/resource/resourceDetail.tpl'}
	</div>
	
</div>

{*
{if !$data.options.viewType || $data.options.viewType !== 'bubble'} 
{include file='common/blocks/admin/resource/retrieve/related.tpl'}
{/if}
*}