-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 02 oct. 2024 à 16:52
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
-- Base de données : `speedcubing`
--

-- --------------------------------------------------------

--
-- Structure de la table `chronos`
--

CREATE TABLE `chronos` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `cube_id` varchar(11) NOT NULL,
  `chrono_time` time(6) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `meilleurs_temps` time(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `chronos`
--

INSERT INTO `chronos` (`id`, `user_id`, `cube_id`, `chrono_time`, `created_at`, `meilleurs_temps`) VALUES
(34, 3, 'Gan-11-M-Pr', '00:00:00.920000', '2024-10-02 14:26:28', '00:00:00.000000'),
(35, 3, 'Gan-11-M-Pr', '00:00:00.000000', '2024-10-02 14:37:44', '00:00:00.000000'),
(36, 3, 'Gan-11-M-Pr', '00:00:00.000000', '2024-10-02 14:37:52', '00:00:00.000000'),
(37, 3, 'Gan-11-M-Pr', '00:00:00.410000', '2024-10-02 14:40:34', '00:00:00.000000');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `chronos`
--
ALTER TABLE `chronos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `chronos`
--
ALTER TABLE `chronos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `chronos`
--
ALTER TABLE `chronos`
  ADD CONSTRAINT `chronos_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
