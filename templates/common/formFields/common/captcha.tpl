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
        <span class="captchaOperation">{$smarty.session.captchaOperation}</span>
        {if empty($type) || !in_array($type, array('email','phone','url')) || !$html5}{$type='text'}{/if}
        <input type="text" class="sized {if $required}check-required{/if}" size="2" name="{$postValName}" id="{$postValName}" {if $required}required="required"{/if} />
        <small class="infos captchaHint">{t}hint{/t}{t}:{/t} {t}The answer is{/t} {$smarty.session.captchaResult}</small>
    </div>
</div>
