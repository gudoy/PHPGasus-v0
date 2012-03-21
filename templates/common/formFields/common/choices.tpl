{strip}
{* Accepted params values: $label, $value, $name, $resourceSingular, $choices, [$selected], [$required] *}

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
		<span class="label" for="{$postValName}">{$label|default:$postValName}{if $required}<span class="required">*</span>{/if}</span>
	</div>
	<div class="fieldBlock">
{/if}
		{foreach $choices as $k => $v}
		<input class="multi {$class} {if $required}check-required{/if}" name="{$postValName}" id="{$postValName}{$v}" {if $required}required="required"{/if} type="radio" value="{$v}" {if $selected === $v || $smarty.post[$postValName] === $v}checked="checked"{/if} />
		<label class="span multi">{$v}</label>
		{/foreach}
{if !isset($inputOnly) || !$inputOnly}
	</div>
</div>
{/if}