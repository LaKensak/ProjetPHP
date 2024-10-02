-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 02 oct. 2024 à 16:53
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

-- --------------------------------------------------------

--
-- Structure de la table `cubes`
--

CREATE TABLE `cubes` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `cubes`
--

INSERT INTO `cubes` (`id`, `name`) VALUES
(1, 'Gan 11 M Pro'),
(2, 'MoYu Weilong WR M 2020'),
(3, 'Gan 356 XS'),
(4, 'QiYi Valk 3 Elite M'),
(5, 'MoYu WeiLong GTS3 M'),
(6, 'QiYi MS Magnetic'),
(7, 'Angstrom Valk 3 Elite M'),
(8, 'YongJun YuLong V2 M'),
(9, 'QiYi Warrior S stickerless');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `token` varchar(64) DEFAULT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `email`, `username`, `password`, `created_at`, `token`, `role`) VALUES
(1, 'rayane.charkaoui2@gmail.com', 'Kensaku', '$2y$10$h0rlukVrLg9v4UqaQCdX8OPdg7ujR6f5F02.3l2KsRKa8N1lZM75G', '2024-09-26 08:50:49', 'dc6ec1f752badbce48e239fb8a7728298050a61844a7dcf7417496a7e21e2be3', 'user'),
(2, 'rayane.charkaoui6@gmail.com', 'Kensaku2T', '$2y$10$Uue/COkXHcme.t0NXRUxC.GTSyvq4HzmnYkICA8zbYIbfp/3sAg7y', '2024-10-01 14:07:28', 'f46997aebc41aa6015c18e1c45660829ee48e109afdbe69fbfaab424e7fed9d1', 'user'),
(3, 'rayane.charkaoui@gmail.com', 'admin', '$2y$10$BZmW27WV01D4rLXg9exLBe9P2V/r1FkymQAo148oPluIsDVrrKlFe', '2024-10-02 12:26:32', 'b0aad1119ef093c842a0b35a6f672f599eb7f33bcf74ff05c68f49deb32f46ae', 'admin');

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
-- Index pour la table `cubes`
--
ALTER TABLE `cubes`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `chronos`
--
ALTER TABLE `chronos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT pour la table `cubes`
--
ALTER TABLE `cubes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
