<?php
session_start();
include "fnct_conn.php";

$conn = connexion();

if (isset($_GET['id'])) {
    $ressource_id = $_GET['id'];

    $deleteQuery = "DELETE FROM ressource WHERE ID_ressource = $ressource_id";

    if ($conn->query($deleteQuery) === TRUE) {
        header("Location: gestion_ressource.php");
        exit();
    } else {
        echo "Erreur lors de la suppression de la ressource : " . $conn->error;
    }
} else {
    echo "ID de la ressource non spécifié.";
    exit();
}

$conn->close();
?>