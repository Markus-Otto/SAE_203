<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'etudiant') {
    header("Location: ../../../../Accueil_note.php");
    exit();
}
include "../Admin/fnct_conn.php";

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$conn = connexion();
$username = $_SESSION['username'];

// Préparer et exécuter la requête SQL pour obtenir les informations personnelles de l'utilisateur
$sql = $conn->prepare("SELECT nom, prenom, username, pass FROM etudiant WHERE username = ?");
$sql->bind_param('s', $username);
$sql->execute();
$result = $sql->get_result();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>EIFFEL NOTE</title>
    <link rel="stylesheet" type="text/css" href="../../CSS/accueil-etudiant.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">EIFFEL NOTE</div>
        <div class="menu">
            <ul>
                <li><a href="recap_notes.php" class="menu-item">Récapitulatif Note</a></li>
                <li><a href="profil_eleve.php" class="menu-item active">Profil</a></li>
            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header with Search Bar -->
        <header>
            <input type="search" placeholder="Rechercher...">
            <button>Rechercher</button>
        </header>
        
        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <div class="container">
                <!-- Personal Information Block -->
                <div class="block1">
                    <h2>Information personnelle</h2>
                    <?php
                    if ($row = $result->fetch_assoc()) {
                        echo "<form>";
                        echo "<table>";
                        echo "<tr>";
                        echo "<td><label for='nom'>Nom:</label></td>";
                        echo "<td><input type='text' id='nom' name='nom' value='" . htmlspecialchars($row['nom']) . "' required></td>";
                        echo "</tr>";
                        echo "<tr>";
                        echo "<td><label for='prenom'>Prénom:</label></td>";
                        echo "<td><input type='text' id='prenom' name='prenom' value='" . htmlspecialchars($row['prenom']) . "' required></td>";
                        echo "</tr>";
                        echo "<tr>";
                        echo "<td><label for='username'>Username:</label></td>";
                        echo "<td><input type='text' id='username' name='username' value='" . htmlspecialchars($row['username']) . "' required></td>";
                        echo "</tr>";
                        echo "</table>";
                        echo "</form>";
                    } else {
                        echo "Aucune information disponible.";
                    }
                    ?>
                </div>
                <div class="block1">
                    <h2>Sécurite</h2>
                    <?php
                    if ($row) {
                        echo "<form>";
                        echo "<table>";
                        echo "<tr>";
                        echo "<td><label for='mot_de_passe'>Mot de passe:</label></td>";
                        echo "<td><input type='text' id='mot_de_passe' name='mot_de_passe' value='" . htmlspecialchars($row['pass']) . "' required></td>";
                        echo "</tr>";
                        echo "</table>";
                        echo "</form>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<?php
$sql->close();
$conn->close();
?>
