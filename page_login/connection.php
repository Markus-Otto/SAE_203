<?php
session_start();

include "fct_connection.php";

// Récupérer les données du formulaire de connexion
$conn = connexion();

$login = $_POST['login'];
$pass = $_POST['password'];
$role = $_POST['role'];

// Préparer et exécuter la requête SQL
$sql = $conn->prepare("SELECT username, pass FROM users WHERE username = ? ");
if ($sql === false) {
    die("Erreur de préparation de la requête: " . $conn->error);
}

$sql->bind_param('s', $login);
$sql->execute();
$sql->store_result();

if ($sql->num_rows > 0) {
    $sql->bind_result($username, $stored_hashed_password);
    $sql->fetch();

    if (!password_verify($pass, $stored_hashed_password)) {
        // Initialiser la session
        $_SESSION['username'] = $login;
        $_SESSION['role'] = $role;

        // Rediriger en fonction du rôle
        if ($role === 'etudiant') {
            header("Location: etudiant.php");
        } elseif ($role === 'enseignant') {
            header("Location: enseignant.php");
        } elseif ($role === 'admin') {
            header("/Admin/accueil_admin.php");
        } else {
            echo "Rôle inconnu.";
        }
        exit();
    } else {
        echo "Mot de passe incorrect.";
    }
} else {
    echo "Identifiant incorrect.";
}

$sql->close();
$conn->close();
?>