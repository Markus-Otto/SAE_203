<?php
session_start();

include "fct_connection.php";

// Récupérer les données du formulaire de connexion
$conn = connexion();

$login = $_POST['login'];
$pass = $_POST['password'];

// Préparer et exécuter la requête SQL
$sql = $conn->prepare("SELECT username, pass FROM users WHERE username = ? AND pass = ? ");
if ($sql === false) {
    die("Erreur de préparation de la requête: " . $conn->error);
}
$hashed_password=md5($pass);

$sql->bind_param('ss', $login,$hashed_password );
$sql->execute();
$sql->store_result();

if ($sql->num_rows > 0) {
    $sql->bind_result( $hashed_password, $username);
    $sql->fetch();

    if (!password_verify($pass, $hashed_password)) {
        // Initialiser la session
    
        $_SESSION['username'] = $login;
        $_SESSION['password'] = $pass;
         // Rediriger vers la page d'accueil ou tableau de bord
        exit();
    } else {
        echo "Mot de passe incorrect.";
    }
} else  {
    echo "Identifiant incorrect.";
    echo $login. "<br>";
    echo "Hashed Password: " . $hashed_password . "<br>";
    echo $pass.'<br>';
    echo $sql->num_rows.'<br>';
    echo $hashed_password.'<br>';
}

$sql->close();
$conn->close();
?>