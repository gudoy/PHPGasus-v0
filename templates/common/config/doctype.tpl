{strip}
{* If doctype has been specificaly defined for the current page *}
{if isset($view.doctype)}
	{if $view.doctype === 'strict'}
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" lang="{$smarty.session.lang|default:$smarty.const._APP_DEFAULT_LANGUAGE|truncate:2:''}" xml:lang="{$smarty.session.lang|default:$smarty.const._APP_DEFAULT_LANGUAGE|truncate:2:''}">
	{elseif $view.doctype === 'html5'}
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{$smarty.session.lang|default:$smarty.const._APP_DEFAULT_LANGUAGE|truncate:2:''}">
	{elseif $view.doctype === 'transitional'}
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	{elseif $view.doctype === 'strict1.1'}
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{$smarty.session.lang|default:$smarty.const._APP_DEFAULT_LANGUAGE|truncate:2:''}">
	{/if}
{* Otherwise, use the app defined doctype *}
{else}
	{assign var='appDoctype' value=$smarty.const._APP_DOCTYPE|default:'strict'}
	{if $appDoctype === 'strict'}
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" lang="{$smarty.session.lang|default:$smarty.const._APP_DEFAULT_LANGUAGE|truncate:2:''}" xml:lang="{$smarty.session.lang|default:$smarty.const._APP_DEFAULT_LANGUAGE|truncate:2:''}">
	{elseif $appDoctype === 'html5'}
	<!DOCTYPE html>
	<html>
	{elseif $appDoctype === 'transitional'}
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	{elseif $appDoctype === 'strict1.1'}
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{$smarty.session.lang|default:$smarty.const._APP_DEFAULT_LANGUAGE|truncate:2:''}">
	{/if}
{/if}
{/strip}