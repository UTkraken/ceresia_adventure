-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 18, 2022 at 09:51 AM
-- Server version: 10.6.5-MariaDB
-- PHP Version: 8.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ceresia_adventure`
--

-- --------------------------------------------------------

--
-- Table structure for table `enigmas`
--

DROP TABLE IF EXISTS `enigmas`;
CREATE TABLE IF NOT EXISTS `enigmas` (
  `enigma_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `question` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `difficulty` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `estimated_time` int(11) NOT NULL,
  `trail_id` int(11) NOT NULL,
  `hint` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`enigma_id`),
  KEY `FK_enigmas_trails` (`trail_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `enigmas`
--

INSERT INTO `enigmas` (`enigma_id`, `name`, `question`, `answer`, `difficulty`, `estimated_time`, `trail_id`, `hint`) VALUES
(5, 'Question culinaire', 'Combien de fougasses peut on généralement manger en une seule session ?', '18', '5', 10, 8, 'Entre 17 et 19'),
(4, 'Mer', 'De quelle couleur est la mer ?', 'bleu', '1', 5, 8, 'Plutôt bleu'),
(6, 'Région', 'Dans quel département se trouve cette randonnée ?', '73', '4', 5, 9, 'La savoie (en chiffre)'),
(7, 'Visite de La Garde', 'Dans quelle ville se situe cette randonnée ?', 'la garde', '1', 5, 10, 'Se compose de deux mots');

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

DROP TABLE IF EXISTS `ratings`;
CREATE TABLE IF NOT EXISTS `ratings` (
  `rating_id` int(11) NOT NULL AUTO_INCREMENT,
  `rating` int(11) DEFAULT NULL,
  `trail_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`rating_id`),
  KEY `trail_id` (`trail_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ratings`
--

INSERT INTO `ratings` (`rating_id`, `rating`, `trail_id`, `user_id`) VALUES
(1, 4, 9, 1),
(3, 1, 9, 9),
(4, 2, 8, 9),
(5, 4, 9, 10),
(6, 0, 8, 10),
(7, 5, 10, 1);

-- --------------------------------------------------------

--
-- Table structure for table `trails`
--

DROP TABLE IF EXISTS `trails`;
CREATE TABLE IF NOT EXISTS `trails` (
  `trail_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `departement` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `estimated_time` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `description` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT 1,
  `date_start` datetime DEFAULT '1990-01-01 00:00:00',
  `date_end` datetime DEFAULT '1990-01-01 00:00:00',
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`trail_id`),
  KEY `FK_trails_users` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `trails`
--

INSERT INTO `trails` (`trail_id`, `name`, `departement`, `estimated_time`, `level`, `description`, `visible`, `date_start`, `date_end`, `user_id`) VALUES
(9, 'Randonnée champêtre', '73', 30, 1, 'Visite du bois vidal à Aix les bains', 1, '1990-01-01 00:00:00', '1990-01-01 00:00:00', 2),
(8, 'Balade du barde', '83', 125, 2, 'Visite des côtés de Porquerolles', 1, '1990-01-01 00:00:00', '1990-01-01 00:00:00', 2),
(10, 'Centre ville de La Garde', '83', 15, 1, 'Exploration du centre ville historique', 1, '1990-01-01 00:00:00', '1990-01-01 00:00:00', 2);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_type_id` int(11) NOT NULL,
  `departement` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`) USING HASH,
  KEY `FK_users_users_types` (`user_type_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `pseudo`, `email`, `password`, `user_type_id`, `departement`) VALUES
(1, 'Joueur', 'joueur@gmail.com', '$2y$10$OoySlgoEp5nlx85/OmIrXehgghiyrITv29IUBzpEuB1E6sgztEeLW', 1, '83'),
(2, 'creator', 'createur@gmail.com', '$2y$10$aj.d9hHkK.ImLOCH9Ixi6Og5C4xXNLKbWQhmWukjmqQVuPmy/WxUi', 2, '83'),
(3, 'Admin', 'admin@gmail.com', '$2y$10$YimIdIK.OKKYVfBlHDmfyuUJcx.6VuDQ6eu/b5kvM3CKLhZOPM4tS', 3, '83'),
(9, 'noteur', 'not@gmail.com', '$2y$10$4U9u97CalUQCPhbQi10Wm.SusKMCLHjTmbwIEmI4D2MEb/8PLfQrm', 1, '85'),
(10, 'Thomas', 'thomas@gmail.com', '$2y$10$BzEEb3m36FQ6KIxuPyuu1eob0sZ90UWeKw0EHosMOQH.Ew7XZbLzy', 1, '74');

-- --------------------------------------------------------

--
-- Table structure for table `users_types`
--

DROP TABLE IF EXISTS `users_types`;
CREATE TABLE IF NOT EXISTS `users_types` (
  `user_type_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`user_type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users_types`
--

INSERT INTO `users_types` (`user_type_id`, `name`) VALUES
(1, 'Joueur'),
(2, 'Créateur'),
(3, 'Admin');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
