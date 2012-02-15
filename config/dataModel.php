<?php 

### RESOURCES GROUPS ###
$_resourcesGroups = array(
    'users'         => array('resources' => array('users', 'usersgroups', 'groups', 'groupsauths', 'sessions')),
    'config'        => array('resources' => array('adminlogs', 'bans', 'resources', 'resourcescolumns', 'tasks',)),
);


### DATAMODEL: RESOURCES ###
$resources = array(
'adminlogs' 			=> array('singular' => 'adminlog', 'table' => 'admin_logs', 'alias' => 'admlog', 'defaultNameField' => 'slug', 'displayName' => 'admin logs'),
'bans' 					=> array('singular' => 'ban', 'table' => 'bans', 'alias' => 'b', 'defaultNameField' => 'ip', 'displayName' => 'bans'),
'groups' 				=> array('singular' => 'group', 'alias' => 'gp', 'crudability' => 'CRUD', 'defaultNameField' => 'admin_title'),
'groupsauths' 			=> array('singular' => 'groupsauth', 'table' => 'groups_auths',  'alias' => 'gpauth', 'crudability' => 'CRUD', 'defaultNameField' => 'group_id'),
'resources' 			=> array('singular' => 'resource', 'alias' => 'res', 'crudability' => 'CRUD', 'defaultNameField' => 'name'),
'resourcescolumns' 		=> array('singular' => 'resourcecolumn', 'alias' => 'rescol', 'table' => 'resources_columns', 'crudability' => 'CRUD', 'defaultNameField' => 'columns', 'displayName' => 'columns'),
'sessions' 				=> array('singular' => 'session', 'alias' => 'sess', 'crudability' => 'R', 'defaultNameField' => 'id'),
'tasks' 				=> array('singular' => 'task', 'alias' => 'tsk', 'crudability' => 'CRUD', 'defaultNameField' => 'slug'),
'users' 				=> array('singular' => 'user', 'alias' => 'u', 'crudability' => 'CRUD', 'defaultNameField' => 'email', 'searchable' => 1),
'usersgroups' 			=> array('singular' => 'usersgroup', 'table' => 'users_groups', 'alias' => 'ugp', 'crudability' => 'CRUD'),
);

### DATAMODEL: RESOURCES COLUMNS ###
$dataModel = array(
'adminlogs' => array(
    'id'                    => array('type' => 'int', 'pk' => 1, 'AI' => 1, 'list' => 1, 'editable' => 0),
    'slug'           		=> array('type' => 'varchar', 'length' => 64, 'list' => 3),
    'action' 				=> array('type' => 'enum', 'possibleValues' => array('create','update','delete','import'), 'list' => 1),
    'resource_name'			=> array('type' => 'varchar', 'length' => 32, 'list' => 3),
    'resource_id'			=> array('type' => 'varchar', 'length' => 32, 'list' => 1),
	'user_id' 				=> array('type' => 'int', 'fk' => 1, 'list' => 3, 'editable' => 0, 'relResource' => 'users', 'relField' => 'id', 'relGetFields' => 'email', 'relGetAs' => 'user_email','displayName' => 'user'),
	'revert_query' 			=> array('type' => 'text'),
    'creation_date'         => array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'list' => 0),
    'update_date'           => array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'forceUpdate' => 1, 'list' => 1),
),
'bans' => array(
    'id'                    => array('type' => 'int', 'pk' => 1, 'AI' => 1, 'list' => 1, 'editable' => 0),
    'ip'					=> array('type' => 'varchar', 'subtype' => 'ip', 'length' => 40, 'list' => 3),
    'reason' 				=> array('type' => 'varchar', 'length' => 32),
    'end_date'         		=> array('type' => 'timestamp', 'default' => null, 'list' => 1),
    'creation_date'         => array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'list' => 0),
    'update_date'           => array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'forceUpdate' => 1, 'list' => 1),
),
'groups' => array(
	'id' 					=> array('type' => 'int', 'pk' => 1, 'list' => 1, 'editable' => 0),
	'name' 					=> array('type' => 'varchar', 'length' => 32, 'list' => 1),
	'admin_title' 			=> array('type' => 'varchar', 'subtype' => 'slug', 'from' => 'name', 'list' => 1),
	'creation_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'list' => 1),
	'update_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'forceUpdate' => 1, 'list' => 1),
),
'groupsauths' => array(
	'id' 					=> array('type' => 'int', 'pk' => 1, 'AI' => 1, 'list' => 1, 'editable' => 0),
	'group_id' 				=> array('type' => 'int', 'fk' => 1, 'required' => 1, 'list' => 1, 'relResource' => 'groups', 'relField' => 'id', 'relGetFields' => 'name', 'relGetAs' => 'group_name'),
	'resource_id' 			=> array('type' => 'int', 'fk' => 1, 'required' => 1, 'list' => 1, 'relResource' => 'resources', 'relField' => 'id', 'relGetFields' => 'name', 'relGetAs' => 'resource_name'),
	'allow_display' 		=> array('type' => 'bool', 'default' => 1, 'list' => 1),
	'allow_create' 			=> array('type' => 'bool', 'default' => 0, 'list' => 1),
	'allow_retrieve' 		=> array('type' => 'bool', 'default' => 0, 'list' => 1),
	'allow_update' 			=> array('type' => 'bool', 'default' => 0, 'list' => 1),
	'allow_delete' 			=> array('type' => 'bool', 'default' => 0, 'list' => 1),
	'creation_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'list' => 0),
	'update_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'forceUpdate' => 1, 'list' => 0),
),
'resources' => array(
	'id' 					=> array('type' => 'int', 'pk' => 1, 'list' => 1, 'editable' => 0),
	'table'                 => array('type' => 'varchar', 'length' => 32, 'list' => 0),
	'name' 					=> array('type' => 'varchar', 'subtype' => 'slug', 'from' => 'table', 'length' => 32, 'list' => 3 , 'required' => 1),
	'singular' 				=> array('type' => 'varchar', 'length' => 32, 'list' => 0),
	'type' 					=> array('type' => 'enum', 'default' => 'native', 'possibleValues' => array('native','relation','filter'), 'list' => 3),
	'alias'                 => array('type' => 'varchar', 'length' => 8, 'list' => 1),
	'extends' 				=> array('type' => 'varchar', 'length' => 8, 'list' => 0),
	'displayName' 			=> array('type' => 'varchar', 'length' => 32, 'list' => 0),
	//'searchable' 			=> array('type' => 'boolean', 'default' => false, 'list' => 1),
	'defaultNameField' 		=> array('type' => 'varchar', 'length' => 32, 'list' => 1),
	'creation_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'list' => 0),
	'update_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'forceUpdate' => 1, 'list' => 0),
),
'resourcescolumns' => array(
	'id' 					=> array('type' => 'int', 'pk' => 1, 'list' => 1, 'editable' => 0),
	'resource_id' 			=> array('type' => 'int', 'fk' => 1, 'list' => 3, 'relResource' => 'resources', 'relField' => 'id', 'relGetFields' => 'name', 'relGetAs' => 'resource_name'),
	'name' 					=> array('type' => 'varchar', 'length' => 32, 'list' => 3),
	'type' 					=> array('type' => 'enum', 'possibleValues' => array('string', 'email', 'password', 'url', 'tel', 'color', 'meta', 'ip', 'slug', 'tag', 'text', 'html', 'code', 'int', 'tinyint', 'float', 'smallint', 'mediumint', 'bigint', 'bool','boolean','timestamp', 'datetime', 'date', 'time', 'year', 'month', 'week', 'day', 'hour', 'minutes', 'seconds', 'onetoone', 'onetomany', 'manytoone', 'manytomany', 'id', 'enum', 'file', 'image', 'video', 'sound',), 'list' => 3),
	'realtype' 				=> array('type' => 'enum', 'possibleValues' => array('serial', 'bit', 'tinyint', 'bool', 'smallint', 'mediumint', 'int', 'bigint', 'float', 'double', 'double precision', 'decimal', 'date', 'datetime', 'timestamp', 'time', 'year', 'char', 'varchar', 'binary', 'varbinary', 'tinyblob', 'tinytext', 'blob', 'text', 'mediumblob', 'mediumtext', 'longblob', 'longtext', 'enum', 'set'), 'list' => 3),
	//'length' 				=> array('type' => 'bigint', 'list' => 1),
	'length' 				=> array('type' => 'int', 'length' => '9223372036854775808', 'default' => 0, 'list' => 1),
	'pk' 					=> array('type' => 'bool', 'default' => 0, 'list' => 1),
	'ai' 					=> array('type' => 'bool', 'default' => 0, 'list' => 1),
	'fk' 					=> array('type' => 'bool', 'default' => 0, 'list' => 1),
	'default' 				=> array('type' => 'varchar', 'length' => 255, 'list' => 1), // TODO: null, now() or user defined value. how to handle this???
	'null' 					=> array('type' => 'bool', 'default' => 0, 'list' => 1),
	'creation_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'list' => 0),
	'update_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'forceUpdate' => 1, 'list' => 0),
),
'usersgroups' => array(
	'id' 					=> array('type' => 'int', 'pk' => 1, 'AI' => 1, 'list' => 1, 'editable' => 0),
	'user_id' 				=> array('type' => 'int', 'fk' => 1, 'list' => 3, 'relResource' => 'users', 'relField' => 'id', 'relGetFields' => 'email', 'relGetAs' => 'user_email'),
	'group_id' 				=> array('type' => 'int', 'fk' => 1, 'list' => 3, 'relResource' => 'groups', 'relField' => 'id', 'relGetFields' => 'name', 'relGetAs' => 'group_name'),
	'creation_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'list' => 1),
	'update_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'forceUpdate' => 1, 'list' => 1),
),
'users' => array(
	'id' 					=> array('type' => 'int', 'pk' => 1, 'AI' => 1, 'list' => 1, 'editable' => 0),
	'email' 				=> array('type' => 'varchar', 'subtype' => 'email', 'list' => 3, 'searchable' => 1),
	'password' 				=> array('type' => 'varchar', 'subtype' => 'password', 'hash' => 'sha1', 'length' => 64, 'editable' => 1, 'exposed' => 0),
	'password_old_1' 		=> array('type' => 'varchar', 'subtype' => 'password', 'hash' => 'sha1', 'length' => 64, 'editable' => 1, 'exposed' => 0),
	'password_old_2' 		=> array('type' => 'varchar', 'subtype' => 'password', 'hash' => 'sha1', 'length' => 64, 'editable' => 1, 'exposed' => 0),
	'password_expiration'	=> array('type' => 'timestamp', 'editable' => 0, 'list' => 0, 'default' => null),
	'password_lastedit_date'=> array('type' => 'timestamp', 'default' => null),
	'first_name' 			=> array('type' => 'varchar', 'length' => 64, 'list' => 3, 'eval' => 'strtolower(trim(---self---))', 'searchable' => 0),
	'last_name' 			=> array('type' => 'varchar', 'length' => 64, 'list' => 3, 'eval' => 'strtolower(trim(---self---))', 'searchable' => 0),
	'name' 					=> array('type' => 'varchar', 'length' => 128, 'list' => 1, 'searchable' => 1),
	'prefered_lang' 		=> array('type' => 'varchar', 'length' => 5, 'list' => 1),
	//'groups' 				=> array('type' => 'onetomany', 'relResource' => 'groups', 'relField' => 'id', 'pivotResource' => 'users_groups', 'pivotLeftField' => 'user_id', 'pivotRightField' => 'group_id', 'getFields' => 'admin_title'),
	'groups' 				=> array('type' => 'onetomany', 'getFields' => 'id,admin_title'),
	'activated' 			=> array('type' => 'bool', 'default' => 0, 'list' => 1, 'exposed' => 0),
	'activation_key' 		=> array('type' => 'varchar', 'subtype' => 'uniqueID', 'length' => 32, 'editable' => 1, 'exposed' => 0),
	'password_reset_key' 	=> array('type' => 'varchar', 'length' => 32, 'exposed' => 0, 'editable' => 1),
	//'device_id' 			=> array('type' => 'varchar', 'length' => 64, 'list' => 1),
	//'private_key' 			=> array('type' => 'varchar', 'subtype' => 'uniqueID', 'lenth' => 16, 'editable' => 0, 'exposed' => 0),
	'creation_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now'),
	'update_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'forceUpdate' => 1),
),
'sessions' => array(
	'id' 					=> array('type' => 'int', 'pk' => 1, 'list' => 1, 'editable' => 0),
	'name' 					=> array('type' => 'varchar', 'length' => 32, 'list' => 1, 'editable' => 0),
	'user_id' 				=> array('type' => 'int', 'fk' => 1, 'list' => 1, 'editable' => 0, 'relResource' => 'users', 'relField' => 'id', 'relGetFields' => 'email', 'relGetAs' => 'user_email','displayName' => 'user'),
	'expiration_time'		=> array('type' => 'timestamp', 'list' => 1, 'editable' => 1),
	'ip' 					=> array('type' => 'varchar', 'length' => 48, 'list' => 1, 'editable' => 0),
	'last_url' 				=> array('type' => 'varchar', 'subtype' => 'url', 'list' => 1, 'editable' => 0, 'forceUpdate' => 1),
	'creation_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now'),
	'update_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'forceUpdate' => 1),	
),
'tasks' => array(
    'id'                    => array('type' => 'int', 'pk' => 1, 'AI' => 1, 'list' => 1, 'editable' => 0),
    'slug'           		=> array('type' => 'varchar', 'length' => 32, 'list' => 3),
    'type'                  => array('type' => 'enum', 'possibleValues' => array('import'), 'list' => 3),
    'subtype'				=> array('type' => 'varchar', 'length' => 32, 'list' => 3),
    'items_count'    		=> array('type' => 'int', 'length' => 8, 'default' => null, 'list' => 3),
    'creation_date'         => array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'list' => 1),
    'update_date'           => array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'forceUpdate' => 1, 'list' => 3),
),
);

?>