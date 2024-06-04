-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 03 juin 2024 à 10:07
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
-- Base de données : `test2`
--


session_start();
include "fnct_conn.php";

$conn = connexion();
function envoyer(){
if (isset($_POST['nom'], $_POST['prenom'], $_POST['TD'], $_POST['TP'], $_POST['annee'])) {
    $requete = 'INSERT INTO etudiant (nom, prenom, TD, TP, annee) VALUES (:nom, :prenom, :TD, :TP, :annee)';
    $stmt = $conn->prepare($requete);
    
    // Lier les paramètres
    $stmt->bindParam(':nom', $_POST['nom']);
    $stmt->bindParam(':Prenom', $_POST['Prenon']);
    $stmt->bindParam(':TD	', $_POST['TD	']);
    $stmt->bindParam(':TP', $_POST['TP']);
    $stmt->bindParam(':annee', $_POST['annee']);
    
    // Exécuter la requête
    $stmt->execute();
}
$stmt = $conn->query('SELECT nom, prenom, TD, TP, annee FROM etudiant');
echo $stmt;

}
header("Location: accueil_admin.html")

// Ajouter un élève
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {
    $stmt = $conn->prepare('INSERT INTO students (nom, prenoms, tp, td, annee_univ) VALUES (:nom, :prenoms, :tp, :td, :annee_univ)');
    $stmt->execute([
        'nom' => $_POST['nom'],
        'prenoms' => $_POST['prenoms'],
        'tp' => $_POST['tp'],
        'td' => $_POST['td'],
        'annee_univ' => $_POST['annee_univ']
    ]);
}

// Modifier un élève
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $stmt = $conn->prepare('UPDATE students SET nom = :nom, prenoms = :prenoms, tp = :tp, td = :td, annee_univ = :annee_univ WHERE id = :id');
    $stmt->execute([
        'nom' => $_POST['nom'],
        'prenoms' => $_POST['prenoms'],
        'tp' => $_POST['tp'],
        'td' => $_POST['td'],
        'annee_univ' => $_POST['annee_univ'],
        'id' => $_POST['id']
    ]);
}

// Supprimer un élève
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
    $stmt = $conn->prepare('DELETE FROM etudiant WHERE id = :id');
    $stmt->execute(['id' => $_POST['id']]);
}

// Afficher la liste des étudiants
$stmt = $conn->query('SELECT nom, prenoms, tp, td, annee_univ FROM etudiant');
$conn = $stmt->fetchAll();


   

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `pass` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `pass`) VALUES
(1, '1', 'c20ad4d76fe97759aa27a0c99bff6710');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
