{if !isset($data.view.displayLangChooser) || ($data.view.displayLangChooser && $data.view.displayLangChooser !== false)}
<div class="block languagesBlock" id="languagesBlock" title="{t}Choose your language{/t}">
<h2 class="blockTitle">{t}Languages{/t}</h2>
{assign var='curLang' value=$smarty.session.lang|truncate:2:''}
{assign var='cleanedURI' value=$smarty.server.REQUEST_URI|regex_replace:'/^(.*)(&?lang=[a-z][a-z]_[A-Z][A-Z]&?)(.*)?$/U':'$1$3'|replace:'?&':'?'|regex_replace:"/^\//":""|replace:'&':'&amp;'}
<ul class="menu nav chooser languagesMenu" id="languagesMenu">
	<li class="secondary {if $curLang === 'fr'}current{/if} first"><a href="{$smarty.const._URL}{$cleanedURI}{if strpos($cleanedURI, '?') > -1}&amp;{else}?{/if}lang=fr_FR" {if strpos($doctype, 'xhtml') !== false}xml:{/if}lang="fr-FR" title="fr-FR"><span class="locale">FR</span> <span class="value">{t}French{/t}</span></a>
	</li><li class="secondary {if $curLang === 'en'}current{/if} last"><a href="{$smarty.const._URL}{$cleanedURI}{if strpos($cleanedURI, '?') > -1}&amp;{else}?{/if}lang=en_US" {if strpos($doctype, 'xhtml') !== false}xml:{/if}lang="en-US" title="en-US"><span class="locale">EN</span> <span class="value">{t}English{/t}</span></a>
	</li>
</ul>
</div>
{/if}