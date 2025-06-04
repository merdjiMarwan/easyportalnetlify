<?php
header('Content-Type: application/json');

$servername = "51.210.151.13"; // IP de ton serveur OVH
$username = "easyportal2025"; // Ton utilisateur MySQL
$password = "EasyPortal2025!"; // Ton mot de passe MySQL
$dbname = "easyportal2025"; // Le nom de ta base de données

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $data = json_decode(file_get_contents("php://input"), true);
    $email = $data['email'] ?? '';

    $stmt = $pdo->prepare("DELETE FROM users WHERE email = ?");
    $stmt->execute([$email]);

    echo json_encode(["success" => true]);
} catch (PDOException $e) {
    echo json_encode(["error" => "Erreur de connexion à la base de données"]);
}
?>
