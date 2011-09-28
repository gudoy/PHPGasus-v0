{strip}

{$doctype 			= $view.doctype|default:$smarty.const._APP_DOCTYPE|default:'html5'}
{$doctypeCompl 		= ''}
{$htmlAttrbitues 	= ''}
{$curLang 			= $smarty.session.lang|default:$smarty.const._APP_DEFAULT_LANGUAGE|truncate:2:''}
{$xmlns 			= ' xmlns="http://www.w3.org/1999/xhtml"'}
{$xmllang 			= 'xml:lang="'|cat:$curLang|cat:'"'}
{$langAttr 			= 'lang="'|cat:$curLang|cat:'"'}

{if $doctype === 'html5' && $data.options.output !== 'xhtml'}
{$htmlAttrbitues 	= " $langAttr $xmllang"}
{elseif $doctype === 'xhtml-strict-1.1'}
{$doctypeCompl 		= ' PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"'}
{$htmlAttrbitues 	= " $xmlns $xmllang"}
{elseif $doctype === 'xhtml-strict'}
{$doctypeCompl 		= ' PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"'}
{$htmlAttrbitues 	= " $xmlns $langAttr $xmllang"}
{else}
{$doctypeCompl 		= ' PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"'}
{$htmlAttrbitues 	= " $xmlns $langAttr $xmllang"}
{/if}
{/strip}
<!DOCTYPE html{$doctypeCompl}>
<html id="{$view.cssid|default:$view.smartname|default:'noSpecificId'}" class="no-js {include file='common/config/css/bodyClasses.tpl'}"{if $smarty.const._APP_USE_MANIFEST} manifest="{$smarty.const._APP_MANIFEST_FILENAME}"{/if}{$htmlAttrbitues}>
