<?php
session_start();
include "fnct_conn.php";

$conn = connexion();

function envoyer($conn) {
    if (isset($_POST['id'], $_POST['nom'], $_POST['prenom'], $_POST['TD'], $_POST['TP'], $_POST['annee'])) {
        $requete = 'INSERT INTO etudiant (ID_utilisateur, nom, prenom, TD, TP, annee) VALUES (?,?, ?, ?, ?, ?)';
        $stmt = $conn->prepare($requete);
        
        // Lier les paramètres
        $stmt->bind_param('ssssss',$_POST['id'], $_POST['nom'], $_POST['prenom'], $_POST['TD'], $_POST['TP'], $_POST['annee']);
        
        // Exécuter la requête
        if ($stmt->execute()) {
            // Redirection après l'insertion
            header("Location: accueil_admin.php");
            exit();
        } else {
            echo "Erreur: " . $stmt->error;
        }
    }
}

function supprimer($conn, $id) {
    $requete = 'DELETE FROM etudiant WHERE ID_utilisateur = ?';
    $stmt = $conn->prepare($requete);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    
    // Redirection après la suppression
    header("Location: accueil_admin.php");
    exit();
}

// Appel de la fonction envoyer si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['envoyer'])) {
    envoyer($conn);
}

// Appel de la fonction supprimer si l'action de suppression est déclenchée
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['supprimer'])) {
    supprimer($conn, $_POST['id']); // Correction ici pour utiliser 'id' au lieu de 'ID_utilisateur'
}

// Récupération des données pour affichage
$result = $conn->query('SELECT ID_utilisateur, nom, prenom, TD, TP, annee FROM etudiant');
$etudiants = $result->fetch_all(MYSQLI_ASSOC);
?>







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
            <div class="active"><a href="accueil_admin.php">Gestion des comptes</a></div>
            <div><a href="Gestion_admin.php">Gestion des ressources</a></div>
        </div>
    </div>
    <div class="main-content">
        <header>
            <input type="text" placeholder="SAE, UE ...">
            <button>Rechercher</button>
            <div class="profile">
                <img src="" alt="Profile Picture">
            </div>
        </header>
        <h1>Gestion des comptes</h1>
        <div class="content">
            <div class="form-section">
                <h2>Saisie étudiant</h2>
                <form action="accueil_admin.php" method="post">
                <input type="text" name="id" id="id" placeholder="ID de l'étudiant" required>
                    <input type="text" name="nom" id="nom" placeholder="Nom" required>
                    <input type="text" name="prenom" id="prenom" placeholder="Prénom" required>
                    <input type="text" name="TD" id="TD" placeholder="TD" required>
                    <input type="text" name="TP" id="TP" placeholder="TP" required>
                    <input type="text" name="annee" id="annee" placeholder="Année univ" required>
                    <button type="submit" name="envoyer">Envoyer</button>
                    <button type="reset">Effacer</button>
                </form>
            </div>
            <div class="update-section">
                <h2>Mis à jour des étudiants</h2>
                <div class="student-list">
                    <table>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>TD</th>
                            <th>TP</th>
                            <th>Année</th>
                            <th>Actions</th>
                        </tr>
                        <?php if ($etudiants): ?>
                            <?php foreach ($etudiants as $etudiant): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($etudiant['ID_utilisateur']); ?></td>
                                <td><?php echo htmlspecialchars($etudiant['nom']); ?></td>
                                <td><?php echo htmlspecialchars($etudiant['prenom']); ?></td>
                                <td><?php echo htmlspecialchars($etudiant['TD']); ?></td>
                                <td><?php echo htmlspecialchars($etudiant['TP']); ?></td>
                                <td><?php echo htmlspecialchars($etudiant['annee']); ?></td>
                                <td>
                                    <form action="accueil_admin.php" method="post" style="display:inline;">
                                        <input type="hidden" name="id" value="<?php echo $etudiant['ID_utilisateur']; ?>">
                                        <button type="submit" name="supprimer">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6">Aucun étudiant trouvé.</td>
                            </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </div>
        <h1>Gestion des comptes</h1>
        <div class="content">
            <div class="form-section">
                <h2>Saisie enseignant</h2>
                <form action="accueil_admin.php" method="post">
                <input type="text" name="id" id="id" placeholder="ID de l'enseignant" required>
                    <input type="text" name="nom" id="nom" placeholder="Nom" required>
                    <input type="text" name="prenom" id="prenom" placeholder="Prénom" required>
                    <button type="submit" name="envoyer">Envoyer</button>
                    <button type="reset">Effacer</button>
                </form>
            </div>
            <div class="update-section">
                <h2>Mis à jour des enseignant</h2>
                <div class="student-list">
                    <table>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Actions</th>
                        </tr>
                        <?php if ($etudiants): ?>
                            <?php foreach ($etudiants as $etudiant): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($etudiant['	ID_enseignant']); ?></td>
                                <td><?php echo htmlspecialchars($etudiant['nom']); ?></td>
                                <td><?php echo htmlspecialchars($etudiant['prenom']); ?></td>
                                <td>
                                    <form action="accueil_admin.php" method="post" style="display:inline;">
                                        <input type="hidden" name="id" value="<?php echo $etudiant['ID_enseignant']; ?>">
                                        <button type="submit" name="supprimer">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6">Aucun étudiant trouvé.</td>
                            </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>