-- phpMyAdmin SQL Dump
-- version 3.2.2deb1
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Jeu 02 Décembre 2010 à 14:05
-- Version du serveur: 5.0.51
-- Version de PHP: 5.3.3-4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Base de données: `photomaton`
--

-- --------------------------------------------------------

-- --------------------------------------------------------

--
-- Structure de la table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(32) NOT NULL,
  `admin_title` varchar(32) NOT NULL,
  `creation_date` timestamp NOT NULL default '0000-00-00 00:00:00',
  `update_date` timestamp NOT NULL default '0000-00-00 00:00:00' on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Contenu de la table `groups`
--

INSERT INTO `groups` (`id`, `name`, `admin_title`, `creation_date`, `update_date`) VALUES
(1, 'users', 'users', '0000-00-00 00:00:00', '2010-11-26 17:59:01'),
(2, 'gods', 'gods', '0000-00-00 00:00:00', '2010-11-26 17:59:11'),
(3, 'superadmins', 'superadmins', '0000-00-00 00:00:00', '2010-11-26 17:59:20'),
(4, 'admins', 'admins', '0000-00-00 00:00:00', '2010-11-26 17:59:26'),
(5, 'contributors', 'contributors', '0000-00-00 00:00:00', '2010-11-26 17:59:39'),
(6, 'moderators', 'moderators', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 'customers', 'customers', '2010-10-01 12:14:16', '2010-10-01 12:15:37'),
(8, 'technicians', 'technicians', '2010-10-01 12:14:25', '2010-10-01 12:15:34'),
(9, 'commercials', 'commercials', '2010-10-01 12:14:34', '2010-10-01 12:15:46'),
(10, 'hotliners', 'hotliners', '2010-10-01 12:14:51', '2010-10-01 12:15:59');

-- --------------------------------------------------------

--
-- Structure de la table `groups_auths`
--

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='groups authorisations (ACL)' AUTO_INCREMENT=9 ;

--
-- Contenu de la table `groups_auths`
--

INSERT INTO `groups_auths` (`id`, `group_id`, `resource_id`, `allow_display`, `allow_create`, `allow_retrieve`, `allow_update`, `allow_delete`, `creation_date`, `update_date`) VALUES
(1, 2, 1, 1, 1, 1, 1, 1, '2010-10-27 11:03:50', '2010-10-28 17:40:49'),
(2, 2, 2, 1, 1, 1, 1, 1, '2010-10-27 11:48:23', '2010-10-27 13:52:59'),
(3, 2, 3, 1, 1, 1, 1, 1, '2010-10-27 11:48:28', '2010-11-12 17:10:08'),
(4, 2, 4, 1, 1, 1, 1, 1, '2010-10-27 11:48:46', '2010-10-27 13:56:08'),
(5, 2, 5, 1, 1, 1, 1, 1, '2010-10-27 11:49:10', '2010-10-27 13:56:42'),
(6, 2, 6, 1, 1, 1, 1, 1, '2010-10-27 11:49:20', '2010-10-27 13:56:59'),
(7, 2, 7, 1, 1, 1, 1, 1, '2010-10-28 14:36:30', '2010-10-28 14:36:30'),
(8, 1, 3, 0, 0, 0, 0, 0, '2010-10-28 17:58:38', '2010-10-28 18:23:06');

-- --------------------------------------------------------

--
-- Structure de la table `resources`
--

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Contenu de la table `resources`
--

INSERT INTO `resources` (`id`, `name`, `singular`, `type`, `table`, `alias`, `extends`, `displayName`, `defaultNameField`, `creation_date`, `update_date`) VALUES
('', 'groups', 'group', 'native', 'groups', 'gp', NULL, NULL, '', '2010-10-04 18:12:21', '2010-11-26 13:52:24'),
('', 'groupsauths', 'groupsauth', 'native', 'groups_auths', 'gpauth', NULL, NULL, NULL, '2010-10-04 18:12:50', '2010-11-26 09:59:32'),
('', 'sessions', 'session', 'native', 'sessions', 'sess', NULL, NULL, NULL, '2010-10-04 18:13:14', '2010-11-26 09:58:27'),
('', 'users', 'user', 'native', 'users', 'u', NULL, NULL, NULL, '2010-10-04 18:13:22', '2010-11-26 09:58:46'),
('', 'usersgroups', 'usersgroup', 'native', 'users_groups', 'ugp', NULL, NULL, NULL, '2010-10-04 18:13:36', '2010-11-26 09:59:04'),
('', 'resources', 'resource', 'native', 'resources', 'res', NULL, NULL, NULL, '2010-10-07 16:28:33', '2010-11-26 09:58:13');

-- --------------------------------------------------------

--
-- Structure de la table `sessions`
--

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=215 ;

--
-- Contenu de la table `sessions`
--


-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL auto_increment,
  `email` varchar(255) default NULL,
  `password` varchar(64) NOT NULL,
  `first_name` varchar(64) NOT NULL,
  `last_name` varchar(64) NOT NULL,
  `name` varchar(128) default NULL,
  `auth_level` enum('user','contributor','admin','superadmin','god') NOT NULL default 'user',
  `auth_level_nb` int(5) NOT NULL,
  `creation_date` timestamp NULL default NULL,
  `update_date` timestamp NULL default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `first_name`, `last_name`, `name`, `auth_level`, `auth_level_nb`, `creation_date`, `update_date`) VALUES
(1, 'nobody@anonymous.com', 'f845fb444033f19b8568373351b868dd5b4e54af', 'john', 'doe', NULL, 'user', 1, '2010-12-02 11:15:23', '2010-12-02 11:15:23'),
(2, 'guyllaume@clicmobile.com', '4d11ca0509003bd78184ed0dcff0b5250b6072a2', 'guyllaume', 'doyer', 'Guyllaume Doyer', 'god', 10000, '2010-09-30 14:11:49', '2010-12-01 23:52:03');

-- --------------------------------------------------------

--
-- Structure de la table `users_groups`
--

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=178 ;

--
-- Contenu de la table `users_groups`
--

INSERT INTO `users_groups` (`id`, `user_id`, `group_id`, `creation_date`, `update_date`) VALUES
(1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 2, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 2, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `groups_auths`
--
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
