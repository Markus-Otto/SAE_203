<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>EIFFEL NOTE</title>
    <link rel="stylesheet" type="text/css" href="../../CSS/style.css">
</head>
<body>
    <div class="sidebar">
        <div class="logo">EIFFEL NOTE</div>
        <div class="menu">
            <ul>
                <li><a href="recap_notes.php" class="menu-item active">Récapitulatif Note</a></li>
                <li><a href="profil_eleve.php" class="menu-item">Profil</a></li>
            </ul>
        </div>
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
             exit();    }   ?>
            </form>
        </div>
    </div>
    <div class="main-content">
        <header>
            <input type="search" id="searchNotes" name="searchNotes" placeholder="SAE, UE ...">
            <button>Rechercher</button>
            <div class="profile">
                <img src="profile.jpg" alt="Profile Picture">
            </div>
        </header>

        <h1>Notes</h1>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID_epreuve</th>
                        <th>Coefficient</th>
                        <th>libelle</th>
                        <th>Ressource</th>
                        <th>Date</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                <?php
function afficherNotesEtudiant($id_etudiant) {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "eiffel_note";
    
    // Créer la chaîne DSN (Data Source Name) pour PDO
    $dsn = "mysql:host=$servername;dbname=$dbname;charset=utf8";
    
    try {
        // Établir une connexion à la base de données
        $pdo = new PDO($dsn, $username, $password);
        // Définir le mode d'erreur PDO sur exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Préparer la requête SQL
        $stmt = $pdo->prepare("SELECT ID_epreuve, Coefficients, libelle, ID_ressource, date_epreuve, Note FROM epreuves WHERE id_etudiant = ?");
        $stmt->execute([$id_etudiant]);
        
        // Récupérer et afficher les résultats
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['ID_epreuve']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Coefficients']) . "</td>";
            echo "<td>" . htmlspecialchars($row['libelle']) . "</td>";
            echo "<td>" . htmlspecialchars($row['ID_ressource']) . "</td>";
            echo "<td>" . htmlspecialchars($row['date_epreuve']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Note']) . "</td>";
            echo "</tr>";
        }
    } catch (PDOException $e) {
        echo "Connexion échouée : " . $e->getMessage();
    }
}
?>

                    <?php
                    // Supposons que l'ID de l'étudiant est 1 à des fins de démonstration
                    afficherNotesEtudiant(1);
                    ?>
                </tbody>
            </table>
            <div class="buttons">
                <button>Exporter</button>
                <button>Filtre</button>
            </div>
        </div>
    </div>
</body>
</html>


        
      
