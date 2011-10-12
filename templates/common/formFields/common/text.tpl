{strip}
{* Accepted params values: $label, $value, $name, [$resourceSingular], [$required], [$inputOnly], [autocomplete] *}

{$resourceSingular=$resourceSingular|default:''}
{if $resourceSingular !== ''}{$secondPart=$name|default:$label|ucfirst}{else}{$secondPart=$name|default:$label}{/if}

{if $mode=='api'}
	{$postValName=$label}
{else}
	{$postValName=$resourceSingular|cat:$secondPart}
{/if}

{/strip}
{if !isset($inputOnly) || !$inputOnly}
<div class="line">
	<div class="labelBlock">
		<label for="{$postValName}">{$label|default:$postValName}{if $required}<span class="required">*</span>{/if}</label>
	</div>
	<div class="fieldBlock">
{/if}
		{if empty($type) || !in_array($type, array('email','phone','url','search','password','datetime','date','time'))}{$type='text'}{/if}
		<input type="{$type}" class="normal{if $required} check-required{/if}" name="{$postValName}" id="{$postValName}"{if $type !== 'password' && $value || $smarty.post[$postValName]} value="{$value|default:{$smarty.post[$postValName]|escape:'html'}}"{/if}{if $required} required="required"{/if}{if $placeholder} placeholder="{$placeholder}"{/if}{if $autofocus} autofocus="autofocus"{/if}{if $disabled} disabled="disabled"{/if} autocomplete="{if $autocomplete == false}off{else}on{/if}" />
{if !isset($inputOnly) || !$inputOnly}
	</div>
</div>
{/if}