<?php
header('Content-Type: application/json');

// Connexion à la base de données
$servername = 'localhost';
$username = 'root';
$password = ''; // Ajoutez votre mot de passe si nécessaire
$dbname = 'eiffel_note_db';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupération des données du formulaire
    $ID_ressource = $_POST['ID_ressource'];
    $coefficient_global = $_POST['coefficient_global'];
    $libelle = $_POST['libelle'];
    $date_epreuve = $_POST['date_epreuve'];
    $notes = $_POST['notes'];

    foreach ($notes as $ID_etudiant => $note) {
        // Insertion dans la table epreuves
        $stmt = $conn->prepare("INSERT INTO epreuves (ID_ressource, note, libelle, date_epreuve, Coefficients) 
                                VALUES (:ID_ressource, :note, :libelle, :date_epreuve, :coefficient_global)");
        $stmt->bindParam(':ID_ressource', $ID_ressource);
        $stmt->bindParam(':libelle', $libelle);
        $stmt->bindParam(':date_epreuve', $date_epreuve);
        $stmt->bindParam(':coefficient_global', $coefficient_global);
        $stmt->bindParam(':note', $note);
        $stmt->execute();
        $ID_epreuve = $conn->lastInsertId(); // Récupération de l'ID auto-incrémenté de l'épreuve
        
        // Insertion dans la table note
        $stmt = $conn->prepare("INSERT INTO note (ID_epreuve, ID_etudiant, notes) 
                                VALUES (:ID_epreuve, :ID_etudiant, :note)");
        $stmt->bindParam(':ID_epreuve', $ID_epreuve);
        $stmt->bindParam(':ID_etudiant', $ID_etudiant);
        $stmt->bindParam(':note', $note);
        $stmt->execute();
    }
    

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
