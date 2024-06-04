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
        $stmt->bindParam(':prenom', $_POST['prenom']); // Correction ici
        $stmt->bindParam(':TD', $_POST['TD']); // Correction ici
        $stmt->bindParam(':TP', $_POST['TP']);
        $stmt->bindParam(':annee', $_POST['annee']);
        
        // Exécuter la requête
        $stmt->execute();
        
        // Redirection après l'insertion
        header("Location: accueil_admin.php");
    }
}

// Appel de la fonction envoyer si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    envoyer();
}

// Récupération des données pour affichage
$stmt = $conn->query('SELECT nom, prenom, TD, TP, annee FROM etudiant');
$etudiants = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <!-- Votre code HTML ici -->
</head>
<body>
    <!-- Votre code HTML ici -->
    <table>
        <tr>
            <th>Nom</th>
            <th>Prénom</th>
            <th>TD</th>
            <th>TP</th>
            <th>Année</th>
        </tr>
        <?php foreach ($etudiants as $etudiant): ?>
        <tr>
            <td><?php echo htmlspecialchars($etudiant['nom']); ?></td>
            <td><?php echo htmlspecialchars($etudiant['prenom']); ?></td>
            <td><?php echo htmlspecialchars($etudiant['TD']); ?></td>
            <td><?php echo htmlspecialchars($etudiant['TP']); ?></td>
            <td><?php echo htmlspecialchars($etudiant['annee']); ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
