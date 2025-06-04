<?php
session_start();

// Connexion BDD
$servername = "51.210.151.13";
$username = "easyportal2025";
$password = "EasyPortal2025!";
$dbname = "easyportal2025";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Échec de la connexion: ' . $conn->connect_error]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $plaqueId = $data['id'] ?? null;
    $status = $data['status'] ?? null;

    if (!isset($plaqueId) || !isset($status)) {
        echo json_encode(['success' => false, 'message' => 'ID ou statut manquant.']);
        exit;
    }

    // Vérifier que le statut est valide
    if ($status !== 'actif' && $status !== 'bloqué') {
        echo json_encode(['success' => false, 'message' => 'Statut invalide']);
        exit;
    }

    // Inverser l'état de la plaque
    $newStatus = ($status === 'actif') ? 'bloqué' : 'actif';

    $stmt = $conn->prepare("UPDATE plaques SET statut = ? WHERE id = ?");
    $stmt->bind_param("si", $newStatus, $plaqueId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur SQL: ' . $stmt->error]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
}

$conn->close();
?>
