{strip}
{* Accepted params values: $label, $resourceSingular, [$required], [$rows], [$cols] *}

{$resourceSingular=$resourceSingular|default:''}

{if $resourceSingular !== ''}{$secondPart=$label|ucfirst}{else}{$secondPart=$label}{/if}

{if $mode=='api'}
	{$postValName=$label}
{else}
	{$postValName=$resourceSingular|cat:$secondPart}
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
		<label class="span" for="{$postValName}">{$label}{if $required}<span class="required">*</span>{/if}</label>
	</div>
	<div class="fieldBlock">
{/if}
		<textarea class="normal {if $required}check-required{/if}" name="{$postValName}" id="{$postValName}" {if $placeholder}placeholder="{$placeholder}"{/if} {if $required}required="required"{/if} cols="{$cols|default:80}" rows="{$rows|default:4}">{$postValue|stripslashes|trim}</textarea>
{if !isset($inputOnly) || !$inputOnly}
	</div>
</div>
{/if}