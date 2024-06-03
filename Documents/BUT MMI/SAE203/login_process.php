<?php
// login_process.php

// Démarrer une session
session_start();

// Remplacer par vos informations de connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Eiffel_note";

// Connexion à la base de données
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Récupérer les données du formulaire
$login = $_POST['login'];
$password = $_POST['password'];

// Requête SQL pour vérifier les informations de l'utilisateur
$sql = "SELECT * FROM Etudiants WHERE Nom = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $login);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Vérifier le mot de passe
    if (password_verify($password, $row['mot_de_passe'])) {
        // Mot de passe correct, utilisateur trouvé
        $_SESSION['login'] = $login;
        // Rediriger vers une page de succès ou tableau de bord
        header("Location: accueil.html");
        exit();
    } else {
        // Mot de passe incorrect
        header("Location: index.php?error=1");
        exit();
    }
} else {
    // Utilisateur non trouvé
    header("Location: index.php?error=1");
    exit();
}

$stmt->close();
$conn->close();
?>
