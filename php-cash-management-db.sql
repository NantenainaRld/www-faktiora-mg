-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 03, 2025 at 10:11 PM
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
-- Database: `php-cash-management-db`
--

-- --------------------------------------------------------

--
-- Table structure for table `autre_entree`
--

CREATE TABLE `autre_entree` (
  `id_entree` varchar(20) NOT NULL COMMENT 'Entree ID',
  `libelle_entree` varchar(50) NOT NULL COMMENT 'Entree libelle',
  `date_entree` datetime NOT NULL COMMENT 'Entree date',
  `montant_entree` decimal(15,2) NOT NULL DEFAULT 0.00 COMMENT 'Entree amount',
  `id_utilisateur` varchar(15) DEFAULT NULL COMMENT 'Utilisateur foreign key',
  `num_caisse` int(11) DEFAULT NULL COMMENT 'Caisse foreign key'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `caisse`
--

CREATE TABLE `caisse` (
  `num_caisse` int(11) NOT NULL COMMENT 'Caisse ID',
  `solde` decimal(15,2) NOT NULL DEFAULT 0.00 COMMENT 'Caisse initial fund',
  `seuil` decimal(15,2) NOT NULL DEFAULT 0.00 COMMENT 'Caisse recent fund',
  `id_utilisateur` varchar(15) DEFAULT NULL COMMENT 'Utilisateur foreign key'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

CREATE TABLE `client` (
  `id_client` varchar(15) NOT NULL COMMENT 'Client ID',
  `nom_client` varchar(100) NOT NULL COMMENT 'Client name',
  `prenoms_client` varchar(100) DEFAULT NULL COMMENT 'CLient first name',
  `sexe_client` enum('masculin','féminin') NOT NULL COMMENT 'Client sex',
  `telephone` varchar(14) DEFAULT NULL COMMENT 'CLient phone number',
  `adresse` varchar(100) DEFAULT NULL COMMENT 'Client address'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `depense`
--

CREATE TABLE `depense` (
  `id_depense` varchar(20) NOT NULL COMMENT 'Depense ID',
  `libelle_depense` varchar(50) NOT NULL COMMENT 'Depense Libelle',
  `montant_depense` decimal(15,1) NOT NULL COMMENT 'Depense amount',
  `quantite` int(11) NOT NULL COMMENT 'Depense quantity',
  `id_sortie` varchar(20) DEFAULT NULL COMMENT 'Sortie foreign key'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `facture`
--

CREATE TABLE `facture` (
  `num_facture` varchar(20) NOT NULL COMMENT 'Facture ID',
  `date_facture` datetime NOT NULL COMMENT 'Facture date',
  `montant_facture` decimal(20,2) NOT NULL COMMENT 'Facture amount',
  `num_caisse` int(11) DEFAULT NULL COMMENT 'Caisse foreign key',
  `id_utilisateur` varchar(15) DEFAULT NULL COMMENT 'Utilisateur foreign key',
  `id_client` varchar(15) DEFAULT NULL COMMENT 'CLient foreign key'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ligne_facture`
--

CREATE TABLE `ligne_facture` (
  `id_ligne_facture` int(11) NOT NULL COMMENT 'Ligne facture ID',
  `quantite` int(11) NOT NULL COMMENT 'Ligne facture amount product',
  `id_facture` varchar(20) DEFAULT NULL COMMENT 'Facture fk',
  `id_produit` varchar(15) DEFAULT NULL COMMENT 'Produit fk'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `produit`
--

CREATE TABLE `produit` (
  `id_produit` varchar(15) NOT NULL COMMENT 'Produit ID',
  `libelle_produit` varchar(50) NOT NULL COMMENT 'Produit libelle',
  `prix_unitaire` decimal(10,2) NOT NULL COMMENT 'Produit unit price',
  `nb_stock` int(11) NOT NULL COMMENT 'Produit stock amount'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produit`
--

INSERT INTO `produit` (`id_produit`, `libelle_produit`, `prix_unitaire`, `nb_stock`) VALUES
('p001', 'zavatra', 500.00, 20),
('test', 'divay fotsy', 3000.00, 5);

-- --------------------------------------------------------

--
-- Table structure for table `sortie`
--

CREATE TABLE `sortie` (
  `id_sortie` varchar(20) NOT NULL COMMENT 'Sortie ID',
  `libelle_sortie` varchar(50) NOT NULL COMMENT 'Sortie libelle',
  `date_sortie` datetime NOT NULL COMMENT 'Sortie date',
  `montant_sortie` decimal(15,2) NOT NULL COMMENT 'Sortie amount',
  `id_utilisateur` varchar(15) DEFAULT NULL COMMENT 'User foreign key',
  `num_caisse` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sortie`
--

INSERT INTO `sortie` (`id_sortie`, `libelle_sortie`, `date_sortie`, `montant_sortie`, `id_utilisateur`, `num_caisse`) VALUES
('ddd', 'ddddd', '2025-11-03 21:39:30', 22222.00, 'U087962YH', NULL);

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
  `mdp_oublie` varchar(200) DEFAULT NULL COMMENT 'User forgot password'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `utilisateur`
--

INSERT INTO `utilisateur` (`id_utilisateur`, `nom_utilisateur`, `prenoms_utilisateur`, `sexe_utilisateur`, `email_utilisateur`, `role`, `mdp`, `mdp_oublie`) VALUES
('232', '232sq', 'qq', 'masculin', 'xq', 'caissier', 'qqqqqqq', NULL),
('U025167CG', 'ANARANA', 'fanampipny', 'féminin', 'emsa@a.com', 'admin', '$2y$10$Y/rStwdCFMqS5RaGFuD0Jur8q1uyoG7sedmPrQblMHNdXjP/gM1Q2', NULL),
('U087962YH', 'ANARANA', 'fanampipny', 'féminin', 'test@a.b', 'admin', '$2y$10$UBffHRSnsZv9oKQKDAUds.Ew7lQtiuMONeVj.J2kpU6790YSutDDO', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `autre_entree`
--
ALTER TABLE `autre_entree`
  ADD PRIMARY KEY (`id_entree`),
  ADD KEY `fk_user` (`id_utilisateur`),
  ADD KEY `fk_caisse` (`num_caisse`);

--
-- Indexes for table `caisse`
--
ALTER TABLE `caisse`
  ADD PRIMARY KEY (`num_caisse`),
  ADD KEY `fk_user_00` (`id_utilisateur`);

--
-- Indexes for table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`id_client`),
  ADD KEY `nom_client` (`nom_client`);

--
-- Indexes for table `depense`
--
ALTER TABLE `depense`
  ADD PRIMARY KEY (`id_depense`),
  ADD KEY `libelle_depense` (`libelle_depense`),
  ADD KEY `fk_id_sortie` (`id_sortie`);

--
-- Indexes for table `facture`
--
ALTER TABLE `facture`
  ADD PRIMARY KEY (`num_facture`),
  ADD KEY `fk_user_01` (`id_utilisateur`),
  ADD KEY `fk_client` (`id_client`),
  ADD KEY `fk_caisse_00` (`num_caisse`);

--
-- Indexes for table `ligne_facture`
--
ALTER TABLE `ligne_facture`
  ADD PRIMARY KEY (`id_ligne_facture`),
  ADD KEY `fk_facture` (`id_facture`),
  ADD KEY `fk_produit` (`id_produit`);

--
-- Indexes for table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`id_produit`),
  ADD KEY `libelle_produit` (`libelle_produit`);

--
-- Indexes for table `sortie`
--
ALTER TABLE `sortie`
  ADD PRIMARY KEY (`id_sortie`),
  ADD KEY `fk_id_utilisateur` (`id_utilisateur`),
  ADD KEY `fk_num_caisse` (`num_caisse`);

--
-- Indexes for table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id_utilisateur`),
  ADD UNIQUE KEY `user.email` (`email_utilisateur`),
  ADD KEY `role.user` (`role`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `caisse`
--
ALTER TABLE `caisse`
  MODIFY `num_caisse` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Caisse ID';

--
-- AUTO_INCREMENT for table `ligne_facture`
--
ALTER TABLE `ligne_facture`
  MODIFY `id_ligne_facture` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Ligne facture ID';

--
-- Constraints for dumped tables
--

--
-- Constraints for table `autre_entree`
--
ALTER TABLE `autre_entree`
  ADD CONSTRAINT `fk_caisse` FOREIGN KEY (`num_caisse`) REFERENCES `caisse` (`num_caisse`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `caisse`
--
ALTER TABLE `caisse`
  ADD CONSTRAINT `fk_user_00` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `depense`
--
ALTER TABLE `depense`
  ADD CONSTRAINT `fk_id_sortie` FOREIGN KEY (`id_sortie`) REFERENCES `sortie` (`id_sortie`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `facture`
--
ALTER TABLE `facture`
  ADD CONSTRAINT `fk_caisse_00` FOREIGN KEY (`num_caisse`) REFERENCES `caisse` (`num_caisse`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `fk_client` FOREIGN KEY (`id_client`) REFERENCES `client` (`id_client`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_01` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `ligne_facture`
--
ALTER TABLE `ligne_facture`
  ADD CONSTRAINT `fk_facture` FOREIGN KEY (`id_facture`) REFERENCES `facture` (`num_facture`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_produit` FOREIGN KEY (`id_produit`) REFERENCES `produit` (`id_produit`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `sortie`
--
ALTER TABLE `sortie`
  ADD CONSTRAINT `fk_id_utilisateur` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_num_caisse` FOREIGN KEY (`num_caisse`) REFERENCES `caisse` (`num_caisse`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
