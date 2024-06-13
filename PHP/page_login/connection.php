<?php
session_start();
include "fct_connection.php";

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = $_POST['login'];
    $pass = $_POST['password'];
    $role = $_POST['role'];

    $conn = connexion();

    // Prepare the SQL statement
    $sql = $conn->prepare("SELECT username, pass FROM users WHERE username = ?");
    if ($sql === false) {
        die("Erreur de préparation de la requête: " . $conn->error);
    }

    // Bind parameters and execute
    $sql->bind_param('s', $login);
    $sql->execute();
    $sql->store_result();

    if ($sql->num_rows > 0) {
        $sql->bind_result($username, $stored_hashed_password);
        $sql->fetch();

        // Check if the password is correct
        if (md5($pass) == $stored_hashed_password) {
            $_SESSION['username'] = $login;
            $_SESSION['role'] = $role;

            // Redirect based on the role
            if ($role === 'admin') {
                header("Location: ../Admin/accueil_admin.php");
            } elseif ($role === 'etudiant') {
                header("Location: ../../etudiant_page.php");
            } elseif ($role === 'enseignant') {
                header("Location: ../../enseignant_page.php");
            } else {
                echo "Rôle inconnu ou non correspondant.";
            }
            exit();
        } else {
            echo "Mot de passe incorrect.";
        }
    } else {
        echo "Identifiant incorrect.";
    }

    // Close the SQL statement and connection
    $sql->close();
    $conn->close();
}
?>