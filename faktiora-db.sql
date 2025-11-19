-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 19, 2025 at 04:10 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `faktiora-db`
--

-- --------------------------------------------------------

--
-- Table structure for table `article`
--

CREATE TABLE `article` (
  `id_article` bigint(20) UNSIGNED NOT NULL,
  `libelle_article` varchar(100) NOT NULL,
  `etat_article` enum('actif','supprimé') NOT NULL DEFAULT 'actif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `autre_entree`
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

-- --------------------------------------------------------

--
-- Table structure for table `caisse`
--

CREATE TABLE `caisse` (
  `num_caisse` int(11) UNSIGNED NOT NULL COMMENT 'Caisse ID',
  `solde` decimal(20,2) UNSIGNED NOT NULL DEFAULT 0.00 COMMENT 'Caisse initial fund',
  `seuil` decimal(20,2) UNSIGNED NOT NULL DEFAULT 0.00 COMMENT 'Caisse recent fund',
  `etat_caisse` enum('libre','supprimé','occupé') NOT NULL DEFAULT 'libre'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

CREATE TABLE `client` (
  `id_client` bigint(20) UNSIGNED NOT NULL COMMENT 'Client ID',
  `nom_client` varchar(100) NOT NULL COMMENT 'Client name',
  `prenoms_client` varchar(100) DEFAULT NULL COMMENT 'CLient first name',
  `sexe_client` enum('masculin','féminin') NOT NULL DEFAULT 'masculin' COMMENT 'Client sex',
  `telephone` varchar(15) DEFAULT NULL COMMENT 'CLient phone number',
  `adresse` varchar(100) DEFAULT NULL COMMENT 'Client address',
  `etat_client` enum('actif','supprimé') NOT NULL DEFAULT 'actif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `demande_sortie`
--

CREATE TABLE `demande_sortie` (
  `id_ds` bigint(20) UNSIGNED NOT NULL,
  `num_ds` varchar(20) DEFAULT NULL,
  `date_ds` datetime NOT NULL,
  `etat_ds` enum('actif','supprimé') NOT NULL DEFAULT 'actif',
  `id_utilisateur` bigint(20) UNSIGNED DEFAULT NULL,
  `num_caisse` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `facture`
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

-- --------------------------------------------------------

--
-- Table structure for table `ligne_caisse`
--

CREATE TABLE `ligne_caisse` (
  `id_lc` int(11) UNSIGNED NOT NULL,
  `date_debut` datetime NOT NULL,
  `date_fin` datetime DEFAULT NULL,
  `id_utilisateur` bigint(20) UNSIGNED DEFAULT NULL,
  `num_caisse` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ligne_ds`
--

CREATE TABLE `ligne_ds` (
  `id_lds` int(11) UNSIGNED NOT NULL,
  `prix_article` decimal(20,2) UNSIGNED NOT NULL,
  `quantite_article` int(11) UNSIGNED NOT NULL DEFAULT 1,
  `id_ds` bigint(20) UNSIGNED DEFAULT NULL,
  `id_article` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ligne_facture`
--

CREATE TABLE `ligne_facture` (
  `id_lf` int(11) UNSIGNED NOT NULL COMMENT 'Ligne facture ID',
  `quantite_produit` int(11) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Ligne facture amount product',
  `id_facture` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'Facture fk',
  `id_produit` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'Produit fk'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `produit`
--

CREATE TABLE `produit` (
  `id_produit` bigint(20) UNSIGNED NOT NULL COMMENT 'Produit ID',
  `libelle_produit` varchar(100) NOT NULL COMMENT 'Produit libelle',
  `prix_produit` decimal(15,2) NOT NULL DEFAULT 0.00 COMMENT 'Produit unit price',
  `nb_stock` int(11) NOT NULL DEFAULT 0 COMMENT 'Produit stock amount',
  `etat_produit` enum('actif','supprimé') NOT NULL DEFAULT 'actif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `utilisateur`
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
  `etat_utilisateur` enum('supprimé','connecté','déconnecté') NOT NULL DEFAULT 'déconnecté',
  `dernier_session` datetime DEFAULT '1700-01-01 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `article`
--
ALTER TABLE `article`
  ADD PRIMARY KEY (`id_article`);

--
-- Indexes for table `autre_entree`
--
ALTER TABLE `autre_entree`
  ADD PRIMARY KEY (`id_ae`),
  ADD KEY `fk_user_ae` (`id_utilisateur`),
  ADD KEY `fk_caisse_ae` (`num_caisse`);

--
-- Indexes for table `caisse`
--
ALTER TABLE `caisse`
  ADD PRIMARY KEY (`num_caisse`);

--
-- Indexes for table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`id_client`),
  ADD KEY `nom_client` (`nom_client`);

--
-- Indexes for table `demande_sortie`
--
ALTER TABLE `demande_sortie`
  ADD PRIMARY KEY (`id_ds`),
  ADD UNIQUE KEY `id_utilisateur` (`id_utilisateur`),
  ADD UNIQUE KEY `num_caisse` (`num_caisse`);

--
-- Indexes for table `facture`
--
ALTER TABLE `facture`
  ADD PRIMARY KEY (`id_facture`),
  ADD KEY `fk_user_facture` (`id_utilisateur`),
  ADD KEY `fk_caisse_facture` (`num_caisse`),
  ADD KEY `fk_client_facture` (`id_client`);

--
-- Indexes for table `ligne_caisse`
--
ALTER TABLE `ligne_caisse`
  ADD PRIMARY KEY (`id_lc`),
  ADD KEY `fk_user_lc` (`id_utilisateur`),
  ADD KEY `fk_caisse_lc` (`num_caisse`);

--
-- Indexes for table `ligne_ds`
--
ALTER TABLE `ligne_ds`
  ADD PRIMARY KEY (`id_lds`),
  ADD KEY `fk_article_lds` (`id_article`),
  ADD KEY `fk_ds_lds` (`id_ds`);

--
-- Indexes for table `ligne_facture`
--
ALTER TABLE `ligne_facture`
  ADD PRIMARY KEY (`id_lf`),
  ADD KEY `fk_facture_lf` (`id_facture`),
  ADD KEY `fk_produit_lf` (`id_produit`);

--
-- Indexes for table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`id_produit`),
  ADD KEY `libelle_produit` (`libelle_produit`);

--
-- Indexes for table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id_utilisateur`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `article`
--
ALTER TABLE `article`
  MODIFY `id_article` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `autre_entree`
--
ALTER TABLE `autre_entree`
  MODIFY `id_ae` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Autree Entree ID';

--
-- AUTO_INCREMENT for table `caisse`
--
ALTER TABLE `caisse`
  MODIFY `num_caisse` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Caisse ID';

--
-- AUTO_INCREMENT for table `client`
--
ALTER TABLE `client`
  MODIFY `id_client` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Client ID', AUTO_INCREMENT=10000;

--
-- AUTO_INCREMENT for table `demande_sortie`
--
ALTER TABLE `demande_sortie`
  MODIFY `id_ds` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `facture`
--
ALTER TABLE `facture`
  MODIFY `id_facture` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ligne_caisse`
--
ALTER TABLE `ligne_caisse`
  MODIFY `id_lc` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ligne_ds`
--
ALTER TABLE `ligne_ds`
  MODIFY `id_lds` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ligne_facture`
--
ALTER TABLE `ligne_facture`
  MODIFY `id_lf` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Ligne facture ID';

--
-- AUTO_INCREMENT for table `produit`
--
ALTER TABLE `produit`
  MODIFY `id_produit` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Produit ID';

--
-- AUTO_INCREMENT for table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id_utilisateur` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'User ID', AUTO_INCREMENT=10000;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `autre_entree`
--
ALTER TABLE `autre_entree`
  ADD CONSTRAINT `fk_caisse_ae` FOREIGN KEY (`num_caisse`) REFERENCES `caisse` (`num_caisse`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_ae` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `demande_sortie`
--
ALTER TABLE `demande_sortie`
  ADD CONSTRAINT `fk_caisse_ds` FOREIGN KEY (`num_caisse`) REFERENCES `caisse` (`num_caisse`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_ds` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `facture`
--
ALTER TABLE `facture`
  ADD CONSTRAINT `fk_caisse_facture` FOREIGN KEY (`num_caisse`) REFERENCES `caisse` (`num_caisse`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_client_facture` FOREIGN KEY (`id_client`) REFERENCES `client` (`id_client`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_facture` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `ligne_caisse`
--
ALTER TABLE `ligne_caisse`
  ADD CONSTRAINT `fk_caisse_lc` FOREIGN KEY (`num_caisse`) REFERENCES `caisse` (`num_caisse`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_lc` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `ligne_ds`
--
ALTER TABLE `ligne_ds`
  ADD CONSTRAINT `fk_article_lds` FOREIGN KEY (`id_article`) REFERENCES `article` (`id_article`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ds_lds` FOREIGN KEY (`id_ds`) REFERENCES `demande_sortie` (`id_ds`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `ligne_facture`
--
ALTER TABLE `ligne_facture`
  ADD CONSTRAINT `fk_facture_lf` FOREIGN KEY (`id_facture`) REFERENCES `facture` (`id_facture`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_produit_lf` FOREIGN KEY (`id_produit`) REFERENCES `produit` (`id_produit`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
