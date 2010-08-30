{$currentUser=$data.current.user}
{if $resourceName === 'users'}
	{if $currentUser.auth_level_nb > $resource['auth_level_nb']}{$hasHigherAuth=true}{/if}
{/if}
{if $currentUser.id === $resource['id'] || ( $currentUser.auth_level_nb >= 500 && $hasHigherAuth)}
	{$allowEdit=true}
{else}
	{$allowEdit=false}
{/if}
<input {strip}
	type="password" 
	name="{$resourceFieldName}{$useArray}" 
	id="{$resourceFieldName}{$itemIndex}"  
	class="sized"
	value=""
	disabled="disabled"{/strip} />
{if $allowEdit}
{include file='common/blocks/actionBtn.tpl' mode='button' btnClasses="changeValBtn changePassBtn" btnId={'change'|cat:{$resourceFieldName|ucfirst}} btnLabel='change'|gettext}
{/if}