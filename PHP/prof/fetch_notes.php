<?php
// Informations de connexion à la base de données
$servername = 'localhost';
$username = 'root';
$password = ''; // Ajoutez votre mot de passe si nécessaire
$dbname = 'eiffel_note_db';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtenez le libellé de l'épreuve à partir de la requête GET
    $libelle = $_GET['libelle'];

    // Requête SQL pour récupérer les notes et les informations sur l'épreuve
    $stmt = $conn->prepare("
        SELECT e.nom, e.prenom, n.notes, ep.coefficients, ep.date_epreuve, ep.ID_epreuve
        FROM note n
        JOIN etudiant e ON n.ID_etudiant = e.ID_etudiant
        JOIN epreuves ep ON n.ID_epreuve = ep.ID_epreuve
        WHERE ep.libelle = :libelle
        ORDER BY e.nom, e.prenom
    ");
    $stmt->bindParam(':libelle', $libelle, PDO::PARAM_STR);
    $stmt->execute();
    $notes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($notes);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
