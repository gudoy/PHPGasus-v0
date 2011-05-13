<?php

class Model extends Application
{
	var $debug         = false;
	var $db            = null;
	var $success       = false;
	var $errors        = null;
	var $warnings      = null;
	var $affectedRows  = null;
	var $numRows       = null;
	var $data          = null;
	var $afterQuery    = null;
	var $launchedQuery = null;
	
	private $safeWrapper   = '`';
	
	// Default options
	var $options = array(
		'conditions'              => array(), // TODO
		//'sortBy'                  => null, // TODO
		//'orderBy'                  => null, // TODO
		//'limit'                   => null, // TODO
	);
	
	public function __construct(&$application)
	{		
		isset($dataModel) || include(_PATH_CONFIG . 'dataModel.php');
		
		//$this->dataModel 	= $dataModel;
		$this->application    = &$application;
		$this->resources      = &$resources;
		$rProps               = &$this->resources[$this->resourceName];
		
        // Handle filters
		if ( !empty($rProps['extends']) && isset($this->resources[$rProps['extends']]) )
        {
            $parentName     = &$rProps['extends'];
            $parentProps    = &$this->resources[$parentName];
            $this->alias    = !empty($parentProps['alias']) ? $parentProps['alias'] : $parentName;
            $this->table    = !empty($parentProps['table']) ? $parentProps['table']: $parentName;            
        }
        else
        {
            $this->alias    = !empty($rProps['alias']) ? $rProps['alias'] : $this->resourceName;
            $this->table    = !empty($rProps['table']) ? $rProps['table']: $this->resourceName;            
        }
								
		//  
		if ( !empty($this->resourceName) )
		{
			$this->resourceSingular = !empty($this->resourceSingular) ? $this->resourceSingular : Tools::singularize((string) $this->resourceName);
		}
		
		// Set the timeout
		if( _DB_CONNECTION_TIMEOUT !== '' ) { ini_set('mysql.connect_timeout', _DB_CONNECTION_TIMEOUT); }
		
		return $this->connect();
	}


    /*
    public function log($data = null, $options = array())
    {
        return $this->debug ? $this->dump($data, $options): $this;
    }*/

    
    public function dump($data = null, $options = array())
	{
		return $this->debug ? parent::dump($data, $options) : $this;
	}
    
    public function init($options = array())
    {
        $this->handleOptions($options);
        
        return $this;
    }
    
    public function handleOptions($options = array())
    {   
        return $this;
    }

	public function connect()
	{
		// Open a connection on the db server
		$this->db 			= @mysql_connect(_DB_HOST, _DB_USER, _DB_PASSWORD);
		
		// TODO: use error codes
		if ( !is_resource($this->db) )
		{
			//$this->errors[] = 4000;
			//die('Database connection error : ' . mysql_errno() . ": " . mysql_error());
			
			// TODO: make something more user friendly. redirect to /error/
			die('Database connection error. ' . ( $this->env['type'] === 'prod' ? '' : mysql_errno() . ': ' . mysql_error() ));
		}
		
		$this->selectedDb 	= @mysql_select_db(_DB_NAME, $this->db);
		
		// TODO: use error codes
		if ( !$this->selectedDb ) { $this->errors[] = 4001; die('Database selection error'); }
		
		// Tell mysql we are sending already utf8 encoded data
		mysql_query("SET NAMES 'UTF8'");
		
		return $this;
	}
	
	// Deprecated
	// TODO: remove and rename query_new to query
	public function query($query, $options = null)
	{
	    if ( defined('_APP_USE_SQL_FETCH_V2') && _APP_USE_SQL_FETCH_V2 ){ return $this->query_new($query, $options); }
        
		$this->data = null;
		
		// Connect to the db
		if ( !$this->db ) { $this->connect(); }
		
		$this->errors 			= array();
		$o 						= &$options;																// Shortcut for options
		$o['type'] 				= !empty($o['type']) ? $o['type'] : 'select'; 							// Set query type to select by default if not already setted
		
		$this->launchedQuery 	= $query;
		
		// Do the query
		$queryResult 			= mysql_query($query, $this->db);
		
		// 
		//$this->success 			= is_bool($queryResult) && $queryResult === false ? false : true;
		$this->success 			= is_bool($queryResult) && !$queryResult ? false : true;
		//$this->success 			= !(is_bool($queryResult) && !$queryResult);
		//$this->success 				= is_bool($queryResult) && $queryResult;

		// If the request succeed
		if ( $this->success )
		{
			// Get number of rows affetected by a insert, update, delete request
			$this->affectedRows = mysql_affected_rows($this->db);
			
			// Get number of selected rows (for select request)
			$this->numRows 		= is_resource($queryResult) ? mysql_num_rows($queryResult) : 0;
			
			if ( $o['type'] === 'insert' ){ $this->insertedId = mysql_insert_id($this->db); }
			
			// If the request returns results
			// HOW TO handle RETURNING clause for mysql ??? 
			if ( $o['type'] === 'select' || ($o['type'] === 'insert' && !empty($o['returning'])) )
			{                
				$this->fetchResults($queryResult, $o);
				
				$this->fixSpecifics($o);
				
				// TODO: if returning !== 'id', make a select using id value, then return column value
				if ( !empty($o['returning']) ) { $this->data['id'] = $this->insertedId; }
				
				//if ( !empty($o['getFields']) && count($o['getFields']) === 1 )
				/*
				if ( !empty($o['getFields']) && count($o['getFields']) === 1 )
				{
                    $colName = $o['getFields'][0];

                    // TODO: handle properly on fetch instead????
                    if ( $this->numRows > 1 )
                    {
                        $tmpData = array();
                        
                        foreach ( $this->data as $item ){ if ( isset($item[$colName]) ){ $tmpData[] = $item[$colName]; } }
                        
                        $this->data = $tmpData;
                        
                        unset($tmpData);
                    }
                    else
                    {
                        $this->data = isset($this->data[$colName]) ? $this->data[$colName] : null;    
                    }

					
				}*/
			}
			
			// For insert, we may need to do some process once the request succeed
			if ( $o['type'] === 'insert' && !empty($this->afterQuery) ){ $this->afterQuery(); }
		}
		else
		{			
			// Get the last error returned by the db
			//$this->errors = mysql_error($this->db);
			$errId = mysql_errno($this->db);
			$this->errors[$errId] = mysql_error($this->db);
		}	
		
		return $this;
	}


    public function query_new($query, $options = null)
    {
        $this->data = null;
        
        // Connect to the db
        if ( !$this->db ) { $this->connect(); }
        
        $this->errors           = array();
        $o                      = &$options;                                    // Shortcut for options
        $o['type']              = !empty($o['type']) ? $o['type'] : 'select';   // Set query type to select by default if not already setted
        
        $this->launchedQuery    = $query;
		
//$this->dump('query_new');
        
        // Do the query
        $queryResult            = mysql_query($query, $this->db);
//$this->dump(mysql_query($query, $this->db));

//$this->dump($queryResult);
        
        // 
        $this->success          = is_bool($queryResult) && !$queryResult ? false : true;

        // If the request succeed
        if ( $this->success )
        {
            // Get number of rows affetected by a insert, update, delete request
            $this->affectedRows = mysql_affected_rows($this->db);
			
//$this->dump(mysql_affected_rows($this->db));
            
            // Get number of selected rows (for select request)
            $this->numRows      = is_resource($queryResult) ? mysql_num_rows($queryResult) : 0;
			$this->numFields 	= is_resource($queryResult) ? mysql_num_fields($queryResult) : 0;
			
//$this->dump('fields: ' . $this->numFields);
            
            if ( $o['type'] === 'insert' ){ $this->insertedId = mysql_insert_id($this->db); }
            
            // If the request returns results
            // HOW TO handle RETURNING clause for mysql ??? 
            if ( $o['type'] === 'select' || ($o['type'] === 'insert' && !empty($o['returning'])) )
            {                
                $this->fetchResults_new($queryResult, $o);
            }
            
            // For insert, we may need to do some process once the request succeed
            if ( $o['type'] === 'insert' && !empty($this->afterQuery) ){ $this->afterQuery(); }
        }
        else
        {           
            // Get the last error returned by the db
            //$this->errors = mysql_error($this->db);
            $errId                  = mysql_errno($this->db);
            $this->errors[$errId]   = mysql_error($this->db);
        }   
        
        return $this;
    }

	
	private function fixSpecifics($options = null)
	{
//$t1 = microtime(true);
		
		$o 			= &$options;
		$fixTypes 	= !defined('_APP_USE_ONFETCH_TYPEFIXING') || !_APP_USE_ONFETCH_TYPEFIXING;
		
		if ( !$fixTypes ){ return $this; }
		
		if ( !empty($o['mode']) && $o['mode'] === 'count' ){ $this->data = is_numeric($this->data) ? (int) $this->data : $this->data; return $this; }
		
		// Handle case where data is just 1 item, where we have to directly loop over the fields
		if ( $this->numRows == 1 && !empty($o['mode']) && $o['mode'] === 'onlyOne' ) { $this->data = $this->fixSpecificsSingle($this->data); }
		// Handle case where data is made of serval items, where we have to loop over them all and apply fix to each one of them
		else
		{
			foreach($this->data as $index => $itemData) { $this->data[$index] = $this->fixSpecificsSingle($itemData); }
		}
		
//$t2 = microtime(true);
//$d = $t2-$t1;
//var_dump($d);
		
		return $this;
	}
	
	private function fixSpecificsSingle_old($dataRow)
	{
		if ( !is_array($dataRow) && !is_object($dataRow) ){ return $dataRow; }

		$rModel 	= &$this->application->dataModel[$this->resourceName];
		
		foreach( $dataRow as $field => $value )
		{
			$rField      = !empty($rModel[$field]) ? $rModel[$field] : null;
			$type        = !empty($rField['type']) ? $rField['type'] : null;
			$subtype     = !empty($rField['subtype']) ? $rField['subtype'] : null;
			$curVal      = &$dataRow[$field];

			// Fix postgresql 't' === true and 'f' === false
			if ( $type === 'bool' )
			{
				//$dataRow[$field] = $curVal === 't' ? true : ( $curVal === 'f' ? false : $curVal);  
				//$dataRow[$field] = $curVal === 't' || $curVal == true  ? true : ( $curVal === 'f' || $curVal == false ? false : $curVal);
				$dataRow[$field] = $curVal === 't' || $curVal == true  ? true : false;
			}
			else if ( $type === 'int' )
			{
				$dataRow[$field] = (int) $curVal;
			}
			else if ( $type === 'float' )
			{
				$dataRow[$field] = (float) $curVal;
			}
			else if ( $type === 'timestamp' )
			{
				//$dataRow[$field] = is_numeric($curVal) ? $curVal : strtotime($curVal);  
				$dataRow[$field] = is_numeric($curVal) ? (int) $curVal : strtotime($curVal);
			}
			//else if ( $type === 'varchar' && $subtype === 'file' )
			else if ( $type === 'varchar' && in_array($subtype, array('file', 'fileDuplicate')) )
			{
				if ( !empty($curVal) && !empty($rField['destBaseURL']) )
				{
					$dataRow[$field] = $rField['destBaseURL'] . $curVal;
				}
			}
		}
		
		return $dataRow;
	}
	
	
	private function fixSpecificsSingle($dataRow, $options = array())
	{
	    $o = &$options;
        $o = array_merge(array(
            //'resource' => $this->resourceName,
            'rModel'        => &$this->application->dataModel[$this->resourceName],
            'fixOneToOne'   => defined('_APP_TYPEFIX_ONETOONE_GETFIELDS') && _APP_TYPEFIX_ONETOONE_GETFIELDS,
            'fixManyToMany' => defined('_APP_TYPEFIX_MANYTOMANY_GETFIELDS') && _APP_TYPEFIX_MANYTOMANY_GETFIELDS,
        ), $options);
        
		if ( !is_array($dataRow) && !is_object($dataRow) ){ return $dataRow; }

		//$rModel 	= &$this->application->dataModel[$this->resourceName];
        //$rModel     = &$this->application->dataModel[$o['resource']];
		
		//foreach( $rModel as $name => $field )
		foreach( $o['rModel'] as $name => $field )
		{
			$skip 		= false;
			$type 		= !empty($field['type']) ? $field['type'] : null;
			$subtype 	= !empty($field['subtype']) ? $field['subtype'] : null;
			
			if 		( $type === 'onetomany' )	{ $skip = false; }
			elseif 	( !isset($dataRow[$name]) )	{ $skip = true; }  			
			
			if ( $skip ) { continue; }
			
			$curVal = !empty($dataRow[$name]) ? $dataRow[$name] : null;
			
			if ( $type === 'bool' )
			{
				//$dataRow[$field] = $curVal === 't' ? true : ( $curVal === 'f' ? false : $curVal);  
				//$dataRow[$field] = $curVal === 't' || $curVal == true  ? true : ( $curVal === 'f' || $curVal == false ? false : $curVal);
				$dataRow[$name] = $dataRow[$name] === 't' || $dataRow[$name] == true ? true : false;
			}
            else if ( $type === 'onetoone' || ($type === 'int' && !empty($field['fk'])) )
            {
                $dataRow[$name] = (int) $curVal;
                
                // Do not continue if the fixing feature is not allowed for this type 
                if ( !$o['fixOneToOne'] ){ continue; }
                
                $relResource    = !empty($field['relResource']) ? $field['relResource'] : preg_replace('/(.*)_(.*)$/U', '$1');
                $relField       = !empty($field['relField']) ? $field['relField'] : preg_replace('/(.*)_(.*)$/U', '$2');
                //$relGetFields   = !empty($field['relGetFields']) ? Tools::toArray($field['relGetFields']) : array($relField, $this->resources[$relResource]['defaultNameField']);
                $relGetFields   = !empty($field['relGetFields']) 
                					? Tools::toArray($field['relGetFields']) 
									: ( isset($this->resources[$relResource]['defaultNameField']) 
										? array($relField, $this->resources[$relResource]['defaultNameField'])
										// TODO: if id not defined, use first field 
										: array('id') 
									);
                $getFields      = !empty($field['relGetAs']) ? Tools::toArray($field['relGetAs']) : $relGetFields;
                
                $i = 0;
                foreach ($getFields as $item)
                {
                    if ( empty($dataRow[$item]) ){ $i++; continue; }
                    
                    $tmp            = array($item => $dataRow[$item]);
                    $relFieldModel  = array($item => &$this->application->dataModel[$relResource][$relGetFields[$i]]);
                    $fixed          = $this->fixSpecificsSingle($tmp, array('rModel' => $relFieldModel));
                    $dataRow[$item] = $fixed[$item];
                    
                    $i++;
                }
            }
			else if ( $type === 'int' )
			{
				$dataRow[$name] = (int) $curVal;
			}
            else if ( $type === 'text' && !empty($field['html']) )
            {
                //$dataRow[$name] = htmlentities($curVal, ENT_COMPAT, 'UTF-8');
                //$dataRow[$name] = htmlspecialchars($curVal, ENT_COMPAT, 'UTF-8');
                //$dataRow[$name] = str_replace(array("<", '>', "'", '"'), array('&lt;','&gt;', '&apos;', '&quot;'), $curVal);
            }
			else if ( $type === 'float' )
			{
				$dataRow[$name] = (float) $curVal;
			}
			else if ( $type === 'timestamp' )
			{
				//$dataRow[$field] = is_numeric($curVal) ? $curVal : strtotime($curVal);  
				$dataRow[$name] = is_numeric($curVal) ? (int) $curVal : strtotime($curVal);
			}
			//else if ( $type === 'varchar' && $subtype === 'file' )
			else if ( $type === 'varchar' && in_array($subtype, array('file', 'fileDuplicate')) )
			{
				//if ( !empty($curVal) && !empty($field['destBaseURL']) )
				if ( !empty($curVal) && !empty($field['destBaseURL']) && filter_var($curVal, FILTER_VALIDATE_URL) === false )
				{
					//$dataRow[$name] = $field['destBaseURL'] . $curVal;
					$dataRow[$name] = $field['destBaseURL'] . preg_replace('/^\/(.*)/','$1',$curVal);
				}
			}
			else if ( $type === 'onetomany' )
			{
                // Do not continue if the fixing feature is not allowed for this type 
                if ( !$o['fixManyToMany'] ){ continue; }
                
				$relResource 	= !empty($field['relResource']) ? $field['relResource'] : $name;
                $relField           = !empty($field['relField']) ? $field['relField'] : 'id'; 
				$getFields      = !empty($field['getFields']) ? Tools::toArray($field['getFields']) : array($relField, $this->resources[$relResource]['defaultNameField']);
				$pivotResource 	= !empty($field['pivotResource']) ? $field['pivotResource'] : $this->resourceName . $relResource;
				$pivotTable 	= !empty($this->resources[$pivotResource]['table']) ? $this->resources[$pivotResource]['table'] : $pivotResource;
                $pivotLeftField     = !empty($field['pivotLeftField']) ? $field['pivotLeftField'] : $this->resourceSingular . '_' . 'id';
                $pivotRightField    = !empty($field['pivotRightField']) ? $field['pivotRightField'] : $this->resources[$relResource]['singular'] . '_' . 'id';
				$tmpData 		= array();
                $relFieldModel      = array();
				
				// Special case for pivolIdField
				$getFields 		= isset($dataRow[$pivotTable . '_id']) ? array_merge($getFields, array($pivotTable . '_id')) : $getFields;
				
                $relFieldModel = array();
                
                // Array containing unique keys for the gotten fields (used to remove doubles)
                $uniqueKeys = array();
                
				// Loop over the gotten fields
				foreach ($getFields as $item)
				{
					// Build the name used for the database output
					$storingName 	= strpos($item, $pivotTable) !== false ? $item : $this->resources[$relResource]['singular'] . '_' . $item . 's';
					
                    // Do not continue if the field is not found with the expected name
					if ( !isset($dataRow[$storingName]) ){ continue; }
					
					// Split the value which should be a concatenated string of the all the field values
					$tmp 			= explode(',', $dataRow[$storingName]);
					
                    //                     
                    if ( $item === $relField ){ $uniqueKeys = $tmp; }
					
                    $relFieldModel[$item] = &$this->application->dataModel[$relResource][$item];   
					
					// Loop over the splited value and reassign into the proper final array
					foreach ( $tmp as $k => $v )
					{
                        $uniqueKey = $uniqueKeys[$k];
                        
                        // Do not continue if the value has already been set for this key
                        if ( isset($tmpData[$uniqueKey][$item]) ) { continue; }
                        
                        //$tmpData[$k][$item] = $v;
                        $tmpData[$uniqueKey][$item] = $v;
                        
                        //$tmpData[$k][$item] = $this->fixSpecificsSingle($v, array('rModel' => array($item => $relFieldModel), 'forceProcess' => true));
                    }
					
					// If the field is not a native one and thus does not belong to dataModel for this resource
					// remove if from the output since it has been reassigned elsewhere 
					//if ( !isset($rModel[$storingName]) ){ unset($dataRow[$storingName]); }
					
					// Once the field has been processed, remove doubles from the concatenated string
					$dataRow[$storingName] = join(',', array_unique($tmp)); 
				}
				
                // Now that we are sure that we do not have doubles, we can reindex with numeric keys
                $tmpData = array_values($tmpData);
                
                // Remove doubles from pivot ids too
                if ( isset($dataRow[$pivotResource . '_ids']) )
                {
                    $dataRow[$pivotResource . '_ids'] = join(',', array_unique(explode(',', $dataRow[$pivotResource . '_ids']))); 
                }

                // TODO: find a way to fix type directly instead of having to loop over the resource model for each row
                foreach ($tmpData as &$tmpRow)
                {
                    $tmpRow = $this->fixSpecificsSingle($tmpRow, array('rModel' => $relFieldModel));
                }
				
				$dataRow[$name] = $tmpData;
			}
		}
		
		return $dataRow;
		//return $this;
	}


    public function getDataType($colName = '', $params = array())
    {		
		$type 	= 'string'; 											// Default type to string
        $p 		= array_merge(array(
            'resource' => $this->resourceName,
        ), $params);
        $rModel = &$this->application->dataModel[$p['resource']];      // Shortcut for current resource dataModel
        
        // Do not continue if the passed colname does not exist if the datamodel
        if ( !isset($rModel[$colName]) ) { return $type; }
        
        $rProps = &$rModel[$colName];
        
        if      ( !empty($rProps['pk']) )   { $type = 'primarykey'; } 
        elseif  ( !empty($rProps['fk']) )   { $type = 'onetoone'; }
        else if ( !empty($rProps['type']) ) { $type = $rProps['type']; }
        //else                                { $type = 'string'; }       
        
        return $type;
    }


    public function fixData(&$data, $params = array())
    {        
        // Extends default param values with passed ones 
        $p      = array_merge(array(
        ), $params);
        
        // If data is an array, 
        if ( is_array($data) )
        {
            // Loop over it's fields
            foreach ( $data as $k => $v )
            {
                // Fix each value (using column type)
                $data[$k] = $this->fixDataValue($v, array('type' => $this->getDataType($k))); 
            }
        }
        // Or if it's a string, directly fix the value
        else if ( is_string($data) )
        {
            // Fix each value (using column type)
            $data = $this->fixDataValue($data, array('colName' => $p['colName']));
        }
        
        return $data;
    }
    
    public function fixDataValue(&$value, $params = array())
    {
//var_dump(__FUNCTION__);
        
        // Shortcuts
        //$p = &$params;
        $p = array_merge(array(
            'resource'  => $this->resourceName, // Use current resource if not passed
            'colName'   => null, 
        ), $params);
        $v = &$value;
        
        // Get data type
        $t = !empty($p['type']) ? $p['type']  : ( !empty($p['colName']) ? $this->getDataType($p['colName']) : '' );

//var_dump('colName: ' . $p['colName']);        
//var_dump('type: ' . $t);
        
        if ( is_null($value) ){ $v = null; return $v; }
        
        switch($t)
        {
            case 'onetomany':
            case 'manytomany':
            case 'onetomany':
            
            case 'timestamp':   $v = is_numeric($v) ? (int) $v : $v; break;
            
            case 'bool':
            case 'boolean':     $v = in_array($v, array(true,1,'1','true','t')) ? true : false; break;
                                
            case 'float':       $v = (float) $v; break;

            case 'serial':
            case 'primarykey':
            case 'pk':            
            case 'onetoone':
            case 'int':         $v = (int) $v;  break;
            
            case 'file':
            case 'fileduplicate':
                /*
                    $v = !empty($v) && !empty($p[$colProps]['destBaseURL']) && filter_var($v, FILTER_VALIDATE_URL) === false
                            ? $p[$colProps]['destBaseURL'] . preg_replace('/^\/(.*)/','$1',$v)
                            : $v;
                 */

            case 'datetime':
            case 'time':
            case 'year':
            case 'month':
            case 'day':
            case 'hours':
            case 'minutes':
            case 'seconds':
            
            case 'html':
            case 'text':
            case 'image':
            case 'video':
            case 'sound':
            case 'url':
            case 'email':
            case 'enum':
            default:            $v = $v; break;
        }
        
        return $v;
    }
	

	private function fetchResults($queryResult, $options = null)
	{		
		$o 			= &$options;
		$o['mode'] 	= !empty($o['mode']) ? $o['mode'] : '';
        $fixTypes   = defined('_APP_USE_ONFETCH_TYPEFIXING') && _APP_USE_ONFETCH_TYPEFIXING;
		
		// Special cases where we know that we will only get 1 result
		// So, we do not want it to be put into an array but want it instead to be directly returned
		if ( $o['mode'] === 'count' )
		{
			$this->data = mysql_result($queryResult, 0,0);
		}
		//else if ( ($o['mode'] === 'onlyOne' || !empty($o['returning'])) && $this->numRows != 0 )
        //else if ( ($o['mode'] === 'onlyOne' || !empty($o['returning']) || count($o['getFields']) === 1) && $this->numRows != 0 )
        //else if ( ($o['mode'] === 'onlyOne' || !empty($o['returning']) || count($o['getFields']) === 1) && $this->numRows === 1 )
        else if ( $this->numRows > 0 && ($o['mode'] === 'onlyOne' || !empty($o['returning']) || (count($o['getFields']) === 1 && $this->numRows === 1)) )
		{
//$this->dump('1 row, x cols');
			$this->data = mysql_fetch_array($queryResult, MYSQL_ASSOC);
            
            $this->data = $fixTypes ? $this->fixSpecificsSingle($this->data) : $this->data;
			
			if ( count($o['getFields']) === 1 )
			{
				$this->data = isset($this->data[$o['getFields'][0]]) ? $this->data[$o['getFields'][0]] : null;
			}
		}
		// Otherwise, fetch the query results set
		else
		{            
			if ( $this->numRows > 0 ) 
			{
				while ($row = mysql_fetch_array($queryResult, MYSQL_ASSOC))
				{
//var_dump($o['getFields']);
					
                    // If we only get 1 column in the results
                    if ( ( count($o['getFields']) === 1 && empty($o['count'])) 
                        || ( $o['mode'] === 'distinct' && !empty($o['field']) ) )
                    {
//$this->dump('several rows, 1 col');
						
                        $row            = $fixTypes ? $this->fixSpecificsSingle($row) : $row;
                        $usedField      = $o['mode'] === 'distinct' && !empty($o['field']) ? $o['field'] : $o['getFields'][0];
//$this->dump($usedField);
//var_dump($row);
                        $this->data[]   = $row[$usedField];
                    }
                    else
                    {
//$this->dump('several rows, several cols');
                        //$this->data[] = $row;
                        $this->data[] = $fixTypes ? $this->fixSpecificsSingle($row) : $row;
                    }
				}
			}
			else { $this->data = array(); }
		}
		
		if ( is_resource($queryResult) ) { mysql_free_result($queryResult); }
		
		return $this;
	}


    private function fetchResults_new($queryResult, $options = null)
    {       
        $o          = &$options;
		$o 			= array_merge(array(
			'mode' => null,
		), $o);
        
        if ( $o['mode'] === 'count' )
        {
//var_dump('case count');
            
            $this->data = (int) mysql_result($queryResult, 0,0);
        }
        else if ( !empty($o['returning']) )
        {
//$this->dump($this->insertedId);
			if 	( $o['returning'] === 'id' ) { $this->data = (int) $this->insertedId; }
        }
        // 1 column, 1 row
        else if ( $o['mode'] === 'onlyOne' && count($o['getFields']) === 1 )
        {
//var_dump('1 column 1 row');
            $this->data = mysql_fetch_array($queryResult, MYSQL_ASSOC);
            $usedCol    = $o['getFields'][0];
            $this->data = $this->fixDataValue($this->data[$usedCol], array('colName' => $usedCol));
        }
        // several columns, 1 row
        else if ( $o['mode'] === 'onlyOne' && ( count($o['getFields']) > 1 || empty($o['getFields']) ) )
        {
//var_dump('several columns 1 row');
            $this->data = $this->fixData(mysql_fetch_array($queryResult, MYSQL_ASSOC));
        }
        // 1 column, several rows
        else if ( $o['mode'] === 'distinct' && count($o['field']) === 1 )
        {
            if ( $this->numRows > 0 ) 
            {
                $usedCol    = $o['field'];
                
                while ($row = mysql_fetch_array($queryResult, MYSQL_ASSOC))
                {
                    //$this->data[] = $this->fixDataValue($row[$usedCol], array('colName' => $usedCol));
					$this->addToDataArray($this->fixDataValue($row[$usedCol], array('colName' => $usedCol)));
                }
            }
            else { $this->data = array(); }
        }
        // 1 column, several rows
        //else if ( count($o['getFields']) === 1 && $o['mode'] !== 'onlyOne' )
        //else if ( $o['mode'] !== 'onlyOne' && count($o['getFields']) === 1 && $this->numFields === 1 )
        else if ( $o['mode'] !== 'onlyOne' && isset($o['getFields']) && count($o['getFields']) === 1 && $this->numFields === 1 )
        {
//$this->dump('1 column, several rows');
            if ( $this->numRows > 0 ) 
            {
                $usedCol    = $o['getFields'][0];
                
                while ($row = mysql_fetch_array($queryResult, MYSQL_ASSOC))
                {
                    //$this->data[] = $this->fixDataValue($row[$usedCol], array('colName' => $usedCol));
					$this->addToDataArray($this->fixDataValue($row[$usedCol], array('colName' => $usedCol)));
                }
            }
            else { $this->data = array(); }
        }
        // several columns, several rows
        else
        {
//$this->dump('several columns, several rows');
            if ( $this->numRows > 0 ) 
            {
                while ($row = mysql_fetch_array($queryResult, MYSQL_ASSOC))
                {
                    //$this->data[] = $this->fixData($row);
                    $this->addToDataArray($this->fixData($row));
                }
            }
            else { $this->data = array(); }
        }
        
        if ( is_resource($queryResult) ) { mysql_free_result($queryResult); }
        
        return $this;
    }


	public function addToDataArray($item, $params = array())
	{
		$this->data[] = $item;
	}


	public function escapeString($string)
	{
		$string = !empty($string) ? (string) $string : '';
		
		return mysql_real_escape_string($string);
	}

	
	private function magicFields($fieldsStringOrArray = null)
	{
		$fields = Tools::toArray($fieldsStringOrArray);
		
		foreach ( $fields as $key => $item )
		{			
			$k = is_array($item) ? $key : $item;
			
			if ( empty($k) ){ continue; }
			
			$this->queryData['fields'][$k] = array(
				'name' 		=> $k,
				'as' 		=> is_array($item) && !empty($item['as']) ? $item['as'] : null,
				'table' 	=> is_array($item) && !empty($item['table']) ? $item['table'] : null,
				'count' 	=> false,
			);
		}
		
		return $this;
	}
	
	
	// TODO: refactor
	private function afterQuery()
	{
		$a = $this->afterQuery;
		
		if ( !empty($a['rename']) )
		{
			// Get the sql id of the resource
			$lastId 		= mysql_insert_id();
			
			// Array tha will contains key/value couples to update
			$updateKeyVals 	= array();
			
			foreach ( $a['rename'] as $item )
			{
				$curFolder 		= $item['currentFolder'];
				$curFilepath 	= $item['destRoot'] . $curFolder . $item['currentName'];
				$newFolder 		= str_replace($item['tempName'] . '/', $lastId . '/', $curFolder);
				//$newFilename	= str_replace('_' . $item['tempName'] . '_', '_' . $lastId . '_', $item['currentName']);
				//$newFilename	= str_replace('_' . $item['tempName'], '_' . $lastId, $item['currentName']);
				$newFilename	= str_replace($item['tempName'], $lastId, $item['currentName']);
				$tmpNewFilepath = $newFolder . $newFilename;
				$newFilepath 	= $item['destRoot'] . $tmpNewFilepath;
				$storedFilePath = $tmpNewFilepath;
				
				$updateKeyVals[$item['dbField']] = $storedFilePath;
				
//$this->dump($curFilepath);
//$this->dump($newFilepath);
				
				// Case:  Amazon S3
				if ( !empty($item['storeOn']) && $item['storeOn'] === 'amazon_S3' )
				{
					class_exists('AmazonS3') || require(_PATH_LIBS . 'storage/CloudFusion/cloudfusion.class.php');
					
					$s3 = new AmazonS3(_AWS_ACCESSKEY, _AWS_SECRET_KEY);
					
					$s3->rename_object(_AWS_BASE_BUCKET, $curFilepath, $newFilepath, $item['acl']);
				}
				// Default case: ftp
				else
				{
					class_exists('FileManager') || require(_PATH_LIBS . 'storage/FileManager.class.php');	
					$FileManager 	= new FileManager();
					$FileManager 	= $FileManager->connect();
					
					$FileManager
						->mkdir($item['destRoot'] . $newFolder)
						->rename($curFilepath, $item['destRoot'] . $newFolder . $newFilename)
						->rmdir($item['destRoot'] . $curFolder);
					
					$FileManager->close();
				}
				
				// Now, we have to update the file path in the db
				//$this->update(array($item['dbField'] => $storedFilePath), array('values' => $lastId, 'upload' => false));	
			}
			
			// Now, we have to update the file path in the db
			$this->update($updateKeyVals, array('values' => $lastId, 'upload' => false));
		}
		
		return $this;
	}
	
	
	public function buildSelect($options = array())
	{
	    $this->init($options);
        
		// Set default params
		$o 					= array_merge($this->options, $options);
		$rModel 			= &$this->application->dataModel[$this->resourceName];
		
		$this->queryData 	= array(
			'fields' => array(),
			'tables' => array(),
		);

		// Get fields we want to request
		if ( !empty($o['getFields']) ) 	{ $this->magicFields($o['getFields']); }
		else 							{ $this->magicFields($rModel); }
		
		if ( !empty($o['count']) )
		{			
			$o['count'] 		= Tools::toArray($o['count']);

			// Get fields to use in the query
			foreach ($o['count'] as $field)
			{
				if ( isset($this->queryData['fields'][$field]) ){ $this->queryData['fields'][$field]['count'] = true; }
				
				else
				{
					$this->queryData['fields'][$field] = array(
						'name' => $field,
						'count' => true,
					);
				}
			}
		}

        if ( !empty($o['groupConcat']) )
        {
			$o['groupConcat'] 	= Tools::toArray($o['groupConcat']);
			
            // Get fields to use in the query
            $i = 0;
            foreach ($o['groupConcat'] as $k => $v)
            {
                $as                             = is_numeric($k) ? Tools::pluralize($v) : $v;
                $colName                        = is_numeric($k) ? $v : $k;
                $this->queryData['fields'][$as] = array(
                    'name'          => $colName,
                    'as'            => $as,
                    'cast'          => true,
                    'groupConcat'   => true,  
                );
				
				// Since we use an aggregate function, we have to use a group By clause
				if ( $i === 0 && empty($o['groupBy']) )
				{
					// use the first concatenated fields as grouping column
					$o['groupBy'] = array($colName);
				}
				
				$i++;
            }
        }
		
		// Case where we just want to count the number of records in the table
        if ( $o['mode'] === 'count')
		{
			// Set the field used to do the count. Try the 'id' field if it exists, otherwise use the first one defined in the datamodel
			$usedfield = isset($rModel['id']) ? 'id' : key($rModel);
			
			$where 		= $this->handleOperations($o);
			$conditions = $this->handleConditions($o + ( !empty($where) ? array('extra' => true) : array() ));
			
			$query 		= "SELECT COUNT(" . $this->escapeString($usedfield) . ") AS total ";
			$query 		.= "FROM " . _DB_TABLE_PREFIX . $this->table . " AS " . $this->alias . " ";
            $query      .=  $where . $conditions;
            
            // TODO
            //$query      .=  $groupBy;
            //$query      .=  ( !empty($orderBy) ? $orderBy . " " : '' );
            //$query      .=  ( !empty($o['limit']) && $o['limit'] != -1 ? "LIMIT " . $o['limit'] . " " : '' );
            //$query      .=  ( !empty($o['offset']) ? "OFFSET " . $o['offset'] . " " : '' );		
		}
		else if ( $o['mode'] === 'distinct' && !empty($o['field']))
		{
			$where 		= $this->handleOperations($o);
			$conditions = $this->handleConditions($o + ( !empty($where) ? array('extra' => true) : array() ));
					
			$query 		= "SELECT DISTINCT " . $o['field'] . " ";
            $query      .= "FROM " . _DB_TABLE_PREFIX . $this->table . " AS " . $this->alias . " ";
            $query      .=  $where . $conditions;
		}
		// Otherwise, do normal select
		else
		{	
			// Get tables to use in the query
			//$queryTables             	= array();
			$this->queryData['tables'] 	= array();
			$queryTables 				= &$this->queryData['tables'];
			$leftJoins               	= array();
			$alreadyJoinedTables     	= array();
			$ljcount                 	= 1;
			$crossJoins             	 = '';
			
//$this->dump($this->queryData['fields']);
			
			foreach ($rModel as $fieldName => $field)
			{
				$type = $field['type'];

				// Do not process relation fields
				if ( $type === 'onetomany' && ( empty($o['getFields']) || (!empty($o['getFields']) && in_array($fieldName, $o['getFields'])) ) )
				{
					$relType 			= !empty($field['relType']) ? $field['relType'] : 'onetomany';
					$relResource 		= !empty($field['relResource']) ? $field['relResource'] : $fieldName;
					$relTable 			= !empty($this->resources[$relResource]['table']) ? $this->resources[$relResource]['table'] : $relResource;
					$relResourceAlias 	= !empty($this->resources[$relResource]['alias']) ? $this->resources[$relResource]['alias'] : null;
					$relField 			= !empty($field['relField']) ? $field['relField'] : 'id';
					//$pivotResource 		= !empty($field['pivotResource']) ? $field['pivotResource'] : $this->resourceName . '_' . $relResource;
					$pivotResource 		= !empty($field['pivotResource']) ? $field['pivotResource'] : $this->resourceName . $relResource;
					$pivotTable 		 = !empty($this->resources[$pivotResource]['table']) ? $this->resources[$pivotResource]['table'] : $pivotResource;
					$pivotLeftField 	= !empty($field['pivotLeftField']) ? $field['pivotLeftField'] : $this->resourceSingular . '_' . 'id';
					$pivotRightField 	= !empty($field['pivotRightField']) ? $field['pivotRightField'] : $this->resources[$relResource]['singular'] . '_' . 'id';
					$pivotAlias 		= !empty($this->resources[$pivotResource]['alias']) ? $this->resources[$pivotResource]['alias'] : null;
					$getFields             = !empty($field['getFields']) ? Tools::toArray($field['getFields']) : array($relField, $this->resources[$relResource]['defaultNameField']);
					
					$crossJoins 		.= 'LEFT OUTER JOIN ' . $pivotTable . ( !empty($pivotAlias) ? ' AS ' . $pivotAlias : '');
					$crossJoins 		.= ' ON ' . $this->alias . '.' . $relField . ' = ' . ( !empty($pivotAlias) ? $pivotAlias : $pivotTable ) . '.' . $pivotLeftField  . ' ';
					$crossJoins 		.= 'LEFT OUTER JOIN ' . $relResource . ( !empty($relResourceAlias) ? ' AS ' . $relResourceAlias : '');
					$crossJoins 		.= ' ON ' . ( !empty($pivotAlias) ? $pivotAlias : $pivotTable ) . '.' . $pivotRightField . ' = ' . $relResourceAlias . '.' . $relField  . ' ';

					// Remove fake column from query fields since we are going to use 'getFields' (defaulted to resource defaultNameField if empty) 
					unset($this->queryData['fields'][$fieldName]);
					
					//$o['groupBy'] 	= !empty($o['groupBy']) ? $this->magic($o['groupBy']) : array();
					$o['groupBy']      = !empty($o['groupBy']) ? Tools::toArray($o['groupBy']) : array();
					$o['groupBy'][]    = 'id';

					// Loop over the fields we have to get
					foreach ($getFields as $item)
					{
						// Do not process fields that are not existing resource fields
						if ( empty($relResource) || empty($relResource[$item]) ) { continue; }
						
						// Build the storing name
						// ie: in a table 'users', a 'groups' with getFields('id,name')
						// will result in 'group_ids' and 'group_name' fields 
						//$storingName 	= $this->resources[$relResource]['singular'] . '_' . $this->pluralize($item);
						$storingName  = $this->resources[$relResource]['singular'] . '_' . Tools::pluralize($item);
						
						$this->queryData['fields'][$storingName] = array(
										'name' 			=> $item,
										'as' 			=> $storingName,
										'resource' 		=> $relResource,
										'table' 		=> $relTable,
										'tableAlias' 	=> $relResourceAlias,
										'cast' 			=> true,
										'groupConcat' 	=> true,
										'relation' 		=> 'onetomany',
						);	
					}
					
					// Build the storing name
					//$storingName = $pivotTable . '_id';
					//$storingName = $this->resourceSingular . '_' . $relResource  . '_ids';
					$storingName 	= $pivotResource . '_ids'; 
										
					$this->queryData['fields'][$storingName] = array(
									'name' 			=> 'id',
									'as' 			=> $storingName,
									'resource' 		=> $pivotResource,
									'table' 		=> $pivotTable,
									'tableAlias' 	=> $pivotAlias,
									'cast' 			=> true,
									'groupConcat' 	=> true,
									'relation' 		=> 'onetomany',
					);
					
					// Destroy tmp vars to prevent var name conflicts
					unset($relType, $relResource, $relTable, $relResourceAlias, $relField, $pivotResource, $pivotTable, $pivotLeftField, $pivotRightField, $pivotAlias, $getFields);
				}
				elseif ( !empty($field['relResource']) && ( empty($o['getFields']) || (!empty($o['getFields']) && in_array($fieldName, $o['getFields'])) ) )
				{
//$this->dump($field['relResource']);
					
					// Get proper table name
					//$field['relResource'] = ( !empty($this->resources[$field['relResource']]['table'] )
												//? $this->resources[$field['relResource']]['table']
											//: $field['relResource'] );
					$field['relTable'] = ( !empty($this->resources[$field['relResource']]['table'] )
												? $this->resources[$field['relResource']]['table']
											: $field['relResource'] );
//$this->dump($field['relResource']);
					
					//$queryTables[] = _DB_TABLE_PREFIX . $field['relResource'];
					$queryTables[] = _DB_TABLE_PREFIX . $field['relTable'];
					

					if ( !empty($field['relGetFields']) )
					{
						//$tmpFields = $this->magic($field['relGetFields']);
						$tmpFields = Tools::toArray($field['relGetFields']);
						
						// 2 possible models for the fields list:
						// case 1: array({$field1} => {$getField1As}, {$field2} => {$getField2As}, ...
						// case 2: or array({$field1}, {$field2}, ...)
						
//$this->dump($tmpFields);
//$this->dump($field);
						
						foreach ($tmpFields as $key => $val)
						{
							// Check the row index type to know in which case we are
							$which 			=  is_int($key) ? 2 : 1;
							$tmpFieldName 	= $which === 2 ? $val : $key;
							
							// In case where the table has already been joined on, we need to use a new table alias for the current join
							//$tmpTableAlias 	= !in_array($field['relResource'], $alreadyJoinedTables) 
							$tmpTableAlias 	= in_array($field['relResource'], $alreadyJoinedTables)
													//? null
													? $this->resources[$field['relResource']]['alias'] . $ljcount
													//: $this->resources[$field['relResource']]['alias'] . $ljcount;
													: $this->resources[$field['relResource']]['alias'];
													
//$this->dump($tmpFieldName);
//$this->dump($field['relResource']);
//$this->dump($this->resources[$field['relResource']]['alias']);
							
							$storingName 	= $which === 2 ? ( !empty($field['relGetAs']) ? $field['relGetAs'] : null) : $val;
							$this->queryData['fields'][$storingName] = array(
											'name' 			=> $tmpFieldName,
											'as' 			=> $storingName,
											//'resource' 		=> $field['relResource'],
											//'table' 		=> $field['relResource'],
											'table' 		=> $field['relTable'],
											'tableAlias' 	=> $tmpTableAlias,
											'count' 		=> isset($this->queryData['fields'][$storingName]['count']) ? $this->queryData['fields'][$storingName]['count'] : false,
							);
						}

						//$joinCondition 			= $this->alias . "." . $fieldName . " = " . (!empty($tmpTableAlias) ? $tmpTableAlias : $field['relResource']) . "." . $field['relField'];
						$joinCondition 			= $this->alias . "." . $fieldName . " = " . (!empty($tmpTableAlias) ? $tmpTableAlias : $field['relTable ']) . "." . $field['relField'];
						//$ljoin 					= "LEFT JOIN " . $field['relResource'];
						$ljoin 					= "LEFT JOIN " . $field['relTable'];
						$ljoin 					.= (!empty($tmpTableAlias) ? " AS " . $tmpTableAlias : '');
						$ljoin 					.= " ON " . $joinCondition . " ";
						$leftJoins[] 			= $ljoin; 
						//$alreadyJoinedTables[] 	= $field['relResource'];
						$alreadyJoinedTables[] 	= $field['relTable'];
						
						$ljcount++;
						
					}
				}
			}
			
			// Get fields to use in the query
			$i = 0;
			$finalFields = '';
			
			foreach ($this->queryData['fields'] as $k => $field)
			{	
				// Get the field type
				$resName 	= !empty($field['resource']) ? $field['resource'] : $this->resourceName;
				$res 		= &$this->application->dataModel[$resName];
				$type 		= !empty($res[$field['name']]['type']) ? $res[$field['name']]['type'] : '';
				//$type 		= isset($field['name']) && !empty($res[$field['name']]['type']) ? $res[$field['name']]['type'] : '';
				
				$finalFields .= ( $i > 0 ? ", " : '' ) 
								. ( $type === 'timestamp' ? "UNIX_TIMESTAMP(" : '')
								//. ( $type === 'int' ? "CAST('" : '')
								//. ( !empty($field['relation']) && $field['relation'] === 'onetomany' 
								. ( !empty($field['groupConcat'])
								    //? ' GROUP_CONCAT(DISTINCT CAST(' 
								    ? ' GROUP_CONCAT(CAST('
                                    : '' )
								. ( !empty($field['table']) 
									//? $field['table']
									? ( !empty($field['tableAlias']) ? $field['tableAlias'] : $field['table'] ) 
									//: $this->dbTableShortName ) . "."
									: $this->alias ) . "."
								. $field['name'] 
								//. ( !empty($field['as']) ? $field['as'] : $field['name'] )
								//. ( !empty($field['relation']) && $field['relation'] === 'onetomany' ? " AS CHAR) SEPARATOR ',' )" : '' )
								. ( !empty($field['groupConcat']) ? " AS CHAR) SEPARATOR ',' )" : '' )
								. ( !empty($field['as']) ? " AS " . $field['as'] : '' )
								. ( $type === 'timestamp' ? ") as " . $field['name'] : '' )
								//. ( $type === 'int' ? "' AS UNSIGNED INTEGER)" : '' )
								;
				
				//if ( !empty($field['table']) && !empty($field['as']) ){ $finalFields .= ( $i > 0 ? ", " : '' ) .  $this->dbTableShortName . "." . $k; }
				//if ( !empty($field['table']) && !empty($field['as']) ){ $finalFields .= ( $i > 0 ? ", " : '' ) .  $this->alias . "." . $k; }
				
				// Add the count if specified to				
				//if ( $field['count'] ){ $finalFields .= ( $i > 0 ? ", " : '' ) . "count(" . $this->dbTableShortName . "." . $k . ") AS " . $k . "_total"; }
				if ( !empty($field['count']) )
				{
					//$finalFields .= (( $i > 0 ) ? ", " : '' ) . "COUNT(" . $this->alias . "." . $k . ") AS " . $k . "_total";
					$finalFields .= ( !empty($finalFields) ? ", " : '' ) . "COUNT(" . $this->alias . "." . $k . ") AS " . $k . "_total";
				}
				
				$i++;
			}
			
			//$queryTables 	= !empty($getFields) ? '' : join(', ', $queryTables);
			$queryTables 	= empty($queryTables) ? '' : join(', ', $queryTables);
			//$leftJoins 		= !empty($getFields) ? '' : join('', $leftJoins);
			$leftJoins 		= empty($leftJoins) ? '' : join('', $leftJoins);
			
//var_dump($queryTables);
//$this->dump($this->queryData);
			
			$groupBy = $this->handleGroupBy($o);
			$orderBy = $this->handleOrder($o);
			
			$where 		= $this->handleOperations($o);
			$conditions = $this->handleConditions($o + ( !empty($where) ? array('extra' => true) : array() ));
			
			// Build final query  
			$query 		= 	"SELECT " . $finalFields . " ";
			//$query 		.= 	"FROM " . _DB_TABLE_PREFIX . $this->dbTableName . " AS " . $this->dbTableShortName . " ";
			$query 		.= 	"FROM " . _DB_TABLE_PREFIX . $this->table . " AS " . $this->alias . " ";
			$query 		.= 	( !empty($leftJoins) ? $leftJoins : " " );
			$query 		.= 	( !empty($crossJoins) ? $crossJoins : '' );
			$query 		.= 	$where . $conditions;
			$query 		.= 	$groupBy;
			$query 		.= 	( !empty($orderBy) ? $orderBy . " " : '' );
			$query 		.= 	( !empty($o['limit']) && $o['limit'] != -1 ? "LIMIT " . $o['limit'] . " " : '' );
			$query 		.= 	( !empty($o['offset']) ? "OFFSET " . $o['offset'] . " " : '' );
		}
		
		return $query;
	}
	

	public function buildInsert($resourceData, $options = array())
	{
        $this->init($options);
        
		$d 			= &$resourceData;										// Shortcut for resource data
		//$o 			= array_merge($this->options, $options); 				// Shortcut for options
		$o 			= array_merge(array(
			'onDuplicateUpdate' => false,	
		), $this->options, $options); 				// Shortcut for options
		
		
//$this->dump($o);
//$this->dump($options);
		
		$rName 		= &$this->resourceName;
		$rModel 	= &$this->application->dataModel[$this->resourceName];
		
		$fieldsNb 	= count($rModel);		// Get the number of fields for this resource
		$after 		= array();
		
		// Start writing request
		$query 		= "INSERT INTO " . _DB_TABLE_PREFIX . $this->table . " (";
		
		// Loop over the data model of the resource
		$i 			= 0;
		
		foreach ($rModel as $fieldName => $field)
		{
			$i++;
			if 		( $field['type'] === 'int' && isset($field['AI']) && $field['AI'] ){ continue; } // Do not process autoincremented fields
			else if ( $field['type'] === 'onetomany' ) { continue; }
			
			$query .= $this->safeWrapper . $fieldName . $this->safeWrapper . ($i < $fieldsNb ? ',' : ''); // Add each fields to the request, with coma if not last field
		}
		// Now we want to add the values
		$query 		.= ") VALUES (";
		
		// Loop over the passed resource data (filtered and validated POST data)
		$i 				= 0;
		$value			= null;
		$storedValues 	= array(); 
		foreach ($rModel as $fieldName => $field)
		{
			$skip = false;
				
			// Shortcuts
			/*
			$props = array('pk','ai','fk','type','subtype','default','length','relResource','relField','relGetFields','relGetAs',
							'computed','computedValues','eval','storeAs','destFolder','relatedFile');
			foreach ( $props as $prop ){ $$prop = !empty($field[$prop]) ? $field[$prop] : null; }
			*/
			
			$i++;
			
			// Do not process some fields
			if 		( ( $field['type'] === 'int' && isset($field['AI']) && $field['AI'] ) ){ $skip = true; }
			// TODO: handle subqueries for onetomany relations
			else if ( $field['type'] === 'onetomany' ) { $skip = true; }
			
			// Skip current field process is we have to 
			if ( $skip ) { continue; }
			
//var_dump($field);
//var_dump($d[$fieldName]);
			
			// Handle value treatments/filters via eval
			if ( !empty($field['eval']) && !empty($d[$fieldName]) )
			{
				$phpCode 		= str_replace('---self---', '\'' . $d[$fieldName] . '\'', $field['eval']);
				$d[$fieldName] 	= eval('return ' . $phpCode . ';');
				$phpCode 		= null;
			}
			
			// Handle specific cases
			if ( !empty($field['subtype']) && $field['subtype'] === 'file' && !empty($d[$fieldName]) )
			{
				// If the passed value is not an array, we do not handle file upload and just update db value 
				$uploadFile 	= is_array($d[$fieldName]);
				
                // Shortcut for uploaded file
                $uf             = &$d[$fieldName];
				
				$destRoot 		= !empty($field['destRoot']) ? $field['destRoot'] : ''; 
				$destFolder 	= $field['destFolder'];
				
				// Get the setted destination name or use the uploaded file name 
				$destName 		= !empty($field['destName'])
                                    ? str_replace(
                                           array("%file_extension%", "%file_name%", "%time%"), 
                                           array($uf['extension'], basename($uf['name'], '.' . $uf['extension']), $_SERVER['REQUEST_TIME']), 
                                        $field['destName'])
                                    : $uf['name'];
									
				if ( !$uploadFile )
				{
					$value = "'" . $this->escapeString(trim(stripslashes($d[$fieldName]))) . "'";
				}
				// Otherwise (common case)
				else
				{
					// Handle duplicates/thumbnails generation if necessary
					if ( !empty($field['duplicates']) )
					{
						$this->requireLibs(array('Image' => 'tools/'));
						$params = array_merge($field['duplicates'], array('src' => $d[$fieldName]['tmp_name']));
						
						$duplicate = Image::getInstance()->duplicate($params);
						$d[$fieldName]['duplicates'] = $duplicate;
					}
					
					// Reset some values
					$renameFolder 	= $renameFile = $tempName = null; 
					
					// Loop over the resource fields to replace placeholders by proper value
					foreach ($rModel as $key => $value)
					{
						// Special case for id where the data to use is not in the resource data but in the options in 'values' var
						$time 			= $key === 'id' ? ( !empty($_SERVER['REQUEST_TIME']) ? $_SERVER['REQUEST_TIME'] : time() ) : null;
						$tmpReplaceVal 	= $this->escapeString($time !== null ? $time : ( !empty($d[$key]) ? $d[$key] : ''));
										
						// If a placeholder for the current column is found in the destination name or the destination folder
						// replace by the proper value
						$nmPlaceholder 	= strpos($destName, '%resource[\'' . $key . '\']%') !== false;
						$fdPlaceholder 	= strpos($destFolder, '%resource[\'' . $key . '\']%') !== false;
	
						if ( $nmPlaceholder || $fdPlaceholder )
						{						
							$destFolder = str_replace("%resource['" . $key . "']%", $this->escapeString($tmpReplaceVal), $destFolder);	
							$destName 	= str_replace("%resource['" . $key . "']%", $this->escapeString($tmpReplaceVal), $destName);
						}
						
						// Have the folder and file to be renamed after upload (only if placeholders used in their names)
						$renameFolder 	= (isset($renameFolder) && $renameFolder) || $fdPlaceholder;
						$renameFile 	= (isset($renameFile) && $renameFile) || $nmPlaceholder; 
						$tempName 		= !empty($tempName) ? $tempName : $time;  		
					}
					
					if ( !empty($field['storeOn']) && $field['storeOn'] === 'amazon_S3' )
					{
						class_exists('AmazonS3') || require(_PATH_LIBS . 'storage/CloudFusion/cloudfusion.class.php');
						
						$s3 	= new AmazonS3(_AWS_ACCESSKEY, _AWS_SECRET_KEY);
						$acl 	= !empty($field['acl']) && constant($field['acl']) ? constant($field['acl']) : S3_ACL_PRIVATE;
						$dest 	= $destRoot . $destFolder . $destName;
						$body 	= file_get_contents($d[$fieldName]['tmp_name']);
						$bucket = !empty($field['bucket']) ? $field['bucket'] : _AWS_BASE_BUCKET;
						
						// If file exists, rename it with a time_suffix
						//if ( $s3->if_object_exists($bucket, $dest) ) { $s3->rename_object($bucket, $dest, $dest . '_old_' . time(), $acl); }
						$time = ( !empty($_SERVER['REQUEST_TIME']) ? $_SERVER['REQUEST_TIME'] : time() );
						if ( $s3->if_object_exists($bucket, $dest) ) { $s3->rename_object($bucket, $dest, $dest . '_old_' . $time, $acl); }
						
						
						// Then, create/replace the file/object
						$FileUpload 			= $s3->create_object($bucket, array('filename' => $dest, 'body' => $body, 'contentType' => $d[$fieldName]['type'], 'acl' => $acl )); 
						$FileUpload->success 	= !empty($FileUpload->status) && $FileUpload->status === 200;
						
						// Handle duplicates/thumbnails generation if necessary
						if ( !empty($field['duplicates']) && is_array($field['duplicates']) )
						{
							//$tmpTime = time();
							$tmpTime = ( !empty($_SERVER['REQUEST_TIME']) ? $_SERVER['REQUEST_TIME'] : time() );
							
							foreach ( $d[$fieldName]['duplicates'] as $dup )
							{
								$storedFilePath = $destFolder . $dup['outputFilename'] . '_' . $tmpTime . '.' . $dup['outputFormat'];
								$destDup 	= $destRoot . $storedFilePath;
								$bodyDup 	= file_get_contents( $dup['outputDirname'] . $dup['outputBasename']);
								$dupUpload 	= $s3->create_object($bucket, array('filename' => $destDup, 'body' => $bodyDup, 'contentType' => $d[$fieldName]['type'], 'acl' => $acl ));
	
								$d[$fieldName][$dup['prefix']] = $storedFilePath;
							}
						}
					}
					// Default upload by ftp
					else
					{
						// Launch the file upload
						class_exists('FileManager') || require(_PATH_LIBS . 'storage/FileManager.class.php');
						$FileUpload = FileManager::getInstance()->uploadByFtp($d[$fieldName], array(
							'destFolder' 	=> $destRoot . $destFolder,
							'destName' 		=> $destName,
							//'destRoot' 		=> $destRoot,
							'filePath' 		=> $d[$fieldName]['tmp_name'],
							'allowedTypes' 	=> $field['allowedTypes'],
						));
					}
					
					if ( $FileUpload->success )
					{					
					    if ( $renameFolder || $renameFile  )
                        {
							// Keep a flag if either the file destination folder or name should contain the resource id 
							// (which is not known as the moment where the file is uploaded)
							$after['rename'][] = array(
								'storeOn' 		=> !empty($field['storeOn']) ? $field['storeOn'] : 'ftp',
								'currentFolder' => $destFolder,
								'currentName' 	=> $destName,
								'renameFolder' 	=> $renameFolder,	// has the folder to be renamed
								'renameFile' 	=> $renameFile, 	// has the file to be renamed
								'tempName' 		=> $tempName,
								'dbField' 		=> $fieldName,		// database colum to update on rename success
								'destRoot' 		=> $destRoot,		// Root folder (may be empty), should not be stored in the db value
								'acl' 			=> !empty($acl) ? $acl : null,
							);
						}
					}
					else { $this->warnings[] = 6110; } // error on file upload
					
					// Even if the upload did not success, we need to add the value into db
					$value = "'" . ( !empty($field['storeAs']) && $field['storeAs'] !== 'filename' ?  '' : $destFolder) . $destName . "'";
				}
												

			}
			else if ( !empty($field['subtype']) && $field['subtype'] === 'fileMetaData' )
			{
				$relField 	= $field['relatedFile'];
				$meta 		= $field['meta'];
				$value 		= "'" . ( !empty($d[$relField]) ? $this->escapeString($d[$relField][$meta]) : '') . "'";
			}
			else if ( !empty($field['subtype']) && $field['subtype'] === 'fileDuplicate' )
			{
				$original 	= $field['original'];
				$prop 		= !empty($field['propertyName']) ? $field['propertyName'] : null;
				$tmpVal 	= $prop && !empty($d[$original][$prop]) ? $d[$original][$prop] : $d[$original];
				$value 		= "'" . ( !empty($d[$original]) ? $this->escapeString($tmpVal) : '') . "'";
			}
			else if ( !empty($field['subtype']) && $field['subtype'] === 'slug' && !empty($field['from']) )
			{
				$tmpVal = !empty($d[$fieldName]) 
							? Tools::slugify($d[$fieldName])
							: ( !empty($d[$field['from']]) ? Tools::slugify($d[$field['from']]) : '');
				$value 	= "'" . $this->escapeString($tmpVal) . "'";
			}
			// TODO: deprecated. remove the following condition
			else if ( isset($field['computed']) && $field['computed'] )
			{
				if ( $field['type'] === 'timestamp' ){ $value = $field['computedValue']; }
				// TODO: use proper str_replace ????
				else if ( !empty($field['subtype']) && $field['subtype'] === 'URIname' && !empty($field['useField']) )
				{
					$tmpVal = Tools::deaccentize($d[$field['useField']]);
					$value 	= "'" . str_replace(' ', '+', $tmpVal) . "'";
				}
				else if ( $field['computedValue'] === 'uniqId' )
				{
					if ( !empty($d[$fieldName]) ) { $value = "'" . $this->escapeString(trim($d[$fieldName])) . "'";  }
					else
					{
						$chars = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
						$value = '';
						$lim = 10;
						for ($j = 0; $j < $lim; $j++) { $value .= $chars[rand(0, strlen($chars) - 1)]; }
						$value = "'" . $value . "'";
					}
				}
				else if ( $field['type'] === 'varchar' ){ $value = "'" . $this->escapeString($field['computedValue']) . "'"; }
				else { $value = !empty($field['computedValue']) ? $field['computedValue'] : "''"; }
			}
			else if ( $field['type'] === 'text' )
			{
				$value = "'" . $this->escapeString( isset($d[$fieldName]) ? trim(stripslashes($d[$fieldName])) : '') . "'";
			}
			else if ( $field['type'] === 'varchar' && !empty($field['subtype']) && $field['subtype'] === 'password' )
			{
				$tmpVal = !empty($d[$fieldName]) ? sha1($this->escapeString($d[$fieldName])) : '';
				$value = "'" . $this->escapeString($tmpVal) . "'";
			}
			else if ( $field['type'] === 'varchar' && !empty($field['subtype']) && $field['subtype'] === 'uniqueID' )
			{
				$len 	= !empty($field['length']) ? $field['length'] : 8; 
                $uniqID = Tools::generateUniqueID(array('length' => $len, 'resource' => $rName, 'field' => $fieldName));
				$value 	= "'" . $uniqID . "'";
			}
			else if ( $field['type'] === 'enum' )
			{
				$tmpVal = !empty($d[$fieldName]) ? $d[$fieldName] : ( !empty($field['default']) ? $field['default'] : '' );
				$value = "'" . $this->escapeString(trim(stripslashes($tmpVal))) . "'";  
			}
			else if ( $field['type'] === 'point' )
			{
				$tmpVal = !empty($d[$fieldName]) ? $d[$fieldName] : ( !empty($field['default']) ? $field['default'] : '' );
				if ($tmpVal !== '') $value = $this->escapeString(trim(stripslashes('POINTFROMTEXT(\''.$tmpVal.'\')')));
				else $value = "''";
			}
			else if ( $field['type'] === 'varchar' )
			{
				$tmpVal = !empty($d[$fieldName]) ? $d[$fieldName] : ( !empty($field['default']) ? $field['default'] : '' );
				$value = "'" . $this->escapeString(trim(stripslashes($tmpVal))) . "'";   
			}
			else if ( $field['type'] === 'bool' ) { $value = ( !empty($d[$fieldName]) && $d[$fieldName]) ? 1 : 0; }
			// Otherwise, just take the posted data value
			//else { $value = $d[$fieldName]; }
			else if ( $field['type'] === 'float' )
			{
				$tmpVal = !empty($d[$fieldName]) ? $d[$fieldName] : ( !empty($field['default']) ? $field['default'] : 0 );
				$value = "'" . $this->escapeString(  str_replace(',','.',(string)($tmpVal))) . "'";
			}
			else if ( $field['type'] === 'date' )
			{
				$tmpVal = !empty($d[$fieldName]) ? $d[$fieldName] : ( !empty($field['default']) ? $field['default'] : '' );
				$value = "'" . $this->escapeString(trim(stripslashes($tmpVal))) . "'";
			}
			else if ( $field['type'] === 'datetime' )
			{
				$tmpVal = !empty($d[$fieldName]) ? $d[$fieldName] : ( !empty($field['default']) ? $field['default'] : '' );
				$value = "'" . $this->escapeString(trim(stripslashes($tmpVal))) . "'";
			}
			else if ( $field['type'] === 'timestamp' )
			{
				// Get the passed value if present, otherwise, try to use default value
                $tmpVal = isset($d[$fieldName])
                            ? $d[$fieldName] 
                            : ( isset($field['default']) || is_null($field['default'])
                                ? ( strpos($field['default'], 'now') !== false 
                                    ? time() 
                                    : ( is_null($field['default']) ? "NULL" : strtotime($field['default']) ) 
                                )
                                : ( !empty($_SERVER['REQUEST_TIME']) ? $_SERVER['REQUEST_TIME'] : time() ) 
                            );
                $value  = is_int($tmpVal) && $tmpVal < 0 
                            ? "DATE_ADD(FROM_UNIXTIME(0), INTERVAL " . $this->escapeString($tmpVal) ." SECOND)"
                            //: "FROM_UNIXTIME('" . $this->escapeString($tmpVal) . "')";
                            : ( $tmpVal === 'NULL'  ? $tmpVal : "FROM_UNIXTIME('" . $this->escapeString($tmpVal) . "')" );
			}
			else if ( $field['type'] === 'datetime' )
			{
				// TODO: how to handle not posted fileds
				$d[$fieldName] = isset($d[$fieldName]) ? $d[$fieldName] : '';
				
				$value = "'" . $this->escapeString($d[$fieldName]) . "'";
			}
			else if ( $field['type'] === 'int' && !empty($field['fk']) )
			{
				$value = !empty($d[$fieldName]) 
							? $d[$fieldName] 
							: (isset($field['default']) ? ( is_null($field['default']) ? "NULL" : $field['default']) : "NULL");
			}
			// Otherwise, just take the posted data value
			else
			{
				// TODO: isset(null) => false how to test if default prop setted, even if set to null?
				$value = !empty($d[$fieldName]) 
							? $d[$fieldName] 
							: (isset($field['default']) ? ( is_null($field['default']) ? "NULL" : $field['default']) : "''");
			}
			
			// Finally, add the value to the request
			$query .= $value . ($i < $fieldsNb ? ',' : ''); // Add each fields to the request, with coma if not last field
			
			// And store it in an array, for possible later use
			$storeValues[$fieldName] = $value; 
		}
		
		// Finish writing the request
		$query 		.= ")";
		
		// Handle "upsert" request
		$query 		.= $o['onDuplicateUpdate'] ? " ON DUPLICATE KEY UPDATE" : '';
		// TODO add {column} = {value} couples to be upserted
		
		
		//if ( !empty($o['returning']) ) { $query .= " RETURNING " . $o['returning']; }
		
		//$this->launchedQuery 	= $query;
		$this->afterQuery 		= $after;
		
		return $query;
	}
	
	
	public function buildUpdate($resourceData, $options = array())
	{
        $this->init($options);
        
		$d 			= &$resourceData;										// Shortcut for resource data
		$o 			= array_merge($this->options, $options); 				// Shortcut for options 											// Shortcut for options
		$fieldsNb 	= count($d);											// Get the number of fields for this resource
		
		$rName 		= &$this->resourceName;
		$rModel 	= &$this->application->dataModel[$this->resourceName];
		
		// Start writing request
		$query 		= "UPDATE ";
		$query 		.=  _DB_TABLE_PREFIX . $this->table . " AS " . $this->alias . " ";
		$query 		.= "SET ";
		
//$this->dump($d);
		
		// Loop over the passed resource data (filtered and validated POST data)
		$i 			= 0;
		$value		= null;
		foreach ($rModel as $fieldName => $field)
		{
			// Shortcuts, maybe usefull to prevent !empty() checks and use props directly
			/*
			$props = array('pk','ai','fk','type','subtype','default','length','relResource','relField','relGetFields','relGetAs',
							'computed','computedValues','eval','storeAs','destFolder','relatedFile');
			foreach ( $props as $prop ){ $$prop = !empty($field[$prop]) ? $field[$prop] : null; }
			*/
			
			// If a field is not passed in the data, do not add it to the request
			$skip = !isset($d[$fieldName]) || $d[$fieldName] === null;
			//$skip = !isset($d[$fieldName]);
			
			// Do not process not editable, autoincrement fields fields
			if ( ( isset($field['editable']) && !$field['editable'] ) || ( $field['type'] === 'int' && isset($field['AI']) && $field['AI'] ) )
				{ $skip = true; }
				
			if ( isset($field['forceUpdate']) && $field['forceUpdate'] ){ $skip = false; }
            
            if ( !empty($field['null']) || ( isset($field['default']) && is_null($field['default']) ) ){ $skip = false; }
			
			// except for fields whose subtype is fileMetaData
			if ( !empty($field['subtype']) && $field['subtype'] === 'fileMetaData' && !empty($d[$field['relatedFile']]) ) { $skip = false; }
			
			// except for fields whose subtype is fileDuplicate
			else if ( !empty($field['subtype']) && $field['subtype'] === 'fileDuplicate' && !empty($field['original']) && !empty($d[$field['original']]) ) { $skip = false; }
			
			//else if ( !empty($field['subtype']) && $field['subtype'] === 'uniqueID' ) { $skip = false; }
			
			// For password fields, only users to modifie oneself password 
			//if ( isset($field['subtype']) && $field['subtype'] === 'password' && $this->resourceName === 'users' )
			if ( isset($field['subtype']) && $field['subtype'] === 'password' && $this->resourceName === 'users' && !empty($d[$fieldName]) )
			{
				// Get the user whose data are being updated and get the logged user
				$updatedUser 	= CUsers::getInstance()->retrieve(array_merge($o, array('limit' => 1)));
				//$currentUser 	= !empty($this->data['current']['user']) ? $this->data['current']['user'] : null; 
				$currentUser 	= CUsers::getInstance()->retrieve(array_merge($o, array('limit' => 1, 'by' => 'id', 'values' => $_SESSION['user_id'])));
                
                // Get logged & updated users groups
                $curUGroups     = !empty($currentUser['group_admin_titles']) ? explode(',',$currentUser['group_admin_titles']) : array();
                $upUGroups      = !empty($updatedUser['group_admin_titles']) ? explode(',',$updatedUser['group_admin_titles']) : array();

				// Has the current user higher authorization than the updated one
				$foundUsersData = !empty($updatedUser) && !empty($currentUser);
				
				// Only god users, or a superadmins (if the update user is not a god or a superadmin too) have higher auths
				$hasHigherAuth 	= in_array('gods', $curUGroups) || ( in_array('superadmins', $curUGroups) && count(array_intersect($upUGroups, array('gods','superadmins'))) ); 

				$allowEdit	 	= $foundUsersData && ( $updatedUser['id'] === $currentUser['id'] || $hasHigherAuth);
				$skip 			= $allowEdit ? false : true;
				
				// If the users data have been found but the current user is not allowed to edit password for this user 
				if ( $foundUsersData && $skip ){ $this->warnings[] = 6050; }
			}
			
			// Skip current field process is we have to 
			if ( $skip ) { continue; }
			
			$i++;
			
			// Handle value treatments/filters via eval
			if ( !empty($field['eval']) )
			{
				$phpCode 		= str_replace('---self---', '\'' . $d[$fieldName] . '\'', $field['eval']);
				$d[$fieldName] 	= eval('return ' . $phpCode . ';');
				$phpCode 		= null;
			}
			
			// Handle specific cases
			//if 		( $field['type'] === 'int' && isset($field['AI']) && $field['AI'] ){ continue; } // Do not process auto-incremented fields
			//else if ( !empty($field['subtype']) && $field['subtype'] === 'file' && ( !isset($o['upload']) || $o['upload'] !== false ) )
			if ( !empty($field['subtype']) && $field['subtype'] === 'file' && ( !isset($o['upload']) || $o['upload'] !== false ) )
			{
				// If the passed value is not an array, we do not handle file upload and just update db value 
				$uploadFile 	= is_array($d[$fieldName]);
				
				$destRoot 		= !empty($field['destRoot']) ? $field['destRoot'] : ''; 
				$destFolder 	= $field['destFolder'];
				
				if ( isset($_GET['forceFileDeletion']) && $_GET['forceFileDeletion'] )
				{
					//class_exists('FileManager') || require(_PATH_LIBS . 'storage/FileManager.class.php');
					$this->requireLibs(array('FileManager' => 'storage/'));
					
					
					// Get the current value
					$curData 	= $this->retrieve(array_merge($options), array('getFields' => $fieldName));
					$curVal 	= !empty($curData) && isset($curData[$fieldName]) ? $curData[$fieldName] : null;
					$filePath 	= $destRoot . $curVal;
					
					// Remove the file from the ftp
					if ( !empty($curVal) ){ FileManager::getInstance()->delete($filePath); }

					// Finally, set the new value as an empty string
					$value = "''";
				}
				else if ( !$uploadFile )
				{
					//$value = "'" . $this->escapeString(trim(stripslashes($d[$fieldName]))) . "'";
					$value = "'" . $this->escapeString(trim(stripslashes($d[$fieldName]))) . "'";
				}
				// Otherwise (common case)
				else
				{					
				    // Shortcut for uploaded file
				    $uf = &$d[$fieldName];
                    			
					// Get the setted destination name or use the uploaded file name 
					$destName 		= !empty($field['destName'])
                                        ? str_replace(
                                               array("%file_extension%", "%file_name%", "%time%"), 
                                               array($uf['extension'], basename($uf['name'], '.' . $uf['extension']), $_SERVER['REQUEST_TIME']), 
                                            $field['destName'])
                                        : $uf['name'];
					
					if ( !empty($field['duplicates']) )
					{
						$this->requireLibs(array('Image' => 'tools/'));
						$params = array_merge($field['duplicates'], array('src' => $d[$fieldName]['tmp_name']));
						
						$duplicate = Image::getInstance()->duplicate($params);
						$d[$fieldName]['duplicates'] = $duplicate;
					}
					
					// Loop over the resource fields to replace placeholders by proper value
					foreach ($rModel as $key => $value)
					{
						// Special case for id where the data to use is not in the resource data but in the options in 'values' var
						$tmpReplaceVal = $this->escapeString($key === 'id' ? $o['values'] : ( !empty($d[$key]) ? $d[$key] : ''));
										
						// If a placeholder for the current field is found in the destination name or the destination folder
						// replace by the proper value
						if ( strpos($destName, '%resource[\'' . $key . '\']%') !== false || strpos($destFolder, '%resource[\'' . $key . '\']%') !== false )
						{						
							$destFolder = str_replace("%resource['" . $key . "']%", $this->escapeString($tmpReplaceVal), $destFolder);	
							$destName 	= str_replace("%resource['" . $key . "']%", $this->escapeString($tmpReplaceVal), $destName);	
						}
					}
					
					if ( !empty($field['storeOn']) && $field['storeOn'] === 'amazon_S3' )
					{
						class_exists('AmazonS3') || require(_PATH_LIBS . 'storage/CloudFusion/cloudfusion.class.php');
						
						$s3 	= new AmazonS3(_AWS_ACCESSKEY, _AWS_SECRET_KEY);
						$acl 	= !empty($field['acl']) && constant($field['acl']) ? constant($field['acl']) : S3_ACL_PRIVATE;
						$dest 	= $destRoot . $destFolder . $destName;
						$body 	= file_get_contents($d[$fieldName]['tmp_name']);
						$bucket = !empty($field['bucket']) ? $field['bucket'] : _AWS_BASE_BUCKET;
						
						// If file exists, rename it with a time_suffix
						//if ( $s3->if_object_exists($bucket, $dest) ) { $s3->rename_object($bucket, $dest, $dest . '_old_' . time(), $acl); }
						$time 	= ( !empty($_SERVER['REQUEST_TIME']) ? $_SERVER['REQUEST_TIME'] : time() );
						if ( $s3->if_object_exists($bucket, $dest) ) { $s3->rename_object($bucket, $dest, $dest . '_old_' . $time, $acl); }
						 
						
						// Then, create/replace the file/object
						$FileUpload 			= $s3->create_object($bucket, array('filename' => $dest, 'body' => $body, 'contentType' => $d[$fieldName]['type'], 'acl' => $acl )); 
						$FileUpload->success 	= !empty($FileUpload->status) && $FileUpload->status === 200;
						
						// Handle duplicates if necessary
						if ( !empty($field['duplicates']) && is_array($field['duplicates']) )
						{
							//$tmpTime = time();
							$tmpTime = !empty($_SERVER['REQUEST_TIME']) ? $_SERVER['REQUEST_TIME'] : time();
							
							foreach ( $d[$fieldName]['duplicates'] as $dup )
							{
								$storedFilePath = $destFolder . $dup['outputFilename'] . '_' . $tmpTime . '.' . $dup['outputFormat'];
								$destDup 		= $destRoot . $storedFilePath;
								$bodyDup 		= file_get_contents( $dup['outputDirname'] . $dup['outputBasename']);
								
								// If file exists, rename it with a time_suffix
								if ( $s3->if_object_exists($bucket, $destDup) ) { $s3->rename_object($bucket, $destDup, $destDup . '_old_' . $tmpTime, $acl); }
								
								$dupUpload 	= $s3->create_object($bucket, array('filename' => $destDup, 'body' => $bodyDup, 'contentType' => $d[$fieldName]['type'], 'acl' => $acl ));
								
								$d[$fieldName][$dup['prefix']] = $storedFilePath;
							}
						}
					}
					// Default upload by ftp
					else
					{
						class_exists('FileManager') || require(_PATH_LIBS . 'storage/FileManager.class.php');
						
						$FileUpload = FileManager::getInstance()->uploadByFtp($d[$fieldName], array(
							'destFolder' 	=> $destRoot . $destFolder,
							'destName' 		=> $destName,
							//'destRoot' 		=> $destRoot,
							'filePath' 		=> $d[$fieldName]['tmp_name'],
							'allowedTypes' 	=> $field['allowedTypes'],
						));
					}
					
					if ( $FileUpload->success )
					{
						//$value = "'" . ( !empty($field['storeAs']) && $field['storeAs'] !== 'filename' ?  '' : $destFolder) . $destName . "'";
					}
					//else { continue; }
					else { $this->warnings[] = 6110; } // error on file upload
					
					$value = "'" . ( !empty($field['storeAs']) && $field['storeAs'] !== 'filename' ?  '' : $destFolder) . $destName . "'";	
				}
			}
			else if ( !empty($field['subtype']) && $field['subtype'] === 'fileMetaData' )
			{
				// If the upload options has been set to false, do not process the fied
				if ( !empty($o['upload']) && !$o['upload'] ) { continue; }
				
				$relField 	= $field['relatedFile'];
				$meta 		= $field['meta'];
				$value 		= "'" . ( !empty($d[$relField]) ? $this->escapeString($d[$relField][$meta]) : '') . "'";
			}
			else if ( !empty($field['subtype']) && $field['subtype'] === 'fileDuplicate' )
			{
				// If the upload options has been set to false, do not process the field
				if ( isset($o['upload']) && !$o['upload'] ) { continue; }
				
				$original 	= $field['original'];
				$prop 		= !empty($field['propertyName']) ? $field['propertyName'] : null;
				$tmpVal 	= $prop && !empty($d[$original][$prop]) ? $d[$original][$prop] : ( is_string($d[$original]) ? $d[$original] : '' );
				$value 		= "'" . ( !empty($d[$original]) ? $this->escapeString($tmpVal) : '') . "'";
			}
			else if ( !empty($field['subtype']) && $field['subtype'] === 'slug' && !empty($field['from']) )
			{
				$tmpVal = !empty($d[$fieldName]) 
							//? $this->slugify($d[$fieldName])
							? Tools::slugify($d[$fieldName])
							//: ( !empty($d[$field['from']]) ? $this->slugify($d[$field['from']]) : '');
							: ( !empty($d[$field['from']]) ? Tools::slugify($d[$field['from']]) : '');
				$value 	= "'" . $this->escapeString($tmpVal) . "'";
			}
			//else if ( !empty($field['computed']) && $field['type'] === 'timestamp' ){ $value = $field['computedValue']; }
			else if ( !empty($field['computed']) && !empty($field['subtype'])  )
			{
				if ( $field['subtype'] === 'URIname' && !empty($field['useField']) )
				{
				    $charsTable = array(
				        'Š'=>'S', 'š'=>'s', 'Đ'=>'Dj', 'đ'=>'dj', 'Ž'=>'Z', 'ž'=>'z', 'Č'=>'C', 'č'=>'c', 'Ć'=>'C', 'ć'=>'c',
				        'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
				        'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
				        'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss',
				        'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e',
				        'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o',
				        'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b',
				        'ÿ'=>'y', 'Ŕ'=>'R', 'ŕ'=>'r',
				    );
					$tmpVal = $d[$field['useField']];
					$tmpVal = strtr($tmpVal,$charsTable);
					$value 	= "'" . str_replace(' ', '+', $tmpVal) . "'";
				}
				else { $value = $d[$fieldName]; }
			}
			else if ( $field['type'] === 'text' )
			{
				//$value = "'" . $this->escapeString(trim($d[$fieldName])) . "'";
				$value = "'" . $this->escapeString(trim(stripslashes($d[$fieldName]))) . "'";
				//$value = "'" . trim($d[$fieldName])) . "'";
			}
			//else if ( $field['type'] === 'bool' ) { $value = ( !empty($d[$fieldName]) && $d[$fieldName]) ? 'true' : 'false'; }
			else if ( $field['type'] === 'bool' ) { $value = ( !empty($d[$fieldName]) && $d[$fieldName]) ? 1 : 0; }
			else if ( $field['type'] === 'float' )
			{
				$value = "'" . $this->escapeString(  str_replace(',','.',(string)($d[$fieldName]))) . "'";
			}
			/*
			else if ( $field['type'] === 'timestamp' )
			{
				$value = is_int($d[$fieldName]) && $d[$fieldName] < 0 
							? "DATE_ADD(FROM_UNIXTIME(0), INTERVAL " . $d[$fieldName] ." SECOND)"
							: "FROM_UNIXTIME('" . $d[$fieldName] . "')";
			}
			*/
			else if ( $field['type'] === 'date' )
			{
				$tmpVal = !empty($d[$fieldName]) ? $d[$fieldName] : ( !empty($field['default']) ? $field['default'] : '' );
				$value = "'" . $this->escapeString(trim(stripslashes($tmpVal))) . "'";
			}
			else if ( $field['type'] === 'datetime' )
			{
				$tmpVal = !empty($d[$fieldName]) ? $d[$fieldName] : ( !empty($field['default']) ? $field['default'] : '' );
				$value = "'" . $this->escapeString(trim(stripslashes($tmpVal))) . "'";
			}
			else if ( $field['type'] === 'timestamp' )
			{
				// Get the passed value if present, otherwise, try to use default value
				//$tmpVal = !empty($d[$fieldName]) ? $d[$fieldName] : (isset($field['default']) ? ( strpos($field['default'], 'now') !== false ? time() : '0' ) : 0);
				//$tmpVal = !empty($d[$fieldName]) ? $d[$fieldName] : (isset($field['default']) ? ( strpos($field['default'], 'now') !== false ? time() : '0' ) : time());
				//$tmpVal = !empty($d[$fieldName]) 
				$tmpVal = isset($d[$fieldName])
							? $d[$fieldName] 
							: ( isset($field['default']) || is_null($field['default'])
                                //? ( strpos($field['default'], 'now') !== false ? time() : '0' )
                                ? ( strpos($field['default'], 'now') !== false 
                                    ? time() 
                                    : ( is_null($field['default']) ? "NULL" : strtotime($field['default']) ) 
                                )
                                //: time() );
                                : ( !empty($_SERVER['REQUEST_TIME']) ? $_SERVER['REQUEST_TIME'] : time() ) 
                            );
				$value 	= is_int($tmpVal) && $tmpVal < 0 
							? "DATE_ADD(FROM_UNIXTIME(0), INTERVAL " . $this->escapeString($tmpVal) ." SECOND)"
							//: "FROM_UNIXTIME('" . $this->escapeString($tmpVal) . "')";
							: ( $tmpVal === 'NULL'  ? $tmpVal : "FROM_UNIXTIME('" . $this->escapeString($tmpVal) . "')" );
			}
			else if ( $field['type'] === 'datetime' )
			{
				// TODO: how to handle not posted fileds
				$d[$fieldName] = isset($d[$fieldName]) ? $d[$fieldName] : '';
				
				$value = "'" . $this->escapeString($d[$fieldName]) . "'";
			}
            else if ( $field['type'] === 'int' && !empty($field['fk']) )
            {
                $value = !empty($d[$fieldName]) 
                            ? $d[$fieldName] 
                            : (isset($field['default']) ? ( is_null($field['default']) ? "NULL" : $field['default']) : "NULL");
            }
			//else if ( $field['type'] === 'varchar' )
			//else if ( $field['type'] === 'varchar' && !empty($field['subtype']) && $field['subtype'] === 'password' )
			else if ( $field['type'] === 'varchar' && !empty($field['subtype']) && $field['subtype'] === 'password' && !empty($d[$fieldName]) )
			{
				$tmpVal = !empty($d[$fieldName]) ? sha1($d[$fieldName]) : '';
				$value 	= "'" . $tmpVal . "'";
			}
			else if ( $field['type'] === 'varchar' && !empty($field['subtype']) && $field['subtype'] === 'uniqueID' )
			{
				$len 	= !empty($field['length']) ? $field['length'] : 8;
				$uniqID = !empty($d[$fieldName]) 
							? $d[$fieldName]
							//: $this->generateUniqueID(array('length' => $len, 'resource' => $rName, 'field' => $fieldName)); 
							: Tools::generateUniqueID(array('length' => $len, 'resource' => $rName, 'field' => $fieldName));
				$value 	= "'" . $this->escapeString(trim(stripslashes($uniqID))) . "'";
			}
			else if ( $field['type'] === 'enum' )
			{
				$tmpVal = !empty($d[$fieldName]) ? $d[$fieldName] : ( !empty($field['default']) ? $field['default'] : '' );
				$value = "'" . $this->escapeString(trim(stripslashes($tmpVal))) . "'";  
				//$value = "'" . $this->escapeString(trim($tmpVal)) . "'";
			}
			else if ( $field['type'] === 'point' )
			{
				$tmpVal = !empty($d[$fieldName]) ? $d[$fieldName] : ( !empty($field['default']) ? $field['default'] : '' );
				if ($tmpVal !== '') $value = $this->escapeString(trim(stripslashes('POINTFROMTEXT('.$tmpVal.')')));
				else $value = "''";
			}
			else if ( $field['type'] === 'varchar' )
			{
				//$value = "'" . $this->escapeString($d[$fieldName]) . "'";
				$value = "'" . $this->escapeString(trim(stripslashes($d[$fieldName]))) . "'";
				//$value = "'" . $this->escapeString(trim($d[$fieldName])) . "'";
			}
			// Otherwise, just take the posted data value
			else
			{
				$value = "'" . $d[$fieldName] . "'";
			}
			
            // Add each fields to the request, with coma if not last field
			//$query .= ($i == 1 ? '' : ', ') . $this->escapeColumn($fieldName) . " = " . $value;
			$query .= ($i == 1 ? '' : ', ') .  $this->escapeColumn($fieldName);
			//$query .= ($i == 1 ? '' : ', ') . $this->alias . '.' . $fieldName;
			$query .= " = " . $value; // Add each fields to the request, with coma if not last field
		}
		
        
        $where      = $this->handleOperations($o);
        $conditions = $this->handleConditions($o + ( !empty($where) ? array('extra' => true) : array() ));
		$orderBy = $this->handleOrder($o);
		
		// Finish writing the request
		//$query 		.= !empty($o['conditions'])
		//				? " " . $this->handleConditions($o)
		//				: " WHERE " . $this->safeWrapper . $o['by'] . $this->safeWrapper . " = '" . $this->escapeString($o['values']) . "'";
        $query      .= ' ' . $where . $conditions;
		$query 		.= 	( !empty($orderBy) ? $orderBy . " " : '' );
		$query 		.= 	( !empty($o['limit']) && $o['limit'] != -1 ? " LIMIT " . $o['limit'] . " " : '' );
		$query 		.= 	( !empty($o['offset']) ? " OFFSET " . $o['offset'] . " " : '' );
		
		//$this->launchedQuery = $query;
		
		return $query;
	}


	public function buildDelete($options = array())
	{
        $this->init($options);
	    
		$o 			= array_merge($this->options, $options); 				// Shortcut for options 											// Shortcut for options

		$where 		= $this->handleOperations($o);
		$conditions = $this->handleConditions($o);
	
		// Start writing request
		// When using "AS", mysql seems to want to have it defined just before the FROM
		$query 		= "DELETE " . $this->alias . " ";
		$query 		.= "FROM " . _DB_TABLE_PREFIX . $this->table . " AS " . $this->alias . " ";
		$query 		.= 	$where . $conditions;
		
		//$this->launchedQuery = $query;
		
		return $query;
	}

	/*
	 * ### Accepted formats for conditions param
	 * $conditions = array(
	 * 		$fields1 => $values1, 
	 * 		$fields2 => $values2, 
	 * 		...
	 *	); // <== no operator passed, use = as default one
	 * $conditions = array(
	 * 		array($fields1,$operator1,$values1), 
	 * 		array($fields2,$operator2,$values2),
	 * 		...
	 * );
	 * 
	 * ### Allowed operators
	 * ----------------------
	 * contains: 			LIKE + %value%
	 * does not contain: 	NOT LIKE + %value%
	 * starts by: 			LIKE + value%
	 * ends by: 			LIKE + %value
	 * not: 				NOT IN (values)
	 * greater: 			> value
	 * lower: 				< value
	 * greater or equal:	>= value
	 * lower or equal: 		<= value
	 * equal: (default)		= value
	 * not equal:			!= value
	 * is not:				NOT IN (values)
	 * is:					IN (values)
	*/	
	public function handleConditions($options = array())
	{		
		$o 				= &$options; 												// Shortcut for $options
		$output 		= '';														// Initialize conditions request outptut
				
		// Do not continue if there's no conditions to handle 
		if ( empty($o['conditions']) ) { return $output; }

		// Known operators
		$knownOps         = array(
			'contains'           => 'LIKE',          // + %value% // TODO
			'like'               => 'LIKE',          // + %value% // TODO
			'doesnotcontains'    => 'NOT LIKE',      // + %value% // TODO
			'notlike'            => 'NOT LIKE',      // + %value% // TODO
			'startsby'           => 'LIKE',          // + value% // TODO
			'endsby'             => 'LIKE',          // + %value // TODO
			'doesnotstartsby'    => 'NOT LIKE',      // + value% // TODO
			'doesnotendsby'      => 'NOT LIKE',      // + %value // TODO
			'not'                => '!=',
			'notin'              => 'NOT IN',
			'greater'            => '>',
			'>'                  => '>',
			'lower'              => '<',
			'<'                  => '<',
			'greaterorequal'     => '>=',
			'>='                 => '>=',
			'lowerorequal'       => '<=',
			'<='                 => '<=',
			'is'                 => '=',
			'equal'              => '=',
			'='                  => '=',
			'in'                 => 'IN',
			'isnot'              => '!=',
			'notequal'           => '!=',
			'!='                 => '!=',
			'notin'              => 'NOT IN',
			'between'            => 'BETWEEN',       // TODO
			'notbetween'         => 'NOT BETWEEN',   // TODO
			// TODO: handle between
		);
		$uniques      = array('>','<','>=','<=');             // operator whose value can only by unique
		$oneAtATime   = array('LIKE','NOT LIKE','=','!='); 	  // operators allowing multiple conditions but with only 1 at a time
		$i            = 0;
		
//$this->dump('foreach conditions');
//$this->dump($o['conditions']);
		
		// Loop over the passed conditions
		foreach ($o['conditions'] as $key => $condition)
		{			
			// If the key is numeric, assume that the conditions array is associative
			// matching the following pattern array($field1 => $values1, [...])
			// and then reformat it into array(array($field1,$values1), [...])
			$condition       = !is_numeric($key) ? array($key,$condition) : $condition;
			
//$this->dump('key: ' . $key);
//$this->dump('condition:');
//$this->dump($condition);
			
			// Do not continue if the current item is not an array, throwing a warning by the way
			if ( !is_array($condition) ){ $this->warnings[4210] = $condition; continue; } // 'Wrong condition format
			
			$fields        = $condition[0];
			$values        = count($condition) > 2 ? $condition[2] : $condition[1];
			// TODO: what if we whant to use float values? have to use "." in float numbers?
			$multiValues   = is_array($values) || ( is_string($values) && strpos($values, ',') !== false );
			$multiFields   = is_array($fields) || ( is_string($fields) && strpos($fields, ',') !== false );
			$operator      = count($condition) > 2 ? strtolower(str_replace(' ', '', $condition[1])) : '=';
			$usedOperator  = $knownOps[$operator];
            
            // Do not continue if the current operator does not belong to the known ones, throwing a warning by the way
            if ( !isset($knownOps[$operator]) ){ $this->warnings[4215] = $operator; continue; } // Unknown operator
      
            // Special case when operator is IN/NOT IN and value is single, use =/!= instead
            $usedOperator   = in_array($usedOperator, array('IN','NOT IN')) && !$multiValues ? ( $usedOperator === 'NOT IN' ? '!=' : '=' ) : $usedOperator;

//$this->dump($values);
//$this->dump(count($values));
//$this->dump('$usedOperator: ' . $usedOperator);
//$this->dump('$multiValues: ' . ($multiValues?'true':'false'));
//$this->dump($values);

            // Special case if the operator is = and the passed value is null, we have to use IS/IS NOT operator instead
            $usedOperator   = in_array($usedOperator, array('=','!=')) && 
                       ( 
                            is_null($values) 
                            || ( is_string($values) && strtolower($values) === 'null' ) 
                            || ( is_array($values) && count($values) === 1 && ( is_null($values[0]) || ( strtolower($values[0]) === 'null' ) ) )
                            //|| ( is_array($values) && count($values) === 1 && isset($values[0]) && ( is_null($values[0]) || ( strtolower($values[0]) === 'null' ) ) )
                       ) ? ( $usedOperator === '!=' ? 'IS NOT' : 'IS' ) : $usedOperator;
			
			// Special case if the operator is = or != and passed values are multiple
			$usedOperator  = in_array($usedOperator, array('=','!=')) && $multiValues ? ( $usedOperator === '!=' ? 'NOT IN' : 'IN' ) : $usedOperator;
			
			// Do not continue if the current values are multiple whereas the operator throwing a warning by the way
			if ( in_array($usedOperator, $uniques) && $multiValues ) { $this->warnings[4215] = $operator . '/' . (string) $values; continue; }
			
            $fields        = is_string($fields) && strpos($fields, ',') !== false ? Tools::toArray($fields) : $fields;
			$values        = is_string($values) && strpos($values, ',') !== false ? Tools::toArray($values) : $values;
			$condKeyword   = $i === 0 && empty($o['extra']) ? 'WHERE ' : 'AND ';
            $condKeyword    = $i > 0 && isset($condition[3]) && strtolower($condition[3]) === 'or' ? 'OR ' : $condKeyword;
            
            // Handle parenthesis wrappers for 'OR' conditions
            $oParenthesis   = isset($condition[4]) && strtolower($condition[4]) === 'first' ? '( ' : '';
			$cParenthesis   = (isset($condition[4]) && strtolower($condition[4]) === 'last') ? ' ) ' : '';
            //$cParenthesis   = !empty($oParenthesis) || (isset($condition[4]) && strtolower($condition[4]) === 'last') ? ' ) ' : '';
			
//$this->dump($oParenthesis);
//$this->dump($cParenthesis);

			// Special case when multiple fields are passed
			// Since we cannot handle more than 1 field at a time,
			// we need to explode the array of $fields, handling the first one and making new conditions for the other ones
			if ( $multiFields )
			{
				$output .= $condKeyword . $oParenthesis;
				
				// Extract the first element from the fields array
				$tmpFields  = $fields;
				$fields     = array_shift($tmpFields);
				
				// For each field, create a single condition 
				//foreach($tmpFields as $field ){ $o['conditions'] = array($field,$operator,$values); $i++; }
				$extraConds = array();
				foreach($tmpFields as $field ){ $extraConds[] = array($field,$operator,$values); $i++; }
				$extraOutput = $this->handleConditions(array('conditions' => $extraConds, 'extra' => true));
			}
			
			// Handle 'or' conditions
			if ( isset($condition[3]) && strtolower($condition[3]) === 'or' )
			{
				// TODO: get next triplets: [5],[6],[7]???? and loop over itself geting the ouput $orConditions
			}
            
            if ( isset($condition[4]) && strtolower($condition[4]) === 'first' )
            {
                
            }            
			
			if ( in_array($usedOperator, array('IN','NOT IN')) )
			{
				// Try to get the queried fields data
				$qf     = !$multiFields && !empty($this->queryData['fields'][$fields]) ? $this->queryData['fields'][$fields] : null;
				$res    = !empty($qf) && isset($qf['resource']) ? $qf['resource'] : $this->resourceName;
				$opts 	= $multiFields ? array() : array('resource' => $res, 'column' => $fields);
				$opts 	+= array('values' => $values); 
				
				$fields = Tools::toArray($fields);
				$output .= $condKeyword . $oParenthesis;
				$output .= $this->handleConditionsColumns(array('columns' =>$fields));
				$output .= ' ' . $usedOperator . ' (' . $this->handleConditionsTypes($opts);
				$output .= ') ';
			}
			// Case for single field & single value operators
			else
			{
//$this->dump($fields);
				// Try to get the queried fields data
				$qf         = !empty($this->queryData['fields'][$fields]) ? $this->queryData['fields'][$fields] : null;
				
				$col        = &$fields;
 
                // TODO: handle this properly (require queryFields to contains joined fields)
                
                // If the column name contains a "., assume that it is like 'table'.'column', matching db real structure
                $useAlias   = strpos($col, '.') === false;
                $colParts   = !$useAlias ? explode('.',$col) : null;

//$this->dump('qf:' . $qf);
				
				// Try to get the related resource for the current field/column, otherwise assume its the current one
				$res        = !empty($qf) && isset($qf['resource']) ? $qf['resource'] : ( !$useAlias ? $colParts[0] : $this->resourceName );
				                
                $alias      = !$useAlias 
                                ? ( isset($this->resources[$colParts[0]]) ? $this->resources[$colParts[0]]['alias'] : $colParts[0] ) 
                                : ( !empty($qf['tableAlias']) ? $qf['tableAlias'] : $this->alias );
                $col        = !$useAlias ? $colParts[1] : $col;
				
//$this->dump($res . ':' . $col);
//$this->dump($this->queryData);
				
                // Do not continue if the column is not a known one or if the resource does not belong to the queried ones for this request
                if ( !isset($this->application->dataModel[$res][$col]) ){ continue; }
                
                // TODO: how to handle joined columns????                
				
				$output .= $condKeyword . $oParenthesis;
				$output .= $alias . '.';
                $output .= $col . ' ' . $usedOperator . ' ';
                $output .= $this->handleConditionsTypes(array('values' => $values, 'resource' => $res ,'column' => $col, 'operator' => $operator)) . ' ';
			}
			
			// Get conditions alternatives
			$output .= !empty($orConditions) ? $orConditions : '';
			
			// Get extra conditions is there's
			$output .= !empty($extraOutput) ? $extraOutput : '';
			
            $output .= $cParenthesis;
			
			$i++;
		}
		
//$this->dump('output: ' . $output);	
//die();
		
		return $output;
	}
	
	public function handleConditionsColumns($options = array())
	{
//$this->dump('handleConditionsColumns');
		
		$o      = &$options;
		$output = '';
		
		// Do not continue if there's no columns passed
		if ( empty($o['columns']) ) { return $output; }
		
//var_dump($o['columns']);
		
		$j = 0;
		foreach( $o['columns'] as $col )
		{
			$qf = !empty($this->queryData['fields'][$col]) ? $this->queryData['fields'][$col] : null;
			
            // TODO: handle this properly (require queryFields to contains joined fields)
            // If the column name contains a "., assume that it is like 'table'.'column', matching db real structure
            $useAlias   = strpos($col, '.') === false;
            $colParts   = !$useAlias ? explode('.',$col) : null;                
            $alias      = !$useAlias 
                            ? ( isset($this->resources[$colParts[0]]) ? $this->resources[$colParts[0]]['alias'] : $colParts[0] ) 
                            : ( !empty($qf['tableAlias']) ? $qf['tableAlias'] : $this->alias );
            $col        = !$useAlias ? $colParts[1] : $col;
            
			// Do not continue if the field is not an existing one
			if ( !$qf && !$useAlias ) { $this->warnings[4213] = $col; continue; } // Unknow field/column
			
			$output .= $j !== 0 ? ', ' : '';
            //$output .= $useAlias ? $alias . '.' : '';
            $output .= $alias . '.';
            $output .= $col;
			$j++;
		}
		
		return $output;
	}


	// Handle values types (depending of the field)
	// TODO: $this->handleType(array('resource' => current, 'column' => current))???
	// TODO: $this->handleType(array('type' => gettype($value)))???
	public function handleConditionsTypes($options = array())
	{
		$o 		= &$options;
		$output = '';
		
		if ( is_array($o['values']) )
		{
			$j = 0; 
			foreach ( $o['values'] as $val )
			{
				$output .= ($j !== 0 ? ',' : '') . $this->handleTypes($val, $o);
				$j++;
			}
		}
		else
		{
			$output = $this->handleTypes($o['values'], $o);	
		}
		
		return $output;
	}
	
	public function handleTypes($val, $options = array())
	{
//$this->dump('handleTypes');
		
		$o            = array_merge(array(
			'resource'   => null,
			'column'     => null,
			'operator'   => null,
		), $options);
        
		$output       = '';
		$res          = $o['resource'];
		$col          = $o['column'];
		$colModel     = !empty($res) && !empty($col) && !empty($this->application->dataModel[$res][$col]) ? $this->application->dataModel[$res][$col] : null;
		$defType      = !empty($colModel['type']) ? $colModel['type'] : null;
		$valPrefix    = !empty($o['operator']) && in_array($o['operator'], array('contains','like','doesnotcontains','notlike','endsby','doesnotendsby')) ? '%' : '';
		$valSuffix    = !empty($o['operator']) && in_array($o['operator'], array('contains','like','doesnotcontains','notlike','startsby','doesnotstartsby')) ? '%' : '';
		
		//if ( $defType === 'timestamp' )                           { $val = "FROM_UNIXTIME('" . $this->escapeString($val) . "')"; }
		if 		( $defType === 'timestamp' && !is_null($val) ) 		{ $val = "FROM_UNIXTIME('" . $this->escapeString($val) . "')"; }
		else if ( $defType === 'bool'  ) 							{ $val = in_array($val, array(1,true,'1','true','t'), true) ? 1 : 0; }
		else if ( is_int($val) ) 									{ $val = (int) $val; }
		else if ( is_float($val) ) 									{ $val = (float) $val; }
		else if ( is_bool($val) ) 									{ $val = (int) $val; }
		else if ( is_null($val) || strtolower($val === 'null') ) 	{ $val = 'NULL'; }
		else if ( is_string($val) ) 								{ $val = "'" . $valPrefix . $this->escapeString($val) . $valSuffix . "'"; }
		//else                                                    { $val = "'" . $this->escapeString($val) . "'"; }
		//else                                                      { $val = $val; }

//$this->dump('val: ' . $val);
		
		return $val;
	}
	
	
    // Deprecated, use handleConditions instead
	public function handleOperations($options = array())
	{
		$o 			= &$options; 		// Shortcut for options
		$where 		= '';
		
		if ( isset($o['values']) && !empty($o['by']) )
		{
			$whereValues     = Tools::toArray($o['values']);
			$op 			= !empty($o['operation']) ? $o['operation'] : '';
			
			switch($op)
			{
				case 'valueContains': 
					foreach ($whereValues as $item) { $tmpWhere[] = $this->alias . "." . $o['by'] . " LIKE '%" . $this->escapeString($item) . "%'"; }
					$where = "WHERE " . join(" OR ", $tmpWhere) . " ";
					break;
				case 'valueNotContains': 
					foreach ($whereValues as $item) { $tmpWhere[] = $this->alias . "." . $o['by'] . " NOT ILIKE '%" . $this->escapeString($item) . "%'"; }
					$where = "WHERE " . join(" AND ", $tmpWhere) . " ";
					break;
				case 'valueStartsBy': 
					foreach ($whereValues as $item) { $tmpWhere[] = $this->alias . "." . $o['by'] . " LIKE '" . $this->escapeString($item) . "%'"; }
					$where = "WHERE " . join(" OR ", $tmpWhere) . " ";
					break;
				case 'valueEndsBy': 
					foreach ($whereValues as $item) { $tmpWhere[] = $this->alias . "." . $o['by'] . " LIKE '%" . $this->escapeString($item) . "'"; }
					$where = "WHERE " . join(" OR ", $tmpWhere) . " ";
					break;
				case 'valueIsNot': 
					$where 	= "WHERE " . $this->alias . "." . $o['by'] . " NOT IN ('" . join("', '", $whereValues) . "') ";
					break;
				case 'valueIsGreater': 
					$where 	= "WHERE " . $this->alias . "." . $o['by'] . " > '" . $this->escapeString($whereValues[0]) . "' ";
					break;
				case 'valueIsGreaterOrEqual': 
					$where 	= "WHERE " . $this->alias . "." . $o['by'] . " >= '" . $this->escapeString($whereValues[0]) . "' ";
					break;
				case 'valueIsLower': 
					$where 	= "WHERE " . $this->alias . "." . $o['by'] . " < '" . $this->escapeString($whereValues[0]) . "' ";
					break;
				case 'valueIsLowerOrEqual': 
					$where 	= "WHERE " . $this->alias . "." . $o['by'] . " <= '" . $this->escapeString($whereValues[0]) . "' ";
					break;
				default:
					$by    = Tools::toArray($o['by']);
					$i = 0;
					$where = "WHERE ";
					foreach ($by as $item)
					{
						$where .= $i === 0 ? '' : 'OR ';
						$where .= $this->alias . "." . $item . " IN ('" . join("', '", $whereValues) . "') ";
						$i++;
					}  
					break;
			}	
		}
		
		return $where;
	}
	
	public function handleOrder($options = array())
	{
		$o 			= &$options; 		// Shortcut for options
        $rModel     = &$this->application->dataModel[$this->resourceName];
		
		// Build ORDER BY
		$orderBy = $tmpOrderBy = '';
		if ( !empty($o['sortBy']) )
		{
			$o['sortBy'] = Tools::toArray($o['sortBy']);
			
			$i = 0;
			foreach ($o['sortBy'] as $f)
			{
				// Shortcut for fields query props 
				$qf = !empty($this->queryData['fields'][$f]) ? $this->queryData['fields'][$f] : null;

				// If the field is not present in the gotten fields (case for select request)
				// do NOT use the ORDER clause with it
				//if ( empty($qf) && ( empty($o['type']) || ( !empty($o['type']) && $o['type'] !== 'update' ) )  ){ continue; }
									
                // If the field is not present in the gotten fields and is not one of the columns of the current resource
                // do NOT use the ORDER clause with it
				if ( empty($qf) && !isset($rModel[$f]) ){ continue; }
									
				$tmpOrderBy .= ($i === 0 ? '' : ", ")  
								//. ( !empty($qf['table']) ? ( !empty($qf['tableAlias']) ? $qf['tableAlias'] : $qf['table'] ) . "." : '' )
                                . ( !empty($qf['table']) ? ( !empty($qf['tableAlias']) 
                                    ? $qf['tableAlias'] : $qf['table'] ) . "." 
                                    : ( !empty($this->alias) ? $this->alias : $this->resourceName) . '.' )
								. ( !empty($qf['table']) ? $qf['name'] : $f)
								. ( ( strpos($f, ' ASC') > -1 || strpos($f, ' DESC') > -1 ) ? '' : ( !empty($o['orderBy']) ? " " . $o['orderBy'] : '') );
				$i++;
			}
			
			$orderBy .= !empty($tmpOrderBy) ? " ORDER BY " . $tmpOrderBy : '';
		}
		
		return $orderBy;
	}


	public function handleGroupBy($options = array())
	{
		$o 			= &$options; 		// Shortcut for options
		
		// Build GROUP BY
		$groupBy = '';
		if ( !empty($o['groupBy']) )
		{
			$o['groupBy'] = Tools::toArray($o['groupBy']);
			
			// We have to append to the GROUP BY all the requested fields			
			// So we get the list of requested fields and store it as a local variable
			$gByFields = $this->queryData['fields'];
			
			// Remove from this array, the fiels really used to the grouping
			foreach ($o['groupBy'] as $field) { unset($gByFields[$field]); }
			
			$i = 0;
			$groupByOthers = '';
			
			// Disabled
			// Why was this used? Maybe bad fix for mysql error #1140 - Mixing of GROUP columns
			/*
			foreach ($gByFields as $k => $f)
			{
				// Skip onetomany gotten fields
				if ( !empty($f['relation']) && $f['relation'] === 'onetomany' ){ continue; } 
				
				$groupByOthers .= ($i === 0 ? '' : ", ") . ( !empty($f['table']) ? $f['table'] : $this->alias ) . "." . $f['name'];
				
				// Case for joined columns where an alias could be used for the gotten fields
				if ( !empty($f['table']) && !empty($f['as']) )
				{
					$groupByOthers .= ( !empty($groupByOthers) ? ", " : '' ) . $k;
				}
				
				$i++;
			}*/
			
//var_dump($groupByOthers);

			$groupByFields 	= is_array($o['groupBy']) ? join(", " . $this->alias . ".", $o['groupBy']) : $o['groupBy'];
			$groupBy 		= "GROUP BY " . $this->alias . "." . $groupByFields . (!empty($groupByOthers) ? ", " . $groupByOthers : '') . " ";
		}

		return $groupBy;
	}
	
	
	public function escapeColumn($name = '')
	{
		return $this->safeWrapper . $name . $this->safeWrapper;
	}
	
	
	public function index($options = array())
	{
		// Set default params
		// TODO: use $this->options instead, and use array_merge
		$o 				= &$options;
		$o['by'] 		= !empty($o['by']) ? $o['by'] : 'id';
		$o['sortBy'] 	= !empty($o['sortBy']) ? $o['sortBy'] : 'id';
		$o['orderBy'] 	= !empty($o['orderBy']) ? $o['orderBy'] : 'ASC';
		$o['type'] 		= 'select';
        $o['mode']      = !empty($o['mode']) ? $o['mode'] : '';         // can be '','count','distinct','onlyOne'
        $o['getFields'] = !empty($o['getFields']) ? Tools::toArray($o['getFields']) : array(); //
		
		// If a manual query has not been passed, build the proper one
		$query 	= !empty($o['manualQuery']) ? $o['manualQuery'] : $this->buildSelect($o);
		
		//$this->log($query);
		$this->dump($query);

		// Execute the query and store the returned data
		$this->query($query, $o);
		
		return $this->data;
	}
	
	
	public function search($options = array())
	{        
		return $this->index($options);
	}
	
	
	public function create($resourceData = null, $options = array())
	{
		// Do not continue if no data has been passed 
		if ( empty($resourceData) ) { return; }
		
		$o 			= &$options;
		$o['type'] 	= 'insert';
		
		// If a manual query has not been passed, build the proper one
		$query 	= !empty($o['manualQuery']) ? $o['manualQuery'] : $this->buildInsert($resourceData, $o);
		
        $this->dump($query);

		// Execute the query and store the returned data
		$this->query($query, $o);
		
		//return ( !empty($o['returning']) && count((array) $o['returning']) === 1 ) ? $this->data[$o['returning']] : $this;
		//return !empty($o['returning']) ? ( isset($this->data[$o['returning']]) ? $this->data[$o['returning']] : null) : $this;
		return defined('_APP_USE_SQL_FETCH_V2') && _APP_USE_SQL_FETCH_V2
			?  $this->data
			: ( !empty($o['returning']) ? ( isset($this->data[$o['returning']]) ? $this->data[$o['returning']] : null) : $this )
		;
			
		
	}
	
	
	public function createTable($params = array())
	{
		$p 			= &$params;
		
		$query = "
			CREATE TABLE IF NOT EXISTS `" . $p['name'] . "` (
			  `id` int(11) NOT NULL auto_increment,
			  `creation_date` timestamp NOT NULL default '0000-00-00 00:00:00',
			  `update_date` timestamp NOT NULL default '0000-00-00 00:00:00' on update CURRENT_TIMESTAMP,
			  PRIMARY KEY  (`id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
		";
		
		$this->dump($query);
		
		// Execute the query and store the returned data
		//$this->data = $this->query($query)->data;
		$this->query($query);
		
		return $this;
	}
	
	
	public function retrieve($options = array())
	{
		// TODO: use $this->options instead, and use array_merge
		$o 				= &$options;
		$o['by'] 		= !empty($o['by']) ? $o['by'] : 'id';
		$o['mode']		= !empty($o['mode']) ? $o['mode'] : ( empty($o['values']) || count($o['values']) <= 1 ? 'onlyOne' : null );
        $o['values']    = !empty($o['values']) ? Tools::toArray($o['values']) : null;
        
		// Using LIMIT 1 (by default) for perf issues
		$o['limit'] 	= $o['mode'] !== 'onlyOne' && !empty($o['limit']) ? $o['limit'] : 1;
		$o['type'] 		= 'select';
        $o['getFields'] = !empty($o['getFields']) ? Tools::toArray($o['getFields']) : array(); //
		
		// If a manual query has not been passed, build the proper one
		$query 	= !empty($o['manualQuery']) ? $o['manualQuery'] : $this->buildSelect($o);
		
        $this->dump($query);
        
		$this->data = $this->query($query, $o)->data;
		
		return $this->data;
	}
	
	
	public function update($resourceData = null, $options = array())
	{
		// TODO: use $this->options instead, and use array_merge
		$o 				= &$options;
		$o['by'] 		= !empty($o['by']) ? $o['by'] : 'id';
		$o['values'] 	= !empty($o['values']) ? $o['values'] : null;
		$o['limit'] 	= !empty($o['limit']) ? $o['limit'] : null;
		$o['type'] 		= 'update';
		
		// Do not continue if no data or no item value has been passed 
		if ( empty($resourceData) ) { return; }
		
		// If a manual query has not been passed, build the proper one
		$query 	= !empty($o['manualQuery']) ? $o['manualQuery'] : $this->buildUpdate($resourceData, $o);
		
        $this->dump($query);

		// Execute the query and store the returned data
		$this->query($query, $o);
		
		return $this;
	}
	
	
	public function upsert($resourceData = null, $options = array())
	{
		$options['onDuplicateUpdate'] = true;
		
		return $this->create($resourceData, $options);
	}
	
	
	public function delete($options = array())
	{		
		$o 				= &$options;
		$o['by'] 		= !empty($o['by']) ? $o['by'] : 'id';
		$o['values'] 	= !empty($o['values']) ? $o['values'] : null;
		$o['type'] 		= 'delete';
		
		// Do not continue if no value has been passed
		if ( empty($o['values']) && empty($o['conditions']) ) { return false; }
		
		// Build the proper query
		$query = $this->buildDelete($o);
		
		$this->dump($query);

		// Execute the query and store the returned data
		$this->query($query, $o);
		
		return $this;
	}

}
?>