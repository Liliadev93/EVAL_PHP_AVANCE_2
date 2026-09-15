-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 15 sep. 2026 à 21:32
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `bdd_eval_php_avance_2`
--

-- --------------------------------------------------------

--
-- Structure de la table `meteo`
--

CREATE TABLE `meteo` (
  `id` int(11) NOT NULL,
  `date_meteo` date DEFAULT NULL,
  `ville` varchar(100) DEFAULT NULL,
  `periode` varchar(50) DEFAULT NULL,
  `resume` varchar(50) DEFAULT NULL,
  `id_resume` int(11) DEFAULT NULL,
  `Temp_min` int(11) DEFAULT NULL,
  `Temp_max` int(11) DEFAULT NULL,
  `commentaire` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `meteo`
--

INSERT INTO `meteo` (`id`, `date_meteo`, `ville`, `periode`, `resume`, `id_resume`, `Temp_min`, `Temp_max`, `commentaire`) VALUES
(1, '2100-12-05', 'Paris', 'matin', 'ensoleillé', 3, 5, 8, 'Temps ensoleillé mais frais'),
(2, '2100-12-05', 'Paris', 'après-midi', 'pluvieux', 1, 9, 11, 'Une pluie fine et attendue'),
(3, '2100-12-05', 'Paris', 'nuit', 'nuageux', 4, 9, 11, 'Temps couverts'),
(4, '2100-12-06', 'Paris', 'matin', 'brumeux', 1, 12, 13, 'Temps brumeux'),
(5, '2100-12-06', 'Paris', 'après-midi', 'ensoleillé', 1, 14, 15, 'Temps ensoleillé'),
(6, '2100-12-06', 'Paris', 'nuit', 'nuageux', 4, 9, 11, 'Temps nuageux'),
(7, '2100-12-07', 'Paris', 'matin', 'brumeux', 1, 12, 13, 'Temps brumeux'),
(8, '2100-12-07', 'Paris', 'après-midi', 'ensoleillé', 2, 14, 15, 'Temps ensoleillé'),
(9, '2100-12-07', 'Paris', 'nuit', 'nuageux', 4, 9, 11, 'Temps nuageux');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `meteo`
--
ALTER TABLE `meteo`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `meteo`
--
ALTER TABLE `meteo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
