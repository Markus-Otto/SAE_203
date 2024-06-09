-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 07 juin 2024 à 22:38
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
-- Base de données : `eiffel_note_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `enseignant`
--

CREATE TABLE `enseignant` (
  `ID_enseignant` int(20) NOT NULL,
  `Nom` varchar(150) NOT NULL,
  `Prenom` varchar(150) NOT NULL,
  `pass` varchar(250) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `enseignant`
--

INSERT INTO `enseignant` (`ID_enseignant`, `Nom`, `Prenom`, `pass`, `username`) VALUES
(1, 'Dupont', 'Jean', NULL, NULL),
(2, 'Martin', 'Claire', NULL, NULL),
(3, 'Durand', 'Louis', NULL, NULL),
(4, '', '', '1234', 'Dupont.Jean'),
(5, '', '', '1234', 'Martin.Claire'),
(6, '', '', '1234', 'Durand.Louis'),
(7, '', '', '1234', 'Lefevre.Alice'),
(8, '', '', '1234', 'Bernard.Marc'),
(9, '', '', '1234', 'Moreau.Sophie');

-- --------------------------------------------------------

--
-- Structure de la table `epreuves`
--

CREATE TABLE `epreuves` (
  `ID_epreuve` int(20) NOT NULL,
  `Coefficients` varchar(255) DEFAULT NULL,
  `date_epreuve` date DEFAULT NULL,
  `libelle` varchar(255) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `ID_UE` int(20) DEFAULT NULL,
  `ID_ressource` int(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `epreuves`
--

INSERT INTO `epreuves` (`ID_epreuve`, `Coefficients`, `date_epreuve`, `libelle`, `note`, `ID_UE`, `ID_ressource`) VALUES
(1, '1', '2024-01-15', 'équation', '18', 1, 1),
(2, '2', '2024-02-20', 'verbe irrégulier', '16', 2, 2),
(3, '1', '2024-03-10', 'html', '19', 3, 3);

-- --------------------------------------------------------

--
-- Structure de la table `etudiant`
--

CREATE TABLE `etudiant` (
  `ID_etudiant` int(20) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `TD` varchar(255) NOT NULL,
  `TP` varchar(255) NOT NULL,
  `annee` varchar(255) NOT NULL,
  `ID_UE` int(20) DEFAULT NULL,
  `pass` varchar(250) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `etudiant`
--

INSERT INTO `etudiant` (`ID_etudiant`, `nom`, `prenom`, `TD`, `TP`, `annee`, `ID_UE`, `pass`, `username`) VALUES
(1, 'Lefevre', 'Alice', 'TD1', 'TP1', '2023', 1, NULL, NULL),
(2, 'Bernard', 'Marc', 'TD2', 'TP2', '2023', 2, NULL, NULL),
(3, 'Moreau', 'Sophie', 'TD3', 'TP3', '2023', 3, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `ressource`
--

CREATE TABLE `ressource` (
  `ID_ressource` int(11) NOT NULL,
  `nom_de_la_ressource` varchar(100) NOT NULL,
  `libelle` varchar(100) NOT NULL,
  `ID_enseignant` int(20) DEFAULT NULL,
  `ID_UE` int(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `ressource`
--

INSERT INTO `ressource` (`ID_ressource`, `nom_de_la_ressource`, `libelle`, `ID_enseignant`, `ID_UE`) VALUES
(1, 'mathématique', 'équation', 1, 1),
(2, 'anglais', 'verbe irrégulier', 2, 2),
(3, 'devellopement web', 'html', 3, 3);

-- --------------------------------------------------------

--
-- Structure de la table `ue`
--

CREATE TABLE `ue` (
  `ID_UE` int(20) NOT NULL,
  `Competence` varchar(100) NOT NULL,
  `Moyenne` int(20) DEFAULT NULL,
  `Penalite` varchar(180) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `ue`
--

INSERT INTO `ue` (`ID_UE`, `Competence`, `Moyenne`, `Penalite`) VALUES
(1, 'comprendre', 12, 'Aucune'),
(2, 'concevoir', 13, 'Aucune'),
(3, 'entreprendre', 18, '1');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id_users` int(20) NOT NULL,
  `username` varchar(255) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `ID_etudiant` int(20) DEFAULT NULL,
  `ID_enseignant` int(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id_users`, `username`, `pass`, `ID_etudiant`, `ID_enseignant`) VALUES
(1, '1234', 'admin', NULL, NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `enseignant`
--
ALTER TABLE `enseignant`
  ADD PRIMARY KEY (`ID_enseignant`);

--
-- Index pour la table `epreuves`
--
ALTER TABLE `epreuves`
  ADD PRIMARY KEY (`ID_epreuve`),
  ADD KEY `ID_UE` (`ID_UE`),
  ADD KEY `ID_ressource` (`ID_ressource`);

--
-- Index pour la table `etudiant`
--
ALTER TABLE `etudiant`
  ADD PRIMARY KEY (`ID_etudiant`),
  ADD KEY `ID_UE` (`ID_UE`);

--
-- Index pour la table `ressource`
--
ALTER TABLE `ressource`
  ADD PRIMARY KEY (`ID_ressource`),
  ADD KEY `ID_enseignant` (`ID_enseignant`),
  ADD KEY `ID_UE` (`ID_UE`);

--
-- Index pour la table `ue`
--
ALTER TABLE `ue`
  ADD PRIMARY KEY (`ID_UE`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_users`),
  ADD KEY `ID_etudiant` (`ID_etudiant`),
  ADD KEY `ID_enseignant` (`ID_enseignant`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `enseignant`
--
ALTER TABLE `enseignant`
  MODIFY `ID_enseignant` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `epreuves`
--
ALTER TABLE `epreuves`
  MODIFY `ID_epreuve` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `etudiant`
--
ALTER TABLE `etudiant`
  MODIFY `ID_etudiant` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `ressource`
--
ALTER TABLE `ressource`
  MODIFY `ID_ressource` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `ue`
--
ALTER TABLE `ue`
  MODIFY `ID_UE` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id_users` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `epreuves`
--
ALTER TABLE `epreuves`
  ADD CONSTRAINT `epreuves_ibfk_1` FOREIGN KEY (`ID_UE`) REFERENCES `ue` (`ID_UE`),
  ADD CONSTRAINT `epreuves_ibfk_2` FOREIGN KEY (`ID_ressource`) REFERENCES `ressource` (`ID_ressource`);

--
-- Contraintes pour la table `etudiant`
--
ALTER TABLE `etudiant`
  ADD CONSTRAINT `etudiant_ibfk_1` FOREIGN KEY (`ID_UE`) REFERENCES `ue` (`ID_UE`);

--
-- Contraintes pour la table `ressource`
--
ALTER TABLE `ressource`
  ADD CONSTRAINT `ressource_ibfk_1` FOREIGN KEY (`ID_enseignant`) REFERENCES `enseignant` (`ID_enseignant`),
  ADD CONSTRAINT `ressource_ibfk_2` FOREIGN KEY (`ID_UE`) REFERENCES `ue` (`ID_UE`);

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`ID_etudiant`) REFERENCES `etudiant` (`ID_etudiant`),
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`ID_enseignant`) REFERENCES `enseignant` (`ID_enseignant`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
