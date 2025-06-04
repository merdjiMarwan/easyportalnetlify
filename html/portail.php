<?php
// Inclure le fichier d'authentification pour vérifier si l'admin est connecté
include('php/auth.php');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Easy Portal - Portail</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="header">
        <div class="logo">✦Easy Portal</div>
        <div class="user-name" id="userName"><?php echo $_SESSION['user_name']; ?></div>
    </div>

    <div class="sidebar">
        <a>Dashboard</a>
        <a href="user">Utilisateurs</a>
        <a href="admin">Admin</a>
        <a href="plaques">Plaques</a>
        <a href="portail" class="active">Portail</a>
        <a href="log">Logs</a>
    </div>

    <div class="main">
        <div class="title">Flux Caméra Ip</div>

        <div class="portal-container">
            <div class="video-frame" id="cameraFeed">
                <!-- Remplacez l'image de la caméra par un texte cliquable -->
                <div id="cameraLink">Cliquer ici pour visualiser le flux</div>
            </div>
            <button class="btn" id="openPortalBtn">OUVRIR</button>
            <div id="portalAlert" class="alert"></div> <!-- Élément pour afficher le message du portail -->
        </div>
    </div>

    <script src="js/dejaconnecter.js"></script>
    <script>
        async function openPortal() {
            const raspberryPiUrl = 'http://172.16.15.39:5051/open_gate';

try {
    const response = await fetch(raspberryPiUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        }
    });

                if (!response.ok) {
                    throw new Error(`Erreur HTTP : ${response.status}`);
                }

                const result = await response.json();
                const alertMessage = document.createElement('div');
                alertMessage.textContent = result.message || "Le portail a été ouvert avec succès.";
                alertMessage.className = 'alert'; // Appliquer la classe d'alerte
                document.getElementById('portalAlert').innerHTML = ''; // Effacer les anciens messages
                document.getElementById('portalAlert').appendChild(alertMessage);

                // Faire disparaître le message après 3 secondes
                setTimeout(() => {
                    alertMessage.remove();
                }, 3000);

            } catch (error) {
                console.error("Erreur lors de l'ouverture du portail :", error);
                const alertMessage = document.createElement('div');
                alertMessage.textContent = "Erreur lors de l'ouverture du portail.";
                alertMessage.className = 'alert'; // Appliquer la classe d'alerte
                document.getElementById('portalAlert').innerHTML = ''; // Effacer les anciens messages
                document.getElementById('portalAlert').appendChild(alertMessage);

                // Faire disparaître le message après 3 secondes
                setTimeout(() => {
                    alertMessage.remove();
                }, 3000);
            }
        }

        document.getElementById("openPortalBtn").addEventListener("click", openPortal);

        // Ajoutez un gestionnaire d'événements pour le clic sur le texte de la caméra
        document.getElementById("cameraLink").addEventListener("click", function() {
            window.open("http://172.16.15.39:5000/video", "_blank");
        });
    </script>
</body>
</html>
