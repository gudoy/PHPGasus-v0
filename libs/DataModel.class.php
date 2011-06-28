<?php

class DataModel
{
	public $resources 	= array();
	public $groups 		= array();
	public $colums 		= array();
	
	public function _construct()
	{
		// Define aliases
		$this->_r 	= &$this->resources;
		$this->_c 	= &$this->columns;
		$this->_gp 	= &$this->groups;
	}
	
	// 
	public function build()
	{
		$this->buildResources();
		$this->buildGroups();
		$this->buildColumns();
		
		$this->generate();
	}
	
	public function parse()
	{
		$this->parseResources();
		$this->parseGroups();
		$this->parseColumns();
	}
	
	public function parseResources()
	{
		// Get resources from dataModel
		require(_PATH_CONF . 'dataModel.php');
		
		// Get resources from database
		$dbResources = CResources::getInstance()->index();
		
		// Merge both
		$this->_r = array_merge($resources, $dbResources);
		
		// Loop over the resources
		foreach ( $this->_r as $name => &$res )
		{			
			$res = array_merge($res,array(
				'name' 				=> $name,
				'type' 				=> !empty($res['type']) ? $res['type'] : $this->guessResourceType($name),
				'singular' 			=> !empty($res['singular']) ? $res['singular'] : Tools::singular($name),
				'plural' 			=> !empty($res['plural']) ? $res['plural'] : $name,
				// TODO
				'database' 			=> 'default',
				'table' 			=> !empty($res['table']) ? $res['table'] : self::getDbTableName($name),
				'alias' 			=> !empty($res['alias']) ? $res['alias'] : self::getDbTableName($name),
				'displayName' 		=> !empty($res['displayName']) ? $res['displayName'] : $name,
				// TODO: deprecate. use nameField instead
				'defaultNameField' 	=> !empty($res['defaultNameField']) ? $res['defaultNameField'] : self::getNameField($name),
				'nameField' 		=> !empty($res['nameField']) ? $res['nameField'] : self::getNameField($name),
				'extends' 			=> !empty($res['extends']) ? $res['extends'] : null,
				'searchable' 		=> !empty($res['searchable']) ? $res['searchable'] : 0,
				'exposed' 			=> !empty($res['exposed']) ? $res['exposed'] : 0,
				'crudability' 		=> !empty($res['crudability']) ? $res['crudability'] : 'CRUD',
			));
		}
	}
	
	public function parseGroups()
	{
		// TODO
	}
	
	public function parseColumns()
	{
		// TODO		
	}
	
	public function generate()
	{
		$this->generateResources();
		$this->generateGroups();
		$this->generateColumns();
	}
	
	public function generateResources()
	{
		$lb 		= "\n";
		$code 		= '<?php' . $lb . $lb . '$resources = array(' . $lb;
		$longer 	= null;
		
		// Try to get the longer resource name (to compute tab proper tab indentation)
		foreach ( $resources as $props ){ $longer = ( empty($longer) || strlen($props['name']) > strlen($longer) ) ? $props['name'] : $longer; }
		
		$verTabPos = strlen($longer) + ( 4 - (strlen($longer) % 4) );
		
		// Loop over the resources
		foreach ( $this->resources as &$res )
		{			
			$tabsCnt = floor(($verTabPos - strlen($name)) / 4);
			$tabs = '';
			for($i=0; $i<$tabsCnt; $i++){ $tabs .= "\t"; }
			
			$code .= "'" . $res['name'] . "' " . $tabs . "=> array(";
			$code .= "'type' => '" . $res['type'] . "'";
			$code .= ", 'singular' => '" . $res['singular'] . "'";
			$code .= ", 'plural' => '" . $res['plural'] . "'";
			$code .= ", 'displayName' => '" . $res['plural'] . "'";
			// TODO
			//$code .= ", 'database' => '" . $displayName . "'";
			$code .= ", 'defaultNameField' => " . (string) $res['defaultNameField'] . "'";
			$code .= ", 'nameField' => " . (string) $res['nameField'] . "'";
			$code .= ", 'extends' => " . ( is_string($res['extends']) ? "'" . $res['extends'] .  "'" : 'null' );
			$code .= ", 'database' => '" . $res['database'] . "'";
			$code .= ", 'table' => '" . $res['table'] . "'";
			$code .= ", 'alias' => '" . $res['alias'] . "'";
			$code .= ", 'searchable' => " . ( $res['searchable'] ? 'true' : 'false' ) . "";
			$code .= ", 'exposed' => '" . ( $res['exposed'] ? 'true' : 'false' ) . "'";
			$code .= ", 'crudability' => '" . $res['crudability'] . "'";
			$code .= ")," . $lb;
		}
		
		$code .= ');' . $lb . '?>';
	}
	
	public function generateGroups()
	{
		
	}
	
	public function generateColumns()
	{
		
	}
	
	
	static function isResource(string $string)
	{
		return isset($this->resources[$string]);
	}
	
	
	static function isColumn(string $resource, string $string)
	{
		return isset($this->resources[$resource][$string]);
	}
	
	
	static function guessResourceType($resName)
	{
		$type = 'native';
		
		// Split the name on the '_'
		$parts 		= explode('_', $colName);
		
		// Check if contains name of 2 resources
		// if (  ){ $type = 'relation'; }
		
		return $type;
	}
	
	
	static function getDbTableName(string $resource)
	{
		$tableName = $resource;
		
		// For relation resources, create names like '{$resource1}_{$resource2}' 
		if ( $this->resources[$resource]['type'] === 'relation' )
		{
			// TODO
		}
		
		return $tableName;
	}
	
	
	static function getNameField(string $resource)
	{
		$nameField = null;
		
		// TODO
		// Get the first unique index char field
		// Get the first defined char field
		
		return $nameField;
	}
	
	// Try to gess column type using it's name
	static function gessColumnType($colName)
	{
		// Split the name on the '_'
		$parts 		= explode('_', $colName);
		
		foreach ( (array) $parts as $part )
		{
			$sing = Tools::singular($part);
			$plur = Tools::plural($part);
			
			// Check if is an existing resource
			$isResource = $this->isResource($sing) || $this->isResource($plur);
			
			// If resource && resource not current one, assume it's a relation
			
			
			// 'name$' 				=> 'string'
			// 'title$' 			=> 'title'
			// 'color' 				=> 'color'
			
			// '_url' 				=> 'url'
			// 'url_' 				=> 'url'
			
			// 'phone' 				=> 'tel'
			// 'mobile' 			=> 'tel'
			
			// is_ 					=> 'boolean'
			// has_ 				=> 'boolean'
			
			// 'mail_'
			// '_mail' 				=> 'email'
			// '_id' 				=> 'onetoone'
			//
			
			// '_nb' 				=> 'integer'
			// '_number' 			=> 'integer'
			// '_count' 			=> 'integer'
			// '_age' 				=> 'integer' + length = 3
			
			// 'id' 				=> 'pk'
			
			// '_date' 				=> 'date'
			// 'creation_date' 		=> 'timestamp'
			// 'created_' 			=> 'timestamp'
			// 'update_date' 		=> 'timestamp'
			// 'updated_at' 		=> 'timestamp'
			
			// 'text' 				=> 'text'
			// 'summary' 			=> 'text',
			// 'description' 		=> 'text',
			// 'desc' 				=> 'text',
			
			// ip 					=> 'ip'
			
			// TODO
			// length, _len, 
			// time, _time, year, _year, month, _month, day, _day, hour, _hour, minutes, _minutes, seconds, _seconds
			
		}
	}
}

?>