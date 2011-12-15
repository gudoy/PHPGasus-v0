{strip}
{$platform.name|cat:' '}{$browser.engine|cat:' '}
{$browser.alias|default:$browser.name|cat:' '}
{$browser.alias|default:$browser.name}
{$browser.versionMajor|cat:' '}
{if isset($browser.versionMinor)}{$browser.alias|default:$browser.name}{$browser.versionMajor}-{$browser.versionMinor|cat:' '}{/if}
{$view.smartclasses|cat:' '}
{$view.cssclasses|cat:' '}
{$view.cssid|default:$view.smartname|cat:' '}
{if $isLogged}userLogged {/if}
{if $smarty.get.emulate}emulate {/if}
{if $smarty.get.orientation && in_array($smarty.get.orientation, array('portrait','landscape'))}{$smarty.get.orientation|cat:' '}{/if}
{/strip}