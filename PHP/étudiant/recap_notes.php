<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'etudiant') {
    header("Location: ../../../../Accueil_note.php");
    exit();
}
include "../page_login/fct_connection.php";

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$conn = connexion();
$username = $_SESSION['username'];

// Préparer et exécuter la requête SQL pour obtenir les notes de l'utilisateur
$sql = $conn->prepare("
    SELECT n.notes, n.ID_etudiant, n.ID_epreuve 
    FROM note n
    JOIN etudiant e ON n.ID_etudiant = e.ID_etudiant
    WHERE e.username = ?
");
$sql->bind_param('s', $username);
$sql->execute();
$result = $sql->get_result();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>EIFFEL NOTE</title>
    <link rel="stylesheet" type="text/css" href="../../CSS/accueil-etudiant.css">
</head>
<body>
    <div class="sidebar">
        <div class="logo">EIFFEL NOTE</div>
        <div class="menu">
            <ul>
                <li><a href="recap_notes.php" class="menu-item active">Récapitulatif Note</a></li>
                <li><a href="profil_eleve.php" class="menu-item">Profil</a></li>
            </ul>
        </div>
    </div>

    <div class="main-content">
        <header>
            <input type="search" id="searchNotes" name="searchNotes" placeholder="SAE, UE ...">
            <button>Rechercher</button>
            <div class="profile">
                <img src="profile.jpg" alt="Profile Picture">
            </div>
        </header>

        <h1>Notes</h1>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID_epreuve</th>
                        <th>Coefficient</th>
                        <th>libelle</th>
                        <th>Ressource</th>
                        <th>Date</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                function afficherNotesEtudiant($username) {
                    $servername = "localhost";
                    $username_db = "root";
                    $password_db = "";
                    $dbname = "eiffel_note_db";

                    $dsn = "mysql:host=$servername;dbname=$dbname;charset=utf8";

                    try {
                        $pdo = new PDO($dsn, $username_db, $password_db);
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        $stmt = $pdo->prepare("
                            SELECT e.ID_epreuve, e.Coefficients, e.libelle, e.ID_ressource, e.date_epreuve, n.notes
                            FROM epreuves e
                            JOIN note n ON e.ID_epreuve = n.ID_epreuve
                            JOIN etudiant et ON n.ID_etudiant = et.ID_etudiant
                            WHERE et.username = ?
                        ");
                        $stmt->execute([$username]);

                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['ID_epreuve']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Coefficients']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['libelle']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['ID_ressource']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['date_epreuve']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['notes']) . "</td>";
                            echo "</tr>";
                        }
                    } catch (PDOException $e) {
                        echo "<tr><td colspan='6'>Connexion échouée : " . htmlspecialchars($e->getMessage()) . "</td></tr>";
                    }
                }
                afficherNotesEtudiant($username);
                ?>
                </tbody>
            </table>
        </div>
    </div>
    
