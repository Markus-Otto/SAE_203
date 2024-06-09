<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluations - Eiffel Note</title>
    <link rel="stylesheet" href="../../CSS/evaluation.css">
    <style>
        /* Ajout de styles CSS pour aligner correctement la colonne Matière */
        table {
            width: 100%;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        /* Définir une largeur fixe pour la colonne Matière */
        th:nth-child(7), td:nth-child(7) {
            width: 120px; /* Ajustez cette valeur selon vos besoins */
        }
    </style>
    <script>
        function copyDate() {
            const dateValue = document.getElementById('global-date').value;
            const dateFields = document.querySelectorAll('input[name^="date"]');
            dateFields.forEach(field => field.value = dateValue);
        }

        function copyCoefficient() {
            const coefficientValue = document.getElementById('global-coefficient').value;
            const coefficientFields = document.querySelectorAll('input[name^="coefficient"]');
            coefficientFields.forEach(field => field.value = coefficientValue);
        }

        function copyNote() {
            const noteValue = document.getElementById('global-note').value;
            const noteFields = document.querySelectorAll('input[name^="note"]');
            noteFields.forEach(field => field.value = noteValue);
        }

        function copyMatiere() {
            const matiereValue = document.getElementById('global-matiere').value;
            const matiereFields = document.querySelectorAll('select[name^="matiere"]');
            matiereFields.forEach(field => field.value = matiereValue);
        }
    </script>
</head>
<body>
    <div class="sidebar">
        <div class="logo">
            <img src="../../image/EiffelNote_logo_V9.png" alt="Eiffel Note Logo">
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Classes</a></li>
                <li class="active"><a href="evaluation.php">Evaluations</a></li>
                <li><a href="profil.php">Profil</a></li>
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
            <div class="recherche">
                <form method="POST" action="">
                    <input type="text" placeholder="SAE, UE ..." id="search-input" name="search-term" />
                    <button type="submit">Recherche</button>
                </form>
            </div>
            <div class="profile">
                <img src="../../image/EiffelNote_logo_V9.png" alt="Profile Picture">
            </div>
        </header>
        <section class="class-table">
            <h3>Liste des élèves</h3>

            <?php
            session_start();
            if (isset($_SESSION['message'])) {
                echo "<p style='color: green;'>" . $_SESSION['message'] . "</p>";
                unset($_SESSION['message']);
            }
            ?>

            <form method="POST" action="save_grades.php">
                <table>
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Numéro Etudiant</th>
                            <th>TD</th>
                            <th>TP</th>
                            <th>Note</th>
                            <th>Matière</th>
                            <th>Date</th>
                            <th>Coefficient</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $servername = "localhost";
                        $username = "root";
                        $password = "";
                        $dbname = "effeil_note_db";

                        try {
                            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                            $searchTerm = isset($_POST['search-term']) ? $_POST['search-term'] : '';

                            $query = "SELECT etudiant.ID_UE, etudiant.prenom, etudiant.nom, etudiant.TD, etudiant.TP FROM etudiant";
                            
                            // You can also include conditions based on search term if needed
                            if (!empty($searchTerm)) {
                                $query .= " WHERE etudiant.prenom LIKE '%$searchTerm%' OR etudiant.nom LIKE '%$searchTerm%'";
                            }

                            $stmt = $conn->prepare($query);
                            $stmt->execute();

                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['nom']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['prenom']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['ID_UE']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['TD']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['TP']) . "</td>";
                                // Add other columns if needed
                                // Note, Matière, Date, Coefficient
                                echo "<td><input type='text' name='note[" . htmlspecialchars($row['ID_UE']) . "]' placeholder='Note'></td>";
                                echo "<td>
                                    <select name='matiere[" . htmlspecialchars($row['ID_UE']) . "]'>";
                                    
                                    // Requête pour récupérer les matières depuis la table ressource
                                    $query_matiere = "SELECT nom_de_la_ressource FROM ressource";
                                    $stmt_matiere = $conn->query($query_matiere);
                                    while ($ressource = $stmt_matiere->fetch(PDO::FETCH_ASSOC)) {
                                        echo "<option value='" . htmlspecialchars($ressource['nom_de_la_ressource']) . "'>" . htmlspecialchars($ressource['nom_de_la_ressource']) . "</option>";
                                    }

                                echo "</select></td>";
                                echo "<td><input type='date' name='date[" . htmlspecialchars($row['ID_UE']) . "]'></td>";
                                echo "<td><input type='text' name='coefficient[" . htmlspecialchars($row['ID_UE']) . "]' placeholder='Coefficient'></td>";
                                echo "</tr>";
                            }
                        } catch (PDOException $e) {
                            echo "Erreur de connexion : " . $e->getMessage();
                        }
                        ?>
                    </tbody>
                </table>
                <div>
                    <label for="global-date">Date globale:</label>
                    <input type="date" id="global-date">
                    <button type="button" class="copy-btn" onclick="copyDate()">Copier la date à tous</button>
                </div>
                <div>
                    <label for="global-coefficient">Coefficient global:</label>
                    <input type="text" id="global-coefficient">
                    <button type="button" class="copy-btn" onclick="copyCoefficient()">Copier le coefficient à tous</button>
                </div>
                <div>
                    <label for="global-note">Note globale:</label>
                    <input type="text" id="global-note">
                    <button type="button" class="copy-btn" onclick="copyNote()">Copier la note à tous</button>
                </div>
                <div>
                    <label for="global-matiere">Matière globale:</label>
                    <input type="text" id="global-matiere">
                    <button type="button" class="copy-btn" onclick="copyMatiere()">Copier la matière à tous</button>
                </div>
                <button type="submit">Enregistrer les notes et commentaires</button>
            </form>
        </section>
    </div>
</body>
</html>
