{strip}
{* Accepted params values: $label, $name, $options, [$resourceSingular], [$value], [$selected], [$default], [$required] *}

{$resourceSingular = $resourceSingular|default:''}

{if $resourceSingular !== ''}{$secondPart=$name|default:$label|ucfirst}{else}{$secondPart=$name|default:$label}{/if}

{if $mode == 'api'}
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
		<label class="span" for="{$postValName}">{$label|default:$postValName}{if $required}<span class="required">*</span>{/if}</label>
	</div>
	<div class="fieldBlock">
{/if}
		<select class="{$class|default:'normal'} {if $required}check-required{/if}" name="{$postValName}" id="{$postValName}" {if $required}required="required"{/if}>
            {foreach $options as $k => $v}
            {$optVal=$v}
            <option value="{$optVal}" {if $postValue === $optVal || ($selected && $selected === $optVal) || $default && $default == $optVal}selected="selected"{/if}>{$v}</option>
            {/foreach}
		</select>
{if !isset($inputOnly) || !$inputOnly}
	</div>
</div>
{/if}