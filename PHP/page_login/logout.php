<?php
session_start();
session_unset();
session_destroy();
header("Location: ../../../../Accueil_note.php");
exit();
?>
