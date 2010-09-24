{* Accepted params values: $label, $resourceSingular, [$required] *}
{$resourceSingular=$resourceSingular|default:''}
{if $resourceSingular !== ''}{$secondPart=$name|default:$label|ucfirst}{else}{$secondPart=$name|default:$label}{/if}
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
		{if empty($type) || !in_array($type, array('email','phone','url')) || !$html5}{$type='text'}{/if}
		<input type="{$type}" class="normal {if $required}check-required{/if}" name="{$postValName}" id="{$postValName}" value="{$smarty.post[$postValName]}" {if $required && $html}required="required"{/if} />
	</div>
</div>