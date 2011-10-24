
CREATE TABLE IF NOT EXISTS `admin_logs` (
  `id` int(11) NOT NULL auto_increment,
  `slug` varchar(64) default NULL,
  `action` enum('create','update','delete','import') NOT NULL,
  `resource_name` varchar(32) NOT NULL,
  `resource_id` varchar(32) NOT NULL,
  `user_id` int(11) default NULL,
  `revert_query` text,
  `creation_date` timestamp NOT NULL default '0000-00-00 00:00:00',
  `update_date` timestamp NOT NULL default '0000-00-00 00:00:00' on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `bans` (
  `id` int(11) NOT NULL auto_increment,
  `ip` varchar(40) NOT NULL,
  `reason` varchar(32) NOT NULL,
  `end_date` timestamp NULL default NULL,
  `creation_date` timestamp NOT NULL default '0000-00-00 00:00:00',
  `update_date` timestamp NOT NULL default '0000-00-00 00:00:00' on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `ip` (`ip`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(32) NOT NULL,
  `admin_title` varchar(32) NOT NULL,
  `creation_date` timestamp NOT NULL default '0000-00-00 00:00:00',
  `update_date` timestamp NOT NULL default '0000-00-00 00:00:00' on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `admin_title` (`admin_title`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `resources` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(32) NOT NULL,
  `singular` varchar(32) NOT NULL,
  `type` enum('native','filter','relation') NOT NULL default 'native',
  `table` varchar(32) default NULL,
  `alias` varchar(8) default NULL,
  `extends` varchar(32) default NULL,
  `displayName` varchar(32) default NULL,
  `defaultNameField` varchar(32) default NULL,
  `creation_date` timestamp NOT NULL default '0000-00-00 00:00:00',
  `update_date` timestamp NOT NULL default '0000-00-00 00:00:00' on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `resources_columns` (
  `id` int(11) NOT NULL auto_increment,
  `resource_id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `type` enum('string','email','password','url','tel','color','meta','ip','slug','tag','text','html','code','int','tinyint','float','smallint','mediumint','bigint','bool','boolean','timestamp','datetime','date','time','year','month','week','day','hour','minutes','seconds','onetoone','onetomany','manytoone','manytomany','id','enum','file','image','video','sound') NOT NULL,
  `realtype` enum('serial','bit','tinyint','bool','smallint','mediumint','int','bigint','float','double','double precision','decimal','date','datetime','timestamp','time','year','char','varchar','binary','varbinary','tinyblob','tinytext','blob','text','mediumblob','mediumtext','longblob','longtext','enum','set') NOT NULL,
  `length` bigint(20) NOT NULL,
  `pk` tinyint(1) NOT NULL default '0',
  `ai` tinyint(1) NOT NULL default '0',
  `fk` tinyint(1) NOT NULL default '0',
  `default` varchar(255) NOT NULL,
  `null` tinyint(1) NOT NULL default '0',
  `creation_date` timestamp NOT NULL default '0000-00-00 00:00:00',
  `update_date` timestamp NOT NULL default '0000-00-00 00:00:00' on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  KEY `resource_id` (`resource_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `groups_auths` (
  `id` int(11) NOT NULL auto_increment,
  `group_id` int(11) NOT NULL,
  `resource_id` int(11) NOT NULL,
  `allow_display` tinyint(1) NOT NULL default '1',
  `allow_create` tinyint(1) NOT NULL default '0',
  `allow_retrieve` tinyint(1) NOT NULL default '0',
  `allow_update` tinyint(1) NOT NULL default '0',
  `allow_delete` tinyint(1) NOT NULL default '0',
  `creation_date` timestamp NOT NULL default '0000-00-00 00:00:00',
  `update_date` timestamp NOT NULL default '0000-00-00 00:00:00' on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `group_auth_unique` (`group_id`,`resource_id`),
  KEY `group_id` (`group_id`),
  KEY `resource_id` (`resource_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='groups authorisations (ACL)';


CREATE TABLE IF NOT EXISTS `sessions` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(32) NOT NULL,
  `user_id` int(11) NOT NULL,
  `creation_date` timestamp NOT NULL default '0000-00-00 00:00:00',
  `update_date` timestamp NOT NULL default '0000-00-00 00:00:00' on update CURRENT_TIMESTAMP,
  `expiration_time` timestamp NOT NULL default '0000-00-00 00:00:00',
  `ip` varchar(48) NOT NULL,
  `last_url` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `name` (`name`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `tasks` (
  `id` int(11) NOT NULL auto_increment,
  `slug` varchar(64) default NULL,
  `type` enum('import') default NULL,
  `subtype` varchar(32) default NULL,
  `items_count` int(8) default NULL,
  `creation_date` timestamp NOT NULL default '0000-00-00 00:00:00',
  `update_date` timestamp NOT NULL default '0000-00-00 00:00:00' on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL auto_increment,
  `email` varchar(255) default NULL,
  `password` varchar(64) NOT NULL,
  `password_old_1` varchar(64) NOT NULL,
  `password_old_2` varchar(64) NOT NULL,
  `password_expiration` timestamp NULL,
  `password_lastedit_date` timestamp NULL,
  `first_name` varchar(64) NOT NULL,
  `last_name` varchar(64) NOT NULL,
  `name` varchar(128) default NULL,
  `prefered_lang` varchar(5) NULL,
  `device_id` varchar(64) NOT NULL,
  `activated` tinyint(1) NOT NULL default '0',
  `activation_key` varchar(32) NOT NULL,
  `password_reset_key` varchar(32) NOT NULL,
  `private_key` varchar(16) NOT NULL,
  `creation_date` timestamp NULL default NULL,
  `update_date` timestamp NULL default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `first_name` (`first_name`),
  KEY `last_name` (`last_name`),
  KEY `device_id` (`device_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `users_groups` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `creation_date` timestamp NOT NULL default '0000-00-00 00:00:00',
  `update_date` timestamp NOT NULL default '0000-00-00 00:00:00' on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `users_id_2` (`user_id`,`group_id`),
  KEY `users_id` (`user_id`),
  KEY `groups_id` (`group_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


ALTER TABLE `admin_logs`
  ADD CONSTRAINT `admin_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `groups_auths`
  ADD CONSTRAINT `groups_auths_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `groups_auths_ibfk_2` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `users_groups`
  ADD CONSTRAINT `users_groups_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `users_groups_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `resources_columns`
  ADD CONSTRAINT `resources_columns_ibfk_1` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
  
  
  


INSERT INTO `resources` (`id`, `name`, `singular`, `type`, `table`, `alias`, `extends`, `displayName`, `defaultNameField`, `creation_date`, `update_date`) VALUES
('', 'adminlogs', 'adminlog', 'native', 'admin_logs', 'admlog', '', 'admin logs', 'admin_title', '2011-03-22 11:49:43', '2011-03-22 13:15:52'),
('', 'bans', 'ban', 'native', 'bans', 'ban', '', 'bans', 'ip', '2011-06-20 11:33:34', '2011-06-20 11:33:34'),
('', 'resourcescolumns', 'resourcescolumn', 'native', 'resources_columns', 'rc', '', 'resources columns', '', '2011-07-07 13:23:03', '2011-07-07 13:23:03'),
('', 'groups', 'group', 'native', 'groups', 'gp', '', 'groups', 'admin_title', '2010-10-04 18:12:21', '2010-12-03 15:14:05'),
('', 'groupsauths', 'groupsauth', 'relation', 'groups_auths', 'gpauth', '', 'groups auths', '', '2010-10-04 18:12:50', '2010-12-03 16:31:59'),
('', 'sessions', 'session', 'native', 'sessions', 'sess', '', 'sessions', 'name', '2010-10-04 18:13:14', '2010-12-03 16:27:12'),
('', 'users', 'user', 'native', 'users', 'u', '', 'users', 'email', '2010-10-04 18:13:22', '2010-12-03 16:28:21'),
('', 'usersgroups', 'usersgroup', 'relation', 'users_groups', 'ugp', '', 'user-groups', '', '2010-10-04 18:13:36', '2010-12-03 16:32:36'),
('', 'resources', 'resource', 'native', 'resources', 'res', '', 'resources', 'name', '2010-10-07 16:28:33', '2010-12-03 16:29:39'),
('', 'tasks', 'task', 'native', 'tasks', 'tsk', '', 'tasks', 'slug', '2011-03-22 10:47:15', '2011-03-22 10:47:15');
  

INSERT INTO `groups` (`id`, `name`, `admin_title`, `creation_date`, `update_date`) VALUES
('', 'users', 'users', '0000-00-00 00:00:00', '2010-11-26 17:59:01'),
('', 'gods', 'gods', '0000-00-00 00:00:00', '2010-11-26 17:59:11'),
('', 'superadmins', 'superadmins', '0000-00-00 00:00:00', '2010-11-26 17:59:20'),
('', 'admins', 'admins', '0000-00-00 00:00:00', '2010-11-26 17:59:26'),
('', 'contributors', 'contributors', '0000-00-00 00:00:00', '2010-11-26 17:59:39'),
('', 'moderators', 'moderators', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

  
INSERT INTO `users` (`id`, `email`, `password`, `first_name`, `last_name`, `name`, `device_id`, `activated`, `activation_key`, `password_reset_key`, `private_key`, `creation_date`, `update_date`) VALUES
('', 'nobody@anonymous.com', 'f845fb444033f19b8568373351b868dd5b4e54af', 'john', 'doe', '', '', 0, '', '', '', '2010-12-02 11:15:23', '2011-06-28 11:11:06'),
('', 'guyllaume@clicmobile.com', '4d11ca0509003bd78184ed0dcff0b5250b6072a2', 'guyllaume', 'doyer', 'Guyllaume Doyer', '', 1, '', '', '', '2011-06-28 11:01:02', '2011-06-28 11:11:09');

  
INSERT INTO `users_groups` (`id`, `user_id`, `group_id`, `creation_date`, `update_date`) VALUES
('', 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
('', 2, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
('', 2, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00');