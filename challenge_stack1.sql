-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 03 fév. 2023 à 11:31
-- Version du serveur : 8.0.27
-- Version de PHP : 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `challenge_stack1`
--

-- --------------------------------------------------------

--
-- Structure de la table `action`
--

DROP TABLE IF EXISTS `action`;
CREATE TABLE IF NOT EXISTS `action` (
  `id` int NOT NULL AUTO_INCREMENT,
  `libelle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `max_user` int DEFAULT NULL,
  `type_id` int NOT NULL,
  `responsible_id` int NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_47CC8C92C54C8C93` (`type_id`),
  KEY `IDX_47CC8C92602AD315` (`responsible_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `action`
--

INSERT INTO `action` (`id`, `libelle`, `description`, `date`, `location`, `max_user`, `type_id`, `responsible_id`, `image`) VALUES
(1, 'Match de foot Grenoble - Saint-Étienne', 'Match amical Grenoble - Saint-Étienne', '2023-02-04 21:00:00', 'Grenoble', 6, 2, 2, 'MjAyMjEwYjMzYThiZjRkOTk5MTg2MTlhMmE2Y2Y2MjEyZGM2ZjI-63dce4092a0ac.jpg'),
(2, 'Rencontre avec les joueurs du FCG', 'Rencontre avec les joueurs de rugby de Grenoble au stade des Alpes.', '2023-02-03 21:00:00', 'Grenoble', 15, 1, 1, '870x489-i1emwtt-63dce466f136b.jpg'),
(3, 'Découverte/Initialisation au handball', 'Venez vous initier au handball', '2023-02-12 18:00:00', 'Echirolles', 4, 3, 2, '128706-63dce59cf083b.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `action_type`
--

DROP TABLE IF EXISTS `action_type`;
CREATE TABLE IF NOT EXISTS `action_type` (
  `id` int NOT NULL AUTO_INCREMENT,
  `libelle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `action_type`
--

INSERT INTO `action_type` (`id`, `libelle`, `description`) VALUES
(1, 'Rencontre', 'Rencontre à propos d\'un sport'),
(2, 'Match', 'Match d\'un sport'),
(3, 'Découverte/Initiation', 'Découverte d\'un sport');

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20230109090219', '2023-02-02 14:51:25', 70),
('DoctrineMigrations\\Version20230109092400', '2023-02-02 14:51:25', 109),
('DoctrineMigrations\\Version20230109093246', '2023-02-02 14:51:25', 174),
('DoctrineMigrations\\Version20230109112432', '2023-02-02 14:51:26', 8),
('DoctrineMigrations\\Version20230130140644', '2023-02-02 14:51:26', 9),
('DoctrineMigrations\\Version20230131094406', '2023-02-02 14:51:26', 61),
('DoctrineMigrations\\Version20230202082539', '2023-02-02 14:51:26', 102),
('DoctrineMigrations\\Version20230202092156', '2023-02-02 14:51:26', 30),
('DoctrineMigrations\\Version20230202101215', '2023-02-02 14:51:26', 12);

-- --------------------------------------------------------

--
-- Structure de la table `messenger_messages`
--

DROP TABLE IF EXISTS `messenger_messages`;
CREATE TABLE IF NOT EXISTS `messenger_messages` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `available_at` datetime NOT NULL,
  `delivered_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `reset_password_request`
--

DROP TABLE IF EXISTS `reset_password_request`;
CREATE TABLE IF NOT EXISTS `reset_password_request` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `selector` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hashed_token` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `requested_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `expires_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_7CE748AA76ED395` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ressource`
--

DROP TABLE IF EXISTS `ressource`;
CREATE TABLE IF NOT EXISTS `ressource` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `action_id` int NOT NULL,
  `is_valid` tinyint(1) NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lien` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_939F4544A76ED395` (`user_id`),
  KEY `IDX_939F45449D32F035` (`action_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `ressource`
--

INSERT INTO `ressource` (`id`, `user_id`, `action_id`, `is_valid`, `nom`, `lien`) VALUES
(4, 4, 1, 1, 'qsd', 'sinistre-declaration-63dc1d788bfcf.png'),
(5, 3, 2, 1, 'aze', 'tutassur-mini-charte-63dc1e926e9e7.pdf'),
(7, 2, 1, 1, 'image2', 'infos-user-63dcd25e935aa.jpg'),
(8, 4, 1, 1, 'pmpm', 'resiliation-63dcde295b7ae.jpg'),
(9, 2, 1, 1, 'pdf', 'tutassur-mini-charte-63dcec8d80aef.pdf');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `firstname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_verified` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `firstname`, `lastname`, `is_verified`) VALUES
(1, 'asport@yopmail.com', '[\"ROLE_MANAGER\"]', '$2y$13$0XgjUgnVZ.Hol1aE6bcuI.UhziDId5G2eFNVIzMhLpTL.tctZb3x2', 'albert', 'einstein', 1),
(2, 'b@t.com', '[\"ROLE_BENEVOLE\"]', '$2y$13$mk.UKTgmvBeydfpISsAb9eUa47T5Gr2HVxL4y..Ezk4RQLEGpUfwG', 'bastienne', 'arphant', 1),
(3, 'c@t.com', '[\"ROLE_SUPER_ADMIN\"]', '$2y$13$P1gcjBY/dKoQN3B5T91Rq.ctLS0UE4mAVXZflyFSZrBKXdQBeunY.', 'clement', 'courtier', 0),
(4, 'k.d@yopmail.com', '[\"ROLE_ACTION_ADMIN\"]', '$2y$13$zMAfxnij/DPp0kQt/Qbrx.cTvQnnAnDt805MSyiIVI1bvgSZ6oQwi', 'Kevin', 'Dupont', 1),
(8, 'pierre.du@yopmail.com', '[\"ROLE_USER\"]', '$2y$13$kkowP4VfG80Q7JmyMkJS5OIKEOCkPNldmQqC1fLq/jHk2muYimTFW', 'Pierre', 'Dupont', 0);

-- --------------------------------------------------------

--
-- Structure de la table `user_action`
--

DROP TABLE IF EXISTS `user_action`;
CREATE TABLE IF NOT EXISTS `user_action` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `action_id` int NOT NULL,
  `status` smallint NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_229E97AFA76ED395` (`user_id`),
  KEY `IDX_229E97AF9D32F035` (`action_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user_action`
--

INSERT INTO `user_action` (`id`, `user_id`, `action_id`, `status`) VALUES
(1, 2, 1, 1),
(2, 1, 1, 2),
(7, 4, 2, 2),
(9, 4, 1, 1),
(12, 2, 2, 2);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `action`
--
ALTER TABLE `action`
  ADD CONSTRAINT `FK_47CC8C92602AD315` FOREIGN KEY (`responsible_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_47CC8C92C54C8C93` FOREIGN KEY (`type_id`) REFERENCES `action_type` (`id`);

--
-- Contraintes pour la table `reset_password_request`
--
ALTER TABLE `reset_password_request`
  ADD CONSTRAINT `FK_7CE748AA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `ressource`
--
ALTER TABLE `ressource`
  ADD CONSTRAINT `FK_939F45449D32F035` FOREIGN KEY (`action_id`) REFERENCES `action` (`id`),
  ADD CONSTRAINT `FK_939F4544A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `user_action`
--
ALTER TABLE `user_action`
  ADD CONSTRAINT `FK_229E97AF9D32F035` FOREIGN KEY (`action_id`) REFERENCES `action` (`id`),
  ADD CONSTRAINT `FK_229E97AFA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
