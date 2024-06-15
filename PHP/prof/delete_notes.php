<?php
header('Content-Type: application/json');

// Informations de connexion à la base de données
$servername = 'localhost';
$username = 'root';
$password = ''; // Ajoutez votre mot de passe si nécessaire
$dbname = 'eiffel_note_db';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtenez l'ID de l'épreuve à partir de la requête POST
    if (!isset($_POST['ID_epreuve'])) {
        echo json_encode(['error' => 'ID_epreuve non spécifié']);
        exit;
    }

    $ID_epreuve = $_POST['ID_epreuve'];
    // Ajoutez cette ligne pour vérifier l'ID reçu
    error_log("Deleting note with ID_epreuve: " . $ID_epreuve);

    // Requête SQL pour supprimer les notes liées à l'épreuve
    $stmt = $conn->prepare("DELETE FROM note WHERE ID_epreuve = :ID_epreuve");
    $stmt->bindParam(':ID_epreuve', $ID_epreuve, PDO::PARAM_INT);
    $noteDeleteSuccess = $stmt->execute();
    error_log("Notes deleted: " . $noteDeleteSuccess);

    // Requête SQL pour supprimer l'épreuve
    $stmt = $conn->prepare("DELETE FROM epreuves WHERE ID_epreuve = :ID_epreuve");
    $stmt->bindParam(':ID_epreuve', $ID_epreuve, PDO::PARAM_INT);
    $epreuveDeleteSuccess = $stmt->execute();
    error_log("Epreuve deleted: " . $epreuveDeleteSuccess);

    if ($noteDeleteSuccess && $epreuveDeleteSuccess) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Erreur lors de la suppression des notes ou de l\'épreuve']);
    }
} catch (PDOException $e) {
    error_log("PDOException: " . $e->getMessage());
    echo json_encode(['error' => $e->getMessage()]);
}
?>
