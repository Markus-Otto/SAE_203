<?php
session_start();
include "fnct_conn.php";

$conn = connexion();

function envoyer($conn) {
    if (isset($_POST['role'])) {
        // Initialize the variables
        $id = isset($_POST['id']) ? $_POST['id'] : null;
        $nom = isset($_POST['nom']) ? $_POST['nom'] : '';
        $prenom = isset($_POST['prenom']) ? $_POST['prenom'] : '';

        // Vérifier si l'ID existe déjà
        if ($_POST['role'] == 'etudiant') {
            $requete_verif = 'SELECT ID_etudiant FROM etudiant WHERE ID_etudiant = ?';
        } elseif ($_POST['role'] == 'enseignant') {
            $requete_verif = 'SELECT ID_enseignant FROM enseignant WHERE ID_enseignant = ?';
        }

        if (isset($requete_verif) && $id !== null) {
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
            $requete = 'INSERT INTO etudiant (ID_etudiant, nom, prenom, TD, TP, annee_promo) VALUES (?, ?, ?, ?, ?, ?)';
            $stmt = $conn->prepare($requete);
            $stmt->bind_param('isssss', $id, $nom, $prenom, $td, $tp, $annee_promo);
        } elseif ($_POST['role'] == 'enseignant' && isset($id, $nom, $prenom)) {
            $requete = 'INSERT INTO enseignant (ID_enseignant, Nom, Prenom) VALUES (?, ?, ?)';
            $stmt = $conn->prepare($requete);
            $stmt->bind_param('iss', $id, $nom, $prenom);
        } else {
            echo "Erreur: Informations manquantes.";
            return;
        }

        if ($stmt->execute()) {
            header("Location: ../../PHP/Admin/accueil_admin.php");
            exit();
        } else {
            echo "Erreur: " . $stmt->error;
        }
    } else {
        echo "Erreur: Rôle non spécifié.";
    }
}

function supprimer($conn, $id, $role) {
    if ($role == 'etudiant') {
        // Suppression des enregistrements liés dans la table 'note'
        $delete_note = 'DELETE FROM note WHERE ID_etudiant = ?';
        $stmt_note = $conn->prepare($delete_note);
        $stmt_note->bind_param('i', $id);
        $stmt_note->execute();
        
        // Ensuite, supprimer l'étudiant
        $requete = 'DELETE FROM etudiant WHERE ID_etudiant = ?';
    } elseif ($role == 'enseignant') {
        // Suppression des enregistrements liés dans la table 'ressource'
        $delete_ressource = 'DELETE FROM ressource WHERE ID_enseignant = ?';
        $stmt_ressource = $conn->prepare($delete_ressource);
        $stmt_ressource->bind_param('i', $id);
        $stmt_ressource->execute();
        
        // Ensuite, supprimer l'enseignant
        $requete = 'DELETE FROM enseignant WHERE ID_enseignant = ?';
    }
    
    $stmt = $conn->prepare($requete);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    header("Location: ../../PHP/Admin/accueil_admin.php");
    exit();
}

function modifier($conn, $id, $role) {
    if ($role == 'etudiant') {
        $requete = 'UPDATE etudiant SET nom = ?, prenom = ?, TD = ?, TP = ?, annee_promo = ? WHERE ID_etudiant = ?';
        $stmt = $conn->prepare($requete);
        $stmt->bind_param('sssssi', $_POST['nom'], $_POST['prenom'], $_POST['TD'], $_POST['TP'], $_POST['annee_promo'], $id);
    } elseif ($role == 'enseignant') {
        $requete = 'UPDATE enseignant SET Nom = ?, Prenom = ? WHERE ID_enseignant = ?';
        $stmt = $conn->prepare($requete);
        $stmt->bind_param('ssi', $_POST['nom'], $_POST['prenom'], $id);
    }
    
    $stmt->execute();
    header("Location: ../../PHP/Admin/accueil_admin.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['envoyer'])) {
        envoyer($conn);
    }

    if (isset($_POST['supprimer'])) {
        supprimer($conn, $_POST['id'], $_POST['role']);
    }

    if (isset($_POST['modifier'])) {
        modifier($conn, $_POST['id'], $_POST['role']);
    }

    if (isset($_POST['logout'])) {
        session_unset();
        session_destroy();
        header("Location: ../page_login/Accueil_note.php");
        exit();
    }
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
    <link rel="stylesheet" href="../../CSS/accueil_admin.css">
    <link rel="icon" type="image/gif" href="../sae203/" class="img2"/>
    <style>
        /* Style pour la fenêtre modale */
        .modal {
            display: none; 
            position: fixed; 
            z-index: 1; 
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto; 
            background-color: rgb(0,0,0); 
            background-color: rgba(0,0,0,0.4); 
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto; 
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo">EIFFEL NOTE</div>
        <div class="menu">
            <div class="active"><a href="../Admin/accueil_admin.php">Gestion des comptes</a></div>
            <div><a href="../Admin/gestion_ressource.php">Gestion des ressources</a></div>
            <div class="logout">
                <form method="post" action="../Admin/accueil_admin.php">
                    <button type="submit" name="logout">Déconnexion</button>
                </form>
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
                    <input type="text" name="id" placeholder="ID" value="<?php echo isset($_GET['id']) ? $_GET['id'] : ''; ?>">
                    <input type="text" name="nom" placeholder="Nom" value="<?php echo isset($_GET['nom']) ? $_GET['nom'] : ''; ?>">
                    <input type="text" name="prenom" placeholder="Prénom" value="<?php echo isset($_GET['prenom']) ? $_GET['prenom'] : ''; ?>">
                    <input type="text" name="TD" placeholder="TD" value="<?php echo isset($_GET['TD']) ? $_GET['TD'] : ''; ?>">
                    <input type="text" name="TP" placeholder="TP" value="<?php echo isset($_GET['TP']) ? $_GET['TP'] : ''; ?>">
                    <input type="text" name="annee_promo" placeholder="Année univ" value="<?php echo isset($_GET['annee_promo']) ? $_GET['annee_promo'] : ''; ?>">
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
                                <td><?php echo $etudiant['ID_etudiant']; ?></td>
                                <td><?php echo $etudiant['nom']; ?></td>
                                <td><?php echo $etudiant['prenom']; ?></td>
                                <td><?php echo $etudiant['TD']; ?></td>
                                <td><?php echo $etudiant['TP']; ?></td>
                                <td><?php echo $etudiant['annee_promo']; ?></td>
                                <td>
                                    <button onclick="openModal('etudiant', '<?php echo $etudiant['ID_etudiant']; ?>', '<?php echo $etudiant['nom']; ?>', '<?php echo $etudiant['prenom']; ?>', '<?php echo $etudiant['TD']; ?>', '<?php echo $etudiant['TP']; ?>', '<?php echo $etudiant['annee_promo']; ?>')">Modifier</button>
                                    <form method="post" action="../Admin/accueil_admin.php" style="display:inline-block;">
                                        <input type="hidden" name="id" value="<?php echo $etudiant['ID_etudiant']; ?>">
                                        <input type="hidden" name="role" value="etudiant">
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
                                    <button onclick="openModal('enseignant', '<?php echo $enseignant['ID_enseignant']; ?>', '<?php echo $enseignant['Nom']; ?>', '<?php echo $enseignant['Prenom']; ?>')">Modifier</button>
                                    <form method="post" action="../Admin/accueil_admin.php" style="display:inline-block;">
                                        <input type="hidden" name="id" value="<?php echo $enseignant['ID_enseignant']; ?>">
                                        <input type="hidden" name="role" value="enseignant">
                                        <button type="submit" name="supprimer">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
             <!-- Fenêtre modale pour la modification -->
        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>
                <form id="modal-form" method="post" action="../Admin/accueil_admin.php">
                    <input type="hidden" name="id" id="modal-id">
                    <input type="hidden" name="role" id="modal-role">
                    <label for="modal-nom">Nom:</label>
                    <input type="text" name="nom" id="modal-nom"><br>
                    <label for="modal-prenom">Prénom:</label>
                    <input type="text" name="prenom" id="modal-prenom"><br>
                    <div id="modal-etudiant-fields" style="display:none;">
                        <label for="modal-td">TD:</label>
                        <input type="text" name="TD" id="modal-td"><br>
                        <label for="modal-tp">TP:</label>
                        <input type="text" name="TP" id="modal-tp"><br>
                        <label for="modal-annee-promo">Année Promo:</label>
                        <input type="text" name="annee_promo" id="modal-annee-promo"><br>
                    </div>
                    <button type="button" onclick="submitModalForm()">Modifier</button>
                </form>
            </div>
        </div>

    </div>

    <script>
        function openModal(role, id, nom, prenom, td, tp, annee_promo) {
            document.getElementById('modal-id').value = id;
            document.getElementById('modal-role').value = role;
            document.getElementById('modal-nom').value = nom;
            document.getElementById('modal-prenom').value = prenom;

            if (role === 'etudiant') {
                document.getElementById('modal-etudiant-fields').style.display = 'block';
                document.getElementById('modal-td').value = td;
                document.getElementById('modal-tp').value = tp;
                document.getElementById('modal-annee-promo').value = annee_promo;
            } else {
                document.getElementById('modal-etudiant-fields').style.display = 'none';
            }

            document.getElementById('myModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('myModal').style.display = 'none';
        }

        function submitModalForm() {
            document.getElementById('modal-form').submit();
        }

        window.onclick = function(event) {
            if (event.target === document.getElementById('myModal')) {
                closeModal();
            }
        }
        function submitModalForm() {
    document.getElementById('modal-form').submit();
    closeModal(); // Ferme la fenêtre modale après la soumission
    // Vous pouvez également rafraîchir manuellement le tableau ici si nécessaire
}
    </script>
</body>
</html>