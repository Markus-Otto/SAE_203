<?php
session_start();
include "fnct_conn.php";
$conn = connexion();
// Vérifier si l'ID de la ressource est passé en paramètre
if (isset($_GET['id'])) {
    $ressource_id = $_GET['id'];

    // Récupérer les informations actuelles de la ressource
    $query = "SELECT * FROM ressource WHERE ID_ressource = $ressource_id";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $ressource_nom = $row['nom_de_la_ressource'];
        $enseignant_id = $row['ID_enseignant'];
        $ue_nom = $row['Nom_UE'];
        $coef = $row['coef'];
    } else {
        echo "Aucune ressource trouvée avec l'ID spécifié.";
        exit();
    }

    // Récupérer la liste des enseignants
    $enseignantsQuery = "SELECT ID_enseignant, username FROM enseignant";
    $enseignantsResult = $conn->query($enseignantsQuery);

    // Récupérer la liste des UEs
    $ueQuery = "SELECT Nom_UE FROM ue";
    $ueResult = $conn->query($ueQuery);
} else {
    echo "ID de la ressource non spécifié.";
    exit();
}

// Traitement du formulaire de mise à jour
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['updateRessource'])) {
    $ressource = $_POST['ressource'];
    $enseignant_id = $_POST['enseignant'];
    $ue_nom = $_POST['ue'];
    $coef = $_POST['coef'];

    // Préparer la requête SQL pour mettre à jour la ressource
    $updateQuery = "UPDATE ressource SET nom_de_la_ressource = '$ressource', ID_enseignant = '$enseignant_id', Nom_UE = '$ue_nom', coef = '$coef' WHERE ID_ressource = $ressource_id";
  
}
// Traitement du formulaire de mise à jour
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['updateRessource'])) {
    $ressource = $_POST['ressource'];
    $enseignant_id = $_POST['enseignant'];
    $ue_nom = $_POST['ue'];
    $coef = $_POST['coef'];

    // Préparer la requête SQL pour mettre à jour la ressource
    $updateQuery = "UPDATE ressource SET nom_de_la_ressource = '$ressource', ID_enseignant = '$enseignant_id', Nom_UE = '$ue_nom', coef = '$coef' WHERE ID_ressource = $ressource_id";

    if ($conn->query($updateQuery) === TRUE) {
        // JavaScript pour fermer le modal et le popup
        echo '<script>
                alert("La ressource a été mise à jour avec succès.");
                var modal = document.getElementById("votreModalID");
                modal.style.display = "none";

                window.opener.location.reload();
                window.close();
              </script>';
        exit();
    } else {
        echo "Erreur lors de la mise à jour de la ressource : " . $conn->error;
    }    
}



$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modification de la ressource</title>
    <link rel="stylesheet" href="/sae203/CSS/accueil_admin.css">
</head>
<body>
    <div class="container">
        <h2>Modifier la ressource</h2>
        <form id="updateResourceForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $ressource_id; ?>">
            <table border="1">
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
                        <td><input type="text" name="ressource" value="<?php echo htmlspecialchars($ressource_nom); ?>" required></td>
                        <td>
                            <select name="enseignant" required>
                                <?php
                                if ($enseignantsResult->num_rows > 0) {
                                    while($row = $enseignantsResult->fetch_assoc()) {
                                        $selected = $row['ID_enseignant'] == $enseignant_id ? 'selected' : '';
                                        echo "<option value='" . $row['ID_enseignant'] . "' $selected>" . htmlspecialchars($row['username']) . "</option>";
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
                                        $selected = $row['Nom_UE'] == $ue_nom ? 'selected' : '';
                                        echo "<option value='" . $row['Nom_UE'] . "' $selected>" . htmlspecialchars($row['Nom_UE']) . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </td>
                        <td><input type="number" name="coef" value="<?php echo htmlspecialchars($coef); ?>" required></td>
                        <td><button type="submit" name="updateRessource">Modifier</button></td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
</body>
</html>