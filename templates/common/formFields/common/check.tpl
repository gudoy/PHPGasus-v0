{strip}
{* Accepted params values: $label, $value, $name, $resourceSingular, [$checked], [$required] *}

{$resourceSingular = $resourceSingular|default:''}

{if $resourceSingular !== ''}{$secondPart=$name|default:$label|ucfirst}{else}{$secondPart=$name|default:$label}{/if}

{if $mode === 'api'}
	{$postValName = $label}
{else}
	{$postValName = $resourceSingular|cat:$secondPart}
{/if}

{* Handle case where the post value use arrays *}
{$postValue = $smarty.post[$postValName]}
{* BUGGY *}
{* if strpos($postValName, '[') !== false}
{$tmp = "smarty.post{preg_replace('/^(.*)\[(.*)$/U', "['$1'][$2", str_replace(array("[","]"), array("['","']"), $postValName))}"}
{$postValue={${$tmp}}}
{/if *}

{/strip}
{if !isset($inputOnly) || !$inputOnly}
<div class="line" id="{$postValName|replace:'[]':''}Line">
	<div class="labelBlock">
		<label class="span" for="{$postValName}">{$label|default:$postValName}{if $required}<span class="required">*</span>{/if}</label>
	</div>
	<div class="fieldBlock">
{/if}
		<input class="multi {$class} {if $required}check-required{/if}" name="{$postValName}" id="{$postValName}" {if $required}required="required"{/if} type="checkbox"  {if $value}value="{$value}"{/if} {if $postValue === $value || $checked}checked="checked"{/if} />
		{if $placeholder}<span class="label">{$placeholder}</span>{/if}
{if !isset($inputOnly) || !$inputOnly}
	</div>
</div>
{/if}