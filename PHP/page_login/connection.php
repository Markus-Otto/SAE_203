<?php
session_start();

include "fct_connection.php";

// Récupérer les données du formulaire de connexion
$conn = connexion();

$login = trim($_POST['login']);
$pass = $_POST['password'];
$role = $_POST['role'];

// Préparer et exécuter la requête SQL
$sql = $conn->prepare("SELECT u.id_users, u.username, u.pass, ut.ID_utilisateur, en.ID_enseignant 
                        FROM users u
                        LEFT JOIN etudiant ut ON u.ID_utilisateur = ut.ID_utilisateur
                        LEFT JOIN enseignant en ON u.ID_enseignant = en.ID_enseignant
                        WHERE u.username = ? AND u.pass = ?");
if ($sql === false) {
    die("Erreur de préparation de la requête: " . $conn->error);
}
$stored_hashed_password=md5($pass);

$sql->bind_param('ss', $login,$stored_hashed_password); // Bind uniquement le paramètre login
$sql->execute();
$sql->store_result();
$username="u.username";
if ($sql->num_rows > 0) {
    $sql->bind_result($id_users, $username, $stored_hashed_password, $ID_utilisateur, $ID_enseignant);
    $sql->fetch();

    // Debug: Afficher les valeurs récupérées de la base de données

    if (!password_verify($pass, $stored_hashed_password)) {
        // Initialiser la session
        $_SESSION['username'] = $login;
        $_SESSION['role'] = $role;
        $_SESSION['password'] = $pass;

        // Rediriger en fonction du rôle
        if ($role === 'etudiant' ) {
            header("Location: etudiant.php");
        } elseif ($role === 'enseignant' ) {
            header("Location: enseignant.php");
        } elseif ($role === 'admin') {
            header("Location: /Admin/accueil_admin.php");
        } else {
            echo "Rôle inconnu ou non correspondant.";
        }
        exit();
    } else {
        echo "Mot de passe incorrect.";
    }
} else {
    echo "Identifiant incorrect.";
    echo $login;
    echo $role;
}

$sql->close();
$conn->close();
?>