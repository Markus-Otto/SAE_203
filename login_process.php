<?php
session_start();

// Paramètres de connexion à la base de données

$req="SELECT password FROM users WHERE username = ?";


echo "<table border='1'>";
echo
"<tr><th>password</th></tr>";
?>

