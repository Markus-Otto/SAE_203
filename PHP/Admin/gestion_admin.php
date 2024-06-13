<?php
session_start();
include "fnct_conn.php";

$conn = connexion();

function envoyer($conn) {
    if (isset($_POST['id'], $_POST['nom'], $_POST['prenom'], $_POST['TD'], $_POST['TP'], $_POST['annee'])) {
        $requete = 'INSERT INTO etudiant (ID_utilisateur, nom, prenom, TD, TP, annee) VALUES (?,?, ?, ?, ?, ?)';
        $stmt = $conn->prepare($requete);
        
        // Lier les paramètres
        $stmt->bind_param('ssssss',$_POST['id'], $_POST['nom'], $_POST['prenom'], $_POST['TD'], $_POST['TP'], $_POST['annee']);
        
        // Exécuter la requête
        if ($stmt->execute()) {
            // Redirection après l'insertion
            header("Location: ../../accueil_admin.php");
            exit();
        } else {
            echo "Erreur: " . $stmt->error;
        }
    }
}

function supprimer($conn, $id) {
    $requete = 'DELETE FROM etudiant WHERE ID_utilisateur = ?';
    $stmt = $conn->prepare($requete);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    
    // Redirection après la suppression
    header("Location: ../../accueil_admin.php");
    exit();
}

// Appel de la fonction envoyer si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['envoyer'])) {
    envoyer($conn);
}

// Appel de la fonction supprimer si l'action de suppression est déclenchée
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['supprimer'])) {
    supprimer($conn, $_POST['id']); // Correction ici pour utiliser 'id' au lieu de 'ID_utilisateur'
}

// Récupération des données pour affichage
$result = $conn->query('SELECT ID_utilisateur, nom, prenom, TD, TP, annee FROM etudiant');
$etudiants = $result->fetch_all(MYSQLI_ASSOC);
?>