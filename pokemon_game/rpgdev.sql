-- phpMyAdmin SQL Dump
-- version 4.0.10.12
-- http://www.phpmyadmin.net
--
-- Host: 127.3.60.130:3306
-- Generation Time: Jun 03, 2016 at 10:35 PM
-- Server version: 5.5.45
-- PHP Version: 5.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `rpgdev`
--

-- --------------------------------------------------------

--
-- Table structure for table `pkmn_badge`
--

CREATE TABLE IF NOT EXISTS `pkmn_badge` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_gym` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `image` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_pkmn_badge_pkmn_gym1_idx` (`id_gym`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `pkmn_badge`
--

INSERT INTO `pkmn_badge` (`id`, `id_gym`, `name`, `image`) VALUES
(5, 1, 'Boulder Badge', 'badge_boulder.gif');

-- --------------------------------------------------------

--
-- Table structure for table `pkmn_clan`
--

CREATE TABLE IF NOT EXISTS `pkmn_clan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner` varchar(100) COLLATE latin1_bin NOT NULL,
  `owner_id` int(11) NOT NULL,
  `name` varchar(100) COLLATE latin1_bin NOT NULL,
  `description` text COLLATE latin1_bin NOT NULL,
  `image` varchar(155) COLLATE latin1_bin NOT NULL,
  `exp` int(11) NOT NULL DEFAULT '0',
  `level` int(11) NOT NULL DEFAULT '1',
  `members` int(11) NOT NULL DEFAULT '1',
  `silver` int(11) NOT NULL DEFAULT '0',
  `gold` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_bin AUTO_INCREMENT=2 ;

--
-- Dumping data for table `pkmn_clan`
--

INSERT INTO `pkmn_clan` (`id`, `owner`, `owner_id`, `name`, `description`, `image`, `exp`, `level`, `members`, `silver`, `gold`) VALUES
(1, 'admin', 1, 'Beast', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'http://pokemonromhack.com/wp-content/uploads/2014/09/Pokemon_ROM_Hacks.png', 0, 1, 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `pkmn_evolution`
--

CREATE TABLE IF NOT EXISTS `pkmn_evolution` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_pkmn` int(11) NOT NULL,
  `id_evolved_pkmn` int(11) NOT NULL,
  `id_required_item` int(11) DEFAULT NULL,
  `required_exp` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_pkmn_evolution_pkmn_pokemon2_idx` (`id_evolved_pkmn`),
  KEY `fk_pkmn_evolution_pkmn_pokemon_item1_idx` (`id_required_item`),
  KEY `fk_pkmn_evolution_pokemon1` (`id_pkmn`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;

--
-- Dumping data for table `pkmn_evolution`
--

INSERT INTO `pkmn_evolution` (`id`, `id_pkmn`, `id_evolved_pkmn`, `id_required_item`, `required_exp`) VALUES
(5, 1, 11, 9, 125000),
(6, 2, 12, 10, 125000),
(9, 11, 13, 9, 1000000),
(10, 12, 14, 10, 1000000),
(11, 15, 16, 11, 1000000),
(12, 3, 15, 11, 125000),
(13, 17, 18, 14, 64000),
(14, 18, 19, 14, 512000),
(15, 20, 21, 14, 64000),
(16, 21, 22, 14, 512000),
(17, 23, 24, 12, 64000),
(18, 24, 25, 12, 512000),
(19, 26, 27, 12, 421875),
(20, 28, 29, 12, 421875),
(21, 30, 31, 15, 421875),
(22, 32, 33, 13, 421875),
(23, 34, 35, 12, 421875),
(24, 36, 37, 12, 125000),
(25, 37, 38, 12, 1000000),
(26, 40, 41, 12, 1000000),
(27, 39, 40, 12, 125000);

-- --------------------------------------------------------

--
-- Table structure for table `pkmn_faction`
--

CREATE TABLE IF NOT EXISTS `pkmn_faction` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `visible` tinyint(1) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `points` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `pkmn_faction`
--

INSERT INTO `pkmn_faction` (`id`, `visible`, `name`, `points`) VALUES
(1, 1, 'Team Cosmos', 10300),
(2, 0, 'Wild Nature', 0),
(3, 1, 'Team Rocket', 0);

-- --------------------------------------------------------

--
-- Table structure for table `pkmn_faction_shop`
--

CREATE TABLE IF NOT EXISTS `pkmn_faction_shop` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_faction` int(11) NOT NULL,
  `id_element` int(11) DEFAULT NULL,
  `type` tinyint(4) DEFAULT NULL,
  `pts_cost` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_pkmn_faction_shop_pkmn_faction1_idx` (`id_faction`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `pkmn_faction_shop`
--

INSERT INTO `pkmn_faction_shop` (`id`, `id_faction`, `id_element`, `type`, `pts_cost`) VALUES
(1, 1, 125, 2, 1000),
(2, 3, 128, 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `pkmn_gym`
--

CREATE TABLE IF NOT EXISTS `pkmn_gym` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_region` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_pkmn_gym_pkmn_region1_idx` (`id_region`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `pkmn_gym`
--

INSERT INTO `pkmn_gym` (`id`, `id_region`, `name`) VALUES
(1, 1, 'Pewter Gym'),
(2, 1, 'Cerulean Gym'),
(3, 1, 'Vermilion Gym'),
(4, 1, 'Celadon Gym'),
(5, 1, 'Fuchsia Gym'),
(6, 1, 'Saffron Gym'),
(7, 1, 'Cinnabar Gym'),
(8, 1, 'Viridian Gym');

-- --------------------------------------------------------

--
-- Table structure for table `pkmn_item_category`
--

CREATE TABLE IF NOT EXISTS `pkmn_item_category` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `pkmn_item_category`
--

INSERT INTO `pkmn_item_category` (`id`, `name`) VALUES
(1, 'Battle'),
(2, 'Berries'),
(3, 'General'),
(4, 'Hold'),
(5, 'Machines'),
(6, 'Medicine'),
(7, 'Pokeballs'),
(8, 'Evolutionary Stone'),
(9, 'Unknown');

-- --------------------------------------------------------

--
-- Table structure for table `pkmn_map`
--

CREATE TABLE IF NOT EXISTS `pkmn_map` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_region` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `image` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_pkmn_map_pkmn_region1_idx` (`id_region`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `pkmn_map`
--

INSERT INTO `pkmn_map` (`id`, `id_region`, `name`, `image`) VALUES
(1, 1, 'Memorial Caves', 'memorial_caves.png'),
(2, 1, 'Empty Cliffs', 'empty_cliffs.png'),
(3, 2, 'Ancient Temple', 'ancient_temple.png'),
(4, 2, 'Cold Cavern', 'cold_cavern.png'),
(5, 2, 'Tree path', 'tree_path.png');

-- --------------------------------------------------------

--
-- Table structure for table `pkmn_move_category`
--

CREATE TABLE IF NOT EXISTS `pkmn_move_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `pkmn_move_category`
--

INSERT INTO `pkmn_move_category` (`id`, `name`) VALUES
(1, 'Physical'),
(2, 'Special'),
(3, 'Status');

-- --------------------------------------------------------

--
-- Table structure for table `pkmn_news`
--

CREATE TABLE IF NOT EXISTS `pkmn_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_trainer` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `title` varchar(45) DEFAULT NULL,
  `content` text,
  PRIMARY KEY (`id`),
  KEY `fk_pkmn_news_pkmn_trainer1_idx` (`id_trainer`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `pkmn_news`
--

INSERT INTO `pkmn_news` (`id`, `id_trainer`, `created`, `title`, `content`) VALUES
(4, 1, '2016-03-19 23:10:49', 'Pokemon Cosmos Beta', '<h2><span style=\\"text-decoration: underline;\\">Pokemon Cosmos Beta</span></h2>\r\n<p>&nbsp;</p>\r\n<p>Welcome to the Pokemon Cosmos Beta. The RPG is in full open beta testing mode. If you find any errors please feel free to email me @ legiondeveloper@outlook.com. The RPG will not be reset when the beta testing is over.&nbsp;</p>'),
(5, 1, '2016-06-03 15:31:59', 'Game ID', '<p>IDs 1 - 29 are reserved for future staff of the RPG if they wish too have them, sorry if you were expecting IDs this low.</p>');

-- --------------------------------------------------------

--
-- Table structure for table `pkmn_pokemon`
--

CREATE TABLE IF NOT EXISTS `pkmn_pokemon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `initial` tinyint(1) DEFAULT NULL,
  `disabled` tinyint(1) DEFAULT NULL,
  `genderless` tinyint(1) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `hp` int(11) DEFAULT NULL,
  `attack` smallint(6) DEFAULT NULL,
  `defense` smallint(6) DEFAULT NULL,
  `speed` smallint(6) DEFAULT NULL,
  `spec_attack` smallint(6) DEFAULT NULL,
  `spec_defense` smallint(6) DEFAULT NULL,
  `base_exp` smallint(6) DEFAULT NULL,
  `evhp` smallint(6) DEFAULT NULL,
  `evattack` smallint(6) DEFAULT NULL,
  `evdefense` smallint(6) DEFAULT NULL,
  `evspeed` smallint(6) DEFAULT NULL,
  `evspec_attack` smallint(6) DEFAULT NULL,
  `evspec_defense` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=42 ;

--
-- Dumping data for table `pkmn_pokemon`
--

INSERT INTO `pkmn_pokemon` (`id`, `initial`, `disabled`, `genderless`, `name`, `hp`, `attack`, `defense`, `speed`, `spec_attack`, `spec_defense`, `base_exp`, `evhp`, `evattack`, `evdefense`, `evspeed`, `evspec_attack`, `evspec_defense`) VALUES
(1, 1, NULL, 0, 'Bulbasaur', 45, 49, 49, 45, 65, 65, 126, 0, 0, 0, 0, 1, 0),
(2, 1, 0, 0, 'Charmander', 39, 52, 43, 65, 60, 50, 126, 0, 0, 0, 1, 0, 0),
(3, 1, 0, 0, 'Squirtle', 44, 48, 65, 43, 50, 64, 126, 0, 0, 1, 0, 0, 0),
(7, 0, 0, 0, 'Mr. Mime', 40, 45, 65, 90, 100, 120, 126, 0, 0, 0, 0, 0, 2),
(9, 0, 0, 1, 'Darkrai', 70, 90, 90, 125, 135, 90, 126, 0, 0, 0, 1, 2, 0),
(10, 0, NULL, 0, 'Flareon', 65, 130, 60, 65, 95, 110, 126, 0, 2, 0, 0, 0, 0),
(11, 0, 0, 0, 'Ivysaur', 60, 62, 63, 60, 80, 80, 126, 0, 0, 0, 0, 1, 1),
(12, 0, 0, 0, 'Charmeleon', 58, 64, 58, 80, 80, 65, 126, 0, 0, 0, 1, 1, 0),
(13, 0, 0, 0, 'Venusaur', 80, 82, 83, 80, 100, 100, 126, 0, 0, 0, 0, 2, 1),
(14, 0, 0, 0, 'Charizard', 78, 84, 78, 100, 109, 85, 126, 0, 0, 0, 0, 3, 0),
(15, 0, 0, 0, 'Wartortle', 59, 63, 80, 58, 65, 80, 126, 0, 0, 1, 0, 0, 1),
(16, 0, 0, 0, 'Blastoise', 79, 83, 100, 78, 85, 105, 126, 0, 0, 0, 0, 0, 3),
(17, 0, 0, 0, 'Caterpie', 45, 30, 35, 45, 20, 20, 126, 1, 0, 0, 0, 0, 0),
(18, 0, 0, 0, 'Metapod', 50, 20, 55, 30, 25, 25, 126, 0, 0, 2, 0, 0, 0),
(19, 0, 0, 0, 'Butterfree', 60, 45, 50, 90, 80, 70, 126, 0, 0, 0, 0, 2, 1),
(20, 0, 0, 0, 'Weedle', 40, 35, 30, 50, 20, 20, 126, 0, 0, 0, 1, 0, 0),
(21, 0, 0, 0, 'Kakuna', 45, 25, 50, 35, 25, 25, 126, 0, 0, 2, 0, 0, 0),
(22, 0, 0, 0, 'Beedrill', 65, 90, 40, 75, 45, 80, 126, 0, 2, 0, 0, 0, 1),
(23, 0, 0, 0, 'Pidgey', 40, 45, 40, 56, 35, 35, 126, 0, 0, 0, 1, 0, 0),
(24, 0, 0, 0, 'Pidgeotto', 63, 60, 55, 71, 50, 50, 126, 0, 0, 0, 2, 0, 0),
(25, 0, 0, 0, 'Pidgeot', 83, 80, 75, 101, 70, 70, 126, 0, 0, 0, 3, 0, 0),
(26, 0, 0, 0, 'Rattata', 30, 56, 35, 72, 25, 35, 126, 0, 0, 0, 1, 0, 0),
(27, 0, 0, 0, 'Raticate', 55, 81, 60, 97, 50, 70, 126, 0, 0, 0, 2, 0, 0),
(28, 0, 0, 0, 'Spearow', 40, 60, 30, 70, 31, 31, 126, 0, 0, 0, 1, 0, 0),
(29, 0, 0, 0, 'Fearow', 65, 90, 65, 100, 61, 61, 126, 0, 0, 0, 2, 0, 0),
(30, 0, 0, 0, 'Ekans', 35, 60, 44, 55, 40, 54, 126, 0, 1, 0, 0, 0, 0),
(31, 0, 0, 0, 'Arbok', 60, 85, 69, 80, 65, 69, 126, 0, 2, 0, 0, 0, 0),
(32, 0, 0, 0, 'Pikachu', 35, 55, 40, 90, 50, 50, 126, 0, 0, 0, 2, 0, 0),
(33, 0, 0, 0, 'Raichu', 60, 90, 55, 110, 90, 80, 126, 0, 0, 0, 0, 0, 0),
(34, 0, 0, 0, 'Sandshrew', 50, 75, 85, 40, 20, 30, 126, 0, 0, 1, 0, 0, 0),
(35, 0, 0, 0, 'Sandslash', 75, 100, 110, 65, 45, 55, 126, 0, 0, 0, 0, 0, 0),
(36, 0, 0, 0, 'NidoranF', 55, 47, 52, 41, 40, 40, 126, 1, 0, 0, 0, 0, 0),
(37, 0, 0, 0, 'Nidorina', 70, 62, 67, 56, 55, 55, 126, 2, 0, 0, 0, 0, 0),
(38, 0, 0, 0, 'Nidoqueen', 90, 92, 87, 76, 75, 85, 126, 3, 0, 0, 0, 0, 0),
(39, 0, 0, 0, 'NidoranM', 46, 57, 40, 50, 40, 40, 126, 0, 1, 0, 0, 0, 0),
(40, 0, 0, 0, 'Nidorino', 61, 72, 57, 65, 55, 55, 126, 0, 2, 0, 0, 0, 0),
(41, 0, 0, 0, 'Nidoking', 81, 102, 77, 85, 85, 75, 126, 0, 3, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `pkmn_pokemon_assig_group`
--

CREATE TABLE IF NOT EXISTS `pkmn_pokemon_assig_group` (
  `id_pokemon` int(11) NOT NULL,
  `id_group` int(11) NOT NULL,
  PRIMARY KEY (`id_pokemon`,`id_group`),
  KEY `fk_pkmn_pokemon_has_pkmn_pokemon_group_pkmn_pokemon_group1_idx` (`id_group`),
  KEY `fk_pkmn_pokemon_has_pkmn_pokemon_group_pkmn_pokemon1_idx` (`id_pokemon`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pkmn_pokemon_assig_group`
--

INSERT INTO `pkmn_pokemon_assig_group` (`id_pokemon`, `id_group`) VALUES
(1, 1),
(2, 1),
(3, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(36, 1),
(39, 1),
(40, 1),
(41, 1),
(7, 2),
(3, 3),
(15, 3),
(16, 3),
(17, 6),
(18, 6),
(19, 6),
(20, 6),
(21, 6),
(22, 6),
(23, 8),
(24, 8),
(25, 8),
(28, 8),
(29, 8),
(10, 10),
(26, 10),
(27, 10),
(30, 10),
(31, 10),
(32, 10),
(33, 10),
(34, 10),
(35, 10),
(36, 10),
(39, 10),
(40, 10),
(41, 10),
(32, 11),
(33, 11),
(1, 13),
(13, 13),
(2, 14),
(12, 14),
(14, 14),
(30, 14),
(31, 14),
(37, 15),
(38, 15);

-- --------------------------------------------------------

--
-- Table structure for table `pkmn_pokemon_assig_move`
--

CREATE TABLE IF NOT EXISTS `pkmn_pokemon_assig_move` (
  `id_trainer_pokemon` int(11) NOT NULL,
  `id_move` int(11) NOT NULL,
  PRIMARY KEY (`id_trainer_pokemon`,`id_move`),
  KEY `fk_pkmn_pokemon_assig_move_pkmn_pokemon_move1_idx` (`id_move`),
  KEY `fk_pkmn_pokemon_assig_move_pkmn_trainer_pokemon1_idx` (`id_trainer_pokemon`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pkmn_pokemon_assig_move`
--

INSERT INTO `pkmn_pokemon_assig_move` (`id_trainer_pokemon`, `id_move`) VALUES
(2, 1),
(3, 1),
(5, 1),
(22, 1),
(23, 1),
(25, 1),
(71, 1),
(73, 1),
(75, 1),
(76, 1),
(77, 1),
(78, 1),
(81, 1),
(4, 2),
(5, 2),
(21, 2),
(22, 2),
(23, 2),
(78, 2),
(81, 2),
(2, 3),
(21, 3),
(22, 3),
(23, 3),
(24, 3),
(71, 3),
(3, 4),
(21, 4),
(23, 4),
(24, 4),
(71, 4),
(2, 5),
(3, 5),
(5, 5),
(21, 5),
(22, 5),
(23, 5),
(24, 5),
(25, 5),
(77, 5),
(2, 6),
(3, 6),
(5, 6),
(23, 6),
(24, 6),
(75, 6),
(77, 6),
(78, 6),
(3, 7),
(22, 7),
(23, 7),
(24, 7),
(76, 7),
(23, 8),
(25, 8),
(3, 9),
(23, 9),
(5, 10),
(25, 10),
(73, 10),
(76, 10),
(77, 10),
(78, 10),
(95, 10),
(115, 10),
(5, 11),
(21, 11),
(25, 11),
(73, 11),
(76, 11),
(77, 11),
(78, 11),
(81, 11),
(95, 11),
(115, 11),
(5, 12),
(21, 12),
(25, 12),
(73, 12),
(76, 12),
(77, 12),
(78, 12),
(81, 12),
(95, 12),
(115, 12),
(2, 13),
(22, 13),
(71, 13),
(93, 13),
(96, 13),
(122, 13),
(127, 13),
(2, 14),
(22, 14),
(71, 14),
(93, 14),
(96, 14),
(122, 14),
(127, 14),
(2, 15),
(22, 15),
(93, 15),
(96, 15),
(122, 15),
(127, 15),
(4, 16),
(65, 16),
(72, 16),
(75, 16),
(114, 16),
(4, 17),
(65, 17),
(114, 17),
(4, 18),
(65, 18),
(75, 18),
(114, 18),
(62, 19),
(67, 19),
(69, 19),
(94, 19),
(97, 19),
(99, 19),
(62, 20),
(67, 20),
(69, 20),
(94, 20),
(97, 20),
(99, 20),
(62, 21),
(67, 21),
(69, 21),
(94, 21),
(97, 21),
(99, 21),
(62, 22),
(67, 22),
(69, 22),
(94, 22),
(97, 22),
(99, 22),
(62, 23),
(67, 23),
(69, 23),
(94, 23),
(97, 23),
(99, 23),
(63, 24),
(63, 25),
(63, 26);

-- --------------------------------------------------------

--
-- Table structure for table `pkmn_pokemon_assig_type`
--

CREATE TABLE IF NOT EXISTS `pkmn_pokemon_assig_type` (
  `id_type` int(11) NOT NULL,
  `id_pokemon` int(11) NOT NULL,
  PRIMARY KEY (`id_type`,`id_pokemon`),
  KEY `fk_pkmn_pokemon_type_has_pkmn_pokemon_pkmn_pokemon1_idx` (`id_pokemon`),
  KEY `fk_pkmn_pokemon_type_has_pkmn_pokemon_pkmn_pokemon_type1_idx` (`id_type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pkmn_pokemon_assig_type`
--

INSERT INTO `pkmn_pokemon_assig_type` (`id_type`, `id_pokemon`) VALUES
(5, 1),
(8, 1),
(2, 2),
(3, 3),
(11, 7),
(16, 9),
(2, 10),
(5, 11),
(8, 11),
(2, 12),
(5, 13),
(8, 13),
(2, 14),
(10, 14),
(3, 15),
(3, 16),
(12, 17),
(12, 18),
(10, 19),
(12, 19),
(8, 20),
(12, 20),
(8, 21),
(12, 21),
(8, 22),
(12, 22),
(1, 23),
(10, 23),
(1, 24),
(10, 24),
(1, 25),
(10, 25),
(1, 26),
(1, 27),
(1, 28),
(10, 28),
(1, 29),
(10, 29),
(8, 30),
(8, 31),
(4, 32),
(4, 33),
(9, 34),
(9, 35),
(8, 36),
(8, 37),
(8, 38),
(9, 38),
(8, 39),
(8, 40),
(8, 41),
(9, 41);

-- --------------------------------------------------------

--
-- Table structure for table `pkmn_pokemon_group`
--

CREATE TABLE IF NOT EXISTS `pkmn_pokemon_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `pkmn_pokemon_group`
--

INSERT INTO `pkmn_pokemon_group` (`id`, `name`) VALUES
(1, 'Monster'),
(2, 'Humanoid'),
(3, 'Water 1'),
(4, 'Water 2'),
(5, 'Water 3'),
(6, 'Bug'),
(7, 'Mineral'),
(8, 'Flying'),
(9, 'Amorphous'),
(10, 'Field'),
(11, 'Fairy'),
(12, 'Ditto'),
(13, 'Grass'),
(14, 'Dragon'),
(15, 'Undiscovered'),
(16, 'Gender Unknown');

-- --------------------------------------------------------

--
-- Table structure for table `pkmn_pokemon_img`
--

CREATE TABLE IF NOT EXISTS `pkmn_pokemon_img` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_pokemon` int(11) NOT NULL,
  `type` tinyint(4) DEFAULT NULL,
  `image` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_pkmn_pokemon_img_pkmn_pokemon1_idx` (`id_pokemon`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=39 ;

--
-- Dumping data for table `pkmn_pokemon_img`
--

INSERT INTO `pkmn_pokemon_img` (`id`, `id_pokemon`, `type`, `image`) VALUES
(1, 1, 0, '96px-001Bulbasaur_0.png'),
(2, 2, 0, '96px-004Charmander_0.png'),
(3, 3, 0, '96px-007Squirtle_0.png'),
(4, 7, 0, '96px-122Mr._Mime_0.png'),
(6, 9, 0, '96px-491Darkrai_0.png'),
(7, 10, 0, 'Berry_Flareon_0.png'),
(8, 11, 0, 'Ivysaur_0.png'),
(9, 12, 0, 'charmeleon_0.png'),
(10, 13, 0, '96px-003Venusaur_0.png'),
(11, 14, 0, '96px-006Charizard_0.png'),
(12, 15, 0, '96px-008Wartortle_0.png'),
(13, 16, 0, '96px-009Blastoise_0.png'),
(14, 17, 0, '96px-010Caterpie_0.png'),
(15, 18, 0, '96px-011Metapod_0.png'),
(16, 19, 0, '96px-012Butterfree_0.png'),
(17, 20, 0, '96px-013Weedle_0.png'),
(18, 21, 0, '96px-014Kakuna_0.png'),
(19, 22, 0, '96px-015Beedrill_0.png'),
(20, 23, 0, '96px-016Pidgey_0.png'),
(21, 24, 0, '96px-017Pidgeotto_0.png'),
(22, 25, 0, '96px-018Pidgeot_0.png'),
(23, 26, 0, '96px-019Rattata_0.png'),
(24, 27, 0, '96px-020Raticate_0.png'),
(25, 28, 0, '96px-021Spearow_0.png'),
(26, 29, 0, '96px-022Fearow_0.png'),
(27, 30, 0, '96px-023Ekans_0.png'),
(28, 31, 0, '96px-024Arbok_0.png'),
(29, 32, 0, '96px-025Pikachu_0.png'),
(30, 33, 0, '96px-026Raichu_0.png'),
(31, 34, 0, '96px-027Sandshrew_0.png'),
(32, 35, 0, '96px-028Sandslash_0.png'),
(33, 36, 0, '96px-029Nidoran_0.png'),
(34, 37, 0, '96px-030Nidorina_0.png'),
(35, 38, 0, '96px-031Nidoqueen_0.png'),
(36, 39, 0, '96px-032Nidoran_0.png'),
(37, 40, 0, '96px-033Nidorino_0.png'),
(38, 41, 0, '96px-034Nidoking_0.png');

-- --------------------------------------------------------

--
-- Table structure for table `pkmn_pokemon_item`
--

CREATE TABLE IF NOT EXISTS `pkmn_pokemon_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_category` tinyint(4) NOT NULL,
  `id_stat` tinyint(4) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `value` smallint(6) DEFAULT NULL,
  `uses` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_pkmn_pokemon_item_pkmn_item_category1_idx` (`id_category`),
  KEY `fk_pkmn_pokemon_item_pkmn_item_effect1_idx` (`id_stat`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `pkmn_pokemon_item`
--

INSERT INTO `pkmn_pokemon_item` (`id`, `id_category`, `id_stat`, `name`, `value`, `uses`) VALUES
(1, 6, 1, 'Moomoo Milk', 100, NULL),
(4, 1, 2, 'X Attack', 10, NULL),
(5, 1, 3, 'X Defend', 10, NULL),
(6, 1, 5, 'X Sp Attack', 10, NULL),
(7, 1, 6, 'X Sp Defend', 10, NULL),
(8, 6, 1, 'Energy Powder', 50, NULL),
(9, 8, NULL, 'Leaf Stone', 0, NULL),
(10, 8, NULL, 'Fire Stone', 0, NULL),
(11, 8, NULL, 'Water Stone', 0, NULL),
(12, 8, NULL, 'Moon Stone', 0, NULL),
(13, 8, NULL, 'Thunder Stone', 0, NULL),
(14, 8, NULL, 'Bug Stone', 0, NULL),
(15, 8, NULL, 'Poison Stone', 0, NULL),
(16, 8, NULL, 'Sun Stone', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pkmn_pokemon_move`
--

CREATE TABLE IF NOT EXISTS `pkmn_pokemon_move` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_nat` int(11) NOT NULL,
  `category` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `value` int(11) DEFAULT NULL,
  `accuracy` float DEFAULT NULL,
  `power_points` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_pkmn_pokemon_move_pkmn_pokemon_type1_idx` (`type_nat`),
  KEY `fk_pkmn_pokemon_move_pkmn_move_category1_idx` (`category`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;

--
-- Dumping data for table `pkmn_pokemon_move`
--

INSERT INTO `pkmn_pokemon_move` (`id`, `type_nat`, `category`, `name`, `value`, `accuracy`, `power_points`) VALUES
(1, 1, 1, 'Tackle', 50, 100, 0),
(2, 1, 1, 'Take Down', 2, 0, 0),
(3, 1, 1, 'Cut', 3, 0, 0),
(4, 1, 1, 'Scratch', 4, 0, 0),
(5, 1, 1, 'Slash', 5, 0, 0),
(6, 1, 1, 'Strength', 6, 0, 0),
(7, 1, 1, 'Bite', 8, 0, 0),
(8, 1, 1, 'Protect', 8, 0, 0),
(9, 17, 1, 'Iron Defense', 9, 0, 0),
(10, 2, 2, 'Ember', 40, 100, 0),
(11, 2, 1, 'Fire Fang', 65, 95, 0),
(12, 2, 2, 'Flame Burst', 70, 100, 0),
(13, 5, 3, 'Leech Seed', 0, 90, 0),
(14, 5, 1, 'Vine Whip', 45, 100, 0),
(15, 5, 3, 'Sleep Powder', 0, 75, 0),
(16, 3, 2, 'Water Gun', 40, 100, 0),
(17, 3, 3, 'Withdraw', 0, 0, 0),
(18, 3, 2, 'Bubble', 40, 100, 0),
(19, 16, 1, 'Feint Attack', 60, 100, 0),
(20, 16, 2, 'Dark Pulse', 80, 100, 0),
(21, 16, 1, 'Foul Play', 95, 100, 0),
(22, 16, 1, 'Knock Off', 65, 100, 0),
(23, 16, 1, 'Thief', 60, 100, 0),
(24, 11, 2, 'Confusion', 50, 100, 0),
(25, 11, 1, 'Psybeam', 65, 100, 0),
(26, 11, 2, 'Dream Eater', 100, 100, 0);

-- --------------------------------------------------------

--
-- Table structure for table `pkmn_pokemon_type`
--

CREATE TABLE IF NOT EXISTS `pkmn_pokemon_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

--
-- Dumping data for table `pkmn_pokemon_type`
--

INSERT INTO `pkmn_pokemon_type` (`id`, `name`) VALUES
(1, 'Normal'),
(2, 'Fire'),
(3, 'Water'),
(4, 'Electric'),
(5, 'Grass'),
(6, 'Ice'),
(7, 'Fighting'),
(8, 'Poison'),
(9, 'Ground'),
(10, 'Flying'),
(11, 'Psychic'),
(12, 'Bug'),
(13, 'Rock'),
(14, 'Ghost'),
(15, 'Dragon'),
(16, 'Dark'),
(17, 'Steel'),
(18, 'Fairy');

-- --------------------------------------------------------

--
-- Table structure for table `pkmn_region`
--

CREATE TABLE IF NOT EXISTS `pkmn_region` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `pkmn_region`
--

INSERT INTO `pkmn_region` (`id`, `name`) VALUES
(1, 'Kanto'),
(2, 'Johto');

-- --------------------------------------------------------

--
-- Table structure for table `pkmn_sale`
--

CREATE TABLE IF NOT EXISTS `pkmn_sale` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_pokemon` int(11) NOT NULL,
  `id_trainer` int(11) NOT NULL,
  `date_sold` datetime DEFAULT NULL,
  `exp` int(11) DEFAULT NULL,
  `gold` int(11) DEFAULT NULL,
  `silver` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_pkmn_sales_pkmn_pokemon1_idx` (`id_pokemon`),
  KEY `fk_pkmn_sales_pkmn_trainer1_idx` (`id_trainer`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `pkmn_sale`
--

INSERT INTO `pkmn_sale` (`id`, `id_pokemon`, `id_trainer`, `date_sold`, `exp`, `gold`, `silver`) VALUES
(1, 9, 1, '2015-03-02 03:44:44', 512, 10, 5);

-- --------------------------------------------------------

--
-- Table structure for table `pkmn_stat`
--

CREATE TABLE IF NOT EXISTS `pkmn_stat` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `pkmn_stat`
--

INSERT INTO `pkmn_stat` (`id`, `name`) VALUES
(1, 'HP'),
(2, 'Attack'),
(3, 'Defense'),
(4, 'Speed'),
(5, 'Special Attack'),
(6, 'Special Defense');

-- --------------------------------------------------------

--
-- Table structure for table `pkmn_trade`
--

CREATE TABLE IF NOT EXISTS `pkmn_trade` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `creation_date` datetime DEFAULT NULL,
  `id_state` tinyint(4) DEFAULT NULL,
  `id_trainer1` int(11) DEFAULT NULL,
  `id_trainer2` int(11) DEFAULT NULL,
  `id_tpkmn1` int(11) DEFAULT NULL,
  `id_tpkmn2` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_pkmn_trade_pkmn_trainer_pokemon2_idx` (`id_tpkmn2`),
  KEY `fk_pkmn_trade_pkmn_trainer_pokemon1_idx` (`id_tpkmn1`),
  KEY `fk_pkmn_trade_pkmn_trade_state1_idx` (`id_state`),
  KEY `fk_pkmn_trade_pkmn_trainer1_idx` (`id_trainer1`),
  KEY `fk_pkmn_trade_pkmn_trainer2_idx` (`id_trainer2`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `pkmn_trade`
--

INSERT INTO `pkmn_trade` (`id`, `creation_date`, `id_state`, `id_trainer1`, `id_trainer2`, `id_tpkmn1`, `id_tpkmn2`) VALUES
(1, '2015-09-01 04:09:35', 1, NULL, NULL, NULL, 2),
(2, '2016-03-21 01:38:41', 2, 29, 1, 128, 93);

-- --------------------------------------------------------

--
-- Table structure for table `pkmn_trade_state`
--

CREATE TABLE IF NOT EXISTS `pkmn_trade_state` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `pkmn_trade_state`
--

INSERT INTO `pkmn_trade_state` (`id`, `name`) VALUES
(1, 'Created'),
(2, 'Accepted'),
(3, 'Rejected'),
(4, 'Cancelled');

-- --------------------------------------------------------

--
-- Table structure for table `pkmn_trainer`
--

CREATE TABLE IF NOT EXISTS `pkmn_trainer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) DEFAULT NULL,
  `id_faction` int(11) DEFAULT NULL,
  `id_gym` int(11) DEFAULT NULL,
  `id_type` tinyint(4) DEFAULT NULL,
  `visible` tinyint(1) DEFAULT NULL,
  `order_index` smallint(6) DEFAULT NULL,
  `silver` int(11) DEFAULT NULL,
  `gold` int(11) DEFAULT NULL,
  `faction_pts` int(11) DEFAULT NULL,
  `name` varchar(32) DEFAULT NULL,
  `victories` int(11) DEFAULT NULL,
  `defeats` int(11) DEFAULT NULL,
  `clan` varchar(11) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_pkmn_trainer_users1_idx` (`id_user`),
  KEY `fk_pkmn_trainer_pkmn_faction1_idx` (`id_faction`),
  KEY `fk_pkmn_trainer_pkmn_gym1_idx` (`id_gym`),
  KEY `fk_pkmn_trainer_pkmn_trainer_type1_idx` (`id_type`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=30 ;

--
-- Dumping data for table `pkmn_trainer`
--

INSERT INTO `pkmn_trainer` (`id`, `id_user`, `id_faction`, `id_gym`, `id_type`, `visible`, `order_index`, `silver`, `gold`, `faction_pts`, `name`, `victories`, `defeats`, `clan`) VALUES
(1, 1, NULL, NULL, NULL, NULL, NULL, 1200, 1300, 47701, NULL, 8, 18, 'Beast'),
(2, 2, NULL, NULL, NULL, NULL, NULL, 100, 100, 2000, NULL, 1, 0, ''),
(4, NULL, NULL, 1, 1, 1, NULL, NULL, NULL, 14002, 'Brock', 5, 8, ''),
(5, 45, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, 0, 0, ''),
(6, 46, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, 0, 0, ''),
(7, 47, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, 0, 0, ''),
(8, 48, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, 0, 0, ''),
(9, 49, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, 0, 0, ''),
(10, 50, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, 0, 0, ''),
(11, 51, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, 0, 0, ''),
(12, 52, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, 0, 0, ''),
(13, 53, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, 0, 0, ''),
(14, NULL, 2, NULL, 1, 0, NULL, NULL, NULL, 11000, 'Forest', 11, 0, ''),
(15, 54, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 0, ''),
(16, 55, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 0, ''),
(17, 56, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 0, ''),
(18, 57, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 0, ''),
(19, 58, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 0, ''),
(20, 59, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, 0, 0, ''),
(21, NULL, NULL, NULL, 2, 1, NULL, NULL, NULL, 2000, 'Forest Tower', 1, 0, ''),
(22, NULL, NULL, NULL, 2, 1, NULL, NULL, NULL, 0, 'WhirlPool tower', 0, 0, ''),
(23, NULL, NULL, NULL, 2, 1, NULL, NULL, NULL, 0, 'Inferno Tower', 0, 0, ''),
(24, NULL, NULL, NULL, 2, 1, NULL, NULL, NULL, 0, 'Baby Tower', 0, 0, ''),
(25, 60, NULL, NULL, 1, NULL, NULL, 0, 0, 0, NULL, NULL, NULL, ''),
(26, 61, NULL, NULL, 1, NULL, NULL, 0, 0, 0, NULL, NULL, NULL, ''),
(27, 62, NULL, NULL, 1, NULL, NULL, 0, 0, 0, NULL, NULL, NULL, ''),
(28, 63, NULL, NULL, 1, NULL, NULL, 0, 0, 0, NULL, NULL, NULL, ''),
(29, 64, 3, NULL, 1, NULL, NULL, 0, 0, 0, NULL, NULL, NULL, '');

-- --------------------------------------------------------

--
-- Table structure for table `pkmn_trainer_badge`
--

CREATE TABLE IF NOT EXISTS `pkmn_trainer_badge` (
  `id_trainer` int(11) NOT NULL,
  `id_badge` int(11) NOT NULL,
  PRIMARY KEY (`id_trainer`,`id_badge`),
  KEY `fk_pkmn_trainer_has_pkmn_badge_pkmn_badge1_idx` (`id_badge`),
  KEY `fk_pkmn_trainer_has_pkmn_badge_pkmn_trainer1_idx` (`id_trainer`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pkmn_trainer_badge`
--

INSERT INTO `pkmn_trainer_badge` (`id_trainer`, `id_badge`) VALUES
(1, 5);

-- --------------------------------------------------------

--
-- Table structure for table `pkmn_trainer_item`
--

CREATE TABLE IF NOT EXISTS `pkmn_trainer_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_trainer` int(11) DEFAULT NULL,
  `id_item` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_pkmn_trainer_has_pkmn_pokemon_item_pkmn_pokemon_item1_idx` (`id_item`),
  KEY `fk_pkmn_trainer_has_pkmn_pokemon_item_pkmn_trainer1_idx` (`id_trainer`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `pkmn_trainer_item`
--

INSERT INTO `pkmn_trainer_item` (`id`, `id_trainer`, `id_item`) VALUES
(3, 1, 4),
(4, 1, 6),
(5, 1, 6),
(6, 1, 1),
(7, 1, 7);

-- --------------------------------------------------------

--
-- Table structure for table `pkmn_trainer_pokemon`
--

CREATE TABLE IF NOT EXISTS `pkmn_trainer_pokemon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_trainer` int(11) DEFAULT NULL,
  `id_pokemon` int(11) NOT NULL,
  `order_index` smallint(6) DEFAULT NULL,
  `exp` int(11) DEFAULT NULL,
  `equipped` tinyint(1) DEFAULT NULL,
  `tradeable` tinyint(1) DEFAULT NULL,
  `sellable` tinyint(1) DEFAULT NULL,
  `gender` varchar(1) DEFAULT NULL,
  `hp` int(11) DEFAULT NULL,
  `cur_hp` int(11) DEFAULT NULL,
  `attack` int(11) DEFAULT NULL,
  `defense` int(11) DEFAULT NULL,
  `speed` int(11) DEFAULT NULL,
  `spec_attack` int(11) DEFAULT NULL,
  `spec_defense` int(11) DEFAULT NULL,
  `silver` int(11) DEFAULT NULL,
  `gold` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_pkmn_trainer_pokemon_idx` (`id_pokemon`),
  KEY `fk_pkmn_trainer_idx` (`id_trainer`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=129 ;

--
-- Dumping data for table `pkmn_trainer_pokemon`
--

INSERT INTO `pkmn_trainer_pokemon` (`id`, `id_trainer`, `id_pokemon`, `order_index`, `exp`, `equipped`, `tradeable`, `sellable`, `gender`, `hp`, `cur_hp`, `attack`, `defense`, `speed`, `spec_attack`, `spec_defense`, `silver`, `gold`) VALUES
(2, 2, 1, NULL, 9274, 1, 1, 0, 'F', 4522, 4522, 4922, 4922, 4522, 6533, 6533, 0, 0),
(3, 2, 2, NULL, 0, 0, 0, NULL, 'M', 39, 39, 52, 43, 65, 60, 50, NULL, NULL),
(4, 2, 3, NULL, 108, 0, 0, NULL, 'M', 444, 444, 484, 655, 433, 504, 645, NULL, NULL),
(5, 2, 2, NULL, 6256, 1, 1, NULL, 'M', 392, 392, 522, 432, 653, 602, 502, NULL, NULL),
(21, 4, 2, NULL, 2300, 1, NULL, NULL, 'M', 39, 39, 52, 43, 65, 60, 50, NULL, NULL),
(22, 4, 1, NULL, 0, 1, NULL, NULL, 'M', 45, 45, 49, 49, 45, 65, 65, NULL, NULL),
(23, 4, 7, NULL, 0, 1, NULL, NULL, 'M', 20, 20, 20, 20, 20, 20, 20, NULL, NULL),
(24, 4, 7, NULL, 3400, 1, NULL, NULL, 'M', 20, 20, 20, 20, 20, 20, 20, NULL, NULL),
(25, 4, 2, NULL, 5000, 1, NULL, NULL, 'M', 39, 39, 52, 43, 65, 60, 50, NULL, NULL),
(26, 7, 3, NULL, NULL, 1, NULL, NULL, 'M', 44, 44, 48, 65, 43, 50, 64, NULL, NULL),
(27, 10, 1, NULL, NULL, 1, NULL, NULL, 'M', 45, 45, 49, 49, 45, 65, 65, NULL, NULL),
(28, 5, 3, NULL, NULL, 1, NULL, NULL, 'M', 44, 44, 48, 65, 43, 50, 64, NULL, NULL),
(29, 8, 7, NULL, NULL, 1, NULL, NULL, 'M', 20, 20, 20, 20, 20, 20, 20, NULL, NULL),
(30, 8, 2, NULL, NULL, 1, NULL, NULL, 'M', 39, 39, 52, 43, 65, 60, 50, NULL, NULL),
(31, 6, 3, NULL, NULL, 1, NULL, NULL, 'M', 44, 44, 48, 65, 43, 50, 64, NULL, NULL),
(32, 13, 3, NULL, NULL, 1, NULL, NULL, 'M', 44, 44, 48, 65, 43, 50, 64, NULL, NULL),
(33, 12, 2, NULL, NULL, 1, NULL, NULL, 'M', 39, 39, 52, 43, 65, 60, 50, NULL, NULL),
(34, 12, 7, NULL, NULL, 1, NULL, NULL, 'M', 20, 20, 20, 20, 20, 20, 20, NULL, NULL),
(35, 8, 1, NULL, NULL, 1, NULL, NULL, 'M', 45, 45, 49, 49, 45, 65, 65, NULL, NULL),
(36, 11, 1, NULL, NULL, 1, NULL, NULL, 'M', 45, 45, 49, 49, 45, 65, 65, NULL, NULL),
(37, 6, 2, NULL, NULL, 1, NULL, NULL, 'M', 39, 39, 52, 43, 65, 60, 50, NULL, NULL),
(38, 9, 3, NULL, NULL, 1, NULL, NULL, 'M', 44, 44, 48, 65, 43, 50, 64, NULL, NULL),
(39, 7, 1, NULL, NULL, 1, NULL, NULL, 'M', 45, 45, 49, 49, 45, 65, 65, NULL, NULL),
(40, 13, 2, NULL, NULL, 1, NULL, NULL, 'M', 39, 39, 52, 43, 65, 60, 50, NULL, NULL),
(41, 5, 1, NULL, NULL, 1, NULL, NULL, 'M', 45, 45, 49, 49, 45, 65, 65, NULL, NULL),
(42, 5, 2, NULL, NULL, 1, NULL, NULL, 'M', 39, 39, 52, 43, 65, 60, 50, NULL, NULL),
(43, 5, 7, NULL, NULL, 1, NULL, NULL, 'M', 20, 20, 20, 20, 20, 20, 20, NULL, NULL),
(44, 9, 2, NULL, NULL, 1, NULL, NULL, 'M', 39, 39, 52, 43, 65, 60, 50, NULL, NULL),
(45, 7, 7, NULL, NULL, 1, NULL, NULL, 'M', 20, 20, 20, 20, 20, 20, 20, NULL, NULL),
(46, 6, 7, NULL, NULL, 1, NULL, NULL, 'M', 20, 20, 20, 20, 20, 20, 20, NULL, NULL),
(47, 11, 2, NULL, NULL, 1, NULL, NULL, 'M', 39, 39, 52, 43, 65, 60, 50, NULL, NULL),
(48, 7, 2, NULL, NULL, 1, NULL, NULL, 'M', 39, 39, 52, 43, 65, 60, 50, NULL, NULL),
(49, 9, 7, NULL, NULL, 1, NULL, NULL, 'M', 20, 20, 20, 20, 20, 20, 20, NULL, NULL),
(50, 8, 3, NULL, NULL, 1, NULL, NULL, 'M', 44, 44, 48, 65, 43, 50, 64, NULL, NULL),
(51, 13, 1, NULL, NULL, 1, NULL, NULL, 'M', 45, 45, 49, 49, 45, 65, 65, NULL, NULL),
(52, 13, 7, NULL, NULL, 1, NULL, NULL, 'M', 20, 20, 20, 20, 20, 20, 20, NULL, NULL),
(53, 10, 7, NULL, NULL, 1, NULL, NULL, 'M', 20, 20, 20, 20, 20, 20, 20, NULL, NULL),
(54, 9, 1, NULL, NULL, 1, NULL, NULL, 'M', 45, 45, 49, 49, 45, 65, 65, NULL, NULL),
(55, 10, 2, NULL, NULL, 1, NULL, NULL, 'M', 39, 39, 52, 43, 65, 60, 50, NULL, NULL),
(56, 11, 3, NULL, NULL, 1, NULL, NULL, 'M', 44, 44, 48, 65, 43, 50, 64, NULL, NULL),
(57, 12, 3, NULL, NULL, 1, NULL, NULL, 'M', 44, 44, 48, 65, 43, 50, 64, NULL, NULL),
(58, 6, 1, NULL, NULL, 1, NULL, NULL, 'M', 45, 45, 49, 49, 45, 65, 65, NULL, NULL),
(59, 12, 1, NULL, NULL, 1, NULL, NULL, 'M', 45, 45, 49, 49, 45, 65, 65, NULL, NULL),
(60, 11, 7, NULL, NULL, 1, NULL, NULL, 'M', 20, 20, 20, 20, 20, 20, 20, NULL, NULL),
(61, 10, 3, NULL, NULL, 1, NULL, NULL, 'M', 44, 44, 48, 65, 43, 50, 64, NULL, NULL),
(62, 5, 9, NULL, 1, 1, 1, NULL, '?', 15, 15, 5, 5, 5, 5, 5, NULL, NULL),
(63, 2, 7, NULL, 8, 0, 0, 0, 'M', 14, 14, 7, 7, 7, 7, 7, 1, 20),
(65, 2, 3, NULL, 512, 1, 1, NULL, 'F', 32, 32, 20, 26, 19, 21, 26, NULL, NULL),
(67, 2, 9, NULL, 13824, NULL, NULL, NULL, '?', 130, 130, 15, 15, 15, 7, 7, NULL, NULL),
(69, 2, 9, NULL, 512, 0, 0, 0, '?', 50, 50, 8, 8, 8, 6, 6, 5, 10),
(71, 21, 1, 1, 1800, 1, 0, 0, 'M', 45, 45, 49, 49, 45, 65, 65, 0, 0),
(72, 21, 3, 2, 2800, 1, 0, 0, 'M', 44, 44, 48, 65, 43, 50, 64, 0, 0),
(73, 21, 10, 3, 2800, 1, 1, 0, 'M', 50, 50, 7, 7, 7, 4, 4, 0, 0),
(75, 22, 1, 1, 2800, 1, 0, 0, 'M', 45, 45, 49, 49, 45, 65, 65, 0, 0),
(76, 22, 10, 2, 2900, 1, 0, 1, 'M', 50, 50, 7, 7, 7, 4, 4, 0, 0),
(77, 22, 2, 3, 3000, 1, 0, 0, 'M', 39, 39, 52, 43, 65, 60, 50, 0, 0),
(78, 22, 7, 4, 2800, 1, 1, 0, 'M', 200, 200, 20, 20, 20, 20, 20, 0, 0),
(81, 27, 2, 0, 4291, 1, 0, 0, 'M', 31111, 24902, 22111, 19111, 26111, 24111, 21111, 0, 0),
(82, 28, 3, NULL, 125, 1, 0, 0, 'M', 24, 24, 15, 18, 14, 15, 18, 0, 0),
(93, 29, 11, 0, 52177, 0, 0, 0, 'M', 123, 0, 69, 70, 67, 88, 88, 0, 0),
(94, 27, 9, NULL, 512, 1, 0, 0, '?', 50, 50, 8, 8, 8, 6, 6, 0, 0),
(95, 27, 10, NULL, 1728, 0, 0, 0, 'M', 46, 46, 8, 8, 8, 7, 7, 0, 0),
(96, 27, 11, NULL, 4913, 0, 0, 0, 'F', 707, 707, 19, 19, 19, 19, 19, 0, 0),
(97, 27, 9, NULL, 2197, 0, 0, 0, '?', 75, 75, 10, 10, 10, 6, 6, 0, 0),
(99, 1, 9, 0, 1000, 1, 0, 0, '?', 38, 0, 27, 27, 34, 37, 27, 0, 0),
(114, 1, 3, NULL, 512, 0, 0, 0, 'F', 26, 26, 13, 16, 13, 14, 16, 0, 0),
(115, 1, 10, NULL, 216, 0, 0, 0, 'F', 25, 25, 21, 13, 14, 17, 19, 0, 0),
(122, 1, 1, 0, 512, 1, 0, 0, 'F', 25, 0, 13, 13, 12, 16, 16, 0, 0),
(125, NULL, 9, NULL, 27000, 1, 0, 0, '?', 87, 87, 64, 64, 85, 91, 64, 0, 0),
(126, 29, 2, NULL, 125, 1, 0, 0, 'F', 19, 19, 10, 10, 12, 11, 10, 0, 0),
(127, 1, 13, 0, 54872, 1, 0, 0, 'F', 113, 6, 71, 71, 69, 84, 84, 0, 0),
(128, 1, 2, NULL, 125, 0, 0, 0, 'F', 19, 19, 11, 10, 12, 11, 10, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `pkmn_trainer_type`
--

CREATE TABLE IF NOT EXISTS `pkmn_trainer_type` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `pkmn_trainer_type`
--

INSERT INTO `pkmn_trainer_type` (`id`, `name`) VALUES
(1, 'Normal'),
(2, 'Tower');

-- --------------------------------------------------------

--
-- Table structure for table `pkmn_type_effect`
--

CREATE TABLE IF NOT EXISTS `pkmn_type_effect` (
  `id_type_one` int(11) NOT NULL,
  `id_type_two` int(11) NOT NULL DEFAULT '0',
  `multiplier` float DEFAULT NULL,
  PRIMARY KEY (`id_type_one`,`id_type_two`),
  KEY `fk_pkmn_pokemon_move_effect_pkmn_pokemon_type2_idx` (`id_type_two`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pkmn_type_effect`
--

INSERT INTO `pkmn_type_effect` (`id_type_one`, `id_type_two`, `multiplier`) VALUES
(1, 1, 1),
(1, 2, 1),
(1, 3, 1),
(1, 4, 1),
(1, 5, 1),
(1, 6, 1),
(1, 7, 1),
(1, 8, 1),
(1, 9, 1),
(1, 10, 1),
(1, 11, 1),
(1, 12, 1),
(1, 13, 0.5),
(1, 14, 0),
(1, 15, 1),
(1, 16, 1),
(1, 17, 0.5),
(1, 18, 1),
(2, 1, 1),
(2, 2, 0.5),
(2, 3, 0.5),
(2, 4, 1),
(2, 5, 2),
(2, 6, 2),
(2, 7, 1),
(2, 8, 1),
(2, 9, 1),
(2, 10, 1),
(2, 11, 1),
(2, 12, 2),
(2, 13, 0.5),
(2, 14, 0),
(2, 15, 1),
(2, 16, 1),
(2, 17, 0.5),
(2, 18, 1),
(3, 1, 1),
(3, 2, 1),
(3, 3, 0.5),
(3, 4, 1),
(3, 5, 0.5),
(3, 6, 1),
(3, 7, 1),
(3, 8, 1),
(3, 9, 2),
(3, 10, 1),
(3, 11, 1),
(3, 12, 1),
(3, 13, 2),
(3, 14, 1),
(3, 15, 0.5),
(3, 16, 1),
(3, 17, 0.5),
(3, 18, 1),
(4, 1, 1),
(4, 2, 1),
(4, 3, 1),
(4, 4, 1),
(4, 5, 1),
(4, 6, 1),
(4, 7, 1),
(4, 8, 1),
(4, 9, 1),
(4, 10, 1),
(4, 11, 1),
(4, 12, 1),
(4, 13, 1),
(4, 14, 1),
(4, 15, 1),
(4, 16, 1),
(4, 17, 1),
(4, 18, 1),
(5, 1, 1),
(5, 2, 1),
(5, 3, 1),
(5, 4, 1),
(5, 5, 1),
(5, 6, 1),
(5, 7, 1),
(5, 8, 1),
(5, 9, 1),
(5, 10, 1),
(5, 11, 1),
(5, 12, 1),
(5, 13, 1),
(5, 14, 1),
(5, 15, 1),
(5, 16, 1),
(5, 17, 1),
(5, 18, 1),
(6, 1, 1),
(6, 2, 1),
(6, 3, 1),
(6, 4, 1),
(6, 5, 1),
(6, 6, 1),
(6, 7, 1),
(6, 8, 1),
(6, 9, 1),
(6, 10, 1),
(6, 11, 1),
(6, 12, 1),
(6, 13, 1),
(6, 14, 1),
(6, 15, 1),
(6, 16, 1),
(6, 17, 1),
(6, 18, 1),
(7, 1, 1),
(7, 2, 1),
(7, 3, 1),
(7, 4, 1),
(7, 5, 1),
(7, 6, 1),
(7, 7, 1),
(7, 8, 1),
(7, 9, 1),
(7, 10, 1),
(7, 11, 1),
(7, 12, 1),
(7, 13, 1),
(7, 14, 1),
(7, 15, 1),
(7, 16, 1),
(7, 17, 1),
(7, 18, 1),
(8, 1, 1),
(8, 2, 1),
(8, 3, 1),
(8, 4, 1),
(8, 5, 2),
(8, 6, 1),
(8, 7, 1),
(8, 8, 0.5),
(8, 9, 0.5),
(8, 10, 1),
(8, 11, 1),
(8, 12, 1),
(8, 13, 0.5),
(8, 14, 0.5),
(8, 15, 1),
(8, 16, 1),
(8, 17, 0),
(8, 18, 2),
(9, 1, 1),
(9, 2, 1),
(9, 3, 1),
(9, 4, 1),
(9, 5, 1),
(9, 6, 1),
(9, 7, 1),
(9, 8, 1),
(9, 9, 1),
(9, 10, 1),
(9, 11, 1),
(9, 12, 1),
(9, 13, 1),
(9, 14, 1),
(9, 15, 1),
(9, 16, 1),
(9, 17, 1),
(9, 18, 1),
(10, 1, 1),
(10, 2, 1),
(10, 3, 1),
(10, 4, 1),
(10, 5, 1),
(10, 6, 1),
(10, 7, 1),
(10, 8, 1),
(10, 9, 1),
(10, 10, 1),
(10, 11, 1),
(10, 12, 1),
(10, 13, 1),
(10, 14, 1),
(10, 15, 1),
(10, 16, 1),
(10, 17, 1),
(10, 18, 1),
(11, 1, 1),
(11, 2, 1),
(11, 3, 1),
(11, 4, 1),
(11, 5, 1),
(11, 6, 1),
(11, 7, 1),
(11, 8, 1),
(11, 9, 1),
(11, 10, 1),
(11, 11, 1),
(11, 12, 1),
(11, 13, 1),
(11, 14, 1),
(11, 15, 1),
(11, 16, 1),
(11, 17, 1),
(11, 18, 1),
(12, 1, 1.5),
(12, 2, 1),
(12, 3, 2),
(12, 4, 1),
(12, 5, 1),
(12, 6, 1),
(12, 7, 1),
(12, 8, 1),
(12, 9, 1),
(12, 10, 1),
(12, 11, 1),
(12, 12, 1),
(12, 13, 1),
(12, 14, 1),
(12, 15, 1),
(12, 16, 1),
(12, 17, 1),
(12, 18, 1),
(13, 1, 1),
(13, 2, 1),
(13, 3, 1),
(13, 4, 1),
(13, 5, 1),
(13, 6, 1),
(13, 7, 1),
(13, 8, 1),
(13, 9, 1),
(13, 10, 1),
(13, 11, 1),
(13, 12, 1),
(13, 13, 1),
(13, 14, 1),
(13, 15, 1),
(13, 16, 1),
(13, 17, 1),
(13, 18, 1),
(14, 1, 1),
(14, 2, 1),
(14, 3, 1),
(14, 4, 1),
(14, 5, 1),
(14, 6, 1),
(14, 7, 1),
(14, 8, 1),
(14, 9, 1),
(14, 10, 1),
(14, 11, 1),
(14, 12, 1),
(14, 13, 1),
(14, 14, 1),
(14, 15, 1),
(14, 16, 1),
(14, 17, 1),
(14, 18, 1),
(15, 1, 1),
(15, 2, 1),
(15, 3, 1),
(15, 4, 1),
(15, 5, 1),
(15, 6, 1),
(15, 7, 1),
(15, 8, 1),
(15, 9, 1),
(15, 10, 1),
(15, 11, 1),
(15, 12, 1),
(15, 13, 1),
(15, 14, 1),
(15, 15, 1),
(15, 16, 1),
(15, 17, 1),
(15, 18, 1),
(16, 1, 1),
(16, 2, 1),
(16, 3, 1),
(16, 4, 1),
(16, 5, 1),
(16, 6, 1),
(16, 7, 1),
(16, 8, 1),
(16, 9, 1),
(16, 10, 1),
(16, 11, 1),
(16, 12, 1),
(16, 13, 1),
(16, 14, 1),
(16, 15, 1),
(16, 16, 1),
(16, 17, 1),
(16, 18, 1),
(17, 1, 1),
(17, 2, 1),
(17, 3, 1),
(17, 4, 1),
(17, 5, 1),
(17, 6, 1),
(17, 7, 1),
(17, 8, 1),
(17, 9, 1),
(17, 10, 1),
(17, 11, 1),
(17, 12, 1),
(17, 13, 1),
(17, 14, 1),
(17, 15, 1),
(17, 16, 1),
(17, 17, 1),
(17, 18, 1),
(18, 1, 1),
(18, 2, 1),
(18, 3, 1),
(18, 4, 1),
(18, 5, 1),
(18, 6, 1),
(18, 7, 1),
(18, 8, 1),
(18, 9, 1),
(18, 10, 1),
(18, 11, 1),
(18, 12, 1),
(18, 13, 1),
(18, 14, 1),
(18, 15, 1),
(18, 16, 1),
(18, 17, 1),
(18, 18, 1);

-- --------------------------------------------------------

--
-- Table structure for table `system_property`
--

CREATE TABLE IF NOT EXISTS `system_property` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `value` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=33 ;

--
-- Dumping data for table `system_property`
--

INSERT INTO `system_property` (`id`, `name`, `value`) VALUES
(1, 'pkmn_msg_success', 'Operation completed correctly'),
(2, 'pkmn_msg_error', 'Unable to perform operation. %s'),
(3, 'avatar_img_bad_format', 'Avatar image must have dimensions of maximum 120 x 120 pixels'),
(4, 'user_exists', 'User name already used'),
(5, 'user_banned', 'User %s banned'),
(6, 'login_failed', 'Login failed. Please try again.'),
(7, 'sign_bad_format', 'Signature image must have dimensions of maximum 402 x 151 pixels'),
(8, 'pkmn_img_bad_format', 'Pokemon image must have dimensions of maximum 96 x 96 pixels'),
(9, 'pkmn_no_moves', 'No moves found for %s'),
(10, 'pkmn_invhead_pokemon', 'Invalid headers, ''%s'' is missing'),
(11, 'pkmn_invcont_pokemon', 'Pokemon data. %s.'),
(12, 'captcha_failed', 'Invalid captcha'),
(13, 'pkmn_appeared', 'A wild %s appeared. What do you want to do?'),
(14, 'map_img_not_loaded', 'Map image not loaded'),
(15, 'map_img_bad_type', 'Invalid image type. Must be png, gif or jpeg.'),
(16, 'map_img_bad_format', 'Invalid map size.'),
(17, 'user_not_exists', 'User %s not exists'),
(18, 'pswd_mail_reset_ok', 'An email was sent to your registered address with instructions to reset your password'),
(19, 'pswd_mail_reset_error', 'An error happened sending password reset email'),
(20, 'pswd_reset_error', 'The requested password reset is invalid. Please try again.'),
(21, 'pswd_reset_ok', 'Your password has been reset'),
(22, 'faction_higher_pts', '2000'),
(23, 'faction_lower_pts', '1000'),
(24, 'badge_img_bad_format', 'Invalid badge image size'),
(25, 'badge_img_bad_type', 'Invalid image type. Must be png, gif or jpeg.'),
(26, 'badge_img_not_loaded', 'Badge image not loaded'),
(27, 'pkmn_not_enough_points', 'You don''t have enough points'),
(28, 'pkmn_evolved_ok', '%s has evolved!'),
(29, 'pkmn_start_level', '5'),
(30, 'pkmn_max_team_count', '6'),
(31, 'pkmn_msg_max_team_count', 'You can have a maximum of 6 pokemon in the team'),
(32, 'pkmn_no_pokemon_available', 'None of your chosen pokemons can fight. Please change them.');

-- --------------------------------------------------------

--
-- Table structure for table `system_role`
--

CREATE TABLE IF NOT EXISTS `system_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rank` tinyint(4) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `system_role`
--

INSERT INTO `system_role` (`id`, `rank`, `name`) VALUES
(1, 3, 'Administrator'),
(2, 2, 'Colaborator'),
(3, 1, 'Player');

-- --------------------------------------------------------

--
-- Table structure for table `system_user`
--

CREATE TABLE IF NOT EXISTS `system_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `last_updated` datetime DEFAULT NULL,
  `banned` tinyint(1) NOT NULL DEFAULT '0',
  `disabled` tinyint(1) DEFAULT NULL,
  `username` varchar(32) COLLATE latin1_general_ci DEFAULT NULL,
  `password` varchar(128) COLLATE latin1_general_ci DEFAULT NULL,
  `tmp_password` varchar(128) COLLATE latin1_general_ci DEFAULT NULL,
  `mail` varchar(256) COLLATE latin1_general_ci DEFAULT NULL,
  `gender` varchar(1) COLLATE latin1_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=65 ;

--
-- Dumping data for table `system_user`
--

INSERT INTO `system_user` (`id`, `last_updated`, `banned`, `disabled`, `username`, `password`, `tmp_password`, `mail`, `gender`) VALUES
(1, '2016-06-03 17:35:34', 0, 0, 'Legion', '92f39f7f2a869838cd5085e6f17fc82109bcf98cd62a47cbc379e38de80bbc0213a23cee6e4a13de6caae0add8a390272d6f0883c274320b1ff60dbcfc6dd750', 'ea7f96e70cf4b3645c5bb69b92b8740f0a54c26932b9b7592596fbde5387cc8eba564e0d64ed0b8a678a0316ce0407402b1bf213f4be7b7dd5c1458d3b210662', 'omaidnadi@hotmail.com', 'M'),
(2, '2015-09-08 10:29:15', 0, 1, 'testuser1', 'daef4953b9783365cad6615223720506cc46c5167cd16ab500fa597aa08ff964eb24fb19687f34d7665f778fcb6c5358fc0a5b81e1662cf90f73a2671c53f991', '11e04f77c72739b07f2bad4b221dcca8cdf364810ee9e0f78362f545f6bbdf2a50ce5998baf6aa90482b4dcc0478d4214704a3dc4f5b8f736320dbe2d652f097', 'sclm256@gmail.com', 'F'),
(45, NULL, 0, 1, 'testuser2', '765d7ebe356fe7350c41180cfcce7dd9079ee70dc9c85a33aa7089344f8230da6ebed5829c448e546943397d848da424a69c01b4651660fc8f683297e7b4c37f', '2a18b6cfef64a7f1c699caca57602d3bd30ab02c168b608b33b31212ad07d51244938d64a1f32f1b9b493c0821e646e8f5a3d091a38e5278fca2561a62c21a13', 'sclm256@hotmail.com', 'M'),
(46, NULL, 0, 1, 'testuser3', '3ae434bfbeefee4596cdb342a5254f6513e1f7923853ebdef7ed0c327b10746a61390292120d4009eb152f8883b868e43bd94f113b7bc0d78a9725238a30cc70', NULL, 'testuser3@hotmail.com', 'M'),
(47, NULL, 0, 1, 'testuser4', '452862d7dff3732b73c0115700c606a77a0d23d177daa852596babe58a70abe1f6bb33226c97ffdfcbc6e14152e44d8570b827f695ad4974156c4b196094dd54', NULL, 'testuser4@hotmail.com', 'M'),
(48, NULL, 0, 1, 'testuser5', 'e87f02263edde8c6dfdc26dfafba668a6dd773647aa9110ab798d4b88ace3452812ba28285f3c38362d207711d9d5327f20e276404a71ee09a78d1319ab80fe5', NULL, 'testuser5@hotmail.com', 'M'),
(49, NULL, 0, 1, 'testuser6', '7228de58046185c37d0748d7b9940e23d89cca85e3b993a2a2f986fa3f9f5ece048f37107da490095369b0479f95b86d6d20a0da4cb8cdc5db3f0d2084c3685c', NULL, 'testuser6@hotmail.com', 'M'),
(50, NULL, 0, 1, 'testuser7', 'ed305bb45a9cbf131c892406c770ac8282e88a27a895da4e0844130a62361a4f2a4bdd1f43d0d5d4ba4bca3bd4596592c3bae348c3060359fc7c6a514406dff6', NULL, 'testuser7@hotmail.com', 'M'),
(51, NULL, 0, 1, 'testuser8', '1360c5dba33ba933717e96096abcb856d22f1f491b68497b6456a7dbecf74aabf4035fa7a90ea3ba732c74c8c2b952b827843156d284e58c28225c2e83b11689', NULL, 'testuser8@hotmail.com', 'M'),
(52, NULL, 0, 1, 'testuser9', '50f68d932b5f1ce51b5e82a258c567eba2034b1c8e654d6968baa0487726614b5795518c6f8a74d52bd67530c996ed23eb04776676c9681546813d191969c0b1', NULL, 'testuser9@hotmail.com', 'M'),
(53, NULL, 0, 1, 'testuser10', '6f934f65fd7dcd81e564b0e60f9951f67bf5ae868c7ac14527882c2ae66d1adc3a52ad5146209e4479d5472a97d4a009872e036c5c5b489bffe80c10009e2135', NULL, 'testuser10@hotmail.com', 'M'),
(54, NULL, 0, 1, 'reguser1', '460f03b048b35fd365af0f8957937f0022dfd9e2a5dd2108ab1cf3c5e7671c174c0e8f857aa3ad1881b3ff91963f51616a21f4e9f67a4d578361d014d9d1c11f', NULL, 'reguser1@hotmail.com', 'M'),
(55, NULL, 0, 1, 'reguser2', '1480fb00fd2390b66a81d3e6021be970755527ad84a6135df9c22890ecd047c618bdf4c7de292316754fdc6412a5593633cdce9d5a5e9a2dffc4065c400f4506', NULL, 'reguser2@hotmail.com', 'M'),
(56, NULL, 0, 1, 'regtest3', 'c660e299ff23079af47c08379f6814f0592eaa3e9cfa35443168d499a1a264537b846aa46e77c8faade6f9bf345a5608d53f45964b1133bc9b6650426c5d123e', NULL, 'regtest3@hotmail.com', 'M'),
(57, NULL, 0, 1, 'regtest4', '04598df02f441ade38d6bf1bd85a02d7231c921dcd180a7b11ab73a8eb0d39805e93b12b9e7aee9b3aab9211d512a4a9ae6109c6cc8daa2ded8936b75a42d349', NULL, 'regtest4@hotmail.com', 'M'),
(58, NULL, 0, 1, 'regtest5', '5d644bc0b5f548b745f01156cf919ac8c08318da7dfe6227ba03a02365e16a50dee959237784613de84ab4cfa776ec64d6496f9793bdd62163a7d47b1e506d3d', NULL, 'regtest5@hotmail.com', 'M'),
(59, NULL, 0, 1, 'regtest6', '994c5d3d7e68bcdf26e7e217c9a8938a17cf0ba2555879dc1a8c77d45b1187f3b2f723926c542f7784ee94a6c3f60abf2130c6c9bb589ab4416656cff038dd26', NULL, 'regtest6@hotmail.com', 'M'),
(60, NULL, 0, 1, 'xtestuser1', '5cada6fa8e005077b76d544cfbe855f9669296f4402d0cb03e8269bcfc4ab2a685c3ec355941a9e0b7d223657e0ed6d3dff8d59401b52f774ae442ff021e14f8', NULL, 'sclm256@hotmail.com', 'M'),
(61, NULL, 0, 1, 'testuser', '2bbe0c48b91a7d1b8a6753a8b9cbe1db16b84379f3f91fe115621284df7a48f1cd71e9beb90ea614c7bd924250aa9e446a866725e685a65df5d139a5cd180dc9', NULL, 'test1234@hotmail.com', 'M'),
(62, NULL, 0, 1, 'xtestuser2', 'fccd71649e0799a0b177ff1127ea0c4baa10a3e75772a30df7681afa3f763f663feb58510cb47a9b54eb498efade690ba1e77ef145bb25418d42525f28bb8717', NULL, 'sclm256@hotmail.com', 'M'),
(63, '2015-06-28 22:12:53', 0, 1, 'xtestuser3', '2bbe0c48b91a7d1b8a6753a8b9cbe1db16b84379f3f91fe115621284df7a48f1cd71e9beb90ea614c7bd924250aa9e446a866725e685a65df5d139a5cd180dc9', NULL, 'sclm256@hotmail.com', 'F'),
(64, '2016-03-22 00:20:56', 0, 0, 'LegionUser', '09239c1162108f2c57fbba2528715f9da3a9152187e65ba06bb91910c4eb758af2eeabfc02d813ce196bda5331300f8a5679027d197935632aba658de30023f8', '5c043d4dd0a3b91d5b87119b1cdbf6a0de95088542f61b852afc3cca7d5600fc9286cca8a17f8b3530b4d6ae3a3ee919aae96d887566fc7d214a90748a1e740e', 'legiondev@hotmail.com', 'M');

-- --------------------------------------------------------

--
-- Table structure for table `system_user_role`
--

CREATE TABLE IF NOT EXISTS `system_user_role` (
  `id_user` int(11) NOT NULL,
  `id_role` int(11) NOT NULL,
  PRIMARY KEY (`id_user`,`id_role`),
  KEY `fk_system_user_has_system_user_role_system_user_role1_idx` (`id_role`),
  KEY `fk_system_user_has_system_user_role_system_user1_idx` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `system_user_role`
--

INSERT INTO `system_user_role` (`id_user`, `id_role`) VALUES
(1, 1),
(1, 2),
(64, 3);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pkmn_badge`
--
ALTER TABLE `pkmn_badge`
  ADD CONSTRAINT `fk_pkmn_badge_pkmn_gym1` FOREIGN KEY (`id_gym`) REFERENCES `pkmn_gym` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `pkmn_evolution`
--
ALTER TABLE `pkmn_evolution`
  ADD CONSTRAINT `fk_pkmn_evolution_item` FOREIGN KEY (`id_required_item`) REFERENCES `pkmn_pokemon_item` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pkmn_evolution_pokemon1` FOREIGN KEY (`id_pkmn`) REFERENCES `pkmn_pokemon` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pkmn_evolution_pokemon2` FOREIGN KEY (`id_evolved_pkmn`) REFERENCES `pkmn_pokemon` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `pkmn_faction_shop`
--
ALTER TABLE `pkmn_faction_shop`
  ADD CONSTRAINT `fk_pkmn_faction_shop_pkmn_faction1` FOREIGN KEY (`id_faction`) REFERENCES `pkmn_faction` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `pkmn_gym`
--
ALTER TABLE `pkmn_gym`
  ADD CONSTRAINT `fk_pkmn_gym_pkmn_region1` FOREIGN KEY (`id_region`) REFERENCES `pkmn_region` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `pkmn_map`
--
ALTER TABLE `pkmn_map`
  ADD CONSTRAINT `fk_pkmn_map_pkmn_region1` FOREIGN KEY (`id_region`) REFERENCES `pkmn_region` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `pkmn_news`
--
ALTER TABLE `pkmn_news`
  ADD CONSTRAINT `fk_pkmn_news_pkmn_trainer1` FOREIGN KEY (`id_trainer`) REFERENCES `pkmn_trainer` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `pkmn_pokemon_assig_group`
--
ALTER TABLE `pkmn_pokemon_assig_group`
  ADD CONSTRAINT `fk_pkmn_pokemon_has_pkmn_pokemon_group_pkmn_pokemon1` FOREIGN KEY (`id_pokemon`) REFERENCES `pkmn_pokemon` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pkmn_pokemon_has_pkmn_pokemon_group_pkmn_pokemon_group1` FOREIGN KEY (`id_group`) REFERENCES `pkmn_pokemon_group` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `pkmn_pokemon_assig_move`
--
ALTER TABLE `pkmn_pokemon_assig_move`
  ADD CONSTRAINT `fk_pkmn_pokemon_assig_move_pkmn_pokemon_move1` FOREIGN KEY (`id_move`) REFERENCES `pkmn_pokemon_move` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pkmn_pokemon_assig_move_pkmn_trainer_pokemon1` FOREIGN KEY (`id_trainer_pokemon`) REFERENCES `pkmn_trainer_pokemon` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `pkmn_pokemon_assig_type`
--
ALTER TABLE `pkmn_pokemon_assig_type`
  ADD CONSTRAINT `fk_pkmn_pokemon_type_has_pkmn_pokemon_pkmn_pokemon1` FOREIGN KEY (`id_pokemon`) REFERENCES `pkmn_pokemon` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pkmn_pokemon_type_has_pkmn_pokemon_pkmn_pokemon_type1` FOREIGN KEY (`id_type`) REFERENCES `pkmn_pokemon_type` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `pkmn_pokemon_img`
--
ALTER TABLE `pkmn_pokemon_img`
  ADD CONSTRAINT `fk_pkmn_pokemon_img_pkmn_pokemon1` FOREIGN KEY (`id_pokemon`) REFERENCES `pkmn_pokemon` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `pkmn_pokemon_item`
--
ALTER TABLE `pkmn_pokemon_item`
  ADD CONSTRAINT `fk_pkmn_pokemon_item_pkmn_item_category1` FOREIGN KEY (`id_category`) REFERENCES `pkmn_item_category` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pkmn_pokemon_item_pkmn_item_effect1` FOREIGN KEY (`id_stat`) REFERENCES `pkmn_stat` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `pkmn_pokemon_move`
--
ALTER TABLE `pkmn_pokemon_move`
  ADD CONSTRAINT `fk_pkmn_pokemon_move_pkmn_move_category1` FOREIGN KEY (`category`) REFERENCES `pkmn_move_category` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pkmn_pokemon_move_pkmn_pokemon_type1` FOREIGN KEY (`type_nat`) REFERENCES `pkmn_pokemon_type` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `pkmn_sale`
--
ALTER TABLE `pkmn_sale`
  ADD CONSTRAINT `fk_pkmn_sales_pkmn_pokemon1` FOREIGN KEY (`id_pokemon`) REFERENCES `pkmn_pokemon` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pkmn_sales_pkmn_trainer1` FOREIGN KEY (`id_trainer`) REFERENCES `pkmn_trainer` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `pkmn_trade`
--
ALTER TABLE `pkmn_trade`
  ADD CONSTRAINT `fk_pkmn_trade_pkmn_trade_state1` FOREIGN KEY (`id_state`) REFERENCES `pkmn_trade_state` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pkmn_trade_pkmn_trainer1` FOREIGN KEY (`id_trainer1`) REFERENCES `pkmn_trainer` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pkmn_trade_pkmn_trainer2` FOREIGN KEY (`id_trainer2`) REFERENCES `pkmn_trainer` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pkmn_trade_pkmn_trainer_pokemon1` FOREIGN KEY (`id_tpkmn1`) REFERENCES `pkmn_trainer_pokemon` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pkmn_trade_pkmn_trainer_pokemon2` FOREIGN KEY (`id_tpkmn2`) REFERENCES `pkmn_trainer_pokemon` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `pkmn_trainer`
--
ALTER TABLE `pkmn_trainer`
  ADD CONSTRAINT `fk_pkmn_trainer_pkmn_faction1` FOREIGN KEY (`id_faction`) REFERENCES `pkmn_faction` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pkmn_trainer_pkmn_gym1` FOREIGN KEY (`id_gym`) REFERENCES `pkmn_gym` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pkmn_trainer_pkmn_trainer_type1` FOREIGN KEY (`id_type`) REFERENCES `pkmn_trainer_type` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pkmn_trainer_users1` FOREIGN KEY (`id_user`) REFERENCES `system_user` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION;

--
-- Constraints for table `pkmn_trainer_badge`
--
ALTER TABLE `pkmn_trainer_badge`
  ADD CONSTRAINT `fk_pkmn_trainer_has_pkmn_badge_pkmn_badge1` FOREIGN KEY (`id_badge`) REFERENCES `pkmn_badge` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pkmn_trainer_has_pkmn_badge_pkmn_trainer1` FOREIGN KEY (`id_trainer`) REFERENCES `pkmn_trainer` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `pkmn_trainer_item`
--
ALTER TABLE `pkmn_trainer_item`
  ADD CONSTRAINT `fk_pkmn_trainer_has_pkmn_pokemon_item_pkmn_pokemon_item1` FOREIGN KEY (`id_item`) REFERENCES `pkmn_pokemon_item` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pkmn_trainer_has_pkmn_pokemon_item_pkmn_trainer1` FOREIGN KEY (`id_trainer`) REFERENCES `pkmn_trainer` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `pkmn_trainer_pokemon`
--
ALTER TABLE `pkmn_trainer_pokemon`
  ADD CONSTRAINT `fk_pokemon` FOREIGN KEY (`id_pokemon`) REFERENCES `pkmn_pokemon` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_trainer` FOREIGN KEY (`id_trainer`) REFERENCES `pkmn_trainer` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `pkmn_type_effect`
--
ALTER TABLE `pkmn_type_effect`
  ADD CONSTRAINT `fk_pkmn_pokemon_move_effect_pkmn_pokemon_type1` FOREIGN KEY (`id_type_one`) REFERENCES `pkmn_pokemon_type` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pkmn_pokemon_move_effect_pkmn_pokemon_type2` FOREIGN KEY (`id_type_two`) REFERENCES `pkmn_pokemon_type` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `system_user_role`
--
ALTER TABLE `system_user_role`
  ADD CONSTRAINT `fk_system_user_has_system_user_role_system_user1` FOREIGN KEY (`id_user`) REFERENCES `system_user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_system_user_has_system_user_role_system_user_role1` FOREIGN KEY (`id_role`) REFERENCES `system_role` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
