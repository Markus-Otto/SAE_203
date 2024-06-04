<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des comptes</title>
    <link rel="stylesheet" href="accueil_admin.css">
    <link rel="icon" type="image/gif" href="../image/EiffelNote_logo_V9.png" class="img2"/>
</head>
<body>
    <div class="sidebar">
        <div class="logo">EIFFEL NOTE</div>
        <div class="menu">
            <div>Accueil</div>
            <div class="active">Gestion des comptes</div>
            <div>Gestion des ressources</div>
        </div>
    </div>
    <div class="main-content">
        <header>
            <input type="text" placeholder="SAE, UE ...">
            <button>Recherce</button>
            <div class="profile">
                <img src="" alt="Profile Picture">
            </div>
        </header>
        <h1>Gestion des comptes</h1>
        <div class="content">
            <div class="form-section">
                <h2>Saisie étudiant</h2>
                <form action="gestion_admin.php" method="post">
                    <input type="text" name="nom" id="nom" placeholder="Nom" required>
                    <input type="text" name="prenom" id="prenom" placeholder="Prénom" required>
                    <input type="text" name="tp" id="tp" placeholder="TP" required>
                    <input type="text" name="Td" id="td" placeholder="TD" required>
                    <input type="text" name="annee_univ" id="anne" placeholder="Année univ" required>
                    <button onclick="envoyer()"></button>
                    <button type="reset">Effacer</button>
                </form>
            </div>
            <div class="update-section">
                <h2>Mis à jour des étudiant</h2>
                <div class="student-list">
                    <?php include 'gestion_admin.php'; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>