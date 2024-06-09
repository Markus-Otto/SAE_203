<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Enseignant - Eiffel Note</title>
    <link rel="stylesheet" href="../../CSS/profil.css">
</head>
<body>
    <div class="sidebar">
        <div class="logo">
            <img src="../../image/EiffelNote_logo_V9.png" alt="Eiffel Note Logo">
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Classes</a></li>
                <li><a href="evaluation.php">Evaluations</a></li>
                <li class="active"><a href="profil.php">Profil</a></li>
            </ul>
        </nav>
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
            <h1>Profil Enseignant</h1>
        </header>
        <section class="profil">
            <?php
            // Informations de connexion à la base de données
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "effeil_note_db";

            try {
                // Création de la connexion à la base de données en utilisant PDO
                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                // Configuration du mode d'erreur pour les exceptions
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // ID de l'enseignant (par exemple, récupéré via une session ou une variable GET/POST)
                $id_enseignant = 1; // Remplacez cette valeur par la méthode de récupération de l'ID appropriée

                // Préparation de la requête SQL
                $stmt = $conn->prepare("SELECT ID_enseignant, Nom, Prenom FROM enseignant WHERE ID_enseignant = :id_enseignant");
                $stmt->bindParam(':id_enseignant', $id_enseignant);
                $stmt->execute();

                // Récupération des résultats
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($result) {
                    echo "<p><strong>ID:</strong> " . htmlspecialchars($result['ID_enseignant']) . "</p>";
                    echo "<p><strong>Nom:</strong> " . htmlspecialchars($result['Nom']) . "</p>";
                    echo "<p><strong>Prénom:</strong> " . htmlspecialchars($result['Prenom']) . "</p>";
                } else {
                    echo "<p>Enseignant non trouvé.</p>";
                }
            } catch (PDOException $e) {
                echo "Erreur de connexion : " . $e->getMessage();
            }

            // Fermeture de la connexion
            $conn = null;
            ?>
        </section>
        <div class="profile-picture">
            <img src="profile.jpg" alt="Profile Picture">
        </div>
    </div>
</body>
</html>
