<?php
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



?>
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


   

?>