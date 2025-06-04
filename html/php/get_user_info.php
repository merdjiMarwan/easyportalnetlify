<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Utilisateur non connecté"]);
    exit;
}

// Vérification si les informations existent en session
$prenom_nom = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : "Utilisateur inconnu";

echo json_encode([
    "success" => true,
    "user" => [
        "prenom_nom" => $prenom_nom
    ]
]);
?>
