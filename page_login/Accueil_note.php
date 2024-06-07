<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eiffel Note</title>
    <link rel="stylesheet" href="accueil_note.css">
    <link rel="icon" type="image/gif" href="../image/EiffelNote_logo_V9.png" class="img2"/>
</head>
<body>
    <div class="login-container">
        <div class="logo-container">
            <img src="../image/EiffelNote_logo_V9.png" alt="Eiffel Note Logo" class="img1">
        </div>
        <h2 class="title1">EIFFEL NOTE</h2>
        <form class="login-form" action="connection.php" method="POST">
            <div class="input-container">
                <label for="login">Identifiant :</label>
                <input class="input1" type="text" id="login" name="login" placeholder="Entrez votre identifiant ..." required>
            </div>
            <div class="input-container">
                <label for="password">Mot de passe :</label>
                <input class="input2" type="password" id="password" name="password" placeholder="Entrez votre mot de passe" required>
            </div>
            <select name="role">
                        <option value="etudiant">Etudiant</option>
                        <option value="enseignant">Enseignant</option>
                        <option value="admin">admin</option>
            </select>
            <button type="submit" class="login-button">Connexion</button>
        </form>
    </div>
</body>
</html>

