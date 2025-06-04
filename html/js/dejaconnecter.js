document.addEventListener("DOMContentLoaded", function () {
    fetch("php/get_user_info.php") // Vérifie bien le bon chemin vers ton fichier PHP
        .then(response => response.json())
        .then(data => {
            if (data.success && data.user) {
                document.getElementById("userName").textContent = data.user.prenom_nom;
            } else {
                document.getElementById("userName").textContent = "Utilisateur inconnu";
            }
        })
        .catch(error => {
            console.error("Erreur :", error);
            document.getElementById("userName").textContent = "Erreur de connexion";
        });
});
