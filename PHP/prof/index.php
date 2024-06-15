<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de Notes</title>
    <link rel="stylesheet" href="../../CSS/accueilprofnote.css">
<link rel="stylesheet" href="../../CSS/sidebar.css">
    <style>
        @font-face {
            font-family: 'Playfair Display';
            src: url('../../font/PlayfairDisplay-Black.ttf') format('truetype');
        }
        @font-face {
            font-family: 'PT Sans';
            src: url('../../font/PTSans-Bold.ttf') format('truetype');
        }
        .note-block.active {
            background-color: #f0f0f0; /* Changez cette couleur pour marquer le bloc actif */
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h1>EIFFEL NOTE</h1>
        <ul>
            <li class="active"><a href="index.php">Evaluations</a></li>
            <li><a href="profil.php">Profil</a></li>
        </ul>
        <div class="logout">
            <form action="../../Accueil_note.php" method="post">
                <button type="submit">Déconnexion</button>
            </form>
        </div>
    </div>
    <div class="main-content">
        <div class="search-bar">
            <input type="text" id="search-input" placeholder="Recherche...">
            <button onclick="searchNotes()">Recherche</button>
        </div>
        <h2>Note</h2>
        <div class="notes">
            <?php
            
            session_start();
            if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'enseignant') {
                header("Location: ../../../../Accueil_note.php");
                exit();
            }
        
            // Connexion à la base de données
            $servername = 'localhost';
            $username = 'root';
            $password = ''; // Ajoutez votre mot de passe si nécessaire
            $dbname = 'eiffel_note_db';

            try {
                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Requête SQL pour récupérer les libellés d'épreuves
                $stmt = $conn->prepare("SELECT DISTINCT libelle FROM epreuves");
                $stmt->execute();
                $epreuves = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($epreuves as $epreuve) {
                    echo '<div class="note-block" data-libelle="' . htmlspecialchars($epreuve['libelle'], ENT_QUOTES) . '" onclick="startAutoRefresh(\''.htmlspecialchars($epreuve['libelle'], ENT_QUOTES).'\')">';
                    echo '<p>' . htmlspecialchars($epreuve['libelle']) . '</p>';
                    echo '</div>';
                }
            } catch (PDOException $e) {
                echo 'Erreur de connexion : ' . $e->getMessage();
            }
            ?>
        </div>
        <div class="notes-table-container hidden" id="notes-table">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Note</th>
                        <th>Coefficient</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="notes-body">
                </tbody>
            </table>
        </div>
        <button onclick="showAddForm()">Ajouter des notes</button>
    </div>
    <div id="edit-students" class="hidden">
        <h3>Modifier la note</h3>
        <div id="edit-form"></div>
    </div>
    <div id="add-students" class="hidden">
        <h3>Ajouter des notes</h3>
        <div id="add-form"></div>
    </div>

    <script>
        let intervalId;
        let libellesIntervalId;
        let currentLibelle = '';

    

        function showNotes(libelle) {
            currentLibelle = libelle;
            document.querySelectorAll('.note-block').forEach(block => block.classList.remove('active'));
            document.querySelector(`.note-block[data-libelle="${libelle}"]`).classList.add('active');
            console.log("Fetching notes for:", libelle);
            fetch('fetch_notes.php?libelle=' + encodeURIComponent(libelle))
                .then(response => response.json())
                .then(data => {
                    if (!Array.isArray(data)) {
                        throw new Error('Invalid notes data');
                    }
                    const notesBody = document.getElementById('notes-body');
                    notesBody.innerHTML = '';
                    data.forEach(note => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${note.date_epreuve}</td>
                            <td>${note.nom}</td>
                            <td>${note.prenom}</td>
                            <td>${note.notes}</td>
                            <td>${note.coefficients}</td>
                            <td>
                                <button onclick="deleteNote(${note.ID_epreuve})">Supprimer</button>
                                <button onclick="showEditForm(${note.ID_epreuve}, ${note.notes}, ${note.coefficients})">Modifier</button>
                            </td>
                        `;
                        notesBody.appendChild(row);
                    });
                    document.getElementById('notes-table').classList.remove('hidden');
                })
                .catch(error => console.error('Error fetching notes:', error));
        }

        function startAutoRefresh(libelle) {
            clearInterval(intervalId);
            showNotes(libelle);
            intervalId = setInterval(() => showNotes(libelle), 5000);
        }

        function deleteNote(ID_epreuve) {
            if (confirm('Voulez-vous vraiment supprimer cette note ?')) {
                console.log("Deleting note with ID_epreuve:", ID_epreuve);
                fetch('delete_notes.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        'ID_epreuve': ID_epreuve
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Note supprimée avec succès');
                        // Actualiser les notes après la suppression
                        showNotes(currentLibelle);
                    } else {
                        alert('Erreur lors de la suppression de la note');
                    }
                })
                .catch(error => console.error('Error deleting note:', error));
            }
        }

        function showEditForm(ID_epreuve, notes, coefficients) {
            const editForm = document.getElementById('edit-form');
            editForm.innerHTML = `
                <form id="edit-note-form">
                    <input type="hidden" name="ID_epreuve" value="${ID_epreuve}">
                    <label>Note: <input type="number" name="notes" value="${notes}" min="0" max="20" step="0.01" required></label>
                    <label>Coefficient: <input type="number" name="coefficients" value="${coefficients}" min="0" step="0.01" required></label>
                    <button type="submit">Enregistrer les modifications</button>
                    <button type="button" onclick="closeEditForm()">Fermer</button>
                    <div id="edit-success-message" class="hidden">Modification réussie !</div>
                    <div id="edit-error-message" class="hidden">Erreur lors de la modification.</div>
                </form>
            `;
            document.getElementById('edit-students').classList.remove('hidden');

            document.getElementById('edit-note-form').addEventListener('submit', function(event) {
                event.preventDefault();
                const formData = new FormData(this);
                fetch('update_notes.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    const successMessage = document.getElementById('edit-success-message');
                    const errorMessage = document.getElementById('edit-error-message');
                    if (data.success) {
                        successMessage.classList.remove('hidden');
                        errorMessage.classList.add('hidden');
                        setTimeout(() => {
                            successMessage.classList.add('hidden');
                        }, 3000);
                        // Actualiser les notes après la mise à jour
                        showNotes(currentLibelle);
                        document.getElementById('edit-students').classList.add('hidden');
                    } else {
                        successMessage.classList.add('hidden');
                        errorMessage.classList.remove('hidden');
                    }
                })
                .catch(error => {
                    const errorMessage = document.getElementById('edit-error-message');
                    errorMessage.classList.remove('hidden');
                    console.error('Error updating note:', error);
                });
            });
        }

        function stopAutoRefresh() {
            clearInterval(intervalId);
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Gérer les clics sur les blocs de libellé
            const noteBlocks = document.querySelectorAll('.note-block');
            noteBlocks.forEach(block => {
                block.addEventListener('click', function() {
                    const libelle = this.getAttribute('data-libelle');
                    stopAutoRefresh(); // Arrêter l'actualisation automatique du libellé précédent
                    startAutoRefresh(libelle); // Démarrer l'actualisation pour le nouveau libellé
                });
            });

            // Gérer la soumission du formulaire de recherche
            const searchButton = document.querySelector('.search-bar button');
            searchButton.addEventListener('click', function() {
                const searchTerm = document.getElementById('search-input').value.trim().toLowerCase();
                if (searchTerm === '') {
                    fetchLibelles(); // Réafficher toutes les notes si le terme de recherche est vide
                } else {
                    clearInterval(libellesIntervalId);
                    stopAutoRefresh(); // Arrêter l'actualisation automatique lors de la recherche
                    searchNotes();
                }
            });

            // Initialiser l'actualisation des libellés au chargement de la page
            fetchLibelles();
            libellesIntervalId = setInterval(fetchLibelles, 5000);
        });


        function deleteNote(ID_epreuve) {
            if (confirm('Voulez-vous vraiment supprimer cette note ?')) {
                console.log("Deleting note with ID_epreuve:", ID_epreuve);
                fetch('delete_notes.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        'ID_epreuve': ID_epreuve
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Note supprimée avec succès');
                        // Actualiser les notes après la suppression
                        showNotes(currentLibelle);
                    } else {
                        alert('Erreur lors de la suppression de la note');
                    }
                })
                .catch(error => console.error('Error deleting note:', error));
            }
        }

        function showEditForm(ID_epreuve, notes, coefficients) {
            const editForm = document.getElementById('edit-form');
            editForm.innerHTML = `
                <form id="edit-note-form">
                    <input type="hidden" name="ID_epreuve" value="${ID_epreuve}">
                    <label>Note: <input type="number" name="notes" value="${notes}" min="0" max="20" step="0.01" required></label>
                    <label>Coefficient: <input type="number" name="coefficients" value="${coefficients}" min="0" step="0.01" required></label>
                    <button type="submit">Enregistrer les modifications</button>
                    <button type="button" onclick="closeEditForm()">Fermer</button>
                    <div id="edit-success-message" class="hidden">Modification réussie !</div>
                    <div id="edit-error-message" class="hidden">Erreur lors de la modification.</div>
                </form>
            `;
            document.getElementById('edit-students').classList.remove('hidden');

            document.getElementById('edit-note-form').addEventListener('submit', function(event) {
                event.preventDefault();
                const formData = new FormData(this);
                fetch('update_notes.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    const successMessage = document.getElementById('edit-success-message');
                    const errorMessage = document.getElementById('edit-error-message');
                    if (data.success) {
                        successMessage.classList.remove('hidden');
                        errorMessage.classList.add('hidden');
                        setTimeout(() => {
                            successMessage.classList.add('hidden');
                        }, 3000);
                        // Actualiser les notes après la mise à jour
                        showNotes(currentLibelle);
                        document.getElementById('edit-students').classList.add('hidden');
                    } else {
                        successMessage.classList.add('hidden');
                        errorMessage.classList.remove('hidden');
                    }
                })
                .catch(error => {
                    const errorMessage = document.getElementById('edit-error-message');
                    errorMessage.classList.remove('hidden');
                    console.error('Error updating note:', error);
                });
            });
        }


        function closeEditForm() {
            document.getElementById('edit-students').classList.add('hidden');
        }

        function showAddForm() {
            fetch('fetch_students.php')
                .then(response => response.json())
                .then(data => {
                    if (!Array.isArray(data)) {
                        throw new Error('Invalid students data');
                    }
                    fetch('fetch_resources.php')
                        .then(response => response.json())
                        .then(resources => {
                            if (!Array.isArray(resources)) {
                                throw new Error('Invalid resources data');
                            }
                            const addForm = document.getElementById('add-form');
                            const studentRows = data.map(student => {
                                return `<tr>
                                            <td>${student.nom}</td>
                                            <td>${student.prenom}</td>
                                            <td><input type="number" name="notes[${student.ID_etudiant}]" min="0" max="20" step="0.01" required></td>
                                        </tr>`;
                            }).join('');
                            const resourceOptions = resources.map(resource => {
                                return `<option value="${resource.ID_ressource}" data-libelle="${resource.nom_de_la_ressource}">${resource.nom_de_la_ressource}</option>`;
                            }).join('');
                            addForm.innerHTML = `
                                <form id="add-notes-form">
                                    <label>Ressource: 
                                        <select name="ID_ressource" required onchange="updateLibelle(this)">
                                            <option value="">Sélectionnez une ressource</option>
                                            ${resourceOptions}
                                        </select>
                                    </label>
                                    <label>Coefficient global: <input type="number" name="coefficient_global" min="0" step="0.01" required></label>
                                    <label>Date de l'épreuve: <input type="date" name="date_epreuve" required></label>
                                    <input type="hidden" name="libelle" id="libelle-input">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Nom</th>
                                                <th>Prénom</th>
                                                <th>Note</th>
                                            </tr>
                                        </thead>
                                        <tbody id="students-list">
                                            ${studentRows}
                                        </tbody>
                                    </table>
                                    <button type="submit">Enregistrer</button>
                                    <button type="button" onclick="closeAddForm()">Fermer</button>
                                    <div id="success-message" class="hidden">Enregistrement réussi !</div>
                                    <div id="error-message" class="hidden">Erreur lors de l'enregistrement.</div>
                                </form>
                            `;
                            document.getElementById('add-students').classList.remove('hidden');

                            document.getElementById('add-notes-form').addEventListener('submit', function(event) {
                                event.preventDefault();
                                const formData = new FormData(this);
                                fetch('add_notes.php', {
                                    method: 'POST',
                                    body: formData
                                })
                                .then(response => response.json())
                                .then(data => {
                                    const successMessage = document.getElementById('success-message');
                                    const errorMessage = document.getElementById('error-message');
                                    if (data.success) {
                                        successMessage.classList.remove('hidden');
                                        errorMessage.classList.add('hidden');
                                        setTimeout(() => {
                                            successMessage.classList.add('hidden');
                                        }, 3000);
                                        // Actualiser les notes après l'ajout
                                        showNotes(currentLibelle);
                                    } else {
                                        successMessage.classList.add('hidden');
                                        errorMessage.classList.remove('hidden');
                                    }
                                })
                                .catch(error => {
                                    const errorMessage = document.getElementById('error-message');
                                    errorMessage.classList.remove('hidden');
                                    console.error('Error adding notes:', error);
                                });
                            });
                        })
                        .catch(error => console.error('Error fetching resources:', error));
                })
                .catch(error => console.error('Error fetching students:', error));
        }



        function updateLibelle(select) {
            const selectedOption = select.options[select.selectedIndex];
            const libelleInput = document.getElementById('libelle-input');
            if (selectedOption) {
                const libelle = selectedOption.getAttribute('data-libelle');
                libelleInput.value = libelle;
            } else {
                libelleInput.value = '';
            }
        }
        function closeAddForm() {
            document.getElementById('add-students').classList.add('hidden');
        }

        function fetchLibelles() {
            fetch('fetch_libelles.php')
                .then(response => response.json())
                .then(data => {
                    if (!Array.isArray(data)) {
                        throw new Error('Invalid libelles data');
                    }
                    const notesContainer = document.querySelector('.notes');
                    const currentLibelles = Array.from(document.querySelectorAll('.note-block')).map(block => block.dataset.libelle);

                    data.forEach(libelle => {
                        const libelleDiv = document.createElement('div');
                        libelleDiv.className = 'note-block';
                        libelleDiv.dataset.libelle = libelle;
                        libelleDiv.textContent = libelle;
                        libelleDiv.onclick = () => startAutoRefresh(libelle);
                        notesContainer.appendChild(libelleDiv);

                        // Vérifier si le libellé est déjà présent dans la page
                        if (!currentLibelles.includes(libelle)) {
                            // Si le libellé n'est pas dans la liste actuelle, montrer les notes
                            if (libelle === currentLibelle) {
                                showNotes(libelle);
                            }
                        }
                    });

                    // Supprimer les libellés qui ne sont plus présents dans les données
                    Array.from(document.querySelectorAll('.note-block')).forEach(block => {
                        if (!data.includes(block.dataset.libelle)) {
                            block.remove();
                        }
                    });

                    // Mettre à jour les notes si le libellé actuel est toujours présent
                    if (currentLibelle && data.includes(currentLibelle)) {
                        showNotes(currentLibelle);
                    } else {
                        // Si le libellé actuel n'est plus dans la liste, arrêter l'actualisation
                        stopAutoRefresh();
                    }
                })
                .catch(error => console.error('Error fetching libelles:', error));
        }


        document.addEventListener('DOMContentLoaded', function() {
            fetchLibelles();
            libellesIntervalId = setInterval(fetchLibelles, 5000);

            document.getElementById('add-notes-form').addEventListener('submit', function(event) {
                event.preventDefault();
                const formData = new FormData(this);
                fetch('add_notes.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    const successMessage = document.getElementById('success-message');
                    const errorMessage = document.getElementById('error-message');
                    if (data.success) {
                        successMessage.classList.remove('hidden');
                        errorMessage.classList.add('hidden');
                        setTimeout(() => {
                            successMessage.classList.add('hidden');
                        }, 3000);
                        // Actualiser les notes après l'ajout
                        showNotes(currentLibelle);
                    } else {
                        successMessage.classList.add('hidden');
                        errorMessage.classList.remove('hidden');
                    }
                })
                .catch(error => {
                    const errorMessage = document.getElementById('error-message');
                    errorMessage.classList.remove('hidden');
                    console.error('Error adding notes:', error);
                });
            });

            // Ajout de la gestion de la fonction de recherche
            const searchInput = document.getElementById('search-input');
            const searchButton = document.getElementById('search-button');

            searchButton.addEventListener('click', function() {
                const searchTerm = searchInput.value.trim().toLowerCase();

                // Si le terme de recherche est vide, réinitialiser l'affichage
                if (searchTerm === '') {
                    fetchLibelles(); // Réafficher toutes les notes
                    return;
                }

                // Arrêter l'actualisation automatique pendant la recherche
                clearInterval(libellesIntervalId);

                // Effectuer la recherche
                searchNotes();

                // Réinitialiser l'affichage après la recherche
                searchInput.value = ''; // Vider le champ de recherche
            });
        });

        function searchNotes() {
            const searchInput = document.getElementById('search-input').value.toLowerCase();
            const notesTable = document.getElementById('notes-table');
            const rows = notesTable.getElementsByTagName('tr');

            for (let i = 1; i < rows.length; i++) {
                let cells = rows[i].getElementsByTagName('td');
                let rowContainsSearchTerm = false;
                
                for (let j = 0; j < cells.length; j++) {
                    if (cells[j].innerText.toLowerCase().includes(searchInput)) {
                        rowContainsSearchTerm = true;
                        break;
                    }
                }

                if (rowContainsSearchTerm) {
                    rows[i].style.display = "";
                } else {
                    rows[i].style.display = "none";
                }
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            // Initialiser l'actualisation des libellés au chargement de la page
            fetchLibelles();
            libellesIntervalId = setInterval(fetchLibelles, 5000);

            // Gérer la soumission du formulaire de recherche
            const searchButton = document.querySelector('.search-bar button');
            searchButton.addEventListener('click', function() {
                const searchTerm = document.getElementById('search-input').value.trim().toLowerCase();
                if (searchTerm === '') {
                    // Réafficher toutes les notes si le terme de recherche est vide
                    fetchLibelles();
                } else {
                    // Arrêter l'actualisation automatique lors de la recherche
                    clearInterval(libellesIntervalId);
                    stopAutoRefresh();
                    // Effectuer la recherche
                    searchNotes();
                }
            });

            // Ajouter d'autres gestionnaires d'événements au besoin
        });

    </script>
</body>
</html>
