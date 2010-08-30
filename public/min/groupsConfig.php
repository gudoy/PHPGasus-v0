<?php
/**
 * Groups configuration for default Minify implementation
 * @package Minify
 */

/** 
 * You may wish to use the Minify URI Builder app to suggest
 * changes. http://yourdomain/min/builder/
 **/

/*
return array(
	'z_common' => array('//Style/Ini.css', '//Style/Layout.css'),
);
*/
// Load css associations file and allow Smarty to access its variables

//require "include.php" ;


/*
 * This function creates minification $groups for the Minify! php lib
 * using the files associations defined in the js and css assoc files
 * @author	Guyllaume Doyer	<guyllaume@clicmobile.com>
 */

require($_SERVER['DOCUMENT_ROOT'] . "config/includes.php");

### CREATE CSS GROUPS ###
function addCSSgroups(&$groups)
{
	require(_PATH_CONFIG . 'cssAssoc.php');
	
	// For each of the file association, we want to create a minified css file
	foreach($pagesCSSassoc as $pageName => $pageFiles)
	{	
		// Build the minified filename
		$groupName = _APP_NAMESPACE . $pageName . '_css';
		
		foreach ($pageFiles as $file)
		{
			$filename 				= substr($file, 0, ( !strpos($file,'?') ? strlen($file) : strpos($file,'?')) );
			$groups[$groupName][] 	= '/' . _URL_STYLESHEETS_REL . $filename;
		}
	}
}

### CREATE JS GROUPS ###
function addJSgroups(&$groups)
{
	require(_PATH_CONFIG . 'jsAssoc.php');
	
	// For each of the file association, we want to create a minified css file
	foreach($pagesJSassoc as $pageName => $pageFiles)
	{
		// Build the minified filename
		$groupName = _APP_NAMESPACE . $pageName . '_js';
		
		foreach ($pageFiles as $file)
		{
			$filename 				= substr($file, 0, ( !strpos($file,'?') ? strlen($file) : strpos($file,'?')) );
			$groups[$groupName][] 	= '/' . _URL_JAVASCRIPTS_REL . $filename;
		}
	}	
}

function addAll()
{
	$groups = array();
	
	addCSSgroups($groups);
	addJSgroups($groups);
	
	return $groups;
}


return addAll();

