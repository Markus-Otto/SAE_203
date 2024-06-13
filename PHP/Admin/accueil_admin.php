<?php
session_start();
include "fnct_conn.php";

$conn = connexion();

function envoyer($conn) {
    if (isset($_POST['role'])) {
        // Initialize the variables
        $stmt_verif = null;
        $stmt = null;
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];

        // Vérifier si l'ID existe déjà
        if ($_POST['role'] == 'etudiant') {
            $requete_verif = 'SELECT ID_utilisateur FROM etudiant WHERE ID_utilisateur = ?';
        } elseif ($_POST['role'] == 'enseignant') {
            $requete_verif = 'SELECT ID_enseignant FROM enseignant WHERE ID_enseignant = ?';
        }

        if (isset($requete_verif)) {
            $stmt_verif = $conn->prepare($requete_verif);
            $stmt_verif->bind_param('i', $id);
            $stmt_verif->execute();
            $stmt_verif->store_result();

            if ($stmt_verif->num_rows > 0) {
                echo "Erreur: L'ID existe déjà.";
                return;
            }
        }

        // Insertion des données
        if ($_POST['role'] == 'etudiant' && isset($id, $nom, $prenom, $_POST['TD'], $_POST['TP'], $_POST['annee_promo'])) {
            $td = $_POST['TD'];
            $tp = $_POST['TP'];
            $annee_promo = $_POST['annee_promo'];
            $requete = 'INSERT INTO etudiant (ID_utilisateur, nom, prenom, TD, TP, annee) VALUES (?, ?, ?, ?, ?, ?)';
            $stmt = $conn->prepare($requete);
            $stmt->bind_param('ssssss', $id, $nom, $prenom, $td, $tp, $annee_promo);
        } elseif ($_POST['role'] == 'enseignant' && isset($id, $nom, $prenom)) {
            $requete = 'INSERT INTO enseignant (ID_enseignant, Nom, Prenom) VALUES (?, ?, ?)';
            $stmt = $conn->prepare($requete);
            $stmt->bind_param('sss', $id, $nom, $prenom);
        }

        if ($stmt && $stmt->execute()) {
            header("Location: ../../accueil_admin.php");
            exit();
        } else {
            echo "Erreur: " . ($stmt ? $stmt->error : "Statement not prepared.");
        }
    } else {
        echo "Erreur: Rôle non spécifié.";
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
    header("Location: ../../accueil_admin.php");
    exit();
}

function modifier($conn, $id, $role) {
    if ($role == 'etudiant') {
        $requete = 'UPDATE etudiant SET nom = ?, prenom = ?, TD = ?, TP = ?, annee = ? WHERE ID_utilisateur = ?';
        $stmt = $conn->prepare($requete);
        $stmt->bind_param('sssssi', $_POST['nom'], $_POST['prenom'], $_POST['TD'], $_POST['TP'], $_POST['annee'], $id);
    } elseif ($role == 'enseignant') {
        $requete = 'UPDATE enseignant SET Nom = ?, Prenom = ? WHERE ID_enseignant = ?';
        $stmt = $conn->prepare($requete);
        $stmt->bind_param('ssi', $_POST['nom'], $_POST['prenom'], $id);
    }
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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['modifier'])) {
    modifier($conn, $_POST['id'], $_POST['role']);
}

$result_etudiants = $conn->query("SELECT * FROM etudiant");
$result_enseignants = $conn->query("SELECT * FROM enseignant");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des comptes</title>
    <link rel="stylesheet" href="/sae203/CSS/accueil_admin.css">
    <link rel="icon" type="image/gif" href="../sae203/" class="img2"/>
</head>
<body>
    <div class="sidebar">
        <div class="logo">EIFFEL NOTE</div>
        <div class="menu">
            <div class="active"><a href="../Admin/accueil_admin.php">Gestion des comptes</a></div>
            <div><a href="#">Gestion des ressources</a></div>
            <div class="logout">
        <form method="post" action="../Admin/accueil_admin.php">
            <button type="submit" name="logout">Déconnexion</button>
    <?php
         if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['logout'])) {
             session_start();
            session_unset();
             session_destroy();
            header("Location: ../page_login/Accueil_note.php");
             exit();    }   ?>
            </form>
        </div>
    </div>
        </div>
    </div>
   
    <div class="main-content">
        <header>
            <input type="text" placeholder="SAE, UE ...">
            <button>Rechercher</button>
            <div class="profile">
                <img src="profile.png" alt="Profile Picture">
            </div>
        </header>
        <h1>Gestion des comptes</h1>
        <div class="content">
            <div class="form-section">
                <h2>Saisie étudiant ou enseignant</h2>
                <form method="post" action="../Admin/accueil_admin.php">
                    
                    <input type="text" name="nom" placeholder="Nom" value="<?php echo isset($_GET['nom']) ? $_GET['nom'] : ''; ?>">
                    <input type="text" name="prenom" placeholder="Prénom" value="<?php echo isset($_GET['prenom']) ? $_GET['prenom'] : ''; ?>">
                    <input type="text" name="TD" placeholder="TD" value="<?php echo isset($_GET['TD']) ? $_GET['TD'] : ''; ?>">
                    <input type="text" name="TP" placeholder="TP" value="<?php echo isset($_GET['TP']) ? $_GET['TP'] : ''; ?>">
                    <input type="text" name="annee" placeholder="Année univ" value="<?php echo isset($_GET['annee_promo']) ? $_GET['annee_promo'] : ''; ?>">
                    <select name="role">
                        <option value="etudiant">Etudiant</option>
                        <option value="enseignant">Enseignant</option>
                    </select>
                    <button type="submit" name="envoyer">Envoyer</button>
                    <button type="reset">Effacer</button>
                </form>
            </div>
            <div class="update-section student-list">
                <h2>Mise à jour des étudiants</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>TD</th>
                            <th>TP</th>
                            <th>Année</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($etudiant = $result_etudiants->fetch_assoc()) { ?>
                            <tr>
                                <td><?php echo $etudiant['ID_utilisateur']; ?></td>
                                <td><?php echo $etudiant['nom']; ?></td>
                                <td><?php echo $etudiant['prenom']; ?></td>
                                <td><?php echo $etudiant['TD']; ?></td>
                                <td><?php echo $etudiant['TP']; ?></td>
                                <td><?php echo $etudiant['annee_promo']; ?></td>
                                <td>
                                    <form method="post" action="../Admin/accueil_admin.php">
                                        <input type="hidden" name="id" value="<?php echo $etudiant['ID_utilisateur']; ?>">
                                        <input type="hidden" name="role" value="etudiant">
                                        <button type="submit" name="modifier">Modifier</button>
                                        <button type="submit" name="supprimer">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="update-section student-list">
                <h2>Mise à jour des enseignants</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($enseignant = $result_enseignants->fetch_assoc()) { ?>
                            <tr>
                                <td><?php echo $enseignant['ID_enseignant']; ?></td>
                                <td><?php echo $enseignant['Nom']; ?></td>
                                <td><?php echo $enseignant['Prenom']; ?></td>
                                <td>
                                    <form method="post" action="../Admin/accueil_admin.php">
                                        <input type="hidden" name="id" value="<?php echo $enseignant['ID_enseignant']; ?>">
                                        <input type="hidden" name="role" value="enseignant">
                                        <button type="submit" name="modifier">Modifier</button>
                                        <button type="submit" name="supprimer">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>