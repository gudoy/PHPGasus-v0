{* Accepted params values: $label, $value, $name, $resourceSingular, $options, [$selected], [$required] *}
{$resourceSingular = $resourceSingular|default:''}
{if $resourceSingular !== ''}{$secondPart=$name|default:$label|ucfirst}{else}{$secondPart=$name|default:$label}{/if}
{if $mode === 'api'}
	{$postValName = $label}
{else}
	{$postValName = $resourceSingular|cat:$secondPart}
{/if}
<div class="line">
	<div class="labelBlock">
		<label class="span" for="{$postValName}">{$label|default:$postValName}{if $required}<span class="required">*</span>{/if}</label>
	</div>
	<div class="fieldBlock">
		<select class="{$class|default:'normal'} {if $required}check-required{/if}" name="{$postValName}" id="{$postValName}" {if $required}required="required"{/if}>
            {*<option></option>*}
            {foreach $options as $k => $v}
            {$optVal=$v}
            {*if is_numeric($k)}{$optVal=$k}{/if*}
            <option value="{$optVal}" {if $selected === $optVal || $postValName === $optVal}selected="selected"{/if}>{$v}</option>
            {/foreach}
		</select>
	</div>
</div>