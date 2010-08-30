{$browser=$data.browser}
{$platform=$data.platform}
{$view=$data.view}
{if $smarty.const._APP_DOCTYPE === 'html5' && $browser.hasHTML5}{$html5=true scope='global'}{else}{$html5=false scope='global'}{/if}