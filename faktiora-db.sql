-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 16, 2025 at 05:47 PM
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
  `id_article` varchar(15) NOT NULL,
  `libelle_article` varchar(100) NOT NULL,
  `etat_article` enum('actif','supprimé') NOT NULL DEFAULT 'actif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `autre_entree`
--

CREATE TABLE `autre_entree` (
  `id_ae` varchar(20) NOT NULL COMMENT 'Autree Entree ID',
  `libelle_ae` varchar(100) NOT NULL COMMENT 'Entree libelle',
  `date_ae` datetime NOT NULL COMMENT 'Entree date',
  `montant_ae` decimal(15,2) NOT NULL DEFAULT 0.00 COMMENT 'Entree amount',
  `etat_ae` enum('actif','supprimé') NOT NULL DEFAULT 'actif',
  `id_utilisateur` varchar(15) DEFAULT NULL COMMENT 'Utilisateur foreign key',
  `num_caisse` int(11) DEFAULT NULL COMMENT 'Caisse foreign key'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `autre_entree`
--

INSERT INTO `autre_entree` (`id_ae`, `libelle_ae`, `date_ae`, `montant_ae`, `etat_ae`, `id_utilisateur`, `num_caisse`) VALUES
('1', 'xx', '2025-11-10 03:17:13', 0.00, 'actif', NULL, NULL),
('xxx', 'xx', '2015-11-25 03:17:13', 550.00, 'actif', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `caisse`
--

CREATE TABLE `caisse` (
  `num_caisse` int(11) NOT NULL COMMENT 'Caisse ID',
  `solde` decimal(15,2) NOT NULL DEFAULT 0.00 COMMENT 'Caisse initial fund',
  `seuil` decimal(15,2) NOT NULL DEFAULT 0.00 COMMENT 'Caisse recent fund',
  `etat_caisse` enum('libre','supprimé','occupé') NOT NULL DEFAULT 'libre'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `caisse`
--

INSERT INTO `caisse` (`num_caisse`, `solde`, `seuil`, `etat_caisse`) VALUES
(3, 0.00, 0.00, 'occupé'),
(4, 0.00, 0.00, 'supprimé'),
(5, 15000.00, 10000.00, 'libre'),
(7, 15000.00, 10000.00, 'libre');

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

CREATE TABLE `client` (
  `id_client` varchar(15) NOT NULL COMMENT 'Client ID',
  `nom_client` varchar(100) NOT NULL COMMENT 'Client name',
  `prenoms_client` varchar(100) DEFAULT NULL COMMENT 'CLient first name',
  `sexe_client` enum('masculin','féminin') NOT NULL COMMENT 'Client sex',
  `telephone` varchar(15) DEFAULT NULL COMMENT 'CLient phone number',
  `adresse` varchar(100) DEFAULT NULL COMMENT 'Client address',
  `etat_client` enum('actif','supprimé') NOT NULL DEFAULT 'actif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `demande_sortie`
--

CREATE TABLE `demande_sortie` (
  `id_ds` varchar(20) NOT NULL,
  `date_ds` datetime NOT NULL,
  `etat_ds` enum('actif','supprimé') NOT NULL DEFAULT 'actif',
  `id_utilisateur` varchar(15) DEFAULT NULL,
  `num_caisse` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `facture`
--

CREATE TABLE `facture` (
  `num_facture` varchar(20) NOT NULL COMMENT 'Facture ID',
  `date_facture` datetime NOT NULL COMMENT 'Facture date',
  `etat_facture` enum('actif','supprimé') NOT NULL DEFAULT 'actif',
  `num_caisse` int(11) DEFAULT NULL COMMENT 'Caisse foreign key',
  `id_utilisateur` varchar(15) DEFAULT NULL COMMENT 'Utilisateur foreign key',
  `id_client` varchar(15) DEFAULT NULL COMMENT 'CLient foreign key'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `facture`
--

INSERT INTO `facture` (`num_facture`, `date_facture`, `etat_facture`, `num_caisse`, `id_utilisateur`, `id_client`) VALUES
('test', '2025-11-16 06:29:48', 'actif', 7, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ligne_caisse`
--

CREATE TABLE `ligne_caisse` (
  `id_lc` int(11) NOT NULL,
  `date_debut` datetime NOT NULL,
  `date_fin` datetime DEFAULT NULL,
  `id_utilisateur` varchar(15) DEFAULT NULL,
  `num_caisse` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ligne_caisse`
--

INSERT INTO `ligne_caisse` (`id_lc`, `date_debut`, `date_fin`, `id_utilisateur`, `num_caisse`) VALUES
(2, '2025-11-10 13:25:01', '2025-11-19 08:51:22', 'U123278VW', 7),
(4, '2025-11-10 10:25:47', NULL, 'U123278VW', 3),
(5, '2025-11-10 10:25:47', '2025-11-12 07:40:53', '000000', 7);

-- --------------------------------------------------------

--
-- Table structure for table `ligne_ds`
--

CREATE TABLE `ligne_ds` (
  `id_lds` int(11) NOT NULL,
  `prix_article` decimal(20,2) NOT NULL,
  `quantite_article` int(11) NOT NULL DEFAULT 1,
  `id_ds` varchar(20) DEFAULT NULL,
  `id_article` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ligne_facture`
--

CREATE TABLE `ligne_facture` (
  `id_lf` int(11) NOT NULL COMMENT 'Ligne facture ID',
  `quantite_produit` int(11) NOT NULL DEFAULT 1 COMMENT 'Ligne facture amount product',
  `num_facture` varchar(20) DEFAULT NULL COMMENT 'Facture fk',
  `id_produit` varchar(15) DEFAULT NULL COMMENT 'Produit fk'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `produit`
--

CREATE TABLE `produit` (
  `id_produit` varchar(15) NOT NULL COMMENT 'Produit ID',
  `libelle_produit` varchar(100) NOT NULL COMMENT 'Produit libelle',
  `prix_produit` decimal(15,2) NOT NULL DEFAULT 0.00 COMMENT 'Produit unit price',
  `nb_stock` int(11) NOT NULL COMMENT 'Produit stock amount',
  `etat_produit` enum('actif','supprimé') NOT NULL DEFAULT 'actif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id_utilisateur` varchar(15) NOT NULL COMMENT 'User ID',
  `nom_utilisateur` varchar(100) NOT NULL COMMENT 'User name',
  `prenoms_utilisateur` varchar(100) DEFAULT NULL COMMENT 'User firstname',
  `sexe_utilisateur` enum('masculin','féminin') NOT NULL COMMENT 'Utilisateur sex',
  `email_utilisateur` varchar(150) NOT NULL COMMENT 'User email',
  `role` enum('admin','caissier') NOT NULL DEFAULT 'caissier' COMMENT 'User role',
  `mdp` varchar(200) NOT NULL COMMENT 'User password',
  `mdp_oublie` varchar(200) DEFAULT NULL COMMENT 'User forgot password',
  `etat_utilisateur` enum('supprimé','connecté','déconnecté') NOT NULL DEFAULT 'déconnecté',
  `dernier_session` datetime DEFAULT '1700-01-01 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `utilisateur`
--

INSERT INTO `utilisateur` (`id_utilisateur`, `nom_utilisateur`, `prenoms_utilisateur`, `sexe_utilisateur`, `email_utilisateur`, `role`, `mdp`, `mdp_oublie`, `etat_utilisateur`, `dernier_session`) VALUES
('000000', 'admin', NULL, 'masculin', 'admin@faktiora.mg', 'admin', '$2y$10$dXsBPIveIwTawqI5RYm.JeZK/BdilM4ZoayDHFVvqD4ayoVA.fbwK', NULL, 'déconnecté', '1700-01-01 00:00:00'),
('U123278VW', 'S', '', 'masculin', 'nantenain@faktiora.mg', 'admin', '$2y$10$.H4oNKM5lRtaNmd5TA6SWOsu3vGiAe4T/ab44nGXh10/ObOq4NvGS', NULL, 'connecté', '1700-01-01 00:00:00');

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
  ADD KEY `fk_user` (`id_utilisateur`),
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
  ADD PRIMARY KEY (`num_facture`),
  ADD KEY `fk_user_01` (`id_utilisateur`),
  ADD KEY `fk_client` (`id_client`),
  ADD KEY `fk_caisse_facture` (`num_caisse`);

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
  ADD KEY `fk_ds` (`id_ds`),
  ADD KEY `fk_article` (`id_article`);

--
-- Indexes for table `ligne_facture`
--
ALTER TABLE `ligne_facture`
  ADD PRIMARY KEY (`id_lf`),
  ADD KEY `fk_facture` (`num_facture`),
  ADD KEY `fk_produit` (`id_produit`);

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
  ADD PRIMARY KEY (`id_utilisateur`),
  ADD KEY `role.user` (`role`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `caisse`
--
ALTER TABLE `caisse`
  MODIFY `num_caisse` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Caisse ID', AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `ligne_caisse`
--
ALTER TABLE `ligne_caisse`
  MODIFY `id_lc` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `ligne_ds`
--
ALTER TABLE `ligne_ds`
  MODIFY `id_lds` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ligne_facture`
--
ALTER TABLE `ligne_facture`
  MODIFY `id_lf` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Ligne facture ID';

--
-- Constraints for dumped tables
--

--
-- Constraints for table `autre_entree`
--
ALTER TABLE `autre_entree`
  ADD CONSTRAINT `fk_caisse_ae` FOREIGN KEY (`num_caisse`) REFERENCES `caisse` (`num_caisse`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE SET NULL ON UPDATE CASCADE;

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
  ADD CONSTRAINT `fk_client` FOREIGN KEY (`id_client`) REFERENCES `client` (`id_client`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_01` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE SET NULL ON UPDATE CASCADE;

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
  ADD CONSTRAINT `fk_article` FOREIGN KEY (`id_article`) REFERENCES `article` (`id_article`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ds` FOREIGN KEY (`id_ds`) REFERENCES `demande_sortie` (`id_ds`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `ligne_facture`
--
ALTER TABLE `ligne_facture`
  ADD CONSTRAINT `fk_facture` FOREIGN KEY (`num_facture`) REFERENCES `facture` (`num_facture`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_produit` FOREIGN KEY (`id_produit`) REFERENCES `produit` (`id_produit`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
