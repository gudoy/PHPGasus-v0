<?php

class Resources extends Application
{
	var $inited = false;
	var $extResources = array();
	var $extDataModel = array();
	var $groups = array();
	
	public function __construct()
	{
		return $this->init();
	}
	
	public function init()
	{
		if ( $this->inited ){ return $this; }
		
		return $this
			->extendsResources()
			->extendDataModel();
	}
	
	public function singularize(string $plural)
	{
		// *s => *
		// *.ies => y
		// 
		$len = strlen($plural);
		
		if 		( substr($plural, -2) === 'ses'  )		{ $sing = preg_replace('/(.*)ses/','$1ss', $plural); }
		else if ( substr($plural, -3) === 'hes' )		{ $sing = preg_replace('/(.*)hes/','$1h', $plural); }
		else if ( substr($plural, -3) === 'ies' )		{ $sing = preg_replace('/(.*)ies$/','$1y', $plural); }
		else if ( substr($plural, -3) === 'oes' )		{ $sing = preg_replace('/(.*)oes$/','$1o', $plural); }
		else if ( substr($plural, -3) === 'ves' )		{ $sing = preg_replace('/(.*)ves$/','$1f', $plural); }
		else if ( $plural[$len-1] === 'a' ) 			{ $sing = preg_replace('/(.*)a$/','$1um', $plural); }
		else if ( $plural[$len-1] === 's' ) 			{ $sing = preg_replace('/(.*)s$/','$1', $plural); }
		
		return $sing;
	}
	
	public function extendsResources()
	{
		foreach ( $this->resources as $k => $v )
		{
			$this->extResources[$k] = array_merge($v, array(
				'singular' 		=> $this->singularise($k),									// Try to gess the singular using the plural
				'plural' 		=> $k,
				//'dbHost' 		=> !empty($v['dbHost']) ? $v['dbHost'] : null, 				//
				//'dbPort' 		=> !empty($v['dbPort']) ? $v['dbPort'] : null, 				//
				//'dbUser' 		=> !empty($v['dbUser']) ? $v['dbUser'] : null, 				//
				//'dbPass' 		=> !empty($v['dbPass']) ? $v['dbPass'] : null, 				//
				'table' 		=> !empty($v['table']) ? $v['table'] : $k, 					// Get the db table (default to resource name)
				'alias' 		=> !empty($v['alias']) ? $v['alias'] : $k[0], 				// Get the db alias (default to resource 1st char)
				'crudability' 	=> !empty($v['crudability']) ? $v['crudability'] : 'CRUD', 	// Get the allowed actions (default to CRUD)
				'identifier' 	=> !empty($v['defaultNameField']) ? $v['defaultNameField'] : null, 	// Get the allowed actions (default to CRUD)
				'groups' 		=> '', // TODO????
				'icon' 			=> '', // TODO????
			));
			
		}
		
		return $this;
	}
	
	public function extendsDataModel()
	{
		// TODO
		// 'name'
		// 'realType' => '' // int || varchar || boolean || enum || timestamp || text
		// 'type'
		// 'subtype' => '' // file || fileMeta || url || email || fileDuplicate || imageDuplicate || date || time || year || richText || html || ip || password
		// 'length'
		// 'ai'
		// 'fk'
		// 'index'
		// 'null'
		// 'default'
		// 'list'
		// 'editable'
		// 'displayName'
		// 'modifiers' ????
		// 'comment'
		// 'icon' 	=> '', // TODO????
		
		
		return $this;
	}
}

?>