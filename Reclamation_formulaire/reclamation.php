<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

$lang = $_POST['lang'] ?? 'fr';
$lang = in_array($lang, ['fr', 'ar']) ? $lang : 'fr';

try {
    $pdo = new PDO("mysql:host=localhost;dbname=abhoer_;charset=utf8mb4", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);

    $nom = htmlspecialchars(trim($_POST['nom_client'] ?? ''));
    $num_tel = trim($_POST['num-tel'] ?? '');
    $mail = trim($_POST['mail'] ?? '');
    $adress = htmlspecialchars(trim($_POST['adress'] ?? ''));
    $type_user = $_POST['Type'] ?? '';
    $CIN = htmlspecialchars(trim($_POST['CIN'] ?? ''));
    $ICE = htmlspecialchars(trim($_POST['ICE'] ?? ''));
    $description = htmlspecialchars(trim($_POST['description'] ?? ''));
    $ville = htmlspecialchars(trim($_POST['ville'] ?? ''));
    $region = htmlspecialchars(trim($_POST['region'] ?? ''));
    $commune = htmlspecialchars(trim($_POST['commune'] ?? ''));
    
    if (empty($nom)) throw new Exception("nom_obligatoire");
    if ($type_user === 'Particulier' && empty($CIN)) throw new Exception("cin_obligatoire");
    if (empty($description)) throw new Exception("desc_obligatoire");

    if (!empty($mail) && !filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("mail_invalide");
    }

    $fichier_path = null;
    if (isset($_FILES['fichier']) && $_FILES['fichier']['error'] === 0) {
    $upload_dir = 'uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true); // crée le dossier s'il n'existe pas
    }

    $original_name = basename($_FILES['fichier']['name']);
    $extension = pathinfo($original_name, PATHINFO_EXTENSION);
    $new_filename = uniqid('fichier_', true) . '.' . $extension;
    $destination = $upload_dir . $new_filename;

    if (move_uploaded_file($_FILES['fichier']['tmp_name'], $destination)) {
        $fichier_path = $destination;
    } else {
        throw new Exception("echec_upload_fichier");
    }
}


    $sql = "INSERT INTO reclamation (nom_client, adress, `Type`, CIN, ICE, tel, mail, description, commune, ville, region, fichier) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nom, $adress, $type_user, $CIN, $ICE, $num_tel, $mail, $description, $commune, $ville, $region, $fichier_path]);

    echo json_encode(['status' => 'success']);
} 
catch (PDOException $e) {
    error_log("PDO Error: " . $e->getMessage() . "\nTrace: " . $e->getTraceAsString(), 3, 'errors.log');
    http_response_code(500);
    echo json_encode(['status' => 'error', 'code' => 'database_error']);
} 
catch (Exception $e) {
    error_log("Error: " . $e->getMessage() . "\nTrace: " . $e->getTraceAsString(), 3, 'errors.log');
    http_response_code(400);
    echo json_encode(['status' => 'error', 'code' => $e->getMessage()]);
}
?>
