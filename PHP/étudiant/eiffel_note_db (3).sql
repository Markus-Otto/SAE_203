-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : dim. 09 juin 2024 à 22:57
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
-- Structure de la table `avengers_rassemblement`
--

CREATE TABLE `avengers_rassemblement` (
  `ID_rassemblement` varchar(255) NOT NULL,
  `ID_enseignant` int(11) DEFAULT NULL,
  `ID_utilisateur` int(11) DEFAULT NULL,
  `id_users` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `enseignant`
--

CREATE TABLE `enseignant` (
  `ID_enseignant` int(11) NOT NULL,
  `Nom` varchar(150) NOT NULL,
  `Prenom` varchar(150) NOT NULL,
  `pass` varchar(250) DEFAULT NULL,
  `username` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `enseignant`
--

INSERT INTO `enseignant` (`ID_enseignant`, `Nom`, `Prenom`, `pass`, `username`) VALUES
(0, '', '', '827ccb0eea8a706c4c34a16891f84e7b', 'JeanDupont'),
(1, 'Dupont', 'Jean', 'password123', 'JeanDupont'),
(2, 'Lefebvre', 'Marie', 'password456', 'MarieLefebvre'),
(3, 'Dubois', 'Paul', 'password789', 'PaulDubois'),
(4, 'Martin', 'Sophie', 'passwordabc', 'SophieMartin'),
(5, 'Bernard', 'Pierre', 'passworddef', 'PierreBernard');

-- --------------------------------------------------------

--
-- Structure de la table `epreuves`
--

CREATE TABLE `epreuves` (
  `ID_epreuve` int(11) NOT NULL,
  `Coefficients` varchar(255) DEFAULT NULL,
  `date_epreuve` date DEFAULT NULL,
  `libelle` varchar(255) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `ID_ressource` int(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `epreuves`
--

INSERT INTO `epreuves` (`ID_epreuve`, `Coefficients`, `date_epreuve`, `libelle`, `note`, `ID_ressource`) VALUES
(1, '1', '2024-01-15', 'équation', '18', 1),
(2, '2', '2024-02-20', 'verbe irrégulier', '16', 2),
(3, '1', '2024-03-10', 'html', '19', 3);

-- --------------------------------------------------------

--
-- Structure de la table `etudiant`
--

CREATE TABLE `etudiant` (
  `ID_utilisateur` int(20) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `TD` varchar(255) NOT NULL,
  `TP` varchar(255) NOT NULL,
  `annee_promo` varchar(255) NOT NULL,
  `ID_UE` int(20) NOT NULL,
  `Moyenne_UE` int(20) NOT NULL,
  `Penalite` varchar(180) DEFAULT NULL,
  `pass` varchar(250) DEFAULT NULL,
  `username` varchar(250) DEFAULT NULL,
  `ID_epreuve` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `etudiant`
--

INSERT INTO `etudiant` (`ID_utilisateur`, `nom`, `prenom`, `TD`, `TP`, `annee_promo`, `ID_UE`, `Moyenne_UE`, `Penalite`, `pass`, `username`, `ID_epreuve`) VALUES
(1, 'Doe', 'John', 'TD1', 'TP1', '2024', 3, 17, '1', 'password123', 'John.Doe', 1),
(2, 'Smith', 'Alice', 'TD2', 'TP2', '2023', 2, 15, '1', 'password456', 'Alice.Smith', 1),
(3, 'Johnson', 'Bob', 'TD3', 'TP3', '2022', 1, 13, '0', 'password789', 'Bob.Johnson', 2),
(4, 'Williams', 'Emily', 'TD4', 'TP4', '2021', 1, 18, '1', 'passwordabc', 'Emily.Williams', 2),
(5, 'Brown', 'Michael', 'TD5', 'TP5', '2020', 2, 15, '1', 'passworddef', 'Michael.Brown', 3);

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
  `ID_UE` int(11) NOT NULL,
  `Nom_UE` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `ue`
--

INSERT INTO `ue` (`ID_UE`, `Nom_UE`) VALUES
(1, 'comprendre'),
(2, 'concevoir'),
(3, 'entreprendre'),
(4, 'Exprimer'),
(5, 'Développer');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id_users` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `pass` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id_users`, `username`, `pass`) VALUES
(1, 'admin', '827ccb0eea8a706c4c34a16891f84e7b');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `avengers_rassemblement`
--
ALTER TABLE `avengers_rassemblement`
  ADD PRIMARY KEY (`ID_rassemblement`),
  ADD KEY `ID_de_enseignant` (`ID_enseignant`),
  ADD KEY `ID_de_utilisateur` (`ID_utilisateur`),
  ADD KEY `ID_de_users` (`id_users`);

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
  ADD KEY `ID_de_la_ressource` (`ID_ressource`);

--
-- Index pour la table `etudiant`
--
ALTER TABLE `etudiant`
  ADD PRIMARY KEY (`ID_utilisateur`),
  ADD KEY `ID_de_l_UE` (`ID_UE`),
  ADD KEY `ID_de_épreuve` (`ID_epreuve`);

--
-- Index pour la table `ressource`
--
ALTER TABLE `ressource`
  ADD PRIMARY KEY (`ID_ressource`),
  ADD KEY `ID_de_enseignant2` (`ID_enseignant`),
  ADD KEY `ID_de_UE2` (`ID_UE`);

--
-- Index pour la table `ue`
--
ALTER TABLE `ue`
  ADD PRIMARY KEY (`ID_UE`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_users`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `epreuves`
--
ALTER TABLE `epreuves`
  MODIFY `ID_epreuve` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `ressource`
--
ALTER TABLE `ressource`
  MODIFY `ID_ressource` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `ue`
--
ALTER TABLE `ue`
  MODIFY `ID_UE` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id_users` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `avengers_rassemblement`
--
ALTER TABLE `avengers_rassemblement`
  ADD CONSTRAINT `ID_de_enseignant` FOREIGN KEY (`ID_enseignant`) REFERENCES `enseignant` (`ID_enseignant`),
  ADD CONSTRAINT `ID_de_users` FOREIGN KEY (`id_users`) REFERENCES `users` (`id_users`),
  ADD CONSTRAINT `ID_de_utilisateur` FOREIGN KEY (`ID_utilisateur`) REFERENCES `etudiant` (`ID_utilisateur`);

--
-- Contraintes pour la table `epreuves`
--
ALTER TABLE `epreuves`
  ADD CONSTRAINT `ID_de_la_ressource` FOREIGN KEY (`ID_ressource`) REFERENCES `ressource` (`ID_ressource`);

--
-- Contraintes pour la table `etudiant`
--
ALTER TABLE `etudiant`
  ADD CONSTRAINT `ID_de_l_UE` FOREIGN KEY (`ID_UE`) REFERENCES `ue` (`ID_UE`),
  ADD CONSTRAINT `ID_de_épreuve` FOREIGN KEY (`ID_epreuve`) REFERENCES `epreuves` (`ID_epreuve`);

--
-- Contraintes pour la table `ressource`
--
ALTER TABLE `ressource`
  ADD CONSTRAINT `ID_de_UE2` FOREIGN KEY (`ID_UE`) REFERENCES `ue` (`ID_UE`),
  ADD CONSTRAINT `ID_de_enseignant2` FOREIGN KEY (`ID_enseignant`) REFERENCES `enseignant` (`ID_enseignant`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
