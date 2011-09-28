<?php 

### RESOURCES GROUPS ###
$_resourcesGroups = array(
	'apps' 			=> array('resources' => array('apps','platforms','appsplatforms','clients','clientsapps')),
	'contents' 		=> array('resources' => array('entries', 'medias',)),
    'users'         => array('resources' => array('users', 'usersgroups', 'groups', 'groupsauths', 'sessions')),
    'config'        => array('resources' => array('adminlogs', 'bans', 'resources', 'resourcescolumns', 'tasks',)),
);


### DATAMODEL: RESOURCES ###
// Deprecated
$resources = array(
'adminlogs' 		=> array('singular' => 'adminlog', 'table' => 'admin_logs', 'alias' => 'admlog', 'defaultNameField' => 'slug', 'displayName' => 'admin logs'),
'apps' 				=> array('singular' => 'app', 'alias' => 'app', 'defaultNameField' => 'admin_title', 'searchable' => 1),
'bans' 				=> array('singular' => 'ban', 'table' => 'bans', 'alias' => 'b', 'defaultNameField' => 'ip', 'displayName' => 'bans'), 
'appsplatforms' 	=> array('singular' => 'appsplatform', 'table' => 'apps_platforms', 'alias' => 'appptf', 'defaultNameField' => 'id', 'displayName' => 'apps platforms'),
'categories'		=> array('singular' => 'category', 'alias' => 'cat', 'defaultNameField' => 'slug', 'searchable' => 1, 'exposed' => 1),
'clients' 			=> array('singular' => 'client', 'alias' => 'cl', 'crudability' => 'CRUD', 'defaultNameField' => 'admin_title', 'searchable' => 1),
'clientsapps' 		=> array('type' => 'relation', 'singular' => 'clientapp', 'plural' => 'clientsapps', 'displayName' => 'clients apps', 'defaultNameField' => 'null', 'extends' => 'null', 'database' => 'default', 'table' => 'client_apps', 'alias' => 'clapps', 'searchable' => false, 'exposed' => 'false', 'crudability' => 'CRUD'), 
'countries' 		=> array('type' => 'native', 'singular' => 'country', 'plural' => 'countries', 'displayName' => 'countries', 'defaultNameField' => 'slug', 'extends' => 'null', 'database' => 'default', 'table' => 'countries', 'alias' => 'cntry', 'searchable' => true, 'exposed' => 'false', 'crudability' => 'CRUD'),
'entries' 			=> array('singular' => 'entry', 'alias' => 'e', 'defaultNameField' => 'admin_title', 'searchable' => 1),
'groups' 			=> array('singular' => 'group', 'alias' => 'gp', 'crudability' => 'CRUD', 'defaultNameField' => 'admin_title'),
'groupsauths' 		=> array('singular' => 'groupsauth', 'table' => 'groups_auths',  'alias' => 'gpauth', 'crudability' => 'CRUD', 'defaultNameField' => 'group_id'),
'medias' 			=> array('singular' => 'media', 'table' => 'medias', 'alias' => 'me', 'defaultNameField' => 'admin_title', 'searchable' => 1),
'platforms' 		=> array('singular' => 'platform', 'alias' => 'ptf', 'defaultNameField' => 'admin_title'),
'products' 			=> array('singular' => 'product', 'alias' => 'p', 'defaultNameField' => 'admin_title', 'searchable' => 1),
'resources' 		=> array('singular' => 'resource', 'alias' => 'res', 'crudability' => 'CRUD', 'defaultNameField' => 'name'),
'resourcescolumns' 	=> array('singular' => 'resourcecolumn', 'alias' => 'rescol', 'table' => 'resources_columns', 'crudability' => 'CRUD', 'defaultNameField' => 'columns', 'displayName' => 'columns'),
'sessions' 			=> array('singular' => 'session', 'alias' => 'sess', 'crudability' => 'R', 'defaultNameField' => 'id'),
'tasks' 			=> array('singular' => 'task', 'alias' => 'tsk', 'crudability' => 'CRUD', 'defaultNameField' => 'slug'),
'users' 			=> array('singular' => 'user', 'alias' => 'u', 'crudability' => 'CRUD', 'defaultNameField' => 'email', 'searchable' => 1),
'usersgroups' 		=> array('singular' => 'usersgroup', 'table' => 'users_groups', 'alias' => 'ugp', 'crudability' => 'CRUD'),
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
'apps' => array(
	'id' 					=> array('type' => 'int', 'pk' => 1, 'AI' => 1, 'list' => 1, 'editable' => 0),
	'title_FR' 				=> array('type' => 'varchar', 'length' => 64, 'list' => 1),
	'title_EN' 				=> array('type' => 'varchar', 'length' => 64),
	'admin_title'			=> array('type' => 'varchar', 'subtype' => 'slug', 'from' => 'title_FR', 'length' => 64, 'list' => 1, 'comment' => 'For admin/url purpose. No special chars', 'searchable' => 1),
	'clients_id' 			=> array('type' => 'int', 'fk' => 1, 'list' => 1, 'relResource' => 'clients', 'relField' => 'id', 'relGetFields' => 'name', 'relGetAs' => 'client_name'),
	//'platforms_id' 			=> array('type' => 'int', 'fk' => 1, 'list' => 1, 'relResource' => 'platforms', 'relField' => 'id', 'relGetFields' => 'name', 'relGetAs' => 'platforms_name'),
	'platforms_id' 			=> array('type' => 'int', 'fk' => 1, 'list' => 1, 'relResource' => 'platforms', 'relField' => 'id', 'relGetFields' => 'admin_title', 'relGetAs' => 'platforms_name'),
	'description_FR' 		=> array('type' => 'text'),
	'description_EN' 		=> array('type' => 'text'),
	'baseline_short_FR'		=> array('type' => 'varchar', 'length' => 80, 'list' => 1),
	'baseline_short_EN'		=> array('type' => 'varchar', 'length' => 80),
	'desc_short_FR'			=> array('type' => 'text'),
	'desc_short_EN'			=> array('type' => 'text'),
	'download_url' 			=> array('type' => 'varchar', 'subtype' => 'url', 'list' => 1),
	'main_orientation' 		=> array('type' => 'enum', 'possibleValues' => array('none', 'portrait', 'landscape', 'mixed'), 'list' => 1),
	'main_color' 			=> array('type' => 'varchar', 'subtype' => 'color', 'length' => 20, 'list' => 1),
	'importance' 			=> array('type' => 'int', 'length' => 4, 'list' => 1),
	'is_available' 			=> array('type' => 'bool', 'default' => 0, 'list' => 1),
	'is_displayable' 		=> array('type' => 'bool', 'default' => 0, 'list' => 1),
	'release_date'			=> array('type' => 'timestamp', 'default' => 'now', 'list' => 1),
	'creation_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now'),
	'update_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'forceUpdate' => 1),
),
'categories' => array(
	'id' 					=> array('type' => 'int', 'pk' => 1, 'AI' => 1, 'list' => 1, 'editable' => 0),
	'name' 					=> array('type' => 'varchar', 'length' => 64, 'list' => 1, 'searchable' => 1),
	'slug' 					=> array('type' => 'varchar', 'subtype' => 'slug', 'from' => 'name', 'length' => 64, 'list' => 3, 'searchable' => 1),
	'creation_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'list' => 0),
	'update_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'forceUpdate' => 1, 'list' => 0),
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
'countries' => array(
    'id'                    => array('type' => 'int', 'pk' => 1, 'AI' => 1, 'list' => 1, 'editable' => 0),
    'slug'           		=> array('type' => 'varchar', 'length' => 64, 'list' => 3, 'searchable' => 1),
    'name' 					=> array('type' => 'varchar', 'length' => 64, 'list' => 1),
    'name_FR' 				=> array('type' => 'varchar', 'length' => 64, 'list' => 1, 'searchable' => 1),
    'iso' 					=> array('type' => 'varchar', 'length' => 3, 'list' => 1, 'searchable' => 1),
    'iso3' 					=> array('type' => 'varchar', 'length' => 3, 'list' => 1, 'searchable' => 1),
    'iso_numeric' 			=> array('type' => 'int', 'length' => 3),
    'fips_code' 			=> array('type' => 'varchar', 'length' => 2),
    'capital_name' 			=> array('type' => 'varchar', 'length' => 128, 'list' => 0),
    'currency_code' 		=> array('type' => 'varchar', 'length' => 3, 'list' => 1),
    'currency_name' 		=> array('type' => 'varchar', 'length' => 16, 'list' => 1),
    'area_square_km' 		=> array('type' => 'float', 'list' => 0),
    'population' 			=> array('type' => 'int', 'length' => 9, 'list' => 0),
    'continent_code' 		=> array('type' => 'varchar', 'length' => 3),
    'tld' 					=> array('type' => 'varchar', 'length' => 5),
    'phone_code'			=> array('type' => 'int', 'length' => 4),
    'postal_code_format' 	=> array('type' => 'varchar', 'length' => 4),
    'postal_code_regex' 	=> array('type' => 'varchar', 'length' => 16),
    'spoken_languages' 		=> array('type' => 'varchar', 'length' => 32),
    'creation_date'         => array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'list' => 0),
    'update_date'           => array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'forceUpdate' => 1, 'list' => 1),
),
'clientsapps' => array(
	'id' 					=> array('type' => 'int', 'pk' => 1, 'AI' => 1, 'list' => 1, 'editable' => 0),
	'client_id' 			=> array('type' => 'int', 'fk' => 1, 'list' => 3, 'relResource' => 'clients', 'relField' => 'id', 'relGetFields' => 'name', 'relGetAs' => 'client_name'),
	'app_id' 				=> array('type' => 'int', 'fk' => 1, 'list' => 3, 'relResource' => 'apps', 'relField' => 'id', 'relGetFields' => 'admin_title', 'relGetAs' => 'app_title'),
	'creation_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'list' => 1),
	'update_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'forceUpdate' => 1, 'list' => 1),
),
'entries' => array(
	'id' 					=> array('type' => 'int', 'pk' => 1, 'AI' => 1, 'list' => 1, 'editable' => 0),
	'slug' 					=> array('type' => 'varchar', 'subtype' => 'slug', 'from' => 'title', 'length' => 64, 'list' => 3),
	'type' 					=> array('type' => 'enum', 'default' => 'news', 'possibleValues' => array('news','comment','blogPost','tweet','rssItem'), 'list' => 1),
	'title' 				=> array('type' => 'varchar', 'length' => 64, 'list' => 3),
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
'entrieslinks' => array(
	'id' 					=> array('type' => 'int', 'pk' => 1, 'AI' => 1, 'list' => 1, 'editable' => 0),
	'entry_id' 				=> array('type' => 'int', 'fk' => 1, 'list' => 3, 'relResource' => 'entries', 'relField' => 'id', 'relGetFields' => 'slug', 'relGetAs' => 'entry_slug'),
	'link_id' 				=> array('type' => 'int', 'fk' => 1, 'list' => 3, 'relResource' => 'links', 'relField' => 'id', 'relGetFields' => 'url', 'relGetAs' => 'link_url'),
	'creation_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'list' => 1),
	'update_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'forceUpdate' => 1, 'list' => 1),
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
	'title'					=> array('type' => 'varchar', 'length' => 64, 'list' => 1),
	'slug'					=> array('type' => 'varchar', 'subtype' => 'slug', 'from' => 'title', 'length' => 64, 'list' => 3, 'searchable' => 1),
	'type' 					=> array('type' => 'enum', 'default' => 'image', 'possibleValues' => array('image','video','audio'), 'list' => 1),
	'url' 					=> array('type' => 'varchar', 'subtype' => 'file', 'list' => 3, 'allowedTypes' => 'jpg,png,gif', 'destFolder' => '/public/media/images/',  /*'destName' => 'media_%resource[\'id\']%.%file_extension%'*/),
	'summary' 				=> array('type' => 'text', 'length' => 255),
	'width' 				=> array('type' => 'int', 'length' => 6, 'list' => 1),
	'height' 				=> array('type' => 'int', 'length' => 6, 'list' => 1),
	'size' 					=> array('type' => 'float', 'list' => 1, 'comment' => 'in octets'),
	'duration_sec' 			=> array('type' => 'float', 'list' => 1),
	'cover_small_url' 		=> array('type' => 'varchar', 'subtype' => 'file', 'list' => 1, 'allowedTypes' => 'jpg,png', 'destFolder' => '/public/media/videos/covers/',  'destName' => 'cover_small_%resource[\'id\']%.%file_extension%'),
	'cover_medium_url' 		=> array('type' => 'varchar', 'subtype' => 'file', 'list' => 1, 'allowedTypes' => 'jpg,png', 'destFolder' => '/public/media/videos/covers/',  'destName' => 'media_%resource[\'id\']%.%file_extension%'),
	'cover_large_url' 		=> array('type' => 'varchar', 'subtype' => 'file', 'list' => 1, 'allowedTypes' => 'jpg,png', 'destFolder' => '/public/media/videos/covers/',  'destName' => 'media_%resource[\'id\']%.%file_extension%'),
	'importance' 			=> array('type' => 'int', 'length' => 5, 'list' => 1),
	'creation_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now'),
	'update_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'forceUpdate' => 1),
),
'platforms' => array(
	'id' 					=> array('type' => 'int', 'pk' => 1, 'AI' => 1, 'list' => 1, 'editable' => 0),
	'name' 					=> array('type' => 'varchar', 'length' => 32, 'list' => 1),
	'admin_title'			=> array('type' => 'varchar', 'subtype' => 'slug', 'from' => 'name', 'length' => 64, 'list' => 1, 'comment' => 'For admin/url purpose. No special chars'),
	'constructor' 			=> array('type' => 'varchar', 'length' => 64, 'list' => 1),
	'creation_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now'),
	'update_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'forceUpdate' => 1),
	'position' 				=> array('type' => 'int', 'length' => 4, 'default' => 0, 'list' => 1, 'comment' => 'Used for display order. Higher is stronger.'),
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
'resources' => array(
	'id' 					=> array('type' => 'int', 'pk' => 1, 'list' => 1, 'editable' => 0),
	'table'                 => array('type' => 'varchar', 'length' => 32, 'list' => 1),
	'name' 					=> array('type' => 'varchar', 'subtype' => 'slug', 'from' => 'table', 'length' => 32, 'list' => 3 , 'required' => 1),
	'singular' 				=> array('type' => 'varchar', 'length' => 32, 'list' => 1),
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
	'first_name' 			=> array('type' => 'varchar', 'length' => 64, 'list' => 3, 'eval' => 'strtolower(trim(---self---))', 'searchable' => 0),
	'last_name' 			=> array('type' => 'varchar', 'length' => 64, 'list' => 3, 'eval' => 'strtolower(trim(---self---))', 'searchable' => 0),
	'name' 					=> array('type' => 'varchar', 'length' => 128, 'list' => 1, 'searchable' => 1),
	//'groups' 				=> array('type' => 'onetomany', 'relResource' => 'groups', 'relField' => 'id', 'pivotResource' => 'users_groups', 'pivotLeftField' => 'user_id', 'pivotRightField' => 'group_id', 'getFields' => 'admin_title'),
	'groups' 				=> array('type' => 'onetomany', 'getFields' => 'id,admin_title'),
	'activated' 			=> array('type' => 'bool', 'default' => 0, 'list' => 1, 'exposed' => 0),
	'activation_key' 		=> array('type' => 'varchar', 'subtype' => 'uniqueID', 'length' => 32, 'editable' => 1, 'exposed' => 0),
	'password_reset_key' 	=> array('type' => 'varchar', 'length' => 32, 'exposed' => 0, 'editable' => 1),
	'device_id' 			=> array('type' => 'varchar', 'length' => 64, 'list' => 1),
	'private_key' 			=> array('type' => 'varchar', 'subtype' => 'uniqueID', 'lenth' => 16, 'editable' => 0, 'exposed' => 0),
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