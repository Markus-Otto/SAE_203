<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "eiffel_note_db";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Sélectionner les informations de l'utilisateur à partir de la base de données
    $stmt = $pdo->query("SELECT nom, prenom, pass FROM etudiant WHERE ID_utilisateur = 1"); // Supposons que l'ID de l'utilisateur est 1 à des fins de démonstration
    $infosUtilisateur = $stmt->fetch(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}
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
                <li><a href="recap_notes.php" class="menu-item active">Récapitulatif Note</a></li>
                <li><a href="profil_eleve.php" class="menu-item">Profil</a></li>
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
                    <form>
                        <table>
                            <!-- Name Field -->
                            <tr>
                                <td><label for="nom">Nom:</label></td>
                                <td><input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($infosUtilisateur['nom']); ?>" required></td>
                            </tr>
                            <!-- First Name Field -->
                            <tr>
                                <td><label for="prenom">Prénom:</label></td>
                                <td><input type="text" id="prenom" name="prenom" value="<?php echo htmlspecialchars($infosUtilisateur['prenom']); ?>" required></td>
                            </tr>
                            <!-- Update Button -->
                        </table>
                    </form>
                </div>
                <div class="block1">
                    <h2>Sécurite</h2>
                    <form>
                        <table>
                            <tr>
                                <td><label for="mot_de_passe">Mot de passe:</label></td>
                                <td><input type="text" id="mot_de_passe" name="mot_de_passe" value="<?php echo htmlspecialchars($infosUtilisateur['pass']); ?>" required></td>
                            </tr>
                            <!-- Update Button -->
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
