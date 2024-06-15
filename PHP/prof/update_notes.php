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

    // Obtenez les données du formulaire POST
    if (!isset($_POST['ID_epreuve']) || !isset($_POST['notes']) || !isset($_POST['coefficients'])) {
        echo json_encode(['error' => 'Données manquantes']);
        exit;
    }

    $ID_epreuve = $_POST['ID_epreuve'];
    $notes = $_POST['notes'];
    $coefficients = $_POST['coefficients'];

    // Requête SQL pour mettre à jour la note et le coefficient
    $stmt = $conn->prepare("UPDATE note n
                            JOIN epreuves ep ON n.ID_epreuve = ep.ID_epreuve
                            SET n.notes = :notes, ep.coefficients = :coefficients
                            WHERE n.ID_epreuve = :ID_epreuve");
    $stmt->bindParam(':ID_epreuve', $ID_epreuve, PDO::PARAM_INT);
    $stmt->bindParam(':notes', $notes, PDO::PARAM_INT);
    $stmt->bindParam(':coefficients', $coefficients, PDO::PARAM_STR);
    $updateSuccess = $stmt->execute();

    if ($updateSuccess) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Erreur lors de la mise à jour de la note']);
    }
} catch (PDOException $e) {
    error_log("PDOException: " . $e->getMessage());
    echo json_encode(['error' => $e->getMessage()]);
}
?>
