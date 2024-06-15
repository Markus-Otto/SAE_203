<?php
session_start();
include "fnct_conn.php";
$conn = connexion();

// Vérifier si le formulaire d'ajout est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ajouterRessource'])) {
    // Récupérer les données du formulaire
    $nomRessource = htmlspecialchars($_POST['ressource']);
    $idEnseignant = intval($_POST['enseignant']);
    $nomUE = htmlspecialchars($_POST['ue']);
    $coef = intval($_POST['coef']);

    // Préparer la requête SQL pour insérer la nouvelle ressource
    $insertQuery = "INSERT INTO ressource (nom_de_la_ressource, ID_enseignant, Nom_UE, coef) 
                    VALUES ('$nomRessource', $idEnseignant, '$nomUE', $coef)";

    if ($conn->query($insertQuery) === TRUE) {
        echo '<script>alert("La ressource a été ajoutée avec succès.");</script>';
        // Rafraîchir la page pour afficher la nouvelle ressource
        echo '<script>window.location.href = "gestion_ressource.php";</script>';
        exit();
    } else {
        echo "Erreur lors de l'ajout de la ressource : " . $conn->error;
    }
}

// Récupérer la liste des enseignants
$enseignantsQuery = "SELECT ID_enseignant, username FROM enseignant";
$enseignantsResult = $conn->query($enseignantsQuery);

// Récupérer la liste des UEs
$ueQuery = "SELECT Nom_UE FROM ue";
$ueResult = $conn->query($ueQuery);

// Récupérer la liste des ressources existantes
$ressourceQuery = "SELECT ressource.ID_ressource, ressource.nom_de_la_ressource, enseignant.username, ue.Nom_UE, ressource.coef FROM ressource
                   JOIN enseignant ON ressource.ID_enseignant = enseignant.ID_enseignant
                   JOIN ue ON ressource.Nom_UE = ue.Nom_UE";
$ressourceResult = $conn->query($ressourceQuery);

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mise à jour des ressources</title>
    <link rel="stylesheet" href="/SAE203/CSS/gestion_ressource.css">
</head>
<body>
<div class="sidebar">
        <div class="logo">EIFFEL NOTE</div>
        <div class="menu">
            <div class="active"><a href="./accueil_admin.php">Gestion des comptes</a></div>
            <div><a href="./gestion_ressource.php">Gestion des ressources</a></div>
            <div class="logout">
        <form method="post" action="accueil_admin.php">
            <button type="submit" name="logout">Déconnexion</button>
    <?php
         if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['logout'])) {
            session_start();
            session_unset();
            session_destroy();
            header("Location: /sae203/Accueil_note.php");
             exit();}?>
            </form>
        </div>
    </div>
    </div>
    </div>
    <H1 id="Titre"> Cher administrateur vous voici a la page de gestion de ressources </H1>
<div id="contenu_de_la_page">  
    <div class="container">
    <div class="form-section">
        <h2>Par ici pour ajouter une nouvelle ressource !</h2>
        <form id="addResourceForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <table>
                <thead>
                    <tr>
                        <th>Ressource</th>
                        <th>Professeur</th>
                        <th>UE</th>
                        <th>Coefficient</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="text" name="ressource" placeholder="Nom de la ressource" required></td>
                        <td>
                            <select name="enseignant" required>
                                <?php
                                if ($enseignantsResult->num_rows > 0) {
                                    while($row = $enseignantsResult->fetch_assoc()) {
                                        echo "<option value='" . $row['ID_enseignant'] . "'>" . $row['username'] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </td>
                        <td>
                            <select name="ue" required>
                                <?php
                                if ($ueResult->num_rows > 0) {
                                    while($row = $ueResult->fetch_assoc()) {
                                        echo "<option value='" . $row['Nom_UE'] . "'>" . $row['Nom_UE'] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </td>
                        <td><input type="number" name="coef" placeholder="Coefficient" required></td>
                        <td><button type="submit" name="ajouterRessource">Ajouter</button></td>
                    </tr>
                </tbody>
            </table>
        </form>
        </div>
        <div class="form-section">
    <h2>Ressources existantes</h2>
    <!-- Bouton pour actualiser la page -->
    <button class="bouton_actualiser" onclick="actualiserPage()">Actualiser</button>
    <table id="resourceTable">
        <thead>
            <tr>
                <th class="en_tête_tableau">ID</th>
                <th class="en_tête_tableau">Ressource</th>
                <th class="en_tête_tableau">Professeur</th>
                <th class="en_tête_tableau">UE</th>
                <th class="en_tête_tableau">Coefficient</th>
                <th class="en_tête_tableau">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($ressourceResult->num_rows > 0) {
                while ($row = $ressourceResult->fetch_assoc()) {
                    echo "<tr>
                            <td>" . $row['ID_ressource'] . "</td>
                            <td>" . $row['nom_de_la_ressource'] . "</td>
                            <td>" . $row['username'] . "</td>
                            <td>" . $row['Nom_UE'] . "</td>
                            <td>" . $row['coef'] . "</td>
                            <td>
                                <button onclick=\"ouvrirModification(" . $row['ID_ressource'] . ")\">Modifier</button>
                                <button onclick=\"supprimerRessource(" . $row['ID_ressource'] . ")\">Supprimer</button>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='6'>Aucune ressource trouvée.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
</div>

    <!-- Fenêtre modale pour la modification de la ressource -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="Fermeture_de_la_modification()">&times;</span>
            <iframe id="modalFrame"></iframe>
        </div>
    </div>

    <script>
        // Fonction pour ouvrir la fenêtre modale avec le formulaire de modification
        function ouvrirModification(id) {
            var modal = document.getElementById("myModal");
            var modalFrame = document.getElementById("modalFrame");
            modal.style.display = "block";
            modalFrame.src = "update_ressource.php?id=" + id;
        }

        // Fonction pour supprimer une ressource
        function supprimerRessource(id) {
            if (confirm("Êtes-vous sûr de vouloir supprimer cette ressource ?")) {
                window.location.href = "delete_ressource.php?action=delete&id=" + id;
            }
        }
        // Fonction pour actualiser la page
        function actualiserPage() {
            location.reload();
        }

        // Fonction pour fermer la fenêtre modale
        function Fermeture_de_la_modification() {
            var modal = document.getElementById("myModal");
            var modalFrame = document.getElementById("modalFrame");
            modal.style.display = "none";
            modalFrame.src = "";
        }
    </script>
</body>
</html>