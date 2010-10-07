-- phpMyAdmin SQL Dump
-- version 3.2.2deb1
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Jeu 30 Septembre 2010 à 17:10
-- Version du serveur: 5.0.51
-- Version de PHP: 5.3.2-2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Base de données: `photomaton`
--

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Contenu de la table `groups`
--

INSERT INTO `groups` (`id`, `name`, `admin_title`, `creation_date`, `update_date`) VALUES
(1, 'users', 'users', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'gods', 'gods', '0000-00-00 00:00:00', '2010-09-28 16:39:44'),
(3, 'superadmins', 'superadmins', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 'admin', 'admins', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 'contributors', 'contributors', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, 'moderators', 'moderators', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `groups_auths`
--

CREATE TABLE IF NOT EXISTS `groups_auths` (
  `id` int(11) NOT NULL auto_increment,
  `group_id` int(11) NOT NULL,
  `resource_id` int(11) NOT NULL,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='groups authorisations (ACL)' AUTO_INCREMENT=1 ;

--
-- Contenu de la table `groups_auths`
--


-- --------------------------------------------------------

--
-- Structure de la table `resources`
--

CREATE TABLE IF NOT EXISTS `resources` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(32) NOT NULL,
  `singular` varchar(32) NOT NULL,
  `creation_date` timestamp NOT NULL default '0000-00-00 00:00:00',
  `update_date` timestamp NOT NULL default '0000-00-00 00:00:00' on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `resources`
--


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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Contenu de la table `sessions`
--

INSERT INTO `sessions` (`id`, `name`, `user_id`, `creation_date`, `update_date`, `expiration_time`, `ip`, `last_url`) VALUES
(7, '0d066135028cb85898429e62ea75b850', 2, '2010-09-30 14:59:01', '2010-09-30 15:08:26', '2010-09-30 15:23:26', '62.100.145.244', 'http://photomaton.clicmobile.com/admin/groupsauths/'),
(8, '0d066135028cb85898429e62ea75b850', 2, '2010-09-30 15:33:10', '2010-09-30 15:51:45', '2010-09-30 16:06:45', '62.100.145.244', 'http://photomaton.clicmobile.com/admin/usersgroups/'),
(9, '0d066135028cb85898429e62ea75b850', 2, '2010-09-30 16:10:11', '2010-09-30 16:26:49', '2010-09-30 16:41:49', '62.100.145.244', 'http://photomaton.clicmobile.com/admin/usersgroups/');

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

INSERT INTO `users` (`id`, `email`, `password`, `first_name`, `last_name`, `auth_level`, `auth_level_nb`, `creation_date`, `update_date`) VALUES
(1, 'nobody@anonymous.com', 'f845fb444033f19b8568373351b868dd5b4e54af', '', '', 'user', 0, '2010-09-28 16:20:52', '2010-09-28 16:20:52'),
(2, 'guyllaume@clicmobile.com', '4d11ca0509003bd78184ed0dcff0b5250b6072a2', '', '', 'god', 10000, '2010-09-30 14:11:49', '2010-09-30 14:11:49');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

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
