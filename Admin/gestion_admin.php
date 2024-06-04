<?php
session_start();
include "fnct_conn.php";

$conn = connexion();

function envoyer($conn) {
    if (isset($_POST['nom'], $_POST['prenom'], $_POST['TD'], $_POST['TP'], $_POST['annee'])) {
        $requete = 'INSERT INTO etudiant (nom, prenom, TD, TP, annee) VALUES (?, ?, ?, ?, ?)';
        $stmt = $conn->prepare($requete);
        
        // Lier les paramètres
        $stmt->bind_param('sssss', $_POST['nom'], $_POST['prenom'], $_POST['TD'], $_POST['TP'], $_POST['annee']);
        
        // Exécuter la requête
        $stmt->execute();
        
        // Redirection après l'insertion
        header("Location: accueil_admin.php");
        exit();
    }
}

// Appel de la fonction envoyer si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    envoyer($conn);
}

// Récupération des données pour affichage
$result = $conn->query('SELECT nom, prenom, TD, TP, annee FROM etudiant');
$etudiants = $result->fetch_all(MYSQLI_ASSOC);
?>