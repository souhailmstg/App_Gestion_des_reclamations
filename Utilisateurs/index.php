<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    session_start();
    $action = $_POST['action'] ?? '';

    try {
        $pdo = new PDO("mysql:host=localhost;dbname=abhoer_;charset=utf8mb4", "root", "", [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo "Erreur de connexion à la base de données";
        exit;
    }

    // ORIENTER
    if ($action === 'orienter') {
        $id = $_POST["id"] ?? null;
        $service = $_POST["service"] ?? null;

        if (!$id || !$service) {
            http_response_code(400);
            echo "Paramètres manquants";
            exit;
        }

        try {
            $stmt = $pdo->prepare("UPDATE reclamation SET service_responsable = :service WHERE id = :id");
            $stmt->execute(['service' => $service, 'id' => $id]);
            echo "Orientation enregistrée !";
        } catch (PDOException $e) {
            http_response_code(500);
            echo "Erreur de base de données";
        }
        exit;
    }

    // VALIDER
    if ($action === 'valider') {
        $id = intval($_POST['id'] ?? 0);
        if (!$id) {
            http_response_code(400);
            echo "ID manquant.";
            exit;
        }

        try {
            $stmt = $pdo->prepare("UPDATE reclamation SET statut = 'validé' WHERE id = :id");
            $stmt->execute(['id' => $id]);
            echo "Réclamation validée avec succès.";
        } catch (PDOException $e) {
            http_response_code(500);
            echo "Erreur lors de la validation.";
        }
        exit;
    }

    // MODIFIER
    if ($action === 'modifier') {
        $role = $_SESSION['role'] ?? '';
        if ($role !== 'Directeur' && $role !== 'Secretaire') {
            http_response_code(403);
            echo "Accès refusé.";
            exit;
        }

        $id = $_POST['id'] ?? null;
        $nom = $_POST['nom_client'] ?? null;
        $mail = $_POST['mail'] ?? null;
        $tel = $_POST['tel'] ?? null;
        $description = $_POST['description'] ?? null;

        if (!$id || !$nom || !$description) {
            http_response_code(400);
            echo "Données incomplètes.";
            exit;
        }

        try {
        $stmt = $pdo->prepare("UPDATE reclamation SET nom_client = :nom, mail = :mail, tel = :tel, description = :desc WHERE id = :id");
        $stmt->execute([
            'nom' => $nom,
            'mail' => $mail,
            'tel' => $tel,
            'desc' => $description,
            'id' => $id
        ]);
        echo "Réclamation mise à jour avec succès.";
        } catch (PDOException $e) {
        http_response_code(500);
        echo "Erreur lors de la mise à jour : " . $e->getMessage();
        }

        exit;
    }

    // ARCHIVER
    if ($action === 'archiver') {
        $role = $_SESSION['role'] ?? '';
        if ($role !== 'Directeur' && $role !== 'Secretaire') {
            http_response_code(403);
            echo "Accès refusé.";
            exit;
        }

        $id = $_POST['id'] ?? null;
        if (!$id || !is_numeric($id)) {
            http_response_code(400);
            echo "ID invalide ou manquant.";
            exit;
        }

        try {
            $stmt = $pdo->prepare("UPDATE reclamation SET archived = 1 WHERE id = :id");
            $stmt->execute(['id' => $id]);
            echo "Réclamation archivée avec succès.";
        } catch (PDOException $e) {
            error_log("Erreur d'archivage : " . $e->getMessage());
            http_response_code(500);
            echo "Erreur serveur.";
        }
        exit;
    }

    // SUPPRIMER
    if ($action === 'supprimer') {
        $role = $_SESSION['role'] ?? '';
        

        $id = $_POST['id'] ?? null;
        if (!$id || !is_numeric($id)) {
            http_response_code(400);
            echo "ID invalide ou manquant.";
            exit;
        }

        try {
            $stmt = $pdo->prepare("DELETE FROM reclamation WHERE id = :id");
            $stmt->execute(['id' => $id]);
            echo "Réclamation supprimée avec succès.";
        } catch (PDOException $e) {
            error_log("Erreur de suppression : " . $e->getMessage());
            http_response_code(500);
            echo "Erreur serveur.";
        }
        exit;
    }
}
?>
