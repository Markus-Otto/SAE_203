<?php
// Informations de connexion à la base de données
$servername = 'localhost';
$username = 'root';
$password = ''; // Ajoutez votre mot de passe si nécessaire
$dbname = 'eiffel_note_db';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupération des ressources depuis la table 'ressource'
    $stmt = $conn->prepare("SELECT ID_ressource, nom_de_la_ressource FROM ressource");
    $stmt->execute();
    $resources = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($resources);
} catch (PDOException $e) {
    echo json_encode([]);
}
?>
