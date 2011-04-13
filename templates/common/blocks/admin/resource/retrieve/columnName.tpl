{strip}
{if $colProps.type === 'onetoone' || $colProps.fk}
	{if $isAdminView}
		{$colName}
	{else}
		{$data._resources[$colProps.relResource].singular|default:$colProps.relResource|default:$colProps}
	{/if}
{elseif $colProps.type === 'onetomany'}
{* TODO: display related resource name + value => count of related items + toggler on grid of related items*}
{else}
{$colName}
{/if}
{/strip}