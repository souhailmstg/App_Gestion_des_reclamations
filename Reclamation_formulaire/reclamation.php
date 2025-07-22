<?php
header('Content-Type: application/json');

// Récupérer la langue (non nécessaire ici car on renvoie un code seulement)
$lang = $_POST['lang'] ?? 'fr';
$lang = in_array($lang, ['fr', 'ar']) ? $lang : 'fr';

try {
    $pdo = new PDO("mysql:host=localhost;dbname=abhoer", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // Récupération
    $nom = htmlspecialchars(trim($_POST['nom_client'] ?? ''));
    $num_tel = trim($_POST['num-tel'] ?? '');
    $mail = trim($_POST['mail'] ?? '');
    $adress = htmlspecialchars(trim($_POST['adress'] ?? ''));
    $type_user = $_POST['Type'] ?? '';
    $CIN = htmlspecialchars(trim($_POST['CIN'] ?? ''));
    $ICE = htmlspecialchars(trim($_POST['ICE'] ?? ''));
    $description = htmlspecialchars(trim($_POST['description'] ?? ''));

    // Erreurs retournées par code
    if (empty($nom)) throw new Exception("nom_obligatoire");
    if ($type_user === 'Particulier' && empty($CIN)) throw new Exception("cin_obligatoire");
    if (empty($description)) throw new Exception("desc_obligatoire");

    if (!empty($mail) && !filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("mail_invalide");
    }

    if ($num_tel !== '' && !preg_match('/^[1-9]\d{7,14}$/', $num_tel)) {
        throw new Exception("tel_invalide");
    }
    //traitement du fichier
    $fichier_joint = null;
    if (isset($_FILES['fichier']) && $_FILES['fichier']['error'] === 0) {
        $tmp_name = $_FILES['fichier']['tmp_name'];
        $name = basename($_FILES['fichier']['name']);
        $extension = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        $allowed_extensions = ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx'];
        $max_size = 5 * 1024 * 1024;

        if (!in_array($extension, $allowed_extensions)) {
            throw new Exception("fichier_non_autorise");
        }

        if ($_FILES['fichier']['size'] > $max_size) {
            throw new Exception("fichier_trop_gros");
        }

        $uploads_dir = 'uploads';
        if (!is_dir($uploads_dir)) mkdir($uploads_dir, 0777, true);
        $unique_name = uniqid() . "_" . $name;
        $chemin_final = "$uploads_dir/$unique_name";

        if (!move_uploaded_file($tmp_name, $chemin_final)) {
            throw new Exception("fichier_erreur");
        }

        $fichier_joint = $chemin_final;
    }

    // Insertion en base
    $sql = "INSERT INTO reclamation (nom_client, adress, user_type, CIN, ICE, telephone_client, email_client, description, piece_jointe)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nom, $adress, $type_user, $CIN, $ICE, $num_tel, $mail, $description, $fichier_joint]);

    echo json_encode(['status' => 'success']);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'code' => $e->getMessage()]);
    }
?>
