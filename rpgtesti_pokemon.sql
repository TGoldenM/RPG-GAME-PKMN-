-- phpMyAdmin SQL Dump
-- version 4.0.10.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 16, 2015 at 05:12 PM
-- Server version: 5.5.40-MariaDB-cll-lve
-- PHP Version: 5.4.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `rpgtesti_pokemon`
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `pkmn_evolution`
--

INSERT INTO `pkmn_evolution` (`id`, `id_pkmn`, `id_evolved_pkmn`, `id_required_item`, `required_exp`) VALUES
(5, 1, 11, 9, 1),
(6, 2, 12, 10, 1);

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `pkmn_news`
--

INSERT INTO `pkmn_news` (`id`, `id_trainer`, `created`, `title`, `content`) VALUES
(2, 1, '2015-01-27 21:57:15', 'Test', '<h2>This is other test</h2>\r\n<p>Especifically, the test2.</p>\r\n<p><iframe src=\\"//www.youtube.com/embed/qxMzWVfNQ3s\\" width=\\"425\\" height=\\"350\\"></iframe></p>\r\n<p>&nbsp;</p>'),
(3, 1, '2015-01-27 17:52:47', 'Test', '<h1>This is a test.</h1>\r\n<p>&nbsp;</p>\r\n<p>To be <span style=\\"background-color: #008000;\\">precise</span> is the <strong>test 1.</strong></p>');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `pkmn_pokemon`
--

INSERT INTO `pkmn_pokemon` (`id`, `initial`, `disabled`, `genderless`, `name`, `hp`, `attack`, `defense`, `speed`, `spec_attack`, `spec_defense`, `base_exp`, `evhp`, `evattack`, `evdefense`, `evspeed`, `evspec_attack`, `evspec_defense`) VALUES
(1, 1, 0, 0, 'Bulbasaur', 45, 49, 49, 45, 65, 65, 64, 0, 0, 0, 0, 1, 0),
(2, 1, 0, 0, 'charmander', 39, 52, 43, 65, 60, 50, 62, 0, 0, 0, 1, 0, 0),
(3, 1, 0, 0, 'squirtle', 44, 48, 65, 43, 50, 64, 63, 0, 0, 1, 0, 0, 0),
(7, 0, 0, 0, 'Mr. Mime', 40, 45, 65, 90, 100, 120, 161, 0, 0, 0, 0, 0, 2),
(9, 0, 0, 1, 'Darkrai', 70, 90, 90, 125, 135, 90, 270, 0, 0, 0, 1, 2, 0),
(10, 0, 0, 0, 'Flareon', 65, 130, 60, 65, 95, 110, 184, 0, 2, 0, 0, 0, 0),
(11, 0, 0, 0, 'Ivysaur', 60, 62, 63, 60, 80, 80, 142, 0, 0, 0, 0, 1, 1),
(12, 0, 0, 0, 'Charmeleon', 58, 64, 58, 80, 80, 65, 142, 0, 0, 0, 1, 1, 0);

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
(1, 13),
(2, 1),
(2, 14),
(3, 1),
(3, 3),
(7, 2),
(10, 10),
(11, 1),
(12, 1),
(12, 14);

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
(2, 3),
(2, 5),
(2, 6),
(2, 13),
(2, 14),
(2, 15),
(3, 1),
(3, 4),
(3, 5),
(3, 6),
(3, 7),
(3, 9),
(4, 2),
(4, 16),
(4, 17),
(4, 18),
(5, 1),
(5, 2),
(5, 5),
(5, 6),
(5, 10),
(5, 11),
(5, 12),
(21, 2),
(21, 3),
(21, 4),
(21, 5),
(21, 11),
(21, 12),
(22, 1),
(22, 2),
(22, 3),
(22, 5),
(22, 7),
(22, 13),
(22, 14),
(22, 15),
(23, 1),
(23, 2),
(23, 3),
(23, 4),
(23, 5),
(23, 6),
(23, 7),
(23, 8),
(23, 9),
(24, 3),
(24, 4),
(24, 5),
(24, 6),
(24, 7),
(25, 1),
(25, 5),
(25, 8),
(25, 10),
(25, 11),
(25, 12),
(62, 19),
(62, 20),
(62, 21),
(62, 22),
(62, 23),
(63, 24),
(63, 25),
(63, 26),
(65, 16),
(65, 17),
(65, 18),
(67, 19),
(67, 20),
(67, 21),
(67, 22),
(67, 23),
(69, 19),
(69, 20),
(69, 21),
(69, 22),
(69, 23),
(71, 1),
(71, 3),
(71, 4),
(71, 13),
(71, 14),
(72, 16),
(73, 1),
(73, 10),
(73, 11),
(73, 12),
(75, 1),
(75, 6),
(75, 16),
(75, 18),
(76, 1),
(76, 7),
(76, 10),
(76, 11),
(76, 12),
(77, 1),
(77, 5),
(77, 6),
(77, 10),
(77, 11),
(77, 12),
(78, 1),
(78, 2),
(78, 6),
(78, 10),
(78, 11),
(78, 12),
(81, 1),
(81, 2),
(81, 11),
(81, 12),
(93, 13),
(93, 14),
(93, 15),
(94, 19),
(94, 20),
(94, 21),
(94, 22),
(94, 23),
(95, 10),
(95, 11),
(95, 12),
(96, 13),
(96, 14),
(96, 15),
(97, 19),
(97, 20),
(97, 21),
(97, 22),
(97, 23),
(99, 19),
(99, 20),
(99, 21),
(99, 22),
(99, 23),
(100, 1),
(101, 13),
(101, 14),
(101, 15),
(104, 13),
(104, 14),
(104, 15),
(113, 10),
(113, 11),
(113, 12),
(114, 16),
(114, 17),
(114, 18),
(115, 10),
(115, 11),
(115, 12),
(116, 16),
(116, 17),
(116, 18),
(117, 10),
(117, 11),
(117, 12),
(118, 1),
(118, 11),
(119, 16),
(119, 17),
(119, 18),
(120, 13),
(120, 14),
(120, 15),
(121, 16),
(121, 17),
(121, 18),
(122, 13),
(122, 14),
(122, 15),
(123, 16),
(123, 17),
(123, 18),
(124, 13),
(124, 14),
(124, 15);

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
(2, 2),
(2, 10),
(2, 12),
(3, 3),
(5, 1),
(5, 11),
(8, 1),
(8, 11),
(11, 7),
(16, 9);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `pkmn_pokemon_img`
--

INSERT INTO `pkmn_pokemon_img` (`id`, `id_pokemon`, `type`, `image`) VALUES
(1, 1, 0, 'bulbasaur_0.jpg'),
(2, 2, 0, 'charmander_0.gif'),
(3, 3, 0, 'squirtle_0.png'),
(4, 7, 0, 'Amber_Mr._Mime_0.png'),
(6, 9, 0, 'Ancient_Darkrai_0.png'),
(7, 10, 0, 'Berry_Flareon_0.png'),
(8, 11, 0, 'Ivysaur_0.png'),
(9, 12, 0, 'charmeleon_0.png');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

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
(10, 8, NULL, 'Fire Stone', 0, NULL);

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
  PRIMARY KEY (`id`),
  KEY `fk_pkmn_trainer_users1_idx` (`id_user`),
  KEY `fk_pkmn_trainer_pkmn_faction1_idx` (`id_faction`),
  KEY `fk_pkmn_trainer_pkmn_gym1_idx` (`id_gym`),
  KEY `fk_pkmn_trainer_pkmn_trainer_type1_idx` (`id_type`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=29 ;

--
-- Dumping data for table `pkmn_trainer`
--

INSERT INTO `pkmn_trainer` (`id`, `id_user`, `id_faction`, `id_gym`, `id_type`, `visible`, `order_index`, `silver`, `gold`, `faction_pts`, `name`, `victories`, `defeats`) VALUES
(1, 1, 1, NULL, NULL, NULL, NULL, 105, 110, 37701, NULL, 6, 15),
(2, 2, NULL, NULL, NULL, NULL, NULL, 100, 100, 2000, NULL, 1, 0),
(4, NULL, NULL, 1, 1, 1, NULL, NULL, NULL, 10002, 'Brock', 3, 6),
(5, 45, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, 0, 0),
(6, 46, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, 0, 0),
(7, 47, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, 0, 0),
(8, 48, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, 0, 0),
(9, 49, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, 0, 0),
(10, 50, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, 0, 0),
(11, 51, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, 0, 0),
(12, 52, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, 0, 0),
(13, 53, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, 0, 0),
(14, NULL, 2, NULL, 1, 0, NULL, NULL, NULL, 10000, 'Forest', 10, 0),
(15, 54, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 0),
(16, 55, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 0),
(17, 56, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 0),
(18, 57, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 0),
(19, 58, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 0),
(20, 59, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, 0, 0),
(21, NULL, NULL, NULL, 2, 1, NULL, NULL, NULL, 2000, 'Forest Tower', 1, 0),
(22, NULL, NULL, NULL, 2, 1, NULL, NULL, NULL, 0, 'WhirlPool tower', 0, 0),
(23, NULL, NULL, NULL, 2, 1, NULL, NULL, NULL, 0, 'Inferno Tower', 0, 0),
(24, NULL, NULL, NULL, 2, 1, NULL, NULL, NULL, 0, 'Baby Tower', 0, 0),
(25, 60, NULL, NULL, 1, NULL, NULL, 0, 0, 0, NULL, NULL, NULL),
(26, 61, NULL, NULL, 1, NULL, NULL, 0, 0, 0, NULL, NULL, NULL),
(27, 62, NULL, NULL, 1, NULL, NULL, 0, 0, 0, NULL, NULL, NULL),
(28, 63, NULL, NULL, 1, NULL, NULL, 0, 0, 0, NULL, NULL, NULL);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `pkmn_trainer_item`
--

INSERT INTO `pkmn_trainer_item` (`id`, `id_trainer`, `id_item`) VALUES
(3, 1, 4),
(4, 1, 6),
(5, 1, 6),
(6, 1, 9);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=125 ;

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
(93, 1, 11, 0, 48460, 1, 0, 0, 'M', 122, 122, 68, 69, 66, 86, 86, 0, 0),
(94, 27, 9, NULL, 512, 1, 0, 0, '?', 50, 50, 8, 8, 8, 6, 6, 0, 0),
(95, 27, 10, NULL, 1728, 0, 0, 0, 'M', 46, 46, 8, 8, 8, 7, 7, 0, 0),
(96, 27, 11, NULL, 4913, 0, 0, 0, 'F', 707, 707, 19, 19, 19, 19, 19, 0, 0),
(97, 27, 9, NULL, 2197, 0, 0, 0, '?', 75, 75, 10, 10, 10, 6, 6, 0, 0),
(99, 1, 9, 0, 1000, 0, 0, 0, '?', 38, 38, 27, 27, 34, 37, 27, 0, 0),
(100, 1, 2, 0, 4096, 0, 0, 0, 'F', 59, 59, 37, 32, 43, 41, 36, 0, 0),
(101, 1, 11, 0, 8, 0, 0, 0, 'M', 17, 17, 9, 9, 9, 10, 10, 0, 0),
(104, 1, 11, NULL, 8, 0, 0, 0, 'M', 17, 17, 10, 10, 10, 11, 11, 0, 0),
(113, 1, 2, NULL, 125, 0, 0, 0, 'M', 19, 19, 11, 10, 12, 12, 11, 0, 0),
(114, 1, 3, NULL, 512, 0, 0, 0, 'F', 26, 26, 13, 16, 13, 14, 16, 0, 0),
(115, 1, 10, NULL, 216, 0, 0, 0, 'F', 25, 25, 21, 13, 14, 17, 19, 0, 0),
(116, 1, 3, NULL, 2744, 0, 0, 0, 'F', 37, 37, 19, 24, 18, 20, 24, 0, 0),
(117, 1, 12, NULL, 1000, 0, 0, 0, 'F', 33, 33, 19, 18, 22, 22, 19, 0, 0),
(118, 1, 1, 0, 1331, 1, 0, 0, 'F', 24, 4, 17, 17, 17, 20, 20, 0, 0),
(119, 1, 3, NULL, 1, 0, 0, 0, 'M', 12, 12, 6, 6, 5, 6, 6, 0, 0),
(120, 1, 1, NULL, 125, 0, 0, 0, 'F', 20, 20, 11, 11, 10, 12, 12, 0, 0),
(121, 1, 3, NULL, 729, 0, 0, 0, 'F', 29, 29, 16, 19, 15, 16, 19, 0, 0),
(122, 1, 1, 0, 512, 1, 0, 0, 'F', 25, 25, 13, 13, 12, 16, 16, 0, 0),
(123, 1, 3, NULL, 1331, 0, 0, 0, 'F', 32, 32, 16, 20, 15, 17, 20, 0, 0),
(124, 1, 1, NULL, 1728, 0, 0, 0, 'F', 36, 36, 20, 20, 19, 23, 23, 0, 0);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=64 ;

--
-- Dumping data for table `system_user`
--

INSERT INTO `system_user` (`id`, `last_updated`, `banned`, `disabled`, `username`, `password`, `tmp_password`, `mail`, `gender`) VALUES
(1, '2015-05-16 16:05:39', 0, 0, 'admin', 'c7ad44cbad762a5da0a452f9e854fdc1e0e7a52a38015f23f3eab1d80b931dd472634dfac71cd34ebc35d16ab7fb8a90c81f975113d6c7538dc69dd8de9077ec', NULL, 'sclm256@hotmail.com', 'M'),
(2, NULL, 0, 0, 'testuser1', 'ddaf35a193617abacc417349ae20413112e6fa4e89a97ea20a9eeee64b55d39a2192992a274fc1a836ba3c23a3feebbd454d4423643ce80e2a9ac94fa54ca49f', '11e04f77c72739b07f2bad4b221dcca8cdf364810ee9e0f78362f545f6bbdf2a50ce5998baf6aa90482b4dcc0478d4214704a3dc4f5b8f736320dbe2d652f097', 'sclm256@gmail.com', 'F'),
(45, NULL, 0, 0, 'testuser2', '765d7ebe356fe7350c41180cfcce7dd9079ee70dc9c85a33aa7089344f8230da6ebed5829c448e546943397d848da424a69c01b4651660fc8f683297e7b4c37f', '2a18b6cfef64a7f1c699caca57602d3bd30ab02c168b608b33b31212ad07d51244938d64a1f32f1b9b493c0821e646e8f5a3d091a38e5278fca2561a62c21a13', 'sclm256@hotmail.com', 'M'),
(46, NULL, 0, 0, 'testuser3', '3ae434bfbeefee4596cdb342a5254f6513e1f7923853ebdef7ed0c327b10746a61390292120d4009eb152f8883b868e43bd94f113b7bc0d78a9725238a30cc70', NULL, 'testuser3@hotmail.com', 'M'),
(47, NULL, 0, 0, 'testuser4', '452862d7dff3732b73c0115700c606a77a0d23d177daa852596babe58a70abe1f6bb33226c97ffdfcbc6e14152e44d8570b827f695ad4974156c4b196094dd54', NULL, 'testuser4@hotmail.com', 'M'),
(48, NULL, 0, 0, 'testuser5', 'e87f02263edde8c6dfdc26dfafba668a6dd773647aa9110ab798d4b88ace3452812ba28285f3c38362d207711d9d5327f20e276404a71ee09a78d1319ab80fe5', NULL, 'testuser5@hotmail.com', 'M'),
(49, NULL, 0, 0, 'testuser6', '7228de58046185c37d0748d7b9940e23d89cca85e3b993a2a2f986fa3f9f5ece048f37107da490095369b0479f95b86d6d20a0da4cb8cdc5db3f0d2084c3685c', NULL, 'testuser6@hotmail.com', 'M'),
(50, NULL, 0, 0, 'testuser7', 'ed305bb45a9cbf131c892406c770ac8282e88a27a895da4e0844130a62361a4f2a4bdd1f43d0d5d4ba4bca3bd4596592c3bae348c3060359fc7c6a514406dff6', NULL, 'testuser7@hotmail.com', 'M'),
(51, NULL, 0, 0, 'testuser8', '1360c5dba33ba933717e96096abcb856d22f1f491b68497b6456a7dbecf74aabf4035fa7a90ea3ba732c74c8c2b952b827843156d284e58c28225c2e83b11689', NULL, 'testuser8@hotmail.com', 'M'),
(52, NULL, 0, 0, 'testuser9', '50f68d932b5f1ce51b5e82a258c567eba2034b1c8e654d6968baa0487726614b5795518c6f8a74d52bd67530c996ed23eb04776676c9681546813d191969c0b1', NULL, 'testuser9@hotmail.com', 'M'),
(53, NULL, 0, 0, 'testuser10', '6f934f65fd7dcd81e564b0e60f9951f67bf5ae868c7ac14527882c2ae66d1adc3a52ad5146209e4479d5472a97d4a009872e036c5c5b489bffe80c10009e2135', NULL, 'testuser10@hotmail.com', 'M'),
(54, NULL, 0, 0, 'reguser1', '460f03b048b35fd365af0f8957937f0022dfd9e2a5dd2108ab1cf3c5e7671c174c0e8f857aa3ad1881b3ff91963f51616a21f4e9f67a4d578361d014d9d1c11f', NULL, 'reguser1@hotmail.com', 'M'),
(55, NULL, 0, 0, 'reguser2', '1480fb00fd2390b66a81d3e6021be970755527ad84a6135df9c22890ecd047c618bdf4c7de292316754fdc6412a5593633cdce9d5a5e9a2dffc4065c400f4506', NULL, 'reguser2@hotmail.com', 'M'),
(56, NULL, 0, 0, 'regtest3', 'c660e299ff23079af47c08379f6814f0592eaa3e9cfa35443168d499a1a264537b846aa46e77c8faade6f9bf345a5608d53f45964b1133bc9b6650426c5d123e', NULL, 'regtest3@hotmail.com', 'M'),
(57, NULL, 0, 0, 'regtest4', '04598df02f441ade38d6bf1bd85a02d7231c921dcd180a7b11ab73a8eb0d39805e93b12b9e7aee9b3aab9211d512a4a9ae6109c6cc8daa2ded8936b75a42d349', NULL, 'regtest4@hotmail.com', 'M'),
(58, NULL, 0, 0, 'regtest5', '5d644bc0b5f548b745f01156cf919ac8c08318da7dfe6227ba03a02365e16a50dee959237784613de84ab4cfa776ec64d6496f9793bdd62163a7d47b1e506d3d', NULL, 'regtest5@hotmail.com', 'M'),
(59, NULL, 0, 0, 'regtest6', '994c5d3d7e68bcdf26e7e217c9a8938a17cf0ba2555879dc1a8c77d45b1187f3b2f723926c542f7784ee94a6c3f60abf2130c6c9bb589ab4416656cff038dd26', NULL, 'regtest6@hotmail.com', 'M'),
(60, NULL, 0, 0, 'xtestuser1', '5cada6fa8e005077b76d544cfbe855f9669296f4402d0cb03e8269bcfc4ab2a685c3ec355941a9e0b7d223657e0ed6d3dff8d59401b52f774ae442ff021e14f8', NULL, 'sclm256@hotmail.com', 'M'),
(61, NULL, 0, 0, 'testuser', 'ee26b0dd4af7e749aa1a8ee3c10ae9923f618980772e473f8819a5d4940e0db27ac185f8a0e1d5f84f88bc887fd67b143732c304cc5fa9ad8e6f57f50028a8ff', NULL, 'test1234@hotmail.com', 'M'),
(62, NULL, 0, 0, 'xtestuser2', 'fccd71649e0799a0b177ff1127ea0c4baa10a3e75772a30df7681afa3f763f663feb58510cb47a9b54eb498efade690ba1e77ef145bb25418d42525f28bb8717', NULL, 'sclm256@hotmail.com', 'M'),
(63, NULL, 0, 0, 'xtestuser3', '377b38b51ef22fc9014894ad9c5c035db176bd489dccdf998618535c0cfa279350b17f8d4375d3331f7aca3e07dcb7b7766a33f0e3df869865adf524eb1331c6', NULL, 'sclm256@hotmail.com', 'F');

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
(1, 3),
(2, 2),
(2, 3),
(45, 2),
(45, 3),
(49, 2),
(49, 3),
(50, 2),
(50, 3),
(51, 3),
(52, 3),
(53, 3),
(60, 3),
(61, 3),
(62, 3),
(63, 3);

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
