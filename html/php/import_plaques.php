<?php
header('Content-Type: application/json');

$inserted = 0;
$skipped = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['csvFile']) && $_FILES['csvFile']['error'] === 0) {
        $file = $_FILES['csvFile']['tmp_name'];
        $handle = fopen($file, "r");

        if ($handle === false) {
            echo json_encode(["success" => false, "error" => "Erreur lors de l'ouverture du fichier."]);
            exit;
        }

        $servername = "51.210.151.13";
        $username = "easyportal2025";
        $password = "EasyPortal2025!";
        $dbname = "easyportal2025";

        try {
            $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $isFirstLine = true;

            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                if ($isFirstLine) {
                    $isFirstLine = false;
                    continue;
                }

                $nom = trim($data[0] ?? '');
                $plaque = substr(trim($data[1] ?? ''), 0, 20);
                $dateAjout = trim($data[2] ?? '');

                if (empty($plaque) || empty($dateAjout)) {
                    $skipped++;
                    continue;
                }

                $stmt = $pdo->prepare("SELECT COUNT(*) FROM plaques WHERE numero = ?");
                $stmt->execute([$plaque]);
                $exists = $stmt->fetchColumn();

                if (!$exists) {
                    $stmt = $pdo->prepare("INSERT INTO plaques (numero, statut, date_ajout) VALUES (?, 'actif', ?)");
                    $stmt->execute([$plaque, $dateAjout]);
                    $inserted++;
                } else {
                    $skipped++;
                }
            }

            fclose($handle);
            echo json_encode([
                "success" => true,
                "imported" => $inserted,
                "skipped" => $skipped
            ]);
        } catch (PDOException $e) {
            echo json_encode(["success" => false, "error" => "Erreur BDD : " . $e->getMessage()]);
        }
    } else {
        echo json_encode(["success" => false, "error" => "Aucun fichier sélectionné ou erreur lors de l'envoi."]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Requête invalide."]);
}
