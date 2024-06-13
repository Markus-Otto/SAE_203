<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classes - Eiffel Note</title>
    <link rel="stylesheet" href="../../CSS/styleprofclasse.css">
    <style>
        .filter-menu {
            position: relative;
            display: inline-block;
        }
        .filter-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }
        .filter-content button {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            width: 100%;
            border: none;
            background: none;
            text-align: left;
            cursor: pointer;
        }
        .filter-content button:hover {
            background-color: #f1f1f1;
        }
        .dropdown-content {
            display: none;
        }
        .show {
            display: block;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo">
            <img src="../../image/EiffelNote_logo_V9.png" alt="Eiffel Note Logo">
        </div>
        <nav>
            <ul>
                <li class="active"><a href="index.php">Classes</a></li>
                <li><a href="evaluation.php">Evaluations</a></li>
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
                exit();
            }
            ?>
        </form>
    </div>
    <div class="main-content">
        <header>
            <div class="recherche">
                <form method="POST" action="">
                    <input type="text" placeholder="SAE, UE ..." id="search-input" name="search-term" />
                    <button type="submit">Recherche</button>
                    <div class="filter-menu">
                        <button type="button" onclick="toggleFilterMenu()">Filtre</button>
                        <div id="filter-content" class="filter-content">
                            <button type="button" onclick="toggleDropdown('tp-dropdown')">TP</button>
                            <div id="tp-dropdown" class="dropdown-content">
                                <button type="button" onclick="setFilter('TP', 'A')">A</button>
                                <button type="button" onclick="setFilter('TP', 'B')">B</button>
                                <button type="button" onclick="setFilter('TP', 'C')">C</button>
                                <button type="button" onclick="setFilter('TP', 'D')">D</button>
                                <button type="button" onclick="setFilter('TP', 'E')">E</button>
                                <button type="button" onclick="setFilter('TP', 'F')">F</button>
                            </div>
                            <button type="button" onclick="toggleDropdown('td-dropdown')">TD</button>
                            <div id="td-dropdown" class="dropdown-content">
                                <button type="button" onclick="setFilter('TD', '1')">1</button>
                                <button type="button" onclick="setFilter('TD', '2')">2</button>
                                <button type="button" onclick="setFilter('TD', '3')">3</button>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="filter-type" name="filter-type" />
                    <input type="hidden" id="filter-value" name="filter-value" />
                </form>
            </div>
        </header>

        <section class="classes">
            <h2>Classes</h2>
            <div class="class-table">
                <div class="class-header">
                    <span>MMI 1</span>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Numéro Etudiant</th>
                            <th>Note</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
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

                            // Récupération des filtres
                            $filterType = isset($_POST['filter-type']) ? $_POST['filter-type'] : '';
                            $filterValue = isset($_POST['filter-value']) ? $_POST['filter-value'] : '';

                            // Construction de la requête SQL avec filtres
                            $query = "SELECT DISTINCT ID_utilisateur, prenom, nom, TD, TP FROM etudiant";
                            if ($filterType && $filterValue) {
                                $query .= " WHERE $filterType = :filterValue";
                            }
                            $query .= " ORDER BY nom, prenom";

                            $stmt = $conn->prepare($query);
                            if ($filterType && $filterValue) {
                                $stmt->bindParam(':filterValue', $filterValue);
                            }
                            $stmt->execute();

                            // Affichage des données pour chaque élève
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['nom']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['prenom']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['ID_utilisateur']) . "</td>";
                                // Récupération des notes de l'élève
                                $query_note = "SELECT Note FROM epreuves WHERE ID_utilisateur = :ID_utilisateur";
                                $stmt_note = $conn->prepare($query_note);
                                $stmt_note->bindParam(':ID_utilisateur', $row['ID_utilisateur']);
                                $stmt_note->execute();
                                $note_row = $stmt_note->fetch(PDO::FETCH_ASSOC);
                                // Affichage de la note ou d'une cellule vide s'il n'y a pas de note
                                echo "<td>";
                                if ($note_row) {
                                    echo htmlspecialchars($note_row['Note']);
                                }
                                echo "</td>";
                                echo "</tr>";
                            }
                        } catch (PDOException $e) {
                            echo "Erreur de connexion : " . $e->getMessage();
                        }

                        // Fermeture de la connexion
                        $conn = null;
                        ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <script>
        function toggleFilterMenu() {
            document.getElementById('filter-content').classList.toggle('show');
        }

        function toggleDropdown(id) {
            var dropdowns = document.getElementsByClassName('dropdown-content');
            for (var i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (openDropdown.id === id) {
                    openDropdown.classList.toggle('show');
                } else {
                    openDropdown.classList.remove('show');
                }
            }
        }

        function setFilter(type, value) {
            document.getElementById('filter-type').value = type;
            document.getElementById('filter-value').value = value;
            document.forms[0].submit();
        }

        window.onclick = function(event) {
            if (!event.target.matches('.filter-menu button')) {
                var dropdowns = document.getElementsByClassName('dropdown-content');
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
                var filterContent = document.getElementById('filter-content');
                if (filterContent.classList.contains('show')) {
                    filterContent.classList.remove('show');
                }
            }
        }
    </script>
</body>
</html>
