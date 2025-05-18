-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : dim. 18 mai 2025 à 14:15
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
-- Base de données : `ifmapci_devis_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

DROP TABLE IF EXISTS `client`;
CREATE TABLE IF NOT EXISTS `client` (
  `id_client` int NOT NULL AUTO_INCREMENT,
  `code_client` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `nom_client` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `localisation_client` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `commune_client` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `bp_client` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `pays_client` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `date_creat_client` int NOT NULL,
  `telephone_client` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `logo_client` text COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id_client`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Déchargement des données de la table `client`
--

INSERT INTO `client` (`id_client`, `code_client`, `nom_client`, `localisation_client`, `commune_client`, `bp_client`, `pays_client`, `date_creat_client`, `telephone_client`, `logo_client`) VALUES
(1, 'Est et sunt distinc', 'Corporis iure dolore', 'In suscipit pariatur', 'Eligendi nemo qui vo', 'Incididunt quis nobi', 'Ipsam consectetur au', 2006, '+1 (413) 132-9195', 'logo_6829eb5611d20.png');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
