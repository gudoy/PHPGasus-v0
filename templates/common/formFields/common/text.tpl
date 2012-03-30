{strip}
{* Accepted params values: $label, $value, $name, [$resourceSingular], [$pattern] [$required], [$inputOnly], [$autocomplete], [$hint] *}

{$resourceSingular = $resourceSingular|default:''}

{if $resourceSingular !== ''}{$secondPart = $name|default:$label|ucfirst}{else}{$secondPart = $name|default:$label}{/if}

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

{if empty($type) || !in_array($type, array('email','phone','url','search','password','datetime','date','time'))}{$type='text'}{/if}
{$isDatetimeType = in_array($type, array('date','datetime'))}

{/strip}
{if !isset($inputOnly) || !$inputOnly}
<div class="line {if $isDatetimeType}type{$type|ucfirst}{else}typeVarchar{/if}" id="{$postValName|replace:'[]':''}Line">
	<div class="labelBlock">
		<label class="span" for="{$postValName}">{strip}{$label|default:$postValName}{if $required}{/strip}<span class="required">*</span>{/if}</label>
	</div>
	<div class="fieldBlock">
{/if}
		{if $isDatetimeType}<span class="icon inputIcon"></span>{/if}
		<input type="{$type}" class="normal{if $required} check-required{/if}" name="{$postValName}" id="{$postValName}"{if $type !== 'password' && $value || $smarty.post[$postValName]} value="{$value|default:{$smarty.post[$postValName]|escape:'html'}}"{/if}{if $pattern} pattern="{trim($pattern, '\/')}"{/if}{if $required} required="required"{/if}{if $placeholder} placeholder="{$placeholder}"{/if}{if $autofocus} autofocus="autofocus"{/if}{if $disabled} disabled="disabled"{/if} autocomplete="{if $autocomplete == false}off{else}on{/if}" />
		{if $hint}
			<small class="hint"><span class="key">{t}hint{/t}{t}:{/t}</span><span class="value">{$hint}</span></small>
		{/if}
{if !isset($inputOnly) || !$inputOnly}
	</div>
</div>
{/if}