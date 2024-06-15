<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Enseignant - Eiffel Note</title>
    <link rel="stylesheet" href="../../CSS/profil_prof.css">
    <link rel="stylesheet" href="../../CSS/sidebar.css">
    <script>
        function showNotes(libelle) {
            fetch('fetch_notes.php?libelle=' + encodeURIComponent(libelle))
                .then(response => response.json())
                .then(data => {
                    const notesBody = document.getElementById('notes-body');
                    notesBody.innerHTML = '';
                    data.forEach(note => {
                        const row = document.createElement('tr');
                        row.innerHTML = `<td>${note.date_epreuve}</td><td>${note.nom}</td><td>${note.prenom}</td><td>${note.note}</td><td>${note.Coefficients}</td>`;
                        notesBody.appendChild(row);
                    });
                    document.getElementById('notes-table').classList.remove('hidden');
                });
        }
    </script>
</head>
<body>
    <div class="sidebar">
        <h1>EIFFEL NOTE</h1>
        <ul>
            <li><a href="index.php">Evaluations</a></li>
            <li class="active"><a href="profil.php">Profil</a></li>
        </ul>
    </div>
    <div class="main-contentprofil">
        <div class="logout">
            <form method="post" action="accueil_admin.php">
                <button type="submit" name="logout" href="../../Accueil_note.php">Déconnexion</button>
                <?php
                    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['logout'])) {
                        session_start();
                        session_unset();
                        session_destroy();
                        header("Location: ../../Accueil_note.php");
                        exit();
                    }
                ?>
            </form>
        </div>
        <header>
            <h1>Profil Enseignant</h1>
        </header>
        <section class="profil">
            <?php
           
           session_start();
           if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'enseignant') {
               header("Location: ../../../../Accueil_note.php");
               exit();
           }
           
                // Informations de connexion à la base de données
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "eiffel_note_db";

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
    </div>
</body>
</html>
