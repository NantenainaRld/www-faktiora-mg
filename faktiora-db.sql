-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : dim. 07 déc. 2025 à 09:19
-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `faktiora-db`
--

-- --------------------------------------------------------

--
-- Structure de la table `article`
--

CREATE TABLE `article` (
  `id_article` bigint(20) UNSIGNED NOT NULL,
  `libelle_article` varchar(100) NOT NULL,
  `etat_article` enum('actif','supprimé') NOT NULL DEFAULT 'actif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `article`
--

INSERT INTO `article` (`id_article`, `libelle_article`, `etat_article`) VALUES
(1, 'capsul', 'actif'),
(4, 'tavoangy', 'actif'),
(5, 'correction/S202511-12', 'actif'),
(10, 'correction/A202511-17 - ajout de remboursement', 'actif'),
(11, 'correction/S202511-21 - achat de table', 'actif'),
(14, 'correction/F202511-6 - /produit 3 : -1 /produit 1 : -1 ', 'actif');

-- --------------------------------------------------------

--
-- Structure de la table `autre_entree`
--

CREATE TABLE `autre_entree` (
  `id_ae` bigint(20) UNSIGNED NOT NULL COMMENT 'Autree Entree ID',
  `num_ae` varchar(20) DEFAULT NULL,
  `libelle_ae` varchar(100) NOT NULL COMMENT 'Entree libelle',
  `date_ae` datetime NOT NULL COMMENT 'Entree date',
  `montant_ae` decimal(20,2) UNSIGNED NOT NULL DEFAULT 0.00 COMMENT 'Entree amount',
  `etat_ae` enum('actif','supprimé') NOT NULL DEFAULT 'actif',
  `id_utilisateur` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'Utilisateur foreign key',
  `num_caisse` int(11) UNSIGNED DEFAULT NULL COMMENT 'Caisse foreign key'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `autre_entree`
--

INSERT INTO `autre_entree` (`id_ae`, `num_ae`, `libelle_ae`, `date_ae`, `montant_ae`, `etat_ae`, `id_utilisateur`, `num_caisse`) VALUES
(1, 'za', 'divay', '2025-10-08 09:40:38', 0.00, 'supprimé', NULL, 1),
(7, 'A202511-7', 'apport de caisse', '2025-11-07 15:02:00', 1.00, 'actif', 10004, 1),
(8, 'A202511-8', 'apport de caisse', '2025-11-07 15:02:00', 1.00, 'actif', 10004, 1),
(14, 'SS', 'correction/S202511-20', '2025-11-21 10:11:00', 500.00, 'actif', 10004, 2),
(15, 'A202511-15', 'mofo', '2025-11-25 10:13:10', 15000.00, 'actif', 10003, 2),
(16, 'A202511-16', 'mofo', '2025-11-25 10:18:31', 15000.00, 'actif', 10003, 2),
(17, 'A202511-17', 'mofo', '2025-11-14 10:19:00', 15000.00, 'actif', 10004, 2),
(20, 'A202511-20', 'correction/A202511-17 - libelle', '2025-11-14 12:42:00', 122.00, 'actif', 10004, 2),
(21, 'A202511-21', 'correction/S202511-22 - achat de bouteille', '2025-11-14 09:35:00', 5000.00, 'actif', 10004, 2);

-- --------------------------------------------------------

--
-- Structure de la table `caisse`
--

CREATE TABLE `caisse` (
  `num_caisse` int(11) UNSIGNED NOT NULL COMMENT 'Caisse ID',
  `solde` decimal(20,2) UNSIGNED NOT NULL DEFAULT 0.00 COMMENT 'Caisse initial fund',
  `seuil` decimal(20,2) UNSIGNED NOT NULL DEFAULT 0.00 COMMENT 'Caisse recent fund',
  `etat_caisse` enum('libre','supprimé','occupé') NOT NULL DEFAULT 'libre'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `caisse`
--

INSERT INTO `caisse` (`num_caisse`, `solde`, `seuil`, `etat_caisse`) VALUES
(1, 1875.00, 1000.00, 'libre'),
(2, 16985.00, 10000.00, 'libre');

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

CREATE TABLE `client` (
  `id_client` bigint(20) UNSIGNED NOT NULL COMMENT 'Client ID',
  `nom_client` varchar(100) NOT NULL COMMENT 'Client name',
  `prenoms_client` varchar(100) DEFAULT NULL COMMENT 'CLient first name',
  `sexe_client` enum('masculin','féminin') NOT NULL DEFAULT 'masculin' COMMENT 'Client sex',
  `telephone` varchar(20) DEFAULT NULL COMMENT 'CLient phone number',
  `adresse` varchar(100) DEFAULT NULL COMMENT 'Client address',
  `etat_client` enum('actif','supprimé') NOT NULL DEFAULT 'actif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `client`
--

INSERT INTO `client` (`id_client`, `nom_client`, `prenoms_client`, `sexe_client`, `telephone`, `adresse`, `etat_client`) VALUES
(10000, 'RALANDISON ', 'Nantenaina', 'masculin', '+261 32 83 294 40', 'Fianarantsoa', 'actif');

-- --------------------------------------------------------

--
-- Structure de la table `demande_sortie`
--

CREATE TABLE `demande_sortie` (
  `id_ds` bigint(20) UNSIGNED NOT NULL,
  `num_ds` varchar(20) DEFAULT NULL,
  `date_ds` datetime NOT NULL,
  `etat_ds` enum('actif','supprimé') NOT NULL DEFAULT 'actif',
  `id_utilisateur` bigint(20) UNSIGNED DEFAULT NULL,
  `num_caisse` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `demande_sortie`
--

INSERT INTO `demande_sortie` (`id_ds`, `num_ds`, `date_ds`, `etat_ds`, `id_utilisateur`, `num_caisse`) VALUES
(16, 'S202511-16', '2025-11-13 12:52:00', 'actif', 10004, 1),
(17, 'S202511-17', '2025-11-21 12:54:00', 'actif', 10004, 1),
(18, 'S202511-18', '2025-11-07 12:55:00', 'actif', 10004, 1),
(19, 'S202511-19', '2025-11-08 13:00:00', 'actif', 10004, 1),
(20, 'S202511-20', '2025-11-08 13:00:00', 'actif', 10004, 1),
(21, 'S202511-21', '2025-11-20 13:02:00', 'actif', 10004, 2),
(22, 'S202511-22', '2025-11-29 08:43:05', 'actif', 10003, 2),
(24, 'S202512-24', '2025-12-01 12:09:00', 'actif', 10004, 1);

-- --------------------------------------------------------

--
-- Structure de la table `facture`
--

CREATE TABLE `facture` (
  `num_facture` varchar(20) DEFAULT NULL COMMENT 'Facture ID',
  `id_facture` bigint(20) UNSIGNED NOT NULL,
  `date_facture` datetime NOT NULL COMMENT 'Facture date',
  `etat_facture` enum('actif','supprimé') NOT NULL DEFAULT 'actif',
  `num_caisse` int(11) UNSIGNED DEFAULT NULL COMMENT 'Caisse foreign key',
  `id_utilisateur` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'Utilisateur foreign key',
  `id_client` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'CLient foreign key'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `facture`
--

INSERT INTO `facture` (`num_facture`, `id_facture`, `date_facture`, `etat_facture`, `num_caisse`, `id_utilisateur`, `id_client`) VALUES
('F202511-2', 2, '2025-11-08 17:35:00', 'actif', 1, 10004, 10000),
('F202511-3', 3, '2025-11-27 17:37:00', 'actif', 2, 10004, 10000),
('F202511-6', 6, '2025-11-15 17:59:00', 'actif', 1, 10004, 10000);

-- --------------------------------------------------------

--
-- Structure de la table `ligne_caisse`
--

CREATE TABLE `ligne_caisse` (
  `id_lc` int(11) UNSIGNED NOT NULL,
  `date_debut` datetime NOT NULL,
  `date_fin` datetime DEFAULT NULL,
  `id_utilisateur` bigint(20) UNSIGNED DEFAULT NULL,
  `num_caisse` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `ligne_caisse`
--

INSERT INTO `ligne_caisse` (`id_lc`, `date_debut`, `date_fin`, `id_utilisateur`, `num_caisse`) VALUES
(3, '2025-11-21 11:00:00', '2025-11-21 19:15:31', 10004, 1),
(5, '2025-11-20 11:32:00', '2025-11-21 19:15:31', 10004, 1),
(13, '2025-11-21 16:35:00', NULL, NULL, 2),
(15, '2025-11-21 16:36:00', '2025-11-21 19:15:31', 10004, 1),
(16, '2025-11-21 16:39:00', '2025-11-21 19:15:31', 10004, 1),
(17, '2025-11-21 16:40:00', '2025-11-21 19:16:10', 10003, 1),
(19, '2025-11-21 18:16:41', '2025-11-21 19:15:31', 10004, 2),
(20, '2025-11-21 18:18:02', '2025-11-21 19:15:31', 10004, 2),
(21, '2025-11-21 18:20:13', '2025-11-21 19:15:31', 10003, 2),
(85, '2025-11-22 17:43:00', NULL, 10004, 2);

-- --------------------------------------------------------

--
-- Structure de la table `ligne_ds`
--

CREATE TABLE `ligne_ds` (
  `id_lds` int(11) UNSIGNED NOT NULL,
  `prix_article` decimal(20,2) UNSIGNED NOT NULL,
  `quantite_article` int(11) UNSIGNED NOT NULL DEFAULT 1,
  `id_ds` bigint(20) UNSIGNED DEFAULT NULL,
  `id_article` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `ligne_ds`
--

INSERT INTO `ligne_ds` (`id_lds`, `prix_article`, `quantite_article`, `id_ds`, `id_article`) VALUES
(1, 15000.00, 2, 20, 1),
(2, 2500.00, 2, NULL, 4),
(3, 1500.00, 2, 24, 1),
(4, 2500.00, 2, NULL, 4),
(5, 1500.00, 2, NULL, 5),
(6, 15.00, 1, 21, 5),
(7, 15000.00, 2, 22, 10),
(8, 15.00, 1, 22, 11),
(9, 15125.00, 1, 24, 14);

-- --------------------------------------------------------

--
-- Structure de la table `ligne_facture`
--

CREATE TABLE `ligne_facture` (
  `id_lf` int(11) UNSIGNED NOT NULL COMMENT 'Ligne facture ID',
  `prix` decimal(15,2) NOT NULL DEFAULT 1.00,
  `quantite_produit` int(11) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Ligne facture amount product',
  `id_facture` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'Facture fk',
  `id_produit` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'Produit fk'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `ligne_facture`
--

INSERT INTO `ligne_facture` (`id_lf`, `prix`, `quantite_produit`, `id_facture`, `id_produit`) VALUES
(1, 125.00, 4, 2, 1),
(6, 15000.54, 2, 2, 3),
(7, 15000.00, 32, 6, 1);

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE `produit` (
  `id_produit` bigint(20) UNSIGNED NOT NULL COMMENT 'Produit ID',
  `libelle_produit` varchar(100) NOT NULL COMMENT 'Produit libelle',
  `prix_produit` decimal(15,2) NOT NULL DEFAULT 0.00 COMMENT 'Produit unit price',
  `nb_stock` int(11) NOT NULL DEFAULT 0 COMMENT 'Produit stock amount',
  `etat_produit` enum('actif','supprimé') NOT NULL DEFAULT 'actif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`id_produit`, `libelle_produit`, `prix_produit`, `nb_stock`, `etat_produit`) VALUES
(1, 'capsule', 15000.00, 19, 'actif'),
(3, 'tavoangy', 15000.00, 31, 'actif');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id_utilisateur` bigint(20) UNSIGNED NOT NULL COMMENT 'User ID',
  `nom_utilisateur` varchar(100) NOT NULL COMMENT 'User name',
  `prenoms_utilisateur` varchar(100) DEFAULT NULL COMMENT 'User firstname',
  `sexe_utilisateur` enum('masculin','féminin') NOT NULL DEFAULT 'masculin' COMMENT 'Utilisateur sex',
  `email_utilisateur` varchar(150) NOT NULL COMMENT 'User email',
  `role` enum('admin','caissier') NOT NULL DEFAULT 'caissier' COMMENT 'User role',
  `mdp` varchar(200) NOT NULL COMMENT 'User password',
  `mdp_oublie` varchar(200) DEFAULT NULL COMMENT 'User forgot password',
  `mdp_oublie_expire` datetime DEFAULT '1700-01-01 00:00:00',
  `etat_utilisateur` enum('supprimé','connecté','déconnecté') NOT NULL DEFAULT 'déconnecté',
  `dernier_session` datetime DEFAULT '1700-01-01 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id_utilisateur`, `nom_utilisateur`, `prenoms_utilisateur`, `sexe_utilisateur`, `email_utilisateur`, `role`, `mdp`, `mdp_oublie`, `mdp_oublie_expire`, `etat_utilisateur`, `dernier_session`) VALUES
(10003, 'admin', '', 'féminin', 'admin@faktiora.mg', 'admin', '$2y$10$bhnhxtvzSpEpjDA30DPX9OA0GzaKeo/kom1/Aboywo/nqgwiBvEdS', NULL, '1700-01-01 00:00:00', 'connecté', '1700-01-01 00:00:00'),
(10004, 'Nantenaina', 'Edouardo', 'masculin', 'sssss', 'caissier', 'sssss', NULL, '1700-01-01 00:00:00', 'déconnecté', '1700-01-01 00:00:00'),
(10005, 'jbj', '', 'féminin', 'jbj', 'caissier', 'jnkjnj', NULL, '1700-01-01 00:00:00', 'supprimé', '1700-01-01 00:00:00'),
(10008, 'admin', ' ', 'masculin', 'admin@faktiora.mg', 'admin', '$2y$10$4si6xxTp1Ztf7TxP9D9d9OPkuzOLe.1QxF2z0hVE0cpWPMPjzZGaK', NULL, '1700-01-01 00:00:00', 'connecté', '1700-01-01 00:00:00'),
(10010, 'Nantenaina', 'Edouardo', 'masculin', 'sssss', 'admin', 'sssss', NULL, '1700-01-01 00:00:00', 'déconnecté', '1700-01-01 00:00:00'),
(10012, 'admin', '', 'masculin', 'admin@faktiora.mg', 'caissier', '$2y$10$3awSWpsnY1Ng4dn3fmREhOMy.p09jEL3WhzqUaXbxPYRVXOdQnon6', NULL, '1700-01-01 00:00:00', 'déconnecté', '1700-01-01 00:00:00'),
(10013, 'Nantenaina', 'Edouardo', 'masculin', 'sssss', 'admin', 'sssss', NULL, '1700-01-01 00:00:00', 'déconnecté', '1700-01-01 00:00:00'),
(10014, 'admin', ' ', 'masculin', 'admin@faktiora.mg', 'admin', '$2y$10$4si6xxTp1Ztf7TxP9D9d9OPkuzOLe.1QxF2z0hVE0cpWPMPjzZGaK', NULL, '1700-01-01 00:00:00', 'connecté', '1700-01-01 00:00:00'),
(10015, 'admin', ' ', 'masculin', 'admin@faktiora.mg', 'admin', '$2y$10$4si6xxTp1Ztf7TxP9D9d9OPkuzOLe.1QxF2z0hVE0cpWPMPjzZGaK', NULL, '1700-01-01 00:00:00', 'connecté', '1700-01-01 00:00:00'),
(10016, 'Nantenaina', 'Edouardo', 'masculin', 'sssss', 'admin', 'sssss', NULL, '1700-01-01 00:00:00', 'déconnecté', '1700-01-01 00:00:00'),
(10017, 'admin', '', 'féminin', 'admin@faktiora.mg', 'caissier', '$2y$10$bhnhxtvzSpEpjDA30DPX9OA0GzaKeo/kom1/Aboywo/nqgwiBvEdS', NULL, '1700-01-01 00:00:00', 'déconnecté', '1700-01-01 00:00:00'),
(10019, 'admin', '', 'masculin', 'admin@faktiora.mg', 'caissier', '$2y$10$3awSWpsnY1Ng4dn3fmREhOMy.p09jEL3WhzqUaXbxPYRVXOdQnon6', NULL, '1700-01-01 00:00:00', 'déconnecté', '1700-01-01 00:00:00'),
(10020, 'admin', '', 'féminin', 'admin@faktiora.mg', 'caissier', '$2y$10$bhnhxtvzSpEpjDA30DPX9OA0GzaKeo/kom1/Aboywo/nqgwiBvEdS', NULL, '1700-01-01 00:00:00', 'déconnecté', '1700-01-01 00:00:00'),
(10021, 'jbj', '', 'féminin', 'jbj', 'caissier', 'jnkjnj', NULL, '1700-01-01 00:00:00', 'supprimé', '1700-01-01 00:00:00'),
(10022, 'admin', '', 'masculin', 'admin@faktiora.mg', 'caissier', '$2y$10$3awSWpsnY1Ng4dn3fmREhOMy.p09jEL3WhzqUaXbxPYRVXOdQnon6', NULL, '1700-01-01 00:00:00', 'déconnecté', '1700-01-01 00:00:00'),
(10023, 'admin', ' ', 'masculin', 'admin@faktiora.mg', 'admin', '$2y$10$4si6xxTp1Ztf7TxP9D9d9OPkuzOLe.1QxF2z0hVE0cpWPMPjzZGaK', NULL, '1700-01-01 00:00:00', 'connecté', '1700-01-01 00:00:00'),
(10024, 'Nantenaina', 'Edouardo', 'masculin', 'sssss', 'admin', 'sssss', NULL, '1700-01-01 00:00:00', 'déconnecté', '1700-01-01 00:00:00'),
(10027, 'Nantenaina', 'Edouardo', 'masculin', 'sssss', 'admin', 'sssss', NULL, '1700-01-01 00:00:00', 'déconnecté', '1700-01-01 00:00:00'),
(10028, 'admin', '', 'féminin', 'admin@faktiora.mg', 'caissier', '$2y$10$bhnhxtvzSpEpjDA30DPX9OA0GzaKeo/kom1/Aboywo/nqgwiBvEdS', NULL, '1700-01-01 00:00:00', 'déconnecté', '1700-01-01 00:00:00'),
(10029, 'jbj', '', 'féminin', 'jbj', 'caissier', 'jnkjnj', NULL, '1700-01-01 00:00:00', 'supprimé', '1700-01-01 00:00:00'),
(10030, 'admin', '', 'féminin', 'admin@faktiora.mg', 'caissier', '$2y$10$bhnhxtvzSpEpjDA30DPX9OA0GzaKeo/kom1/Aboywo/nqgwiBvEdS', NULL, '1700-01-01 00:00:00', 'déconnecté', '1700-01-01 00:00:00'),
(10031, 'admin', '', 'masculin', 'admin@faktiora.mg', 'caissier', '$2y$10$3awSWpsnY1Ng4dn3fmREhOMy.p09jEL3WhzqUaXbxPYRVXOdQnon6', NULL, '1700-01-01 00:00:00', 'déconnecté', '1700-01-01 00:00:00'),
(10032, 'admin', '', 'masculin', 'admin@faktiora.mg', 'caissier', '$2y$10$3awSWpsnY1Ng4dn3fmREhOMy.p09jEL3WhzqUaXbxPYRVXOdQnon6', NULL, '1700-01-01 00:00:00', 'déconnecté', '1700-01-01 00:00:00'),
(10033, 'admin', '', 'féminin', 'admin@faktiora.mg', 'caissier', '$2y$10$bhnhxtvzSpEpjDA30DPX9OA0GzaKeo/kom1/Aboywo/nqgwiBvEdS', NULL, '1700-01-01 00:00:00', 'déconnecté', '1700-01-01 00:00:00'),
(10034, 'EDOUARDO', 'Noelly', 'masculin', 'nantenaina@gmail.com', 'caissier', '$2y$10$BThBmPH8MZI.9a8HcBAAZO4E05TkuKQeVkVOBxt3yo1UatbiJJBCm', NULL, '1700-01-01 00:00:00', 'connecté', '1700-01-01 00:00:00'),
(10038, 'EDOUARDO', 'Noelly', 'masculin', 'nantenainarlda@gmail.com', 'caissier', '$2y$10$nkN5PUB7Q7LAXboK/3DcG.IrrXHNQ96a/aXMnRM.yVmJYqcYuuN7G', NULL, '1700-01-01 00:00:00', 'déconnecté', '1700-01-01 00:00:00'),
(10039, 'TEST', 'Nantenaina', 'féminin', 'nantenainarld@gmail.com', 'caissier', '$2y$10$Bu36OGLY7K/Yj2/VCFbUZ.Xy6Ki2oeu90ATgdQ6GpvYGbQLI1DH7C', NULL, '1700-01-01 00:00:00', 'déconnecté', '1700-01-01 00:00:00');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `article`
--
ALTER TABLE `article`
  ADD PRIMARY KEY (`id_article`);

--
-- Index pour la table `autre_entree`
--
ALTER TABLE `autre_entree`
  ADD PRIMARY KEY (`id_ae`),
  ADD KEY `fk_user_ae` (`id_utilisateur`),
  ADD KEY `fk_caisse_ae` (`num_caisse`);

--
-- Index pour la table `caisse`
--
ALTER TABLE `caisse`
  ADD PRIMARY KEY (`num_caisse`);

--
-- Index pour la table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`id_client`),
  ADD KEY `nom_client` (`nom_client`);

--
-- Index pour la table `demande_sortie`
--
ALTER TABLE `demande_sortie`
  ADD PRIMARY KEY (`id_ds`),
  ADD KEY `num_caisse` (`num_caisse`) USING BTREE,
  ADD KEY `id_utilisateur` (`id_utilisateur`) USING BTREE;

--
-- Index pour la table `facture`
--
ALTER TABLE `facture`
  ADD PRIMARY KEY (`id_facture`),
  ADD KEY `fk_user_facture` (`id_utilisateur`),
  ADD KEY `fk_caisse_facture` (`num_caisse`),
  ADD KEY `fk_client_facture` (`id_client`);

--
-- Index pour la table `ligne_caisse`
--
ALTER TABLE `ligne_caisse`
  ADD PRIMARY KEY (`id_lc`),
  ADD KEY `fk_user_lc` (`id_utilisateur`),
  ADD KEY `fk_caisse_lc` (`num_caisse`);

--
-- Index pour la table `ligne_ds`
--
ALTER TABLE `ligne_ds`
  ADD PRIMARY KEY (`id_lds`),
  ADD KEY `fk_article_lds` (`id_article`),
  ADD KEY `fk_ds_lds` (`id_ds`);

--
-- Index pour la table `ligne_facture`
--
ALTER TABLE `ligne_facture`
  ADD PRIMARY KEY (`id_lf`),
  ADD KEY `fk_facture_lf` (`id_facture`),
  ADD KEY `fk_produit_lf` (`id_produit`);

--
-- Index pour la table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`id_produit`),
  ADD KEY `libelle_produit` (`libelle_produit`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id_utilisateur`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `article`
--
ALTER TABLE `article`
  MODIFY `id_article` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `autre_entree`
--
ALTER TABLE `autre_entree`
  MODIFY `id_ae` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Autree Entree ID', AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT pour la table `caisse`
--
ALTER TABLE `caisse`
  MODIFY `num_caisse` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Caisse ID', AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `client`
--
ALTER TABLE `client`
  MODIFY `id_client` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Client ID', AUTO_INCREMENT=10003;

--
-- AUTO_INCREMENT pour la table `demande_sortie`
--
ALTER TABLE `demande_sortie`
  MODIFY `id_ds` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT pour la table `facture`
--
ALTER TABLE `facture`
  MODIFY `id_facture` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `ligne_caisse`
--
ALTER TABLE `ligne_caisse`
  MODIFY `id_lc` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT pour la table `ligne_ds`
--
ALTER TABLE `ligne_ds`
  MODIFY `id_lds` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `ligne_facture`
--
ALTER TABLE `ligne_facture`
  MODIFY `id_lf` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Ligne facture ID', AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `produit`
--
ALTER TABLE `produit`
  MODIFY `id_produit` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Produit ID', AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id_utilisateur` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'User ID', AUTO_INCREMENT=10040;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `autre_entree`
--
ALTER TABLE `autre_entree`
  ADD CONSTRAINT `fk_caisse_ae` FOREIGN KEY (`num_caisse`) REFERENCES `caisse` (`num_caisse`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_ae` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `demande_sortie`
--
ALTER TABLE `demande_sortie`
  ADD CONSTRAINT `fk_caisse_ds` FOREIGN KEY (`num_caisse`) REFERENCES `caisse` (`num_caisse`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_ds` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `facture`
--
ALTER TABLE `facture`
  ADD CONSTRAINT `fk_caisse_facture` FOREIGN KEY (`num_caisse`) REFERENCES `caisse` (`num_caisse`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_client_facture` FOREIGN KEY (`id_client`) REFERENCES `client` (`id_client`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_facture` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `ligne_caisse`
--
ALTER TABLE `ligne_caisse`
  ADD CONSTRAINT `fk_caisse_lc` FOREIGN KEY (`num_caisse`) REFERENCES `caisse` (`num_caisse`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_lc` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `ligne_ds`
--
ALTER TABLE `ligne_ds`
  ADD CONSTRAINT `fk_article_lds` FOREIGN KEY (`id_article`) REFERENCES `article` (`id_article`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ds_lds` FOREIGN KEY (`id_ds`) REFERENCES `demande_sortie` (`id_ds`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `ligne_facture`
--
ALTER TABLE `ligne_facture`
  ADD CONSTRAINT `fk_facture_lf` FOREIGN KEY (`id_facture`) REFERENCES `facture` (`id_facture`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_produit_lf` FOREIGN KEY (`id_produit`) REFERENCES `produit` (`id_produit`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
