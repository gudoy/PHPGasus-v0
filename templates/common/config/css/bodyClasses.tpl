{$platform.name} {strip}
{/strip}{$browser.engine} {strip}
{/strip}{$browser.alias|default:$browser.name} {strip}
{/strip}{$browser.alias|default:$browser.name}{$browser.versionMajor|default:''}{if isset($browser.versionMinor)}-{$browser.versionMinor}{/if} {strip}
{/strip}{$view.smartclasses} {strip}
{/strip}{$view.cssclasses} {strip}
{/strip}{$view.cssid|default:$view.smartname} {strip}
{/strip}{if $isLogged}userLogged {/if}{strip}
{/strip}{if $smarty.get.emulate}emulate {/if}{strip}
{/strip}{if $smarty.get.orientation && in_array($smarty.get.orientation, array('portrait','landscape'))}{$smarty.get.orientation} {/if}