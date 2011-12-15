{strip}
{block name='shortcuts'}
{$browser 	= $data.browser scope='global'}
{$platform 	= $data.platform scope='global'}
{$view 		= $data.view scope='global'}
{if $smarty.const._APP_DOCTYPE === 'html5' && $browser.hasHTML5}{$html5=true scope='global'}{else}{$html5=false scope='global'}{/if}
{/block}
{/strip}