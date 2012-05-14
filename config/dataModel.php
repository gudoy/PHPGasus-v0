<?php 

### RESOURCES GROUPS ###
$_resourcesGroups = array(
	// App specific

	// PHPGasus default
    'users'         => array('resources' => array('users', 'usersgroups', 'groups', 'groupsauths', 'sessions')),
    'config'        => array('resources' => array('adminlogs', 'bans', 'resources', 'resourcescolumns', 'tasks',)),
);


### DATAMODEL: RESOURCES ###
$resources = array(
// PHPGasus default
'adminlogs' 				=> array('singular' => 'adminlog', 'table' => 'admin_logs', 'alias' => 'admlog', 'defaultNameField' => 'slug', 'displayName' => 'admin logs'),
'bans' 						=> array('singular' => 'ban', 'table' => 'bans', 'alias' => 'b', 'defaultNameField' => 'ip', 'displayName' => 'bans'), 
'groups' 					=> array('singular' => 'group', 'alias' => 'gp', 'crudability' => 'CRUD', 'defaultNameField' => 'slug'),
'groupsauths' 				=> array('singular' => 'groupsauth', 'table' => 'groups_auths',  'alias' => 'gpauth', 'crudability' => 'CRUD', 'defaultNameField' => 'group_id', 'displayName' => 'groups auths'),
'resources' 				=> array('singular' => 'resource', 'alias' => 'res', 'crudability' => 'CRUD', 'defaultNameField' => 'name'),
'resourcescolumns' 			=> array('singular' => 'resourcecolumn', 'alias' => 'rescol', 'table' => 'resources_columns', 'crudability' => 'CRUD', 'defaultNameField' => 'columns', 'displayName' => 'columns'),
'sessions' 					=> array('singular' => 'session', 'alias' => 'sess', 'crudability' => 'R', 'defaultNameField' => 'name'),
'tasks' 					=> array('singular' => 'task', 'alias' => 'tsk', 'crudability' => 'CRUD', 'defaultNameField' => 'slug'),
'users' 					=> array('singular' => 'user', 'alias' => 'u', 'crudability' => 'CRUD', 'defaultNameField' => 'email', 'nameField' => 'email', 'descField' => 'name', 'searchable' => 1, 'related' => array('usersgroups' => array('on' => 'user_id')), 'icon' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAFcklEQVR42s2XS2xUZRTH//cxc+fOA+2TNrAwUFFICBSBPkgqoCtcmggrorIyJEKaoro2IYqI4saVLnQFicoCYQOUWl4tqAgWoZTadqZD6bzn3rmP774892YMUV3MwBg9nV/ufM3JOf/zzXfON4P/2jjUZ8L+gSc/E3l+t8dxEgeuGsQzbdf58uOR4hsAnH9DAPd6zxNdTbIwyYd4tLaGkVgSgu1y0HUXqmKhUrYR4j1kVGvVF2OlKQAeajARtVm4KSpOShEBa1ZHEYvFIPBhWA6HsmJD5HXoZgWFAkNbIjQJIEKYqMH4WrZ975amg4Ig4OmuMGLRKHiBBITjCEsJROQEpEgCciQG2+VJlIA3B5oPAhAaJIByhIW90TgQk2VKLkMMyRDEKELSEoTCCcIXJdFahqq55CfuBRBpmACO56VojKqjCjkhRMlDlDQOkQQIYpwql8DxIYi8iIruguNEqZECBI7z3VwwBljMg+3wAclUHmNXJzCbWsBipgDTtPyOAE/+L62JrwLANeYQupTcEZF+YGA2nYZtLaBU1tGxvBM7X9uBFV3LoSoVfPDeV6ioJXj0990tdaYqwHtsAa7nmhZzpFde3YXMgzx4nrph/WpEYxFoioZyUYFLIjdsfhanZ+7Do/dBYqIRO+Aqhn0hZOCFzmUdWNO7ATZVq2s61HIFhmHCNBgcx0H70iaEwxyKGjsDwCa8RpwBNl+yTpiqhcX0IjzNgOGjU2KTgTELzKanxaCQIDnsIauYIwCsRh1C8+StymmTedl8vgjHtoOklmVXseDQ07EdWIZOc0LMHL+uHmvkIGJE6caiO5SaXoDr+V3gBIntABu27dL/XKi5An687xwAUCRYowR4hDI6rY6eODFy/KfLNwDXoZcDh3Apsee5mLo9gyvjt4+P3C2NAigTbiPvAkYUIiE3MjVXhJBIgHNtWIzBME1MTadxa+I3REREfD+CNfI25A4fOboxIsfeF6TY9i1PZcEKs5hJZZArlKEqCs0EBeFEJ5Zt3A1dKZzTKuo7Q4P7rgHwHkcAd+jDIztkWf46Ho9La9euxXMb1mHu2ufIzk1ANwyUSgoyuTxKhTxMJLDr3WPIzqfw8/UbUFXF1HX95bcODJ7yhdQrQPzok0/PtjY3D/T29qCzswNlqrKtfSk8VkDy5imUc/MoLs7CE+MB7asGIDWtQCIiYUkijvT9BVy5MoZMNvvN0OD+nQDsWgWIhw4fGelaubJ/S38fyoqKXC4HVdMgcDw2btoEiZKYlgPL9g+i3wUWKoqC5NxsMAWjsoyWlpZAyMVLl3H33r1Lbw8NPu+LqOUQRjmO6+/uXo/UfBoPFhdBlxEBMM/DxYuj6O/rA3gersX8bgjacWryzsPBwRjyhQKWtreju3sdkslkvx+XKNciQIrKMaiqirlkivJwIEHwjfLDdR2MjY+jt2czBIGn6hkmbv4CwzDIl3/Yu+ScTKUgigIRDuLW2oacaZmoVDQwZv4R9E+Bc3TwRr6/gL6+Hlwd/4F8K76Yv32iLvkWS0Uwywzi1ioADmPQdQ0ms8BXq/+riHy+gJMnT/u7E2Db/+gX3Bu2xeobRIpSQbADphkEf1QLBBgGGLPrE6Bqqt/nwbx3XQ+PooFyB+eH2TZy+Vx9Aixm5WdmZpqbmluoAo3Wjh8StRsHMSQEX1YpThCvHgFsePjsnq1bt39bKpaQoLkfjcXAC4JfVg25uaA1K0oFC+kFFItFnD9/bg8AVusgChMdbW1tq7dte3GfHI0+I0nSCtRppmlO65p2Z3j4zNFMJvMrgAWC1SKAIySiiWglEtU1j9rNJUxCIbJEobr26rmMREKqItT5Q9YjHMKsYuP/ar8D0J3XbruuI+AAAAAASUVORK5CYII='),
'usersgroups' 				=> array('singular' => 'usersgroup', 'table' => 'users_groups', 'alias' => 'ugp', 'crudability' => 'CRUD', 'displayName' => 'users groups',),

// App specific
);

### DATAMODEL: RESOURCES COLUMNS ###
$dataModel = array(
// PHPGasus default
'adminlogs' => array(
    'id'                    => array('type' => 'int', 'pk' => 1, 'AI' => 1, 'list' => 0, 'editable' => 0),
    'slug'           		=> array('type' => 'varchar', 'length' => 64, 'list' => 3),
    'action' 				=> array('type' => 'enum', 'possibleValues' => array('create','update','delete','import'), 'list' => 3),
    'resource_name'			=> array('type' => 'varchar', 'length' => 32, 'list' => 3),
    'resource_id'			=> array('type' => 'varchar', 'length' => 32, 'list' => 1),
	'user_id' 				=> array('type' => 'int', 'fk' => 1, 'list' => 3, 'editable' => 0, 'relResource' => 'users', 'relField' => 'id', 'relGetFields' => 'email', 'relGetAs' => 'user_email','displayName' => 'user'),
	'revert_query' 			=> array('type' => 'text'),
    'creation_date'         => array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'list' => 0),
    'update_date'           => array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'forceUpdate' => 1, 'list' => 1),
),
'bans' => array(
    'id'                    => array('type' => 'int', 'pk' => 1, 'AI' => 1, 'list' => 0, 'editable' => 0),
    'ip'					=> array('type' => 'varchar', 'subtype' => 'ip', 'length' => 40, 'list' => 3),
    'reason' 				=> array('type' => 'varchar', 'length' => 32, 'list' => 3),
    'end_date'         		=> array('type' => 'timestamp', 'default' => null, 'list' => 3),
    'creation_date'         => array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'list' => 0),
    'update_date'           => array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'forceUpdate' => 1, 'list' => 1),
),
'groups' => array(
	'id' 					=> array('type' => 'int', 'pk' => 1, 'list' => 1, 'editable' => 0),
	'name' 					=> array('type' => 'varchar', 'length' => 32, 'list' => 1),
	'slug' 					=> array('type' => 'varchar', 'subtype' => 'slug', 'from' => 'name', 'list' => 1),
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
	'id' 					=> array('type' => 'int', 'pk' => 1, 'list' => 0, 'editable' => 0),
	'table'                 => array('type' => 'varchar', 'length' => 32, 'list' => 0),
	'name' 					=> array('type' => 'varchar', 'subtype' => 'slug', 'from' => 'table', 'length' => 32, 'list' => 3 , 'required' => 1),
	'singular' 				=> array('type' => 'varchar', 'length' => 32, 'list' => 0),
	'type' 					=> array('type' => 'enum', 'default' => 'native', 'possibleValues' => array('native','relation','filter'), 'list' => 1),
	'alias'                 => array('type' => 'varchar', 'length' => 8, 'list' => 1),
	'extends' 				=> array('type' => 'varchar', 'length' => 8, 'list' => 1),
	'displayName' 			=> array('type' => 'varchar', 'length' => 32, 'list' => 0),
	'defaultNameField' 		=> array('type' => 'varchar', 'length' => 32, 'list' => 3),
	//'icon' 				=> array('type' => 'dataURI', 'list' => 1),
	//'nameField' 			=> array('type' => 'varchar', 'length' => 32, 'list' => 3),
	//'descField' 			=> array('type' => 'varchar', 'length' => 32, 'list' => 3),
	//'imageField' 			=> array('type' => 'varchar', 'length' => 32, 'list' => 3),
	'creation_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'list' => 0),
	'update_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'forceUpdate' => 1, 'list' => 0),
),
'resourcescolumns' => array(
	'id' 					=> array('type' => 'int', 'pk' => 1, 'list' => 0, 'editable' => 0),
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
	'id' 					=> array('type' => 'int', 'pk' => 1, 'AI' => 1, 'list' => 0, 'editable' => 0),
	'email' 				=> array('type' => 'varchar', 'subtype' => 'email', 'list' => 3, 'searchable' => 1),
	'password' 				=> array('type' => 'varchar', 'subtype' => 'password', 'hash' => 'sha1', 'length' => 64, 'editable' => 1, 'exposed' => 0),
	//'password_old_1' 		=> array('type' => 'varchar', 'subtype' => 'password', 'hash' => 'sha1', 'length' => 64, 'editable' => 1, 'exposed' => 0),
	//'password_old_2' 		=> array('type' => 'varchar', 'subtype' => 'password', 'hash' => 'sha1', 'length' => 64, 'editable' => 1, 'exposed' => 0),
	//'password_expiration'	=> array('type' => 'timestamp', 'editable' => 0, 'list' => 1, 'default' => null),
	//'password_lastedit_date'=> array('type' => 'timestamp', 'default' => null),
	'first_name' 			=> array('type' => 'varchar', 'length' => 64, 'list' => 0, 'eval' => 'strtolower(trim(---self---))', 'searchable' => 0),
	'last_name' 			=> array('type' => 'varchar', 'length' => 64, 'list' => 0, 'eval' => 'strtolower(trim(---self---))', 'searchable' => 0),
	'name' 					=> array('type' => 'varchar', 'length' => 128, 'list' => 3, 'searchable' => 1),
	//'prefered_lang' 		=> array('type' => 'varchar', 'length' => 5, 'list' => 1),
	//'groups' 				=> array('type' => 'onetomany', 'relResource' => 'groups', 'relField' => 'id', 'pivotResource' => 'users_groups', 'pivotLeftField' => 'user_id', 'pivotRightField' => 'group_id', 'getFields' => 'admin_title'),
	'groups' 				=> array('type' => 'onetomany', 'getFields' => 'id,slug'),
	'activated' 			=> array('type' => 'bool', 'default' => 0, 'list' => 1, 'exposed' => 0),
	//'activation_key' 		=> array('type' => 'varchar', 'subtype' => 'uniqueID', 'length' => 32, 'editable' => 1, 'exposed' => 0),
	//'password_reset_key' 	=> array('type' => 'varchar', 'length' => 32, 'exposed' => 0, 'editable' => 1),
	//'device_id' 			=> array('type' => 'varchar', 'length' => 64, 'list' => 1),
	//'private_key' 			=> array('type' => 'varchar', 'subtype' => 'uniqueID', 'lenth' => 16, 'editable' => 0, 'exposed' => 0),
	'creation_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now'),
	'update_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'forceUpdate' => 1),
),
'sessions' => array(
	'id' 					=> array('type' => 'int', 'pk' => 1, 'list' => 0, 'editable' => 0),
	'name' 					=> array('type' => 'varchar', 'length' => 32, 'list' => 1, 'editable' => 0),
	'user_id' 				=> array('type' => 'int', 'fk' => 1, 'list' => 1, 'editable' => 0, 'relResource' => 'users', 'relField' => 'id', 'relGetFields' => 'email', 'relGetAs' => 'user_email','displayName' => 'user'),
	'expiration_time'		=> array('type' => 'timestamp', 'list' => 1, 'editable' => 1),
	'ip' 					=> array('type' => 'varchar', 'length' => 48, 'list' => 1, 'editable' => 0),
	'last_url' 				=> array('type' => 'varchar', 'subtype' => 'url', 'list' => 1, 'editable' => 0, 'forceUpdate' => 1),
	'creation_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now'),
	'update_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'forceUpdate' => 1),	
),
'tasks' => array(
    'id'                    => array('type' => 'int', 'pk' => 1, 'AI' => 1, 'list' => 0, 'editable' => 0),
    'slug'           		=> array('type' => 'varchar', 'length' => 32, 'list' => 3),
    'type'                  => array('type' => 'enum', 'possibleValues' => array('import','export','custom'), 'list' => 3),
    'subtype'				=> array('type' => 'varchar', 'length' => 32, 'list' => 3),
    'items_count'    		=> array('type' => 'int', 'length' => 8, 'default' => null, 'list' => 3),
    'log' 					=> array('type' => 'text', 'default' => null),
    'creation_date'         => array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'list' => 1),
    'update_date'           => array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'forceUpdate' => 1, 'list' => 3),
),
);

// App specific
?>