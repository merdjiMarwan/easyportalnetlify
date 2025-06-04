<?php
$servername = "51.210.151.13";
$username = "easyportal2025";
$password = "EasyPortal2025!";
$dbname = "easyportal2025";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Requête SQL pour récupérer les plaques actives avec nom complet de l'utilisateur
    $stmt = $pdo->prepare("
        SELECT 
            CONCAT(COALESCE(u.prenom, ''), ' ', COALESCE(u.nom, '')) AS nom_utilisateur,
            p.numero,
            p.date_ajout
        FROM plaques p
        LEFT JOIN users u ON p.user_id = u.id
        WHERE p.statut = 'actif'
    ");
    $stmt->execute();

    // Préparation du téléchargement CSV
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=plaques_autorisees.csv');

    $output = fopen('php://output', 'w');

    // En-têtes du fichier CSV
    fputcsv($output, ['Nom utilisateur', 'Numéro de plaque', 'Date d\'ajout']);

    // Lignes du fichier CSV
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        fputcsv($output, [
            trim($row['nom_utilisateur']) ?: 'Non attribué',
            $row['numero'],
            $row['date_ajout']
        ]);
    }

    fclose($output);
    exit;

} catch (PDOException $e) {
    echo "Erreur de base de données : " . $e->getMessage();
    exit;
}
?>
