<?php
include "fnct_conn.php";
include "Verif_session.php";

$conn = connexion();

function handleRequest($conn, $action) {
    if ($action == 'valider') {
        if (isset($_POST['nom_ressource'], $_POST['ID_enseignant'], $_POST['ID_UE'])) {
            $nom_ressource = $_POST['nom_ressource'];
            $ID_enseignant = $_POST['ID_enseignant'];
            $ID_UE = $_POST['ID_UE'];
            $requete = 'INSERT INTO ressource (nom_de_la_ressource, ID_enseignant, ID_UE) VALUES (?, ?, ?)';
            $stmt = $conn->prepare($requete);
            $stmt->bind_param('sii', $nom_ressource, $ID_enseignant, $ID_UE);
            if ($stmt->execute()) {
                echo "Ressource ajoutée avec succès.";
            } else {
                echo "Erreur: " . $stmt->error;
            }
        }
    } elseif ($action == 'supprimer') {
        if (isset($_POST['nom_ressource'])) {
            $nom_ressource = $_POST['nom_ressource'];
            $requete = 'DELETE FROM ressource WHERE nom_de_la_ressource = ?';
            $stmt = $conn->prepare($requete);
            $stmt->bind_param('s', $nom_ressource);
            if ($stmt->execute()) {
                echo "Ressource supprimée avec succès.";
            } else {
                echo "Erreur: " . $stmt->error;
            }
        }
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['valider'])) {
        handleRequest($conn, 'valider');
    } elseif (isset($_POST['supprimer'])) {
        handleRequest($conn, 'supprimer');
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des ressources</title>
    <link rel="stylesheet" href="../../CSS/accueil_admin.css">
    <link rel="icon" type="image/gif" href="../../image/EiffelNote_logo_V9.png" class="img2"/>
</head>
<body>
<div class="sidebar">
    <div class="logo">EIFFEL NOTE</div>
    <div class="menu">
        <div><a href="accueil_admin.php">Gestion des comptes</a></div>
        <div class="active"><a href="#">Gestion des ressources</a></div>
    </div>
    <div class="logout">
        <form method="post" action="accueil_admin.php">
            <button type="submit" name="logout">Déconnexion</button>
            <?php
                if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['logout'])) {
                    session_start();
                    session_unset();
                    session_destroy();
                    header("Location: ../page_login/Accueil_note.php");
                    exit();
                }
            ?>
        </form>
    </div>
</div>
<div class="main-content">
    <header>
        <input type="text" placeholder="SAE, UE ...">
        <button>Rechercher</button>
        <div class="profile">
            <img src="profile.png" alt="Profile Picture">
        </div>
    </header>
    <h1>Gestion des ressources</h1>
    <div class="content">
        <div class="resource-section">
            <div id="saisie-module" class="module">
                <h2>Saisie module</h2>
                <form method="post" action="">
                    <input type="text" name="nom_ressource" placeholder="Nom ressource">
                    <input type="text" name="ID_enseignant" placeholder="Nom enseignant">
                    <input type="text" name="ID_UE" placeholder="ID UE">
                    <button type="submit" name="valider">Valider</button>
                </form>
            </div>
            <div id="suppression-module" class="module">
                <h2>Suppression module</h2>
                <form method="post" action="">
                    <input type="text" name="nom_ressource" placeholder="Nom ressource">
                    <input type="text" name="ID_enseignant" placeholder="ID_enseignant">
                    <input type="text" name="ID_UE" placeholder="ID UE">
                    <button type="reset">Effacer</button>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>