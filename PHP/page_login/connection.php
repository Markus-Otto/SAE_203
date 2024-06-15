<?php
session_start();
include "fct_connection.php";

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = $_POST['login'];
    $pass = $_POST['password'];

    $conn = connexion();
    $hashed_password = md5($pass);
    echo "Login: " . htmlspecialchars($login) . "<br>";
    echo "Hashed Password: " . htmlspecialchars($hashed_password) . "<br>";
    // Prepare the SQL statement
    $sql = $conn->prepare("
        SELECT username, pass, 'admin' as role FROM admin WHERE username = ? AND pass = ?
        UNION ALL
        SELECT username, pass, 'etudiant' as role FROM etudiant WHERE username = ? AND pass = ?
        UNION ALL
        SELECT username, pass, 'enseignant' as role FROM enseignant WHERE username = ? AND pass = ?
    ");
    if ($sql === false) {
        die("Erreur de préparation de la requête: " . $conn->error);
    }

    $sql->bind_param('ssssss', $login, $hashed_password, $login, $hashed_password, $login, $hashed_password);
    $sql->execute();
    $sql->store_result();

    if ($sql->num_rows > 0) {
        $sql->bind_result($username, $pass, $user_role );
        $sql->fetch();

        // Check if the password is correct
        if ($hashed_password == $pass) {
            $_SESSION['username'] = $login;
            $_SESSION['role'] = $user_role;

            // Redirect based on the role
            if ($user_role === 'admin') {
                header("Location: ../Admin/accueil_admin.php");
            } elseif ($user_role === 'etudiant') {
                header("Location: ../étudiant/recap_notes.php");
            } elseif ($user_role === 'enseignant') {
                header("Location: ../prof/index.php");
            } else {
                echo "Rôle inconnu ou non correspondant.";
            }
            exit();
        } else {
            echo "Mot de passe incorrect.";
        }
    } else {
        echo $sql->num_rows;
        echo "Identifiant incorrect.";
    }

    // Close the SQL statement and connection
    $sql->close();
    $conn->close();
}
?>