<?php 

### RESOURCES GROUPS ###
$_resourcesGroups = array(
	'push'         => array('resources' => array('pushsubscriptions')),
    'users'         => array('resources' => array('users','usersgroups','groups','groupsauths',)),
    'config'        => array('resources' => array('adminlogs', 'resources','sessions', 'tasks')),
);


### DATAMODEL: RESOURCES ###
$resources = array(
'adminlogs' 			=> array('singular' => 'adminlog', 'table' => 'admin_logs', 'alias' => 'admlog', 'defaultNameField' => 'admin_title', 'displayName' => 'admin logs'),
'apiclients' 		=> array('singular' => 'apiclient', 'table' => 'api_clients', 'alias' => 'apicl', 'defaultNameField' => 'name'),
'apps' 				=> array('singular' => 'app', 'alias' => 'app', 'defaultNameField' => 'admin_title'),
'appsplatforms' 	=> array('singular' => 'appsplatform', 'table' => 'apps_platforms', 'alias' => 'appptf', 'defaultNameField' => 'id', 'displayName' => 'apps platforms'),
'categories'			=> array('singular' => 'category', 'alias' => 'cat', 'defaultNameField' => 'admin_title', 'searchable' => 1, 'exposed' => 1),
'clients' 			=> array('singular' => 'client', 'alias' => 'cl', 'crudability' => 'CRUD', 'defaultNameField' => 'admin_title', 'searchable' => 1),
'contents' 			=> array('singular' => 'content', 'table' => 'issue_contents', 'alias' => 'ic', 'defaultNameField' => 'admin_title'),
'entries' 			=> array('singular' => 'entry', 'alias' => 'e', 'defaultNameField' => 'admin_title'),
'issues' 			=> array('singular' => 'issue', 'alias' => 'i', 'defaultNameField' => 'number'),
'groups' 			=> array('singular' => 'group', 'alias' => 'gp', 'crudability' => 'CRUD', 'defaultNameField' => 'admin_title'),
'groupsauths' 		=> array('singular' => 'groupsauth', 'table' => 'groups_auths',  'alias' => 'gpauth', 'crudability' => 'CRUD', 'defaultNameField' => 'group_id'),
'keywords' 			=> array('singular' => 'keyword', 'alias' => 'k', 'defaultNameField' => 'label'),
'machines' 			=> array('singular' => 'machine', 'alias' => 'mach', 'crudability' => 'CRUD', 'defaultNameField' => 'reference'),
'medias' 			=> array('singular' => 'media', 'table' => 'medias', 'alias' => 'me', 'defaultNameField' => 'admin_title'),
'pointsofinterest' 	=> array('singular' => 'pointofinterest', 'table' => 'points_of_interest', 'alias' => 'poi', 'defaultNameField' => 'admin_title', 'displayName' => 'Points of Interest'),
'platforms' 		=> array('singular' => 'platform', 'alias' => 'ptf', 'defaultNameField' => 'admin_title'),
'products' 				=> array('singular' => 'product', 'alias' => 'p', 'defaultNameField' => 'admin_title', 'searchable' => 1),
'pushregistrations' => array('singular' => 'pushregistration','table' => 'push_registrations', 'alias' => 'pshreg','defaultNameField' => 'device_id','displayName' => 'push registrations'),
'resources' 		=> array('singular' => 'resource', 'alias' => 'res', 'crudability' => 'CRUD', 'defaultNameField' => 'name'),
'sessions' 			=> array('singular' => 'session', 'alias' => 'sess', 'crudability' => 'R', 'defaultNameField' => 'id'),
'tasks' 				=> array('singular' => 'task', 'alias' => 'tsk', 'crudability' => 'CRUD', 'defaultNameField' => 'admin_title'),
'users' 			=> array('singular' => 'user', 'alias' => 'u', 'crudability' => 'CRUD', 'defaultNameField' => 'email', 'searchable' => 1),
'usersgroups' 		=> array('singular' => 'usersgroup', 'table' => 'users_groups', 'alias' => 'ugp', 'crudability' => 'CRUD', 'defaultNameField' => 'user_id'),		
'versions' 			=> array('singular' => 'version', 'alias' => 'v', 'crudability' => 'CRUD', 'defaultNameField' => 'value'),

//'clients'       => array('type' => 'filter', 'singular' => 'client', 'extends' => 'users'),
);

### DATAMODEL: RESOURCES COLUMNS ###
$dataModel = array(
'adminlogs' => array(
    'id'                    => array('type' => 'int', 'pk' => 1, 'AI' => 1, 'list' => 1, 'editable' => 0),
    'admin_title'           => array('type' => 'varchar', 'length' => 64, 'list' => 0),
    'action' 				=> array('type' => 'enum', 'possibleValues' => array('create','update','delete','import'), 'list' => 1),
    'resource_name'			=> array('type' => 'varchar', 'length' => 32, 'list' => 1),
    'resource_id'			=> array('type' => 'varchar', 'length' => 32, 'list' => 1),
	'user_id' 				=> array('type' => 'int', 'fk' => 1, 'list' => 1, 'editable' => 0, 'relResource' => 'users', 'relField' => 'id', 'relGetFields' => 'email', 'relGetAs' => 'user_email','displayName' => 'user'),
	'revert_query' 			=> array('type' => 'text'),
    'creation_date'         => array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'list' => 0),
    'update_date'           => array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'forceUpdate' => 1, 'list' => 1),
),
'contents' => array(
	'id' 					=> array('type' => 'int', 'pk' => 1, 'AI' => 1, 'list' => 1, 'editable' => 0),
	'issues_id' 			=> array('type' => 'int', 'fk' => 1, 'list' => 1, 'relResource' => 'issues', 'relField' => 'id', 'relGetFields' => 'number', 'relGetAs' => 'issue_number','displayName' => 'number'),
	'admin_title' 			=> array('type' => 'varchar', 'length' => 64, 'list' => 1),
	'type' 					=> array('type' => 'enum', 'list' => 1, 'possibleValues' => array('sommaire','image_sommaire_plus','carte','fiche_decouverte','fiche_decouverte_savoir_plus','chapo_fiche_decouverte','sujet_photo','infographie','infographie_plus','histoire_du_jour','image_du_jour','bd','meteo','numero_photo','ours')),
	'heading' 				=> array('type' => 'varchar', 'length' => 64,),
	'title' 				=> array('type' => 'varchar', 'list' => 1),
	'text' 					=> array('type' => 'text'),
	'main_color' 			=> array('type' => 'varchar', 'subtype' => 'color', 'comment' => 'example: #b73133 or rgb(183,49,51) or rgba(183,49,51,0.5)'),
	'more_about' 			=> array('type' => 'text'),
	'creation_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'list' => 1),
	'update_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'forceUpdate' => true, 'list' => 1),
	'origine' 				=> array('type' => 'enum', 'default' => 'human', 'possibleValues' => array('lpq_xml','human')),
),
'categories' => array(
	'id' 					=> array('type' => 'int', 'pk' => 1, 'AI' => 1, 'list' => 1, 'editable' => 0),
	'name' 					=> array('type' => 'varchar', 'length' => 64, 'list' => 1, 'searchable' => 1),
	'admin_title' 			=> array('type' => 'varchar', 'subtype' => 'slug', 'from' => 'name', 'length' => 64, 'list' => 3, 'searchable' => 1),
	'creation_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'list' => 0),
	'update_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'forceUpdate' => 1, 'list' => 0),
),
'entries' => array(
	'id' 					=> array('type' => 'int', 'pk' => 1, 'AI' => 1, 'list' => 1, 'editable' => 0),
	'admin_title' 			=> array('type' => 'varchar', 'length' => 32, 'list' => 1),
	'type' 					=> array('type' => 'enum', 'default' => 'news', 'possibleValues' => array('news','comment','blogPost','tweet','rssItem'), 'list' => 1),
	'title_FR' 				=> array('type' => 'varchar', 'length' => 64, 'list' => 1),
	'link_url' 				=> array('type' => 'varchar', 'subtype' => 'url', 'list' => 1),
	'origine_url' 			=> array('type' => 'varchar', 'subtype' => 'url'),
	'author_name' 			=> array('type' => 'varchar'),
	'summary_FR' 			=> array('type' => 'varchar'),
	'summary_EN' 			=> array('type' => 'varchar'),
	'text_FR' 				=> array('type' => 'text'),
	'text_EN' 				=> array('type' => 'text'),
	'publication_date'		=> array('type' => 'timestamp', 'default' => 'now', 'list' => 1),
	'creation_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now'),
	'update_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'forceUpdate' => 1),
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
'medias' => array(
	'id' 					=> array('type' => 'int', 'pk' => 1, 'AI' => 1, 'list' => 1, 'editable' => 0),
	'title'					=> array('type' => 'varchar', 'length' => 255, 'list' => 1),
	'admin_title'			=> array('type' => 'varchar', 'subtype' => 'slug', 'from' => 'title', 'length' => 64, 'list' => 1, 'comment' => 'For admin/url purpose. No special chars'),
	'type' 					=> array('type' => 'enum', 'default' => 'image', 'possibleValues' => array('image','video','audio')),
	'url' 					=> array('type' => 'varchar', 'subtype' => 'file', 'list' => 1, 'allowedTypes' => 'jpg,png,gif', 'destFolder' => '/public/media/images/',  /*'destName' => 'media_%resource[\'id\']%.%file_extension%'*/),
	'width' 				=> array('type' => 'int', 'length' => 6, 'list' => 1),
	'height' 				=> array('type' => 'int', 'length' => 6, 'list' => 1),
	'entries_id' 			=> array('type' => 'int', 'fk' => 1, 'list' => 1, 'relResource' => 'entries', 'relField' => 'id', 'relGetFields' => 'admin_title', 'relGetAs' => 'entry_title'),
	'creation_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now'),
	'update_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'forceUpdate' => 1),
),
'products' => array(
	'id' 					=> array('type' => 'int', 'pk' => 1, 'AI' => 1, 'list' => 1, 'editable' => 0),
		'admin_title' 			=> array('type' => 'varchar', 'length' => 32, 'list' => 1),
		'title_FR' 				=> array('type' => 'varchar', 'length' => 64, 'list' => 1),
		'title_EN' 				=> array('type' => 'varchar', 'length' => 64, 'list' => 1),
		'description_FR' 		=> array('type' => 'text'),
		'description_EN' 		=> array('type' => 'text'),
		'creation_date'			=> array('type' => 'timestamp', 'list' => 1, 'editable' => false,'computed' => true, 'computedValue' => 'NOW()'),
		'update_date'			=> array('type' => 'timestamp', 'list' => 1,'computed' => true, 'computedValue' => 'NOW()', 'forceUpdate' => true),
		'price_euros' 			=> array('type' => 'varchar', 'length' => 10, 'default' => '800', 'list' => 1),
		'price_dollars' 		=> array('type' => 'varchar', 'length' => 10, 'default' => '1000', 'list' => 1),
		'available_nb' 			=> array('type' => 'int', 'default' => 200, 'list' => 1),
),
'pushsubscriptions' => array(
	'id' 					=> array('type' => 'int', 'pk' => 1, 'AI' => 1, 'list' => 1, 'editable' => 0),
	'device_id' 			=> array('type' => 'varchar', 'length' => 128, 'list' => 3, 'unique' => 1, 'required' => 1),
	'token' 				=> array('type' => 'varchar', 'list' => 1, 'required' => 1),
	'language' 				=> array('type' => 'varchar', 'length' => 5, 'list' => 1, 'eval' => 'strtolower(trim(---self---))',),
	'creation_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'list' => 0),
	'update_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'forceUpdate' => 1, 'list' => 0),
),
'resources' => array(
	'id' 					=> array('type' => 'int', 'pk' => 1, 'list' => 1, 'editable' => 0),
	'name' 					=> array('type' => 'varchar', 'length' => 32, 'list' => 3 , 'required' => 1),
	'singular' 				=> array('type' => 'varchar', 'length' => 32, 'list' => 1),
	'type'                   => array('type' => 'enum', 'default' => 'native', 'possibleValues' => array('native','relation','filter'), 'list' => 3),
	'table'                 => array('type' => 'varchar', 'length' => 32, 'list' => 1),
	'alias'                 => array('type' => 'varchar', 'length' => 8, 'list' => 1),
	'extends'              => array('type' => 'varchar', 'length' => 8, 'list' => 0),
	//'displayName'          => array('type' => 'varchar', 'subtype' => 'slug', 'from' => 'name', 'length' => 32, 'list' => 0),
	'displayName'              => array('type' => 'varchar', 'length' => 32, 'list' => 0),
	'defaultNameField'     => array('type' => 'varchar', 'length' => 32, 'list' => 1),
	'creation_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'list' => 0),
	'update_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'forceUpdate' => 1, 'list' => 0),
),
'tasks' => array(
    'id'                    => array('type' => 'int', 'pk' => 1, 'AI' => 1, 'list' => 1, 'editable' => 0),
    'admin_title'           => array('type' => 'varchar', 'length' => 32, 'list' => 0),
    'type'                  => array('type' => 'enum', 'possibleValues' => array('import'), 'list' => 1),
    'subtype'				=> array('type' => 'varchar', 'length' => 32, 'list' => 1),
    'processed_items_nb'    => array('type' => 'int', 'length' => 6, 'list' => 1),
    'creation_date'         => array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'list' => 1),
    'update_date'           => array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'forceUpdate' => 1, 'list' => 1),
),
'usersgroups' => array(
	'id' 					=> array('type' => 'int', 'pk' => 1, 'AI' => 1, 'list' => 1, 'editable' => 0),
	'user_id' 				=> array('type' => 'int', 'fk' => 1, 'list' => 1, 'relResource' => 'users', 'relField' => 'id', 'relGetFields' => 'email', 'relGetAs' => 'user_email'),
	'group_id' 				=> array('type' => 'int', 'fk' => 1, 'list' => 1, 'relResource' => 'groups', 'relField' => 'id', 'relGetFields' => 'name', 'relGetAs' => 'group_name'),
	'creation_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'list' => 1),
	'update_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'forceUpdate' => 1, 'list' => 1),
),
'users' => array(
	'id' 					=> array('type' => 'int', 'pk' => 1, 'AI' => 1, 'list' => 1, 'editable' => 0),
	'email' 				=> array('type' => 'varchar', 'subtype' => 'email', 'list' => 3, 'searchable' => 1),
	//'password' 				=> array('type' => 'varchar', 'subtype' => 'password', 'hash' => 'sha1', 'length' => 64, 'list' => 1, 'editable' => 0),
	'password'                 => array('type' => 'varchar', 'subtype' => 'password', 'hash' => 'sha1', 'length' => 64, 'editable' => 1),
	'first_name' 			=> array('type' => 'varchar', 'length' => 64, 'list' => 3, 'eval' => 'strtolower(trim(---self---))', 'searchable' => 1),
	'last_name' 			=> array('type' => 'varchar', 'length' => 64, 'list' => 3, 'eval' => 'strtolower(trim(---self---))', 'searchable' => 1),
	// TODO: type custom (firstname)
	//'name' 					=> array('type' => 'varchar', 'length' => 128, 'list' => 1),
	//'name'                     => array('type' => 'varchar', 'length' => 128, 'searchable' => 1),
	//'auth_level' 			=> array('type' => 'enum', 'default' => 'user', 'possibleValues' => array('user','contributor','admin','superadmin','god'), 'editable' => 0),
	//'auth_level_nb' 		=> array('type' => 'int', 'default' => 10, 'editable' => 0, 'comment' => '10=user, 100=contributor, 500=admin, 1000=superadmin, 10000=god'),
	//'groups' 				=> array('type' => 'onetomany', 'relResource' => 'groups', 'relField' => 'id', 'pivotResource' => 'users_groups', 'pivotLeftField' => 'user_id', 'pivotRightField' => 'group_id', 'getFields' => 'admin_title'),
	'groups' 				=> array('type' => 'onetomany', 'getFields' => 'id,admin_title'),
	//'company' 				=> array('type' => 'varchar', 'length' => 64),
	//'address' 				=> array('type' => 'varchar'),
	//'zipcode' 				=> array('type' => 'varchar', 'length' => 16),
	//'city' 					=> array('type' => 'varchar', 'length' => 32),
	//'country' 				=> array('type' => 'varchar', 'length' => 64),
	//'TCS_accepted' 			=> array('type' => 'bool', 'default' => 0, 'list' => 1, 'comment' => 'Terms and Condtions of Sales accepted?'),
	//'TU_accepted' 			=> array('type' => 'bool', 'default' => 0, 'list' => 1, 'comment' => 'Terms of Use accepted?'),
	//'billing_address' 		=> array('type' => 'varchar'),
	//'billing_zipcode' 		=> array('type' => 'varchar', 'length' => 16),
	//'billing_city' 			=> array('type' => 'varchar', 'length' => 32),
	//'billing_country' 		=> array('type' => 'varchar', 'length' => 64),
	'creation_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now'),
	'update_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'forceUpdate' => 1),
),
'sessions' => array(
	'id' 					=> array('type' => 'int', 'pk' => 1, 'list' => 1, 'editable' => 0),
	'name' 					=> array('type' => 'varchar', 'length' => 32, 'list' => 1, 'editable' => 0),
	'user_id' 				=> array('type' => 'int', 'fk' => 1, 'list' => 1, 'editable' => 0, 'relResource' => 'users', 'relField' => 'id', 'relGetFields' => 'email', 'relGetAs' => 'user_email','displayName' => 'user'),
	'creation_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now'),
	'update_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'forceUpdate' => 1),
	'expiration_time'		=> array('type' => 'timestamp', 'list' => 1, 'editable' => 1),
	'ip' 					=> array('type' => 'varchar', 'length' => 48, 'list' => 1, 'editable' => 0),
	'last_url' 				=> array('type' => 'varchar', 'subtype' => 'url', 'list' => 1, 'editable' => 0, 'forceUpdate' => 1),
),
'versions' => array(
	'id' 					=> array('type' => 'int', 'pk' => 1, 'AI' => 1, 'list' => 1, 'editable' => 0),
	'major' 				=> array('type' => 'int', 'length' => 4, 'list' => 1),
	'minor' 				=> array('type' => 'int', 'length' => 4, 'list' => 1),
	'build' 				=> array('type' => 'int', 'length' => 4, 'list' => 1),
	'revision' 				=> array('type' => 'int', 'length' => 4, 'list' => 1),
	'creation_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'list' => 1),
	'update_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'forceUpdate' => 1, 'list' => 1),
),
);

// Filter resources
// TODO:
$dataModel['clients'] = &$dataModel['users'];

?>