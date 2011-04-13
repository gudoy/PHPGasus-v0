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

INSERT INTO `groups` (`id`, `name`, `admin_title`, `creation_date`, `update_date`) VALUES
('', 'users', 'users', '0000-00-00 00:00:00', '2010-11-26 17:59:01'),
('', 'gods', 'gods', '0000-00-00 00:00:00', '2010-11-26 17:59:11'),
('', 'superadmins', 'superadmins', '0000-00-00 00:00:00', '2010-11-26 17:59:20'),
('', 'admins', 'admins', '0000-00-00 00:00:00', '2010-11-26 17:59:26'),
('', 'contributors', 'contributors', '0000-00-00 00:00:00', '2010-11-26 17:59:39'),
('', 'moderators', 'moderators', '0000-00-00 00:00:00', '0000-00-00 00:00:00');





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
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Contenu de la table `resources`
--

INSERT INTO `resources` (`id`, `name`, `singular`, `type`, `table`, `alias`, `extends`, `displayName`, `defaultNameField`, `creation_date`, `update_date`) VALUES
('', 'groups', 'group', 'native', 'groups', 'gp', '', 'groups', 'admin_title', '2010-10-04 18:12:21', '2010-12-03 15:14:05'),
('', 'groupsauths', 'groupsauth', 'relation', 'groups_auths', 'gpauth', '', 'groups auths', '', '2010-10-04 18:12:50', '2010-12-03 16:31:59'),
('', 'sessions', 'session', 'native', 'sessions', 'sess', '', 'sessions', 'name', '2010-10-04 18:13:14', '2010-12-03 16:27:12'),
('', 'users', 'user', 'native', 'users', 'u', '', 'users', 'email', '2010-10-04 18:13:22', '2010-12-03 16:28:21'),
('', 'usersgroups', 'usersgroup', 'relation', 'users_groups', 'ugp', '', 'user-groups', '', '2010-10-04 18:13:36', '2010-12-03 16:32:36'),
('', 'resources', 'resource', 'native', 'resources', 'res', '', 'resources', 'name', '2010-10-07 16:28:33', '2010-12-03 16:29:39');





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


CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL auto_increment,
  `email` varchar(255) default NULL,
  `password` varchar(64) NOT NULL,
  `first_name` varchar(64) NOT NULL,
  `last_name` varchar(64) NOT NULL,
  `name` varchar(128) default NULL,
  `creation_date` timestamp NULL default NULL,
  `update_date` timestamp NULL default NULL,
  `sector_code` varchar(4) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `sector_code` (`sector_code`),
  KEY `first_name` (`first_name`),
  KEY `last_name` (`last_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


INSERT INTO `users` (`id`, `email`, `password`, `first_name`, `last_name`, `name`, `creation_date`, `update_date`, `sector_code`) VALUES
('', 'nobody@anonymous.com', 'f845fb444033f19b8568373351b868dd5b4e54af', 'john', 'doe', '', '2010-12-02 11:15:23', '2010-12-03 14:23:51', NULL),
('', 'guyllaume@clicmobile.com', '4d11ca0509003bd78184ed0dcff0b5250b6072a2', 'guyllaume', 'doyer', 'Guyllaume Doyer', '2010-09-30 14:11:49', '2010-12-01 23:52:03', NULL);





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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1083 ;

INSERT INTO `users_groups` (`id`, `user_id`, `group_id`, `creation_date`, `update_date`) VALUES
(1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 2, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 2, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00');




ALTER TABLE `groups_auths`
  ADD CONSTRAINT `groups_auths_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `groups_auths_ibfk_2` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `users_groups`
--
ALTER TABLE `users_groups`
  ADD CONSTRAINT `users_groups_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `users_groups_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;