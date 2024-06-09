<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../../../../Accueil_note.php");
    exit();
}
?>