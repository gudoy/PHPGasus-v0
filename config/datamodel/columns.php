<?php 

### DATAMODEL: RESOURCES COLUMNS ###
$_resourcesColumns = array(
'adminlogs' => array(
    'id'                    => array(), // first col && === 'id' => default to serial+pk+index+list=1
    'slug'          	 	=> array('type' => 'slug', 'length' => 64), // slug => list=3, searchable = 1 ??? or |=> is defaultNameField
    'action' 				=> array('type' => 'enum', 'values' => array('create','update','delete','import'), 'list' => 1),
    'resource_name'			=> array('length' => 32, 'list' => 1), // contains 'name' => 'string'
    'resource_id'			=> array('length' => 32, 'list' => 1),
	'user_id' 				=> array('list' => 1), // match the pattern {resource}_{$column} => onetoone
	'revert_query' 			=> array('type' => 'text'),
    'creation_date'         => array(),
    'update_date'           => array(),
),
'categories' => array(
    'id'                    => array(), // first col && === 'id' => default to serial+pk+index+list=1
	'name' 					=> array('length' => 64, 'list' => 1, 'searchable' => 1),
	'slug' 					=> array('from' => 'name', 'length' => 64, 'searchable' => 1),
	'creation_date'			=> array(),
	'update_date'			=> array(),
),
'creators' => array(
    'id'                    => array(), // first col && === 'id' => default to serial+pk+index+list=1
	'name' 					=> array('length' => 64, 'list' => 1),
	'slug' 					=> array('from' => 'name', 'length' => 64),
	'eclaireur_id' 			=> array('type' => 'int', 'list' => 1),
	'ref' 					=> array('type' => 'varchar', 'length' => 16, 'list' => 3),
	'creation_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'list' => 0),
	'update_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'forceUpdate' => 1, 'list' => 0),
),
'clients' => array(
    'id'                    => array(), // first col && === 'id' => default to serial+pk+index+list=1
	'name' 					=> array('type' => 'varchar', 'length' => 32, 'list' => 1, 'searchable' => 1),
	'slug' 					=> array('type' => 'varchar', 'subtype' => 'slug', 'from' => 'name', 'list' => 1),
	'logo_url' 				=> array('type' => 'varchar', 'subtype' => 'url', 'list' => 1),
	'website_url' 			=> array('type' => 'varchar', 'subtype' => 'url', 'list' => 1),
	'creation_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now'),
	'update_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'forceUpdate' => 1),
),
'groups' => array(
    'id'                    => array(), // first col && === 'id' => default to serial+pk+index+list=1
	'name' 					=> array('type' => 'varchar', 'length' => 32, 'list' => 1),
	'slug' 					=> array('type' => 'varchar', 'subtype' => 'slug', 'from' => 'name', 'list' => 1),
	'creation_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'list' => 1),
	'update_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'forceUpdate' => 1, 'list' => 1),
),
'groupsauths' => array(
    'id'                    => array(), // first col && === 'id' => default to serial+pk+index+list=1
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
    'id'                    => array(), // first col && === 'id' => default to serial+pk+index+list=1
	'title'					=> array('type' => 'varchar', 'length' => 255, 'list' => 1),
	'slug'					=> array('type' => 'varchar', 'subtype' => 'slug', 'from' => 'title', 'length' => 64, 'list' => 1, 'comment' => 'For admin/url purpose. No special chars'),
	'type' 					=> array('type' => 'enum', 'default' => 'image', 'possibleValues' => array('image','video','audio')),
	'url' 					=> array('type' => 'varchar', 'subtype' => 'file', 'list' => 1, 'allowedTypes' => 'jpg,png,gif', 'destFolder' => '/public/media/images/',  /*'destName' => 'media_%resource[\'id\']%.%file_extension%'*/),
	'width' 				=> array('type' => 'int', 'length' => 6, 'list' => 1),
	'height' 				=> array('type' => 'int', 'length' => 6, 'list' => 1),
	'entries_id' 			=> array('type' => 'int', 'fk' => 1, 'list' => 1, 'relResource' => 'entries', 'relField' => 'id', 'relGetFields' => 'slug', 'relGetAs' => 'entry_title'),
	'creation_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now'),
	'update_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'forceUpdate' => 1),
),
'products' => array(
    'id'                    => array(), // first col && === 'id' => default to serial+pk+index+list=1
	'ref' 					=> array('type' => 'varchar' ,'length' => 16, 'list' => 3, 'searchable' => 1),
	'slug' 					=> array('type' => 'varchar', 'length' => 64, 'list' => 3, 'searchable' => 1),
	'sector_id' 			=> array('type' => 'int', 'fk' => 1, 'list' => 1, 'relResource' => 'sectors', 'relField' => 'id', 'relGetFields' => 'name', 'relGetAs' => 'sector_name'),
	'category_id' 			=> array('type' => 'int', 'fk' => 1, 'list' => 1, 'relResource' => 'categories', 'relField' => 'id', 'relGetFields' => 'admin_title', 'relGetAs' => 'category_name'),
	'creator_id' 			=> array('type' => 'int', 'fk' => 1, 'list' => 3, 'relResource' => 'creators', 'relField' => 'id', 'relGetFields' => 'admin_title', 'relGetAs' => 'creator_name'),
	'ref_creator' 			=> array('type' => 'varchar' ,'length' => 16, 'list' => 1, 'searchable' => 1),
	'ref_creator_decl' 		=> array('type' => 'int' ,'length' => 3, 'default' => null, 'list' => 1),
	'title_01' 				=> array('type' => 'varchar', 'length' => 32),
	'title_02' 				=> array('type' => 'varchar', 'length' => 32),
	'title' 				=> array('type' => 'varchar', 'length' => 64),
	'season' 				=> array('type' => 'enum', 'possibleValues' => array('summer','winter')),
	//'year' 					=> array('type' => 'year'),
	'year' 					=> array('type' => 'int', 'length' => 4),
	'has_pictures'			=> array('type' => 'bool', 'default' => 0),
	'creation_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'list' => 0),
	'update_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'forceUpdate' => 1, 'list' => 0),
),
'productcategories' => array(
    'id'                    => array(), // first col && === 'id' => default to serial+pk+index+list=1
	'product_id' 			=> array('type' => 'int', 'fk' => 1, 'list' => 1, 'relResource' => 'products', 'relField' => 'id', 'relGetFields' => 'ref', 'relGetAs' => 'product_ref'),
	'category_id' 			=> array('type' => 'int', 'fk' => 1, 'list' => 1, 'relResource' => 'categories', 'relField' => 'id', 'relGetFields' => 'admin_title', 'relGetAs' => 'category_name'),
	'creation_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'list' => 1),
	'update_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'forceUpdate' => 1, 'list' => 1),
),
'productsectors' => array(
    'id'                    => array(), // first col && === 'id' => default to serial+pk+index+list=1
	'product_id' 			=> array('type' => 'int', 'fk' => 1, 'list' => 1, 'relResource' => 'products', 'relField' => 'id', 'relGetFields' => 'ref', 'relGetAs' => 'product_ref'),
	'sector_id' 			=> array('type' => 'int', 'fk' => 1, 'list' => 1, 'relResource' => 'sectors', 'relField' => 'id', 'relGetFields' => 'admin_title', 'relGetAs' => 'sector_name'),
	'creation_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'list' => 1),
	'update_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'forceUpdate' => 1, 'list' => 1),
),
'productsizes' => array(
    'id'                    => array(), // first col && === 'id' => default to serial+pk+index+list=1
	'product_id' 			=> array('type' => 'int', 'fk' => 1, 'list' => 1, 'relResource' => 'products', 'relField' => 'id', 'relGetFields' => 'ref', 'relGetAs' => 'product_ref'),
	'size_id' 				=> array('type' => 'int', 'fk' => 1, 'list' => 1, 'relResource' => 'sizes', 'relField' => 'id', 'relGetFields' => 'admin_title', 'relGetAs' => 'size'),
	'creation_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'list' => 1),
	'update_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'forceUpdate' => 1, 'list' => 1),
),
'productsstocks' => array(
    'id'                    => array(), // first col && === 'id' => default to serial+pk+index+list=1
	'product_id' 			=> array('type' => 'int', 'fk' => 1, 'list' => 3, 'relResource' => 'products', 'relField' => 'id', 'relGetFields' => 'ref', 'relGetAs' => 'product_ref'),
	'size_id' 				=> array('type' => 'int', 'fk' => 1, 'list' => 3, 'relResource' => 'sizes', 'relField' => 'id', 'relGetFields' => 'admin_title', 'relGetAs' => 'size'),
	//'product_size_id' 		=> array('type' => 'int', 'fk' => 1, 'list' => 3, 'relResource' => 'productsizes', 'relField' => 'id'),
	'store_id' 				=> array('type' => 'int', 'fk' => 1, 'list' => 3, 'relResource' => 'stores', 'relField' => 'id', 'relGetFields' => 'name', 'relGetAs' => 'store_name'),
	'stock' 				=> array('type' => 'int', 'length' => 6, 'list' => 3),
	'creation_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'list' => 1),
	'update_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'forceUpdate' => 1, 'list' => 1),
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
'sectors' => array(
	'id' 					=> array('type' => 'int', 'pk' => 1, 'AI' => 1, 'list' => 1, 'editable' => 0),
	'name' 					=> array('type' => 'varchar', 'length' => 64, 'list' => 1, 'searchable' => 1),
	//'eclaireur_id' 			=> array('type' => 'int', 'list' => 1, 'searchable' => 1),
	'slug' 			=> array('type' => 'varchar', 'subtype' => 'slug', 'from' => 'name', 'length' => 64, 'list' => 3, 'searchable' => 1),
	'creation_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'list' => 0),
	'update_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'forceUpdate' => 1, 'list' => 0),
),
'sizes' => array(
	'id' 					=> array('type' => 'int', 'pk' => 1, 'AI' => 1, 'list' => 1, 'editable' => 0),
	'admin_title' 			=> array('type' => 'varchar', 'length' => 9, 'list' => 3),
	'grid' 					=> array('type' => 'varchar', 'length' => 4, 'list' => 3),
	'value' 				=> array('type' => 'varchar', 'length' => 4, 'list' => 3),
	'creation_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'list' => 0),
	'update_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'forceUpdate' => 1, 'list' => 0),
),
'stores' => array(
	'id' 					=> array('type' => 'int', 'pk' => 1, 'AI' => 1, 'list' => 1, 'editable' => 0),
	'name' 					=> array('type' => 'varchar', 'length' => 32, 'list' => 1, 'searchable' => 1),
	'slug' 			=> array('type' => 'varchar', 'subtype' => 'slug', 'from' => 'name', 'length' => 32, 'list' => 3, 'searchable' => 1),
	'eclaireur_id' 			=> array('type' => 'int', 'list' => 1, 'searchable' => 1),
	'address' 				=> array('type' => 'varchar', 'list' => 3, 'searchable' => 1),
	'zipcode'				=> array('type' => 'varchar', 'length' => 8),
	'city' 					=> array('type' => 'varchar', 'length' => 64, 'searchable' => 1, 'list' => 3),
	'creation_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'list' => 1),
	'update_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now', 'forceUpdate' => 1, 'list' => 1),
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
);

// Filter resources
// TODO:
//$dataModel['commercials'] = &$dataModel['users'];

?>