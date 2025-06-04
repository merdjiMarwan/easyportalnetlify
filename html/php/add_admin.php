<?php
header('Content-Type: application/json');

$servername = "51.210.151.13"; // IP de ton serveur OVH
$username = "easyportal2025"; // Ton utilisateur MySQL
$password = "EasyPortal2025!"; // Ton mot de passe MySQL
$dbname = "easyportal2025"; // Le nom de ta base de données

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer les données du POST
    $data = json_decode(file_get_contents("php://input"), true);
    $prenom = $data['prenom'] ?? '';
    $nom = $data['nom'] ?? '';
    $email = $data['email'] ?? '';
    $password = $data['password'] ?? '';

    // Hacher le mot de passe
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Préparer l'insertion
    $stmt = $pdo->prepare("INSERT INTO users (prenom, nom, email, mot_de_passe, role) VALUES (?, ?, ?, ?, 'admin')");
    $stmt->execute([$prenom, $nom, $email, $hashedPassword]);

    echo json_encode(["success" => true]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Erreur de connexion à la base de données : " . $e->getMessage()]);
}
?>
