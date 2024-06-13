<?php
session_start();

// Vérification des données soumises
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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

        // Parcourir les notes soumises
        foreach ($_POST['note'] as $ID_UE => $note) {
            // Vérifier si la note est non vide
            if (!empty($note)) {
                // Préparation et exécution de la requête pour obtenir l'ID_epreuve
                $stmt = $conn->prepare("SELECT ID_epreuve FROM epreuves WHERE ID_UE = :id");
                $stmt->bindParam(':id', $ID_UE);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $ID_epreuve = $row['ID_epreuve'];

                // Vérifier si l'ID_epreuve existe
                if ($ID_epreuve) {
                    // Préparation et exécution de la requête de mise à jour
                    $stmt = $conn->prepare("UPDATE epreuves SET Note = :note WHERE ID_epreuve = :id");
                    $stmt->bindParam(':note', $note);
                    $stmt->bindParam(':id', $ID_epreuve);
                    $stmt->execute();
                }
            }
        }

        // Message de succès
        $_SESSION['message'] = "Les notes ont été enregistrées avec succès.";

        // Redirection après la sauvegarde
        header('Location: ' . $_SERVER['HTTP_REFERER']); // Redirection vers la page précédente
        exit;
    } catch (PDOException $e) {
        // Message d'erreur en cas de problème de connexion
        $_SESSION['message'] = "Erreur de connexion : " . $e->getMessage();
        header('Location: ' . $_SERVER['HTTP_REFERER']); // Redirection vers la page précédente
        exit;
    } finally {
        // Fermeture de la connexion
        $conn = null;
    }
} else {
    // Redirection en cas d'accès non autorisé
    header('Location: ' . $_SERVER['HTTP_REFERER']); // Redirection vers la page précédente
    exit;
}
?>
