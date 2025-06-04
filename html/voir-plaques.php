<?php
// Inclure le fichier d'authentification pour vérifier si l'admin est connecté
include('php/auth.php');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/voir-plaques.css">
    <title>Easy Portal - Voir Plaques</title>
</head>
<body>
    <div class="header">
        <div class="logo">✦Easy Portal</div>
    </div>

    <div class="main">
        <h2>Voir Plaques</h2>

        <!-- Bouton Retour -->
        <div class="return-button-container">
            <a href="user" class="button-return">Retour</a>
        </div>

        <div class="add-plaque-container">
            <input type="text" id="plaqueInput" placeholder="Ajouter une nouvelle plaque" />
            <button id="addPlaqueButton" class="button-primary">Ajouter Plaque</button>
        </div>

        <div class="plaques-container">
            <table>
                <thead>
                    <tr>
                        <th>Utilisateur</th>
                        <th>Plaque</th>
                        <th>État</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="plaquesTableBody"></tbody>
            </table>
        </div>
    </div>

    <script>
        let plaquesData = [];
        const userEmail = new URLSearchParams(window.location.search).get('email');

        async function fetchPlaques() {
            try {
                const response = await fetch(`php/getPlaquesByUser.php?email=${userEmail}`);
                if (!response.ok) throw new Error("Erreur de réseau");
                const text = await response.text();
                const data = JSON.parse(text);
                if (data.success) {
                    plaquesData = data.plaques;
                    displayPlaques(plaquesData);
                } else {
                    console.error("Erreur:", data.message);
                }
            } catch (error) {
                console.error("Erreur de récupération:", error);
            }
        }

        function displayPlaques(plaques) {
            const tableBody = document.getElementById("plaquesTableBody");
            tableBody.innerHTML = "";
            plaques.forEach(plaque => {
                const row = document.createElement("tr");
                row.innerHTML = `
                    <td>${plaque.nom} ${plaque.prenom}</td>
                    <td>${plaque.numero}</td>
                    <td>${plaque.statut}</td>
                    <td>
                        <button class="button-secondary" onclick="confirmToggle('${plaque.id}', '${plaque.statut}')">
                            ${plaque.statut === 'actif' ? 'Bloquer' : 'Activer'}
                        </button>
                        <button class="button-danger" onclick="confirmDelete('${plaque.id}')">Supprimer</button>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        }

        function confirmToggle(plaqueId, currentState) {
            const action = currentState === 'actif' ? "bloquer" : "activer";
            if (confirm(`Voulez-vous vraiment ${action} cette plaque ?`)) {
                toggleState(plaqueId, currentState);
            }
        }

        async function toggleState(plaqueId, currentState) {
            try {
                const response = await fetch("php/toggle_plaque_state.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ id: plaqueId, status: currentState })  // ← on envoie l'état actuel
                });
                if (!response.ok) throw new Error("Erreur réseau");
                const data = await response.json();
                if (data.success) {
                    fetchPlaques(); // recharger les plaques
                } else {
                    alert("Erreur de changement d'état : " + data.message);
                }
            } catch (error) {
                console.error("Erreur toggleState:", error);
            }
        }

        function confirmDelete(plaqueId) {
            if (confirm("Êtes-vous sûr de vouloir supprimer cette plaque ?")) {
                deletePlaque(plaqueId);
            }
        }

        async function deletePlaque(plaqueId) {
            try {
                const response = await fetch("php/delete_plaque.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ id: plaqueId })
                });
                const data = await response.json();
                if (data.success) {
                    fetchPlaques();
                } else {
                    alert("Erreur de suppression : " + data.message);
                }
            } catch (error) {
                console.error("Erreur deletePlaque:", error);
            }
        }

        async function addPlaque() {
            const plaqueInput = document.getElementById("plaqueInput");
            const plaqueValue = plaqueInput.value.trim();

            if (!plaqueValue) {
                alert("Veuillez entrer un numéro de plaque.");
                return;
            }

            if (plaquesData.length >= 5) {
                alert("Vous ne pouvez pas ajouter plus de 5 plaques.");
                return;
            }

            try {
                const response = await fetch("php/add_plaque.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ email: userEmail, numero: plaqueValue })
                });
                const data = await response.json();
                if (data.success) {
                    plaqueInput.value = "";
                    fetchPlaques();
                } else {
                    alert("Erreur lors de l'ajout.");
                }
            } catch (error) {
                console.error("Erreur addPlaque:", error);
            }
        }

        document.getElementById("addPlaqueButton").addEventListener("click", addPlaque);
        document.addEventListener("DOMContentLoaded", fetchPlaques);
    </script>
</body>
</html>
