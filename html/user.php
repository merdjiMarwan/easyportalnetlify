<?php
// Inclure le fichier d'authentification pour vérifier si l'admin est connecté
include('php/auth.php');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/user-admin.css">
    <title>Easy Portal - Utilisateurs</title>
    <script src="js/dejaconnecter.js" defer></script>
</head>
<body>
    <div class="header">
        <div class="logo">✦Easy Portal</div>
        <div class="user-name" id="userName">
        </div>
        <a href="../php/logout.php" class="logout-button">Déconnexion</a>
    </div>

    <div class="sidebar">
        <a>Dashboard</a>
        <a href="user" class="active">Utilisateurs</a>
        <a href="admin">Admin</a>
        <a href="plaques">Plaques</a>
        <a href="portail">Portail</a>
        <a href="log">Logs</a>
    </div>

    <div class="main">
        <div class="title">Utilisateurs</div>
        <div id="alert-container"></div>

        <div class="search-container">
            <a href="ajouterUser" class="btn-add">Ajouter</a>
            <div class="search-bar">
                <span>🔎</span>
                <input type="text" id="searchInput" placeholder="Rechercher un utilisateur..." oninput="filterUsers()">
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Prénom</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="userTableBody"></tbody>
        </table>
    </div>

    <script>
        let usersData = [];

        async function fetchUsers() {
            try {
                const response = await fetch("php/get_users.php");
                const data = await response.json();
                usersData = data.data;
                displayUsers(usersData);
            } catch (error) {
                console.error("Erreur lors de la récupération des utilisateurs", error);
                document.getElementById("alert-container").innerHTML = 
                    `<div class="alert alert-danger">Erreur lors de la récupération des utilisateurs.</div>`;
                setTimeout(() => document.getElementById("alert-container").innerHTML = "", 5000);
            }
        }

        function displayUsers(users) {
            const tableBody = document.getElementById("userTableBody");
            tableBody.innerHTML = "";
            users.forEach(user => {
                const row = document.createElement("tr");
                row.innerHTML = `
                    <td>${user.prenom}</td>
                    <td>${user.nom}</td>
                    <td>${user.email}</td>
                    <td>${user.role}</td>
                    <td>
                        <button class="delete-button" onclick="deleteUser('${user.email}', '${user.prenom}', '${user.nom}')">Supprimer</button>
                        <button class="plaques-button" onclick="window.location.href='voir-plaques?email=${user.email}'">Plaques</button>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        }

        async function deleteUser(email, prenom, nom) {
            if (confirm(`Êtes-vous sûr de vouloir supprimer l'utilisateur ${prenom} ${nom} ?`)) {
                try {
                    const response = await fetch("php/delete_user.php", {
                        method: "POST",
                        headers: { "Content-Type": "application/json" },
                        body: JSON.stringify({ email })
                    });
                    const data = await response.json();
                    if (data.success) {
                        fetchUsers();
                        document.getElementById("alert-container").innerHTML = 
                            `<div class="alert alert-success">Utilisateur ${prenom} ${nom} supprimé avec succès.</div>`;
                        setTimeout(() => document.getElementById("alert-container").innerHTML = "", 5000);
                    } else {
                        alert("Erreur lors de la suppression de l'utilisateur.");
                    }
                } catch (error) {
                    console.error("Erreur lors de la suppression de l'utilisateur", error);
                }
            }
        }

        function filterUsers() {
            const searchInput = document.getElementById("searchInput").value.toLowerCase();
            displayUsers(usersData.filter(user =>
                user.prenom.toLowerCase().includes(searchInput) ||
                user.nom.toLowerCase().includes(searchInput) ||
                user.email.toLowerCase().includes(searchInput) ||
                user.role.toLowerCase().includes(searchInput)
            ));
        }

        document.addEventListener("DOMContentLoaded", fetchUsers);
    </script>
</body>
</html>
