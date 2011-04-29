{strip}
{$htmlClasses 	= 'no-js'}
{$doctype 		= $view.doctype|default:$smarty.const._APP_DOCTYPE|default:'strict' scope='global'}
{/strip}
{if $doctype === 'html5' && $data.options.output !== 'xhtml'}
<!DOCTYPE html>
<html class="{$htmlClasses}"{if $smarty.const._APP_USE_MANIFEST} manifest="{$smarty.const._APP_MANIFEST_FILENAME}"{/if}>
{elseif $doctype === 'xhtml-strict-1.1'}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{$smarty.session.lang|default:$smarty.const._APP_DEFAULT_LANGUAGE|truncate:2:''}" class="{$htmlClasses}">
{elseif $doctype === 'xhtml-strict'}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="{$smarty.session.lang|default:$smarty.const._APP_DEFAULT_LANGUAGE|truncate:2:''}" xml:lang="{$smarty.session.lang|default:$smarty.const._APP_DEFAULT_LANGUAGE|truncate:2:''}" class="{$htmlClasses}">
{else}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="{$smarty.session.lang|default:$smarty.const._APP_DEFAULT_LANGUAGE|truncate:2:''}" xml:lang="{$smarty.session.lang|default:$smarty.const._APP_DEFAULT_LANGUAGE|truncate:2:''}" class="{$htmlClasses}">
{/if}