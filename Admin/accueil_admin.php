<?php
session_start();
include "fnct_conn.php";

$conn = connexion();

function envoyer($conn) {
    if (isset($_POST['role'])) {
        if ($_POST['role'] == 'etudiant' && isset($_POST['nom'], $_POST['prenom'], $_POST['TD'], $_POST['TP'], $_POST['annee'])) {
            $requete = 'INSERT INTO etudiant (nom, prenom, TD, TP, annee) VALUES (?, ?, ?, ?, ?)';
            $stmt = $conn->prepare($requete);
            $stmt->bind_param('sssss', $_POST['nom'], $_POST['prenom'], $_POST['TD'], $_POST['TP'], $_POST['annee']);
        } elseif ($_POST['role'] == 'enseignant' && isset($_POST['nom'], $_POST['prenom'])) {
            $requete = 'INSERT INTO enseignant (nom, prenom) VALUES (?, ?)';
            $stmt = $conn->prepare($requete);
            $stmt->bind_param('ss', $_POST['nom'], $_POST['prenom']);
        }
        
        if ($stmt->execute()) {
            header("Location: accueil_admin.php");
            exit();
        } else {
            echo "Erreur: " . $stmt->error;
        }
    }
}

function supprimer($conn, $id, $role) {
    if ($role == 'etudiant') {
        $requete = 'DELETE FROM etudiant WHERE ID_utilisateur = ?';
    } elseif ($role == 'enseignant') {
        $requete = 'DELETE FROM enseignant WHERE ID_enseignant = ?';
    }
    $stmt = $conn->prepare($requete);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    header("Location: accueil_admin.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['envoyer'])) {
    envoyer($conn);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['supprimer'])) {
    supprimer($conn, $_POST['id'], $_POST['role']);
}

$result_etudiants = $conn->query('SELECT ID_utilisateur, nom, prenom, TD, TP, annee FROM etudiant');
$etudiants = $result_etudiants->fetch_all(MYSQLI_ASSOC);

$result_enseignants = $conn->query('SELECT ID_enseignant, nom, prenom FROM enseignant');
$enseignants = $result_enseignants->fetch_all(MYSQLI_ASSOC);
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
                <h2>Saisie étudiant ou enseignant</h2>
                <form action="accueil_admin.php" method="post">
                    <input type="text" name="nom" id="nom" placeholder="Nom" required>
                    <input type="text" name="prenom" id="prenom" placeholder="Prénom" required>
                    <input type="text" name="TD" id="TD" placeholder="TD">
                    <input type="text" name="TP" id="TP" placeholder="TP">
                    <input type="text" name="annee" id="annee" placeholder="Année univ">
                    <label for="role">Sélectionnez le rôle :</label>
                    <select name="role" id="role">
                        <option value="etudiant">Étudiant</option>
                        <option value="enseignant">Enseignant</option>
                    </select>
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
                                        <input type="hidden" name="role" value="etudiant">
                                        <button type="submit" name="supprimer">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7">Aucun étudiant trouvé.</td>
                            </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
            <div class="update-section">
                <h2>Mis à jour des enseignants</h2>
                <div class="teacher-list">
                    <table>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Actions</th>
                        </tr>
                        <?php if ($enseignants): ?>
                            <?php foreach ($enseignants as $enseignant): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($enseignant['ID_enseignant']); ?></td>
                                <td><?php echo htmlspecialchars($enseignant['nom']); ?></td>
                                <td><?php echo htmlspecialchars($enseignant['prenom']); ?></td>
                                <td>
                                    <form action="accueil_admin.php" method="post" style="display:inline;">
                                        <input type="hidden" name="id" value="<?php echo $enseignant['ID_enseignant']; ?>">
                                        <input type="hidden" name="role" value="enseignant">
                                        <button type="submit" name="supprimer">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4">Aucun enseignant trouvé.</td>
                            </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>