-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : dim. 18 mai 2025 à 12:55
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `ifmap_ci_devis_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `role_devis`
--

DROP TABLE IF EXISTS `role_devis`;
CREATE TABLE IF NOT EXISTS `role_devis` (
  `id_role_devis` int NOT NULL AUTO_INCREMENT,
  `lib_role_devis` text COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id_role_devis`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Déchargement des données de la table `role_devis`
--

INSERT INTO `role_devis` (`id_role_devis`, `lib_role_devis`) VALUES
(1, 'commercial'),
(2, 'directeur commercial'),
(3, 'directeur general'),
(4, 'informaticien');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
