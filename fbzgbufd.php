<?php
session_start();

include "fct_connection.php";

// Récupérer les données du formulaire de connexion
$conn = connexion();

$login = $_POST['login'];
$pass = $_POST['password'];

echo $login;
echo $pass;

// Préparer et exécuter la requête SQL
$sql = $conn->prepare("SELECT id, password FROM users WHERE username = ? AND password = ?");
$sql->bind_param('ss',$login,$pass);
$sql->execute();
$sql->store_result();

$pass1= md5($pass);
echo $pass1;
// Vérifier si l'utilisateur existe
if ($sql->num_rows > 0) {
    $sql->bind_result($login, $pass);
    $sql->fetch();

    // Vérifier le mot de passe
    if (password_verify($pass)) {
        // Initialiser la session 
      //  $_SESSION['userid'] = $id;
        $_SESSION['username'] = $login;
        header("Location: dashboard.php"); // Rediriger vers la page d'accueil ou tableau de bord
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




<?php
session_start();

include "fct_connection.php";

// Récupérer les données du formulaire de connexion
$conn = connexion();

$login = $_POST['login'];
$pass = $_POST['password'];

echo $login;
echo $pass;

// Préparer et exécuter la requête SQL
$sql = $conn->prepare("SELECT 'username', 'password' FROM users WHERE 'username' = ? AND 'password' = ?");
if ($sql === false) {
    die("Erreur de préparation de la requête: " . $conn->error);
}
$sql->bind_param('ss', $login,$pass);
$sql->execute();
$sql->store_result();

// Vérifier si l'utilisateur existe
if ($sql->num_rows>0) {
    $sql->bind_result($login, $hashed_password);
    $sql->fetch();

    // Vérifier le mot de passe
    if (password_verify($pass, $hashed_password)) {
        // Initialiser la session
        $_SESSION['userid'] = $id;
        $_SESSION['username'] = $login;
        header("Location: Accueil_note.php"); // Rediriger vers la page d'accueil ou tableau de bord
        exit();
    } else {
        echo "Mot de passe incorrect.";
    }
} else  {
    echo "Identifiant incorrect.";

}

$sql->close();
$conn->close();
?>