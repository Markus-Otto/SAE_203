<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Eiffel Note</title>
    <link rel="stylesheet" href="accueil.css">
</head>
<body>
    <header>
        <div class="logo-container">
            <img src="jdh.png" alt="Eiffel Note Logo">
        </div>
        <h1>Bienvenue sur Eiffel Note</h1>
    </header>
    <main>
        <section class="welcome-section">
            <h2>Bonjour, <?php echo htmlspecialchars($_SESSION['login']); ?>!</h2>
            <p>Bienvenue sur votre tableau de bord. Vous pouvez accéder à vos notes, consulter les ressources, et bien plus encore.</p>
        </section>
        <nav class="nav-section">
            <ul>
                <li><a href="#">Mes Notes</a></li>
                <li><a href="#">Ressources</a></li>
                <li><a href="#">Messages</a></li>
                <li><a href="#">Paramètres</a></li>
                <li><a href="logout.php">Déconnexion</a></li>
            </ul>
        </nav>
    </main>
</body>
</html>
