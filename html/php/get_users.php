<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

$servername = "51.210.151.13"; // IP de ton serveur OVH
$username = "easyportal2025"; // Ton utilisateur MySQL
$password = "EasyPortal2025!"; // Ton mot de passe MySQL
$dbname = "easyportal2025"; // Le nom de ta base de données

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer uniquement les utilisateurs
    $stmt = $pdo->prepare("SELECT id, nom, prenom, email, role FROM users WHERE role = 'utilisateur'");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Assurez-vous que les données sont renvoyées correctement
    echo json_encode(["success" => true, "data" => $users]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "error" => "Erreur de connexion : " . $e->getMessage()]);
}
?>
