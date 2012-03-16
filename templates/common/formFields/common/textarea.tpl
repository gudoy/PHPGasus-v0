{* Accepted params values: $label, $resourceSingular, [$required], [$rows], [$cols] *}
{$resourceSingular=$resourceSingular|default:''}
{if $resourceSingular !== ''}{$secondPart=$label|ucfirst}{else}{$secondPart=$label}{/if}
{if $mode=='api'}
	{$postValName=$label}
{else}
	{$postValName=$resourceSingular|cat:$secondPart}
{/if}
<div class="line">
	<div class="labelBlock">
		<label class="span" for="{$postValName}">{$label}{if $required}<span class="required">*</span>{/if}</label>
	</div>
	<div class="fieldBlock">
		<textarea class="normal {if $required}check-required{/if}" name="{$postValName}" id="{$postValName}" {if $placeholder}placeholder="{$placeholder}"{/if} {if $required}required="required"{/if} cols="{$cols|default:80}" rows="{$rows|default:4}">{$smarty.post[$postValName]|stripslashes|trim}</textarea>
	</div>
</div>